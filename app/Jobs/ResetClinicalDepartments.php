<?php

namespace App\Jobs;

use App\Events\BuildMessageGenerated;
use App\Events\UpdateBuildStatus;
use App\Models\AssignedShift;
use App\Models\Conflict;
use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResetClinicalDepartments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $event;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UpdateBuildStatus $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $schedule = Schedule::find($this->event->scheduleId);

        AssignedShift::clearSchedule($schedule);

        Conflict::clearSchedule($schedule);

        event(new BuildMessageGenerated($schedule, 'Mise à zéro effectuée avec succès.'));
        event(new UpdateBuildStatus($this->event->scheduleId, 'clinical', 0));
    }
}
