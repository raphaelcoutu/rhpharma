<?php

namespace App\Jobs;

use App\Builders\BuildStatus;
use App\Models\AssignedShift;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\Statistic;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GenerateStatsByDepartments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected int $scheduleId) {}

    public function handle(): void
    {
        $schedule = Schedule::findOrFail($this->scheduleId);

        try {
            Statistic::where('type', 'department')->where('schedule_id', $this->scheduleId)->delete();

            $assignedShift = AssignedShift::with('shift.shiftType')
                ->inDateInterval($schedule->start_date, $schedule->end_date)
                ->get();
            $departments = Department::with(['users', 'shifts'])->get();
            $stats = collect([]);

            foreach ([24, 25, 26, 27, 28] as $onco) {
                $department = $departments->firstWhere('id', $onco);

                if ($department === null) {
                    continue;
                }

                $activeUsers = $department->users->where('pivot.active', 1);
                $departmentStats = collect([]);

                foreach ($activeUsers as $user) {
                    $hours = 0;
                    $departmentUserShifts = $assignedShift
                        ->where('user_id', $user->id)
                        ->whereIn('shift_id', $department->shifts->pluck('id'));

                    foreach ($departmentUserShifts as $assignedShift) {
                        $start = Carbon::parse($assignedShift->shift->shiftType->start_time);
                        $end = Carbon::parse($assignedShift->shift->shiftType->end_time);

                        $hours += $end->diffInHours($start);
                    }

                    $departmentStats->push(['user' => $user->only('id', 'firstname', 'lastname'), 'hours' => $hours]);
                }

                $stats->push(['department' => $department->only('id', 'name'), 'users' => $departmentStats]);
            }

            Statistic::create([
                'schedule_id' => $this->scheduleId,
                'type' => 'department',
                'content' => $stats->toJson(),
            ]);

            $schedule->forceFill(['status_statistics' => BuildStatus::Success])->save();
        } catch (Throwable $exception) {
            $schedule->forceFill(['status_statistics' => BuildStatus::Error])->save();

            throw $exception;
        }
    }

    public function failed(?Throwable $exception): void
    {
        Schedule::whereKey($this->scheduleId)->update(['status_statistics' => BuildStatus::Error]);
    }
}
