<?php

namespace App\Builders;


class GenericBuilder extends BaseBuilder
{
    public function __construct(Precalculation $precalculation, $departmentId)
    {
        parent::__construct($precalculation, $departmentId);

        $weeksCount = $precalculation->getWeeksCount();

        // On sélectionne les pharmaciens (> 3 jours/sem) qui font parti de ce secteur
        $pharmaciensOfDepartment = $this->precalculation->pharmaciens->filter(function ($pharmacien) use ($departmentId) {
            if($pharmacien->workdays_per_week > 3) {
                foreach($pharmacien->departments as $department) {
                    if($department->id == $departmentId) {
                        return true;
                    };
                }
            }
        });

        // On sort les ids des pharmaciens ex: [1, 4]
        $ids = $pharmaciensOfDepartment->pluck('id')->toArray();

        // TODO: faire un filter pour retirer des ids (selon attributes pour toute la durée de l'horaire par exemple)
        // avant de générer les sampling

        // On calcule le score de disponibilité par pharmacien par semaine [1 => [20,20,20], 4 => [...]]
        $this->scores = $this->precalculation->calculateScore($ids, $departmentId);

        $this->combinaisons = $this->optimizedSampling($ids, $weeksCount);
        //todo: ajouter le dernier pharmacien à faire la semaine ICI
        $this->removeUsedSequence();

        $this->selectSequence();

        // Comment For Debug Only:
        $this->combinaisons = [];
    }

    private function selectSequence()
    {
        for($group = 0; $group < count($this->combinaisons); $group++) {
            $this->calculateScores($group);
            //todo: screen les dernières semaines pour déterminer le bonus

            if($group == 0) {
                //TODO: aller voir les dernières semaines dans la base de donnée
            } else {
                $this->bonusMalusPrecedingGroup($group);
            }

            $this->sortByScores($group);


            if (count($this->combinaisons[$group]) != 0) {
                $this->selectedSequence = $this->combinaisons[$group][0]['sequence'];

                $this->precalculation->assignWeekSequence($this->selectedSequence, $this->departmentId);
            } else {
                //TODO: generate conflict
                throw new \Exception("No more pharmacist available to assign. [Department = $this->departmentId]");
            }
        }
    }

    private function bonusMalusPrecedingGroup($group)
    {
        $bonus = $this->precalculation->bonus;
        $malus = $this->precalculation->malus;

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

            if($consecutive >= $bonus['weeks'])
                $this->combinaisons[$group][$i]['score'] += $bonus['pts'];
            if($consecutive >= $malus['weeks'])
                $this->combinaisons[$group][$i]['score'] -= $malus['pts'];
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
                            if ($department[($group * $this->weeksPerGroup) + $week] == $splitSequence[$week]) {
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
        $bonus = $this->precalculation->bonus;
        $malus  = $this->precalculation->malus;

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

                    if ($consecutiveCount >= $bonus['weeks']) {
                        $result += $bonus['pts'];
                    }

                    if ($consecutiveCount >= $malus['weeks']) {
                        $result -= $malus['pts'];
                    }

                } else {
                    //Ici on ce n'est plus le même pharmacien, donc on reset le décompte
                    $lastSeq = $id;
                    $consecutiveCount = 1;
                }

                $week++;
            }

            $this->combinaisons[$group][$i]["score"] = $result;
        }

    }

    private function sortByScores($group)
    {
        usort($this->combinaisons[$group], function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });
    }
}