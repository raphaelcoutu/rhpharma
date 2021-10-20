<?php

namespace App\Jobs;

use App\Models\AssignedShift;
use App\Models\Department;
use App\Events\StatsByDepartmentsGenerated;
use App\Models\Schedule;
use App\Models\Statistic;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateStatsByDepartments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $scheduleId;

    /**
     * Create a new job instance.
     *
     * @param $scheduleId
     */
    public function __construct($scheduleId)
    {
        $this->scheduleId = $scheduleId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Statistic::where('type', 'department')->where('schedule_id', $this->scheduleId)->delete();

        $schedule = Schedule::find($this->scheduleId);
        $activeDays = $schedule->durationInWeeks * 5;

        $assignedShift = AssignedShift::with('shift.shiftType')->InDateInterval($schedule->start_date, $schedule->end_date)->get();
        $departments = Department::with(['users'])->get();
        $stats = collect([]);

        foreach([24,25,26,27,28] as $index => $onco) {
            $department = $departments->firstWhere('id', $onco);
            $activeUsers = $department->users->where('pivot.active', 1);
            $departmentStats = collect([]);

            foreach($activeUsers as $user) {
                $hours = 0;

                // Tous les shifts assignés dont l'utilisateur est $user et dont le shift est inclus au $department
                $departmentUserShifts = $assignedShift
                    ->where('user_id', $user->id)
                    ->whereIn('shift_id', $department->shifts->pluck('id'));

                foreach($departmentUserShifts as $assignedShift) {
                    $start = Carbon::parse($assignedShift->shift->shiftType->start_time);
                    $end = Carbon::parse($assignedShift->shift->shiftType->end_time);

                    $hours += $end->diffInHours($start);
                }

                $departmentStats->push(['user' => $user->only('id', 'firstname', 'lastname'), 'hours' => $hours]);
            }
            $stats->push(['department' => $department->only('id', 'name'), 'users' => $departmentStats]);
        }

        $statistic = Statistic::create([
            'schedule_id' => $this->scheduleId,
            'type' => 'department',
            'content' => $stats->toJson()
        ]);

        event(new StatsByDepartmentsGenerated($this->scheduleId, $statistic->id));
    }
}
