<?php

namespace App\Jobs;

use App\Builders\Precalculation;
use App\Builders\GenericBuilder;
use App\Events\UpdateBuildStatus;
use App\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class BuildClinicalDepartments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $event;

    private $precalculation;
    private $start;
    private static $timer = false;

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
        $this->start = microtime(true);

        $this->precalculation = new Precalculation($this->event->scheduleId);

        $departments = collect(json_decode(Setting::valueByKey('departments_order')))
            ->where('active', '=', 'true')->pluck('id')->each(function ($departmentId) {
                $this->generate($departmentId);
            });

        if($departments->count() == 0) {
            $message = 'No departments found in settings.';
            Log::info('BuildClinicalDepartments Job: STOPPED - ' . $message);
            event(new UpdateBuildStatus($this->event->scheduleId, 'clinical', 2, $message));

        } else {
            Log::info('BuildClinicalDepartments Job: FINISHED');
            event(new UpdateBuildStatus($this->event->scheduleId, 'clinical', 1));
        }


    }

    private function generate($departmentId)
    {
        $builder = new GenericBuilder($this->precalculation, $departmentId);
        if(self::$timer == true) {
            echo "Après departmentId {$departmentId} : " . (microtime(true)-$this->start) . "<br>";
        }
        return $builder;
    }
}
