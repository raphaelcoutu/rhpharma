<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\BuildMessage;
use App\Models\Constraint;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\Statistic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('read', Schedule::class);

        $schedules = Schedule::orderedDesc()->paginate(15);

        $constraintsInSchedule = [];

        if (! $schedules->empty()) {
            $constraints = Constraint::unvalidated()->inDateInterval($schedules->last()->start_date, $schedules->first()->end_date)->get();
            foreach ($schedules as $schedule) {
                $collision = 0;
                foreach ($constraints as $constraint) {
                    if (detectsIntervalCollision($constraint->start_datetime, $constraint->end_datetime,
                        $schedule->start_date->setTime(0, 0), $schedule->end_date->setTime(23, 59))) {
                        $collision++;
                    }
                }

                $constraintsInSchedule[$schedule->id] = $collision;
            }
        }

        return Inertia::render('schedules/index', [
            'pageTitle' => 'Horaires',
            'schedules' => $schedules,
            'constraintsInSchedule' => $constraintsInSchedule,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('write', Schedule::class);

        return Inertia::render('schedules/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScheduleRequest $request): RedirectResponse
    {
        Gate::authorize('write', Schedule::class);

        Schedule::create([
            ...$request->validated(),
            'branch_id' => $request->user()->branch->id,
        ]);

        return redirect()->route('schedules.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): Response
    {
        Gate::authorize('write', Schedule::class);

        $schedule = Schedule::with('conflicts.department')
            ->where('branch_id', auth()->user()->branch_id)
            ->findOrFail($id);

        $departments = Department::orderBy('name')->get();

        $constraintsCount = Constraint::unvalidated()->inDateInterval($schedule->start_date, $schedule->end_date)->count();
        $buildMessages = $schedule->buildMessages()
            ->orderByDesc('id')
            ->limit(100)
            ->get()
            ->sortBy('id')
            ->values()
            ->map(fn (BuildMessage $message): array => [
                'id' => $message->id,
                'timestamp' => $message->created_at?->format('Y/m/d H:i:s'),
                'message' => $message->message,
            ]);
        $latestStatistic = Statistic::where('type', 'department')
            ->where('schedule_id', $schedule->id)
            ->latest('id')
            ->first();

        return Inertia::render('schedules/show', [
            'schedule' => $schedule,
            'durationInWeeks' => $schedule->duration_in_weeks,
            'constraintsCount' => $constraintsCount,
            'conflicts' => $schedule->conflicts,
            'departments' => $departments,
            'buildMessages' => $buildMessages,
            'statistics' => $latestStatistic === null ? [] : (json_decode($latestStatistic->content, true) ?: []),
            'statisticsStatus' => $schedule->status_statistics,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Response
    {
        Gate::authorize('write', Schedule::class);

        $schedule = Schedule::findOrFail($id);

        return Inertia::render('schedules/edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScheduleRequest $request, int $id): RedirectResponse
    {
        Gate::authorize('write', Schedule::class);

        $schedule = Schedule::findOrFail($id);
        $schedule->update($request->validated());

        return redirect()->route('schedules.index');
    }

    public function updateNotes(Request $request, int $id): void
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->notes = $request->notes;
        $schedule->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        abort(404);
    }
}
