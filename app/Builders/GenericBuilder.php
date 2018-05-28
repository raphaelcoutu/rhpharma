<?php

namespace App\Builders;


use App\AssignedShift;
use App\Department;
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

        // Correction pour les 4 jours par semaine
        $this->partialTimeCorrection();

        Log::debug('Department Id : ' . $departmentId . ' - Memory usage ' . round(memory_get_usage() / pow(1024,2),2) . ' Mo'
            . ' (' . round(microtime(true) - $start, 2) . 's)');

        // Comment For Debug Only:
        $this->combinaisons = [];
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

    private function partialTimeCorrection()
    {
        // On parcours chacune des semaines

        // On évite le secteur VIH, car secteur à 4 jours par semaine.
        if($this->departmentId === 7) return;

        // Définir les variables pertinentes && requêtes à la BD
        $schedule = $this->precalculation->schedule;
        $holidays = $this->precalculation->holidays->pluck('date');
        $assignedShifts = AssignedShift::with('user', 'shift')
            ->inDateInterval($schedule->start_date, $schedule->end_date)->get();

        // Itération de toutes les semaines
        for ($i = 0; $i < $schedule->durationInWeeks; $i++) {
            $day = $i * 7 + 1; // Correspond au lundi
            $firstWorkday = $schedule->start_date->addDays($day);
            $previousWorkday = $schedule->start_date->addDays($day - 3);

            $lastWorkday = $schedule->start_date->addDays($day + 4);
            $nextWorkday = $schedule->start_date->addDays($day + 7);

            $departmentAssignedShifts = $assignedShifts->where('shift.department_id', $this->departmentId);

            $firstAssignedShift = $departmentAssignedShifts->where('date', $firstWorkday)->first();
            $previousAssignedShift = $departmentAssignedShifts->where('date', $previousWorkday)->first();

            $lastAssignedShift = $departmentAssignedShifts->where('date', $lastWorkday)->first();
            $nextAssignedShift = $departmentAssignedShifts->where('date', $nextWorkday)->first();

            // Si le pharmacien n'est pas à 4 jours/sem, on passe à la prochaine semaine.
            if(!$firstAssignedShift || $firstAssignedShift->user->workdays_per_week !== 4) continue;

            // On regarde le nombre de jours attribué à notre pharmacien 4 jr/sem. (utile plus loin)
            $userAssignedShifts = $assignedShifts->where('user_id', $firstAssignedShift->user->id)
                ->where('date', '>=', $firstAssignedShift->date->startOfWeek()->addDays(-1))
                ->where('date', '<=', $firstAssignedShift->date->endOfWeek()->addDays(-1));

            // PARTIE : DÉBUT DE LA SEMAINE
            // Si on a été capable de savoir à qui appartient la semaine
            if($firstAssignedShift) {

                // On regarde qui était le pharmacien le vendredi avant (s'il y en avait un)
                // et si ce pharmacien n'est pas lui-même
                // Puis, s'il est disponible, on le prend.
                if($previousAssignedShift && $firstAssignedShift->user->id !== $previousAssignedShift->user->id) {
                    $previousUser = $previousAssignedShift->user;
                    $firstWorkdayInt = $schedule->start_date->diffInDays($firstWorkday);

                    // On regarde s'il est disponible
                    if(!$this->precalculation->isAvailableBetween($previousUser->id, $firstWorkdayInt, '08:00', '16:30')) continue;

                    // On regarde les jours attribué à la personne (samedi à dimanche)
                    $previousUserAssignedShifts = $assignedShifts->where('user_id', $previousUser->id)
                        ->where('date', '>=', $firstAssignedShift->date->startOfWeek()->addDays(-1))
                        ->where('date', '<=', $firstAssignedShift->date->endOfWeek()->addDays(-1));

                    // Si le nombre de jours est inférieur à son nombre dispo par semaine:
                    // On fait l'échange de shift et on passe à la prochaine itération (pour pas lui enlever le vendredi)
                    if($previousUserAssignedShifts->count() < $previousUser->workdays_per_week) {

                        // Cas où on aurait un CPSS en lundi. Donc, horaire "fitte" pour notre 4jr/sem
                        // mais il manque une journée à notre secteur!
                        if($userAssignedShifts->count() <= 4) {
                            // Déterminer que le jour manquant est vraiment le lundi.
                            if($firstWorkday->dayOfWeek !== 2) continue;

                            $newShift = AssignedShift::create([
                                'user_id' => $previousUser->id,
                                'shift_id' => $firstAssignedShift->shift_id,
                                'is_generated' => 1,
                                'is_published' => 0,
                                'date' => $firstWorkday->addDays(-1),
                            ]);
                            $this->precalculation->pharmaciens->firstWhere('id', $previousUser->id)->assignedShifts->push($newShift);
                        }

                        // Cas classique où on doit donner le quart à la personne précédente.
                        else {
                            $firstAssignedShift->user_id = $previousUser->id;
                            $firstAssignedShift->save();

                            $this->precalculation->pharmaciens->firstWhere('id', $previousUser->id)->assignedShifts->push($firstAssignedShift);
                            continue;
                        }
                    }
                }
            }

            // PARTIE : FIN DE LA SEMAINE
            // Si on a été capable de savoir à qui appartient la semaine
            if($lastAssignedShift) {
                if($nextAssignedShift && $lastAssignedShift->user->id !== $nextAssignedShift->user->id) {
                    $nextUser = $nextAssignedShift->user;
                    $lastWorkdayInt = $schedule->start_date->diffInDays($lastWorkday);

                    // On regarde s'il a une contrainte
                    if(!$this->precalculation->isAvailableBetween($nextUser->id, $lastWorkdayInt, '08:00', '16:30')) continue;

                    // On regarde les jours attribué à la personne
                    $nextUserAssignedShifts = $assignedShifts->where('user_id', $nextUser->id)
                        ->where('date', '>=', $lastAssignedShift->date->startOfWeek()->addDays(-1))
                        ->where('date', '<=', $lastAssignedShift->date->endOfWeek()->addDays(-1));

                    if($nextUserAssignedShifts->count() < $nextUser->workdays_per_week) {
                        $lastAssignedShift->user_id = $nextUser->id;
                        $lastAssignedShift->save();

                        $this->precalculation->pharmaciens->firstWhere('id', $nextUser->id)->assignedShifts->push($lastAssignedShift);
                        continue;
                    }

                }
            }
        }
    }
}