<?php

namespace App\Listeners;

use App\Events\UpdateBuildStatus;
use App\Jobs\BuildClinicalDepartments;
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
        //Update Status icon
        if($event->buildStep == 'clinicalDepartments') {
            //Pusher update
        }

        //Build selon le Step
        if($event->buildStep == 'clinicalDepartments') {
            BuildClinicalDepartments::dispatch($event);
        }
    }
}
