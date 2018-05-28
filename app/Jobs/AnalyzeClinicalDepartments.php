<?php

namespace App\Jobs;

use App\Builders\DepartmentAnalyzer;
use App\Builders\UserAnalyzer;
use App\Conflict;
use App\Events\UpdateBuildStatus;
use App\Schedule;
use App\Setting;
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
        Log::debug('AnalyzerJob : begins');

        $schedule = Schedule::find($this->event->scheduleId);

        Conflict::clearSchedule($schedule);

        collect(json_decode(Setting::valueByKey('departments_order')))
            ->where('active', '=', 'true')->pluck('id')->each(function ($departmentId) use ($schedule) {
                    DepartmentAnalyzer::run($schedule, $departmentId);
            });

        UserAnalyzer::run($schedule);

        Log::debug('AnalyzeJob : finishes (' . number_format(microtime(true) - $start, 2) . 's)');

        event(new UpdateBuildStatus($this->event->scheduleId, 'clinical', 1));
    }
}
