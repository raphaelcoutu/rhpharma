<?php

namespace App\Jobs;

use App\Models\AssignedShift;
use App\Builders\BuildStatus;
use App\Events\BuildMessageGenerated;
use App\Events\UpdateBuildStatus;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CompleteWeekendsAndDaysOff implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $event;

    protected $schedule;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UpdateBuildStatus $event)
    {
        $this->event = $event;

        $this->schedule = Schedule::find($this->event->scheduleId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->completeWeekends();
        $this->completeDaysoff();

        event(new UpdateBuildStatus($this->event->scheduleId, 'weekends', BuildStatus::Success));
    }

    private function completeWeekends()
    {
        $assignedShifts = AssignedShift::inDateInterval($this->schedule->start_date, $this->schedule->end_date)
            ->get();
        $shiftsToAdd = collect();

        foreach($assignedShifts as $shift) {
            // Si on est samedi, on s'assure d'avoir un dimanche
            if($shift->date->dayOfWeek === 6) {

                $nextDate = $shift->date->addDay(1);
                $nextDayExists = $assignedShifts->where('date', $nextDate)
                    ->where('user_id', $shift->user_id)
                    ->count();

                if(!$nextDayExists && $nextDate->lte($this->schedule->end_date)) {
                    $newShift = new AssignedShift([
                        'user_id' => $shift->user_id,
                        'shift_id' => $this->convertShift($shift->shift_id),
                        'is_generated' => false,
                        'is_published' => true,
                        'date' => $nextDate,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);

                    $shiftsToAdd->push($newShift);
                }
            }

            // Si on est dimanche, on s'assure d'avoir un samedi
            else if($shift->date->dayOfWeek === 0) {
                $previousDate = $shift->date->addDay(-1);
                $previousDayExists = $assignedShifts->where('date', $previousDate)
                    ->where('user_id', $shift->user_id)
                    ->count();

                if(!$previousDayExists && $previousDate->gte($this->schedule->start_date)) {
                    $newShift = new AssignedShift([
                        'user_id' => $shift->user_id,
                        'shift_id' => $this->convertShift($shift->shift_id, $reverse = true),
                        'is_generated' => false,
                        'is_published' => true,
                        'date' => $previousDate,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);

                    logger()->info($previousDate);

                    $shiftsToAdd->push($newShift);
                }
            }

            // On place les congés si on a pas déjà des congés dans la semaine
        }

        if($shiftsToAdd->count()) {
            AssignedShift::insert($shiftsToAdd->toArray());
        }

        event(new BuildMessageGenerated($this->schedule, "Weekends ajoutées. ({$shiftsToAdd->count()})"));
    }

    private function completeDaysOff()
    {
        $assignedShifts = AssignedShift::inDateInterval($this->schedule->start_date, $this->schedule->end_date)
            ->get();
        $daysOffToAdd = collect();

        foreach($assignedShifts as $shift) {
            // Si on est dimanche, on s'assure d'avoir un dimanche
            if ($shift->date->dayOfWeek === 0) {

                // On regarde les congés présents
                $daysOffCount = $assignedShifts->where('user_id', $shift->user_id)
                    ->where('shift_id', 1)
                    ->where('date', '>=', $shift->date->addDay(1))
                    ->where('date', '<', $shift->date->addDay(6))
                    ->count();

                if($daysOffCount === 0) {
                    foreach($this->getDaysOffWithShift($shift->shift_id) as $dayOfWeek) {
                        $newDayOff = new AssignedShift([
                            'user_id' => $shift->user_id,
                            'shift_id' => 1,
                            'is_generated' => false,
                            'is_published' => true,
                            'date' => $shift->date->copy()->next($dayOfWeek),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);

                        $daysOffToAdd->push($newDayOff);
                    }

                }
            }
        }

        if($daysOffToAdd->count()) {
            AssignedShift::insert($daysOffToAdd->toArray());
        }

        event(new BuildMessageGenerated($this->schedule, "Congés de semaine ajoutées. ({$daysOffToAdd->count()})"));
    }

    private function getDaysOffWithShift($shift)
    {
//        5 Pharmaciens
//        $daysOffByShift = [
//            2 => [Carbon::MONDAY,Carbon::TUESDAY],
//            3 => [Carbon::THURSDAY, Carbon::FRIDAY],
//            4 => [Carbon::THURSDAY, Carbon::FRIDAY],
//            5 => [Carbon::MONDAY,Carbon::TUESDAY],
//            8 => [Carbon::THURSDAY, Carbon::FRIDAY]
//        ];

        // 6 Pharmaciens
        $daysOffByShift = [
            2 => [Carbon::THURSDAY, Carbon::FRIDAY],
            3 => [Carbon::MONDAY,Carbon::TUESDAY],
            4 => [Carbon::THURSDAY, Carbon::FRIDAY],
            5 => [Carbon::THURSDAY, Carbon::FRIDAY],
            42 => [Carbon::MONDAY,Carbon::TUESDAY],
            8 => [Carbon::THURSDAY, Carbon::FRIDAY]
        ];

        return $daysOffByShift[$shift];
    }


    private function convertShift($shift_id, $reverse = false)
    {
//        5 Pharmaciens
//        $conversion = [
//            2 => 4,
//            3 => 2,
//            4 => 3,
//            5 => 8,
//            8 => 5
//        ];

        // 6 Pharmaciens
        $conversion = [
            2 => 4,
            3 => 3,
            4 => 2,
            5 => 8,
            42 => 42,
            8 => 5
        ];

        return (!$reverse) ? $conversion[$shift_id] : array_search($shift_id, $conversion);
    }
}
