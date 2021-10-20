<?php

namespace App\Jobs;

use App\Builders\BuildStatus;
use App\Builders\DepartmentAnalyzer;
use App\Builders\UserAnalyzer;
use App\Models\Conflict;
use App\Events\BuildMessageGenerated;
use App\Events\UpdateBuildStatus;
use App\Models\Schedule;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnalyzeClinicalDepartments implements ShouldQueue
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
        $start = microtime(true);
        $schedule = Schedule::find($this->event->scheduleId);

        Log::debug('AnalyzerJob : begins');
        event(new BuildMessageGenerated($schedule, 'Analyse a débuté...'));

        Conflict::clearSchedule($schedule);

        collect(json_decode(Setting::valueByKey('departments_order')))
            ->where('active', '=', 'true')->pluck('id')->each(function ($departmentId) use ($schedule) {
                    DepartmentAnalyzer::run($schedule, $departmentId);
            });

        UserAnalyzer::run($schedule);

        $end = number_format(microtime(true) - $start, 2);
        Log::debug('AnalyzeJob : finishes (' . $end . 's)');

        event(new BuildMessageGenerated($schedule, 'Analyse est terminée (' . $end . 's)'));
        event(new UpdateBuildStatus($this->event->scheduleId, 'clinical', BuildStatus::Success));
    }
}
