<?php

namespace App\Listeners;

use App\Builders\BuildStatus;
use App\Events\BuildMessageGenerated;
use App\Events\UpdateBuildStatus;
use App\Jobs\AnalyzeClinicalDepartments;
use App\Jobs\AssignPreWeekendConstraint;
use App\Jobs\BuildClinicalDepartments;
use App\Jobs\CompleteWeekendsAndDaysOff;
use App\Jobs\ResetClinicalDepartments;
use App\Models\Schedule;

class BuildStatusChanged
{
    /**
     * @var array<string, string>
     */
    private const STATUS_COLUMNS = [
        'weekends' => 'status_weekends',
        'last_evening' => 'status_last_evening',
        'clinical' => 'status_clinical_departments',
    ];

    public function handle(UpdateBuildStatus $event): void
    {
        $schedule = Schedule::findOrFail($event->scheduleId);
        $statusColumn = self::STATUS_COLUMNS[$event->buildStep] ?? null;

        if ($statusColumn === null || $event->status < BuildStatus::Standby || $event->status > BuildStatus::Analyze) {
            return;
        }

        $schedule->forceFill([$statusColumn => $event->status])->save();

        if ($event->status === BuildStatus::Error && filled($event->message)) {
            event(new BuildMessageGenerated($schedule, 'Erreur: '.$event->message));
        }

        if ($event->status !== BuildStatus::Build && $event->status !== BuildStatus::Analyze && $event->status !== BuildStatus::Reset) {
            return;
        }

        if ($event->buildStep === 'clinical') {
            match ($event->status) {
                BuildStatus::Build => BuildClinicalDepartments::dispatch($event),
                BuildStatus::Analyze => AnalyzeClinicalDepartments::dispatch($event),
                BuildStatus::Reset => ResetClinicalDepartments::dispatch($event),
                default => null,
            };

            return;
        }

        if ($event->status === BuildStatus::Build && $event->buildStep === 'last_evening') {
            AssignPreWeekendConstraint::dispatch($event);
        }

        if ($event->status === BuildStatus::Build && $event->buildStep === 'weekends') {
            CompleteWeekendsAndDaysOff::dispatch($event);
        }
    }
}
