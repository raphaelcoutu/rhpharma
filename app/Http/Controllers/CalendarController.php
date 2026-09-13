<?php

namespace App\Http\Controllers;

use App\Models\AssignedShift;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CalendarController extends Controller
{
    public function show(Schedule $schedule): InertiaResponse
    {
        return Inertia::render('calendar', $this->query($schedule));
    }

    public function showByDepartment(Schedule $schedule, string $department): InertiaResponse
    {
        return Inertia::render('calendar', $this->query($schedule, $department));
    }

    public function getUserData(Request $request): array
    {
        $validated = $request->validate([
            'userId' => ['required', 'integer'],
            'date' => ['required', 'date'],
            'schedule_id' => ['nullable', 'integer'],
        ]);
        $parsedDate = Carbon::parse($validated['date']);
        $schedule = $this->scheduleFromRequest($validated['schedule_id'] ?? null);

        $this->ensureDateIsInSchedule($parsedDate, $schedule);

        $user = User::ownBranch()->with(['constraints' => function ($query) use ($parsedDate) {
            $query->InDateInterval($parsedDate, $parsedDate)->whereHas('constraintType', function ($constraintType) {
                $constraintType->where('is_group_constraint', 0);
            });
        }, 'constraints.constraintType' => function ($query) {
            $query->select(['id', 'name', 'code']);
        }, 'assignedShifts' => function ($query) use ($parsedDate): void {
            $query->whereDate('date', $parsedDate);
        }, 'assignedShifts.shift'])->findOrFail($validated['userId']);

        return [
            'user' => $user,
            'assignedShifts' => $user->assignedShifts,
            'constraints' => $user->constraints,
            'shifts' => $this->availableShifts(),
        ];
    }

    public function setUserData(Request $request): array
    {
        Gate::authorize('write', Schedule::class);

        $validated = $request->validate([
            'user_id' => ['required', 'integer'],
            'date' => ['required', 'date'],
            'shifts' => ['present', 'array'],
            'shifts.*' => ['integer', 'distinct'],
            'schedule_id' => ['nullable', 'integer'],
        ]);
        $parsedDate = Carbon::parse($validated['date']);
        $schedule = $this->scheduleFromRequest($validated['schedule_id'] ?? null);

        $this->ensureDateIsInSchedule($parsedDate, $schedule);
        $user = User::ownBranch()->where('is_active', true)->findOrFail($validated['user_id']);
        $shiftIds = $this->validatedShiftIds($validated['shifts']);

        DB::transaction(function () use ($validated, $parsedDate, $shiftIds): void {
            AssignedShift::query()
                ->where('user_id', $validated['user_id'])
                ->whereDate('date', $parsedDate)
                ->delete();

            if ($shiftIds->isEmpty()) {
                return;
            }

            $timestamp = now();
            AssignedShift::insert($shiftIds->map(fn (int $shiftId): array => [
                'user_id' => $validated['user_id'],
                'shift_id' => $shiftId,
                'is_generated' => false,
                'is_published' => false,
                'date' => $parsedDate->toDateString(),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ])->all());
        });

        return AssignedShift::with('shift')
            ->where('user_id', $user->id)
            ->whereDate('date', $parsedDate)
            ->get()
            ->all();
    }

    public function getShifts(): array
    {
        return $this->availableShifts()->all();
    }

    public function setSelectedData(Request $request): array
    {
        Gate::authorize('write', Schedule::class);

        $validated = $request->validate([
            'selected' => ['present', 'array'],
            'selected.*.user_id' => ['required', 'integer'],
            'selected.*.date' => ['required', 'date'],
            'shifts' => ['present', 'array'],
            'shifts.*' => ['integer', 'distinct'],
            'schedule_id' => ['nullable', 'integer'],
        ]);
        $schedule = $this->scheduleFromRequest($validated['schedule_id'] ?? null);
        $selected = collect($validated['selected'])->map(function (array $selection) use ($schedule): array {
            $date = Carbon::parse($selection['date']);
            $this->ensureDateIsInSchedule($date, $schedule);

            return [
                'user_id' => (int) $selection['user_id'],
                'date' => $date->toDateString(),
            ];
        });

        if ($selected->isEmpty()) {
            return [];
        }

        $userIds = $selected->pluck('user_id')->unique()->values();
        $users = User::ownBranch()->where('is_active', true)->whereIn('id', $userIds)->pluck('id');

        if ($users->count() !== $userIds->count()) {
            throw ValidationException::withMessages([
                'selected' => 'Certains utilisateurs ne sont pas accessibles.',
            ]);
        }

        $shiftIds = $this->validatedShiftIds($validated['shifts']);
        $timestamp = now();

        DB::transaction(function () use ($selected, $shiftIds, $timestamp): void {
            foreach ($selected as $selection) {
                AssignedShift::query()
                    ->where('user_id', $selection['user_id'])
                    ->whereDate('date', $selection['date'])
                    ->delete();
            }

            if ($shiftIds->isEmpty()) {
                return;
            }

            $assignments = $selected->flatMap(fn (array $selection): array => $shiftIds->map(fn (int $shiftId): array => [
                'user_id' => $selection['user_id'],
                'shift_id' => $shiftId,
                'is_generated' => false,
                'is_published' => false,
                'date' => $selection['date'],
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ])->all())->all();

            AssignedShift::insert($assignments);
        });

        return AssignedShift::with('shift')
            ->where(function (Builder $query) use ($selected): void {
                foreach ($selected as $selection) {
                    $query->orWhere(function (Builder $selectionQuery) use ($selection): void {
                        $selectionQuery
                            ->where('user_id', $selection['user_id'])
                            ->whereDate('date', $selection['date']);
                    });
                }
            })
            ->get()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function query(Schedule $schedule, ?string $departmentIds = null): array
    {
        Gate::authorize('read', Schedule::class);

        $schedule = Schedule::query()
            ->where('branch_id', auth()->user()->branch_id)
            ->findOrFail($schedule->id);
        $departmentIds = collect(explode(',', (string) $departmentIds))
            ->filter(fn (string $departmentId): bool => ctype_digit($departmentId))
            ->map(fn (string $departmentId): int => (int) $departmentId)
            ->values();

        $users = User::query()->select([
            'id',
            'firstname',
            'lastname',
            'workdays_per_week',
            'branch_id',
        ])->with(['assignedShifts' => function ($query) use ($schedule) {
            $query->InDateInterval($schedule->start_date, $schedule->end_date);
        }, 'assignedShifts.shift:id,department_id,code,description,is_default', 'constraints' => function ($query) use ($schedule) {
            $query->InDateInterval($schedule->start_date, $schedule->end_date)
                ->where('status', 1)
                ->whereHas('constraintType', function ($query) {
                    $query->where('is_group_constraint', 0);
                });
        }, 'constraints.constraintType:id,name,code,status', 'departments:id,name'])->ownBranch()->where('is_active', true);

        if ($departmentIds->isNotEmpty()) {
            $users->whereHas('departments', function ($query) use ($departmentIds): void {
                $query->whereIn('departments.id', $departmentIds);
            });
        }

        $users = $users->orderBy('lastname')->orderBy('firstname')->get();

        $departments = Department::ownBranch()->orderBy('name')->get(['id', 'name']);

        return [
            'schedule' => $schedule,
            'users' => $users,
            'departments' => $departments,
            'shifts' => $this->availableShifts(),
            'selectedDepartmentIds' => $departmentIds,
            'canEdit' => Gate::allows('write', Schedule::class),
        ];
    }

    private function scheduleFromRequest(?int $scheduleId): ?Schedule
    {
        if ($scheduleId === null) {
            return null;
        }

        return Schedule::query()
            ->where('branch_id', auth()->user()->branch_id)
            ->findOrFail($scheduleId);
    }

    private function ensureDateIsInSchedule(Carbon $date, ?Schedule $schedule): void
    {
        if ($schedule === null) {
            return;
        }

        if ($date->isBefore($schedule->start_date->copy()->startOfDay()) || $date->isAfter($schedule->end_date->copy()->endOfDay())) {
            throw ValidationException::withMessages([
                'date' => 'La date doit se trouver dans la période de l’horaire.',
            ]);
        }
    }

    /**
     * @param  array<int, int>  $requestedShiftIds
     * @return Collection<int, int>
     */
    private function validatedShiftIds(array $requestedShiftIds): Collection
    {
        $shiftIds = collect($requestedShiftIds)->map(fn (int $shiftId): int => (int) $shiftId)->unique()->values();
        $availableShiftIds = $this->availableShifts()->whereIn('id', $shiftIds)->pluck('id');

        if ($availableShiftIds->count() !== $shiftIds->count()) {
            throw ValidationException::withMessages([
                'shifts' => 'Un ou plusieurs shifts ne sont pas accessibles.',
            ]);
        }

        return $availableShiftIds;
    }

    private function availableShifts(): EloquentCollection
    {
        return Shift::query()
            ->whereHas('department', function ($query): void {
                $query->where('branch_id', auth()->user()->branch_id);
            })
            ->with('department:id,name')
            ->orderBy('code')
            ->get(['id', 'department_id', 'code', 'description', 'is_default']);
    }
}
