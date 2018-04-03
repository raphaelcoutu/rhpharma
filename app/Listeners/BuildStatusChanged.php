<?php

namespace App\Listeners;

use App\Events\UpdateBuildStatus;
use App\Jobs\BuildClinicalDepartments;
use App\Schedule;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
            if($event->status == 3) {
                //Update database
                $schedule->status_clinical_departments = 3;
                $schedule->update();

                //Start job
                (new BuildClinicalDepartments($event))->handle();

            }

            if($event->status == 1) {
                //Update database
                //Update database
                $schedule->status_clinical_departments = 1;
                $schedule->update();

                //Update schedule.show

                //Send notification to admins
            }

            if($event->status == 2) {
                //Update database
                //Update database
                $schedule->status_clinical_departments = 2;
                $schedule->update();

                //Update schedule.show

                //Send notification to admins
            }
        }



    }
}
