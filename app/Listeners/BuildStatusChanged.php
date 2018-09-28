<?php

namespace App\Listeners;

use App\Builders\BuildStatus;
use App\Events\UpdateBuildStatus;
use App\Jobs\AnalyzeClinicalDepartments;
use App\Jobs\BuildClinicalDepartments;
use App\Jobs\ResetClinicalDepartments;
use App\Schedule;

class BuildStatusChanged
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UpdateBuildStatus  $event
     * @return void
     */
    public function handle(UpdateBuildStatus $event)
    {
        $schedule = Schedule::findOrFail($event->scheduleId);

        if($event->buildStep == 'clinical') {
            //On update database (peu importe le status, tant qu'il existe!)
            if($event->status >= 0 && $event->status <= 6) {
                $schedule->status_clinical_departments = $event->status;
                $schedule->update();
            }

            if($event->status === BuildStatus::Build) {
                //Start job
                (new BuildClinicalDepartments($event))->handle();
            }

            if($event->status === BuildStatus::Analyze) {
                (new AnalyzeClinicalDepartments($event))->handle();
            }

            if($event->status === BuildStatus::Reset) {
                (new ResetClinicalDepartments($event))->handle();
            }
        }
    }
}
