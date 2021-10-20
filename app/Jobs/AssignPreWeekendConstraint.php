<?php

namespace App\Jobs;

use App\Models\AssignedShift;
use App\Builders\BuildStatus;
use App\Models\Constraint;
use App\Events\BuildMessageGenerated;
use App\Events\UpdateBuildStatus;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AssignPreWeekendConstraint implements ShouldQueue
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
        $saturdays = $this->getSaturdays();

        // DepartmentIds de distribution (18, 19)
        $saturdaysShifts = AssignedShift::with('shift')
            ->inDateInterval($this->schedule->start_date, $this->schedule->end_date)
            ->whereIn('date', $saturdays)
            ->whereHas('shift', function ($query) {
                $query->whereIn('department_id', [18, 19]);
            })->get();

        $constraintsToAdd = $saturdaysShifts
            ->map(function ($as) {
                return new Constraint([
                    'user_id' => $as->user_id,
                    'start_datetime' => $as->date->addDays(-1)->setTime(12,30),
                    'end_datetime' => $as->date->addDays(-1)->setTime(22,00),
                    'constraint_type_id' => 72,
                    'weight' => 1,
                    'comment' => '**AUTO GENERATED**',
                    'status' => 1,
                    'validated_by' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            });

        // Enlever les constraintes [VS] existantes + insérer les nouvelles
        Constraint::inDateInterval($this->schedule->start_date, $this->schedule->end_date)
            ->where('constraint_type_id', 72)->delete();
        Constraint::insert($constraintsToAdd->toArray());

        event(new BuildMessageGenerated($this->schedule, 'Constraintes pré-weekend ajoutées. ('.$constraintsToAdd->count().')'));
        event(new UpdateBuildStatus($this->event->scheduleId, 'last_evening', BuildStatus::Success));
    }

    private function getSaturdays()
    {
        $saturdays = [];

        $weeks = $this->schedule->durationInWeeks;
        for ($i = 0; $i <= $weeks; $i++) {
            $saturdays[] = $this->schedule->start_date
                ->addDays(6)
                ->addWeeks($i)
                ->format('Y-m-d');
        }

        return $saturdays;
    }
}
