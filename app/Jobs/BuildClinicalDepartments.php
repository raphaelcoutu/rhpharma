<?php

namespace App\Jobs;

use App\Builders\Precalculation;
use App\Builders\GenericBuilder;
use App\Events\UpdateBuildStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BuildClinicalDepartments implements ShouldQueue
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

        $start = microtime(true);

        $precalculation = new Precalculation($this->event->scheduleId);

        // Obtenir l'ordre des secteurs à générer
        // Boucler pour chaque secteur avec le builder correspondant

        $SIM = new GenericBuilder($precalculation, 4);
        echo 'Après SIM ' . (microtime(true)-$start) . '<br>';
        $SIC = new GenericBuilder($precalculation, 5);
        echo 'Après SIC ' . (microtime(true)-$start) . '<br>';
        $SIHD = new GenericBuilder($precalculation, 6);
        echo 'Après SIHD ' . (microtime(true)-$start) . '<br>';
        $SIPA = new GenericBuilder($precalculation, 8);
        echo 'Après SIPA ' . (microtime(true)-$start) . '<br>';
        $MI = new GenericBuilder($precalculation, 2);
        echo 'Après MI ' . (microtime(true)-$start) . '<br>';

//        dd($precalculation->scheduleWeeks, $precalculation->getAvailability(), $end-$start);
        dd($precalculation->scheduleWeeks);
    }
}
