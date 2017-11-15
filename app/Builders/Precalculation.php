<?php

namespace App\Builders;

use App\Schedule;
use App\User;

class Precalculation
{
    private $daysTemplate;
    private $availability;

    public $pharmaciens;

    public $schedule;
    public $bonus;
    public $malus;

    public $scheduleWeeks;
    public $scheduleDays;

    public function __construct($scheduleId)
    {
        // Récupérer la Schedule
        $this->schedule = Schedule::select('start_date', 'end_date')->where('branch_id', 1)->findOrFail($scheduleId);

        // Récupérer les pharmaciens avec constraintes associées
        $this->pharmaciens = User::with(['constraints' => function ($query) {
            $query->InInterval($this->schedule->start_date, $this->schedule->end_date)->where('status', 1);
        }, 'departments'])->select('id', 'workdays_per_week')->where('is_active', 1)->where('branch_id', 1)->get();

        $this->generateAvailability();

        //TODO: Coder la page pour setter les bonus/malus
        $this->bonus = ['weeks' => 2, 'pts' => 4];
        $this->malus = ['weeks' => 3, 'pts' => 8];
    }

    public function getWeeksCount()
    {
        $start = $this->schedule->start_date;
        $end = $this->schedule->end_date;

        return $end->diffInWeeks($start) + 1;
    }

    public function calculateScore($ids, $departmentId) {
        if(empty($ids)) {
            throw new \Exception('No id in this department');
        }

        //todo: Query le tableau de score via page de paramètres
        $scoreTable = [
            2 => [
                [2,2],
                [2,2],
                [2,2],
                [2,2],
                [2,2]
            ],
            4 => [
                [2,2],
                [2,2],
                [2,2],
                [2,2],
                [2,2]
            ],
            5 => [
                [2,2],
                [2,2],
                [2,2],
                [2,2],
                [2,2]
            ],
            6 => [
                [2,2],
                [2,2],
                [2,2],
                [2,2],
                [2,2]
            ],
            8 => [
                [2,2],
                [2,2],
                [2,2],
                [2,2],
                [2,2]
            ],
        ];

        $scores = [];

        foreach($ids as $id) {
            $scoreSchedule = [];
            // Chaque semaine
            for($i = 0; $i < $this->getWeeksCount(); $i++) {
                $scoreWeek = 0;
                // Chaque jour
                for($j = 0; $j < 5; $j++){
                    $day = $i*7+(1+$j);

                    if($this->availability[$id]['days'][$day]['AM'] == 0) {
                        $scoreWeek += $scoreTable[$departmentId][$j][0];
                    }
                    if($this->availability[$id]['days'][$day]['PM'] == 0) {
                        $scoreWeek += $scoreTable[$departmentId][$j][1];
                    }
                }
                $scoreSchedule[] = $scoreWeek;
            }
            $scores[$id] = $scoreSchedule;
        }

        return $scores;
    }

    public function assignWeekSequence($sequence, $departmentId)
    {
        $splitSequence = explode(',', $sequence);
        foreach($splitSequence as $seq) {
            $this->scheduleWeeks[$departmentId][] = $seq;
        }

        for($i = 0; $i < count($splitSequence); $i++) {
            $pharmacienId = $splitSequence[$i];
            for($j = 0; $j < 5; $j++) {
                $day = $i*7+($j+1);

                $am = &$this->availability[$pharmacienId]['days'][$day]['AM'];
                $pm = &$this->availability[$pharmacienId]['days'][$day]['PM'];

                if($am == 0 && $pm == 0) {
                    $am = 1;
                    $pm = 1;
                } else if($am != 0) {
                    $pm = 0;
                } else if($pm != 0) {
                    $am = 0;
                }
            }
        }
    }

    public function getAvailability()
    {
        return $this->availability;
    }

    private function generateAvailability()
    {
        $this->generateDaysTemplate();

        $this->generateMainTemplate();

        foreach($this->pharmaciens as $pharmacien) {
            foreach ($pharmacien->constraints as $constraint) {

                //TODO: faire pour chacun des types de contrainte disponible
                if ($constraint->constraint_type_id === 2) {
                    $dayInSchedule = $constraint->start_datetime->diffInDays($this->schedule->start_date);

                    $this->availability[$pharmacien->id]['days'][$dayInSchedule]['AM'] = 2;
                    $this->availability[$pharmacien->id]['days'][$dayInSchedule]['PM'] = 2;
                }
            }

            //TODO: filter par attributs aussi!! (maladie, vacances, maternité)
        }
    }

    private function generateDaysTemplate()
    {
        $daysTemplate = [];
        $number_of_days_in_schedule = $this->schedule->end_date->diffInDays($this->schedule->start_date) +1;

        for ($i = 0; $i < $number_of_days_in_schedule; $i++) {
            $daysTemplate[$i] = [
                'AM' => 0,
                'PM' => 0,
                'date' => $this->schedule->start_date->addDays($i)];
        }

        $this->daysTemplate = $daysTemplate;
    }

    private function generateMainTemplate()
    {
        foreach($this->pharmaciens as $pharmacien) {
            $temp = [];
            $temp['workdays_per_week'] = $pharmacien->workdays_per_week;
            $temp['days'] = $this->daysTemplate;

            $this->availability[$pharmacien->id] = $temp;
        }
    }
}