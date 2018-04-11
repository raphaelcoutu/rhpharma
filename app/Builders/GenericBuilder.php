<?php

namespace App\Builders;


use App\AssignedShift;
use Illuminate\Support\Facades\Log;

class GenericBuilder extends BaseBuilder
{
    private $bonus;
    private $malus;

    private $selectedCombinaison;

    public function __construct(Precalculation $precalculation, $departmentId)
    {
        parent::__construct($precalculation, $departmentId);

        $start = microtime(true);

        $weeksCount = $precalculation->getWeeksCount();

        $this->getBonusMalus();

        $this->manualWeeks = $this->getManualWeeks();

        // On sort les ids des pharmaciens ex: [1, 4]
        $ids = $this->pharmacistsIdsInDepartment($departmentId);

        // TODO: faire un filter pour retirer des ids (selon attributes pour toute la durée de l'horaire par exemple)
        // avant de générer les sampling

        // On calcule le score de disponibilité par pharmacien par semaine [1 => [20,20,20], 4 => [...]]
        $this->scores = $this->precalculation->calculateScore($ids, $departmentId);

        $this->combinaisons = $this->optimizedSampling($ids, $weeksCount);
        //todo: ajouter le dernier pharmacien à faire la semaine ICI
        $this->removeUsedSequence();

        $this->selectSequence();

        $this->assignThreeDaysUsers();

        Log::debug('Department Id : ' . $departmentId . ' - Memory usage ' . round(memory_get_usage() / pow(1024,2),2) . ' Mo'
            . ' (' . round(microtime(true) - $start, 2) . 's)');

        // Comment For Debug Only:
        //$this->combinaisons = [];
    }

    private function selectSequence()
    {
        $allocatedWeeks = $this->precalculation->getAllocatedWeeks($this->departmentId);

        for($group = 0; $group < count($this->combinaisons); $group++) {
            $this->calculateScores($group);

            foreach ($this->combinaisons[$group] as &$combinaison) {
                foreach ($combinaison['count'] as $pharmId => $count) {

                        $diff = $allocatedWeeks[$pharmId] - $count;

                        if ($diff >= 0) {
                            // Différentiel à allouer supérieur ou égal à la combine
                            $combinaison['score'] += $count * 5;
                        } else {
                            // Différentiel à allouer inférieur à la combine
                            $combinaison['score'] += $diff * 10;
                        }
                }
            }

            if($group == 0) {
                //todo: screen les dernières semaines pour déterminer le bonus
                //TODO: aller voir les dernières semaines dans la base de donnée

            } else {
                $this->bonusMalusPrecedingGroup($group);
            }

            // Trier les séquences par scores desc.
            $this->sortByScores($group);

            if (count($this->combinaisons[$group]) != 0) {
                $this->selectedCombinaison = $this->combinaisons[$group][0];
                // S'il y a un pharmacien ajouté manuellement, vérifier s'il existe dans une séquence
                for ($i = 0; $i < $this->weeksPerGroup; $i++) {
                    $this->manualWeeks->each(function ($week) use ($i, $group) {
                        if(($i + $this->weeksPerGroup * $group) == $week) {
                            $temp = explode(",", $this->selectedCombinaison["sequence"]);
                            $temp[$i] = null;

                            $this->selectedCombinaison["sequence"] = implode(",", $temp);
                        }
                    });
                }

                //Retirer les semaines allouées des allocated
                foreach ($this->selectedCombinaison['count'] as $pharmId => $count) {
                    $allocatedWeeks[$pharmId] -= $count;
                }

                $this->precalculation->assignWeekSequence($this->departmentId, $group, $this->selectedCombinaison['sequence']);
            } else {
                //TODO: generate conflict
                Log::debug("No more pharmacist available to assign. [Department = {$this->departmentId}]");
            }
        }
    }

    private function bonusMalusPrecedingGroup($group)
    {
        for($i = 0; $i < count($this->combinaisons[$group]); $i++) {

            // Nombre de séquences consécutives (au début de nouvelle séquence)
            $split = explode(',', $this->combinaisons[$group][$i]['sequence']);
            $first = $split[0];

            $consecutive = 1;
            for($j = 1; $j < count($split); $j++) {
                if($split[$j] == $first) {
                    $consecutive++;
                } else {
                    break;
                }
            }

            // Nombre de séquences consécutives (à la fin de séquence précédente)
            if(!isset($this->precalculation->scheduleWeeks[$this->departmentId])) continue;
            $lastSequence = $this->precalculation->scheduleWeeks[$this->departmentId];
            for($j = count($lastSequence) - 1; $j >= 0; $j--) {
                if($lastSequence[$j] == $first) {
                    $consecutive++;
                } else {

                    break;
                }
            }

            // Pour debug only:
            $this->combinaisons[$group][$i]['consecutive'] = $consecutive;

            if($consecutive >= $this->bonus['weeks'])
                $this->combinaisons[$group][$i]['score'] += $this->bonus['pts'];
            if($consecutive >= $this->malus['weeks'])
                $this->combinaisons[$group][$i]['score'] -= $this->malus['pts'];
        }
    }

    private function removeUsedSequence()
    {
        for($group = 0; $group < count($this->combinaisons); $group++) {
            $combinaisonsToUnset = [];
            for ($i = 0; $i < count($this->combinaisons[$group]); $i++) {
                $splitSequence = explode(',', $this->combinaisons[$group][$i]['sequence']);
                if (!empty($this->precalculation->scheduleWeeks)) {
                    foreach ($this->precalculation->scheduleWeeks as $department) {
                        for ($week = 0; $week < count($splitSequence); $week++) {
                            if (isset($department[($group * $this->weeksPerGroup) + $week])
                                && $department[($group * $this->weeksPerGroup) + $week] == $splitSequence[$week]) {
                                $combinaisonsToUnset[] = $i;
                            }
                        }
                    }
                }
            }

            $combinaisonsToUnset = array_unique($combinaisonsToUnset);

            foreach ($combinaisonsToUnset as $item) {
                unset($this->combinaisons[$group][$item]);
            }

            // Reset index
            $this->combinaisons[$group] = array_values($this->combinaisons[$group]);
        }
    }

    private function calculateScores($group)
    {
        for ($i = 0; $i < count($this->combinaisons[$group]); $i++) {
            $sequence = explode(',', $this->combinaisons[$group][$i]["sequence"]);
            $result = 0;
            $week = $group*$this->weeksPerGroup;

            $lastSeq = 0;
            $consecutiveCount = 1;
            foreach ($sequence as $id) {
                $result += $this->scores[$id][$week];

                if ($id == $lastSeq) {
                    $consecutiveCount++;

                    if ($consecutiveCount >= $this->bonus['weeks']) {
                        $result += $this->bonus['pts'];
                    }

                    if ($consecutiveCount >= $this->malus['weeks']) {
                        $result -= $this->malus['pts'];
                    }

                } else {
                    //Ici on ce n'est plus le même pharmacien, donc on reset le décompte
                    $lastSeq = $id;
                    $consecutiveCount = 1;
                }

                $week++;
            }

            $this->combinaisons[$group][$i]["count"] = array_count_values($sequence);
            $this->combinaisons[$group][$i]["score"] = $result;
        }

    }

    private function sortByScores($group)
    {
        usort($this->combinaisons[$group], function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });
    }

    private function pharmacistsIdsInDepartment($departmentId)
    {
        // On sélectionne les pharmaciens (> 3 jours/sem) qui font parti de ce secteur
        return $this->precalculation->pharmaciens->filter(function ($pharmacien) use ($departmentId) {
            if ($pharmacien->workdays_per_week > 3 && !$pharmacien->is_manual) {
                foreach($pharmacien->departments as $department)
                {
                    if($department->id == $departmentId)
                        return true;
                }
            }
        })->pluck('id')->toArray();
    }

    private function getBonusMalus()
    {
        $department = $this->precalculation->departments->find(['id' => $this->departmentId])->first();

        $this->bonus = ['weeks' => $department->bonus_weeks, 'pts' => $department->bonus_pts];
        $this->malus = ['weeks' => $department->malus_weeks, 'pts' => $department->malus_pts];
    }

    private function getManualWeeks()
    {
        return AssignedShift::whereHas('shift', function ($query) {
            $query->where('department_id', $this->departmentId);
        })->get()
            ->map(function ($shift) {
                return $shift['week'] = $shift->date->diffInWeeks($this->precalculation->schedule->start_date);
            })->unique();
    }

    private function assignThreeDaysUsers()
    {
        //
    }
}