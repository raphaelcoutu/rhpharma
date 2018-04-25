<?php

namespace App\Jobs;

use App\Builders\GenericBuilder;
use App\Builders\Precalculation;
use App\Events\UpdateBuildStatus;
use App\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BuildClinicalDepartments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $event;

    private $precalculation;
    private $start;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UpdateBuildStatus $event)
    {
        $this->event = $event;
        $this->start = microtime(true);

        Log::debug('Precalculation - start');
        $this->precalculation = new Precalculation($event->scheduleId);
        Log::debug('Precalculation - end');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Précalculation
        // - Importation des jours fériés
        // - Importation des fins de semaine
        // - Importation des derniers soirs de semaine
        // - Importation des contraintes
        // - Calculer les statistiques antérieures de prévision main d'oeuvre(ou importer via page temporaire)

        //Revérifier si y'a des contraintes non validé dans l'interval avant de procéder?

        // Génération d'une matrice de disponibilité du département pour toutes les demie-journées de l'horaire
        // - Durée de l'horaire
        // - Tous les pharmaciens actifs (incluant les attributs d'indisponibilité)
        // - Boucle : jours fériés (mentionner comme étant travaillé)
        // - Boucle : jours weekends (mentionner comme étant travaillé)
        // - Passer chacune des contraintes et modifier la matrice

        Log::info('BuildClinicalDepartments Job: STARTED');

        $departments = collect(json_decode(Setting::valueByKey('departments_order')))
            ->where('active', '=', 'true')->pluck('id')->each(function ($departmentId) {
                set_time_limit(30);
                new GenericBuilder($this->precalculation, $departmentId);
            });

        if ($departments->count() == 0) {
            $message = 'No departments found in settings.';
            Log::warning('BuildClinicalDepartments Job: ' . $message);
            Log::info('BuildClinicalDepartments Job: STOPPED');
            event(new UpdateBuildStatus($this->event->scheduleId, 'clinical', 2, $message));

        } else {
            $executionTime = round(microtime(true) - $this->start, 2);
            Log::info('BuildClinicalDepartments Job: FINISHED - ' . $executionTime . ' sec');
            event(new UpdateBuildStatus($this->event->scheduleId, 'clinical', 1));
        }

    }
}
