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

        $this->start = microtime(true);

        $this->precalculation = new Precalculation($this->event->scheduleId);

        // Obtenir l'ordre des secteurs à générer
        // Boucler pour chaque secteur avec le builder correspondant

        $this->generate(4);     //SIM
        $this->generate(5);     //SIC
        $this->generate(6);     //SIHD
        $this->generate(8);     //SIPA
        $this->generate(11);    //SP
        $this->generate(2);     //MI
        $this->generate(9);     //L
        $this->generate(10);    //IC
        $this->generate(14);    //ME
        $this->generate(15);    //PE
        $this->generate(12);    //CIM
        $this->generate(16);    //URHF
        $this->generate(17);    //URHF


//        dd($precalculation->scheduleWeeks, $precalculation->getAvailability(), $end-$start);
//        dd($precalculation->scheduleWeeks, $precalculation->getAvailability()[9]);

//        dd($this->precalculation->scheduleWeeks, $SP->getCombinaisons()[1], $this->precalculation->getAllocatedWeeks());

//        dd($this->precalculation->getAllocatedWeeks(11));

        return view('test.test',
            [
                'precalculation' => $this->precalculation,
                'departments' => $this->precalculation->departments,
                'pharmaciens' => $this->precalculation->pharmaciens
            ]);
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
