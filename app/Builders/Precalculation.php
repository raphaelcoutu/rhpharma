<?php

namespace App\Builders;

use App\AssignedShift;
use App\Department;
use App\Schedule;
use App\User;

class Precalculation
{
    private $weeksPerGroup = 4;

    private $daysTemplate;
    private $availability;
    private $scoreTable;
    private $allocatedWeeks;

    public $pharmaciens;
    public $departments;

    public $schedule;
    public $assignedShifts;

    public $scheduleWeeks;
    public $scheduleDays;

    public function __construct($scheduleId)
    {
        // Récupérer la Schedule
        $this->schedule = Schedule::select('start_date', 'end_date')->where('branch_id', 1)->findOrFail($scheduleId);

        //Récupérer les chiffres déjà assignés pour l'horaire en cours
        $this->assignedShifts = AssignedShift::where('date', '>=', $this->schedule->start_date)->where('date', '<=', $this->schedule->end_date)->get();

        // Récupérer les pharmaciens avec constraintes associées
        $this->pharmaciens = User::with(['constraints' => function ($query) {
            $query->InInterval($this->schedule->start_date, $this->schedule->end_date)->where('status', 1);
        }, 'departments'])->select('id', 'firstname', 'lastname', 'workdays_per_week', 'is_manual')->where('is_active', 1)->where('branch_id', 1)->get();

        $this->departments = Department::with(['users' => function ($query) {
            $query->where('is_active', 1)->where('branch_id', 1)->get();
        }])->get();

        $this->getScoreTable();

        $this->generateAvailability();

        $this->calculateAllocation();
    }

    public function getWeeksCount()
    {
        $start = $this->schedule->start_date;
        $end = $this->schedule->end_date;

        return $end->diffInWeeks($start) + 1;
    }

    public function calculateScore($ids, $departmentId) {
        if(empty($ids)) {
            throw new \Exception("No id in this department: {$departmentId}");
        }

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
                        $scoreWeek += $this->scoreTable[$departmentId][$j][0];
                    }
                    if($this->availability[$id]['days'][$day]['PM'] == 0) {
                        $scoreWeek += $this->scoreTable[$departmentId][$j][1];
                    }
                }
                $scoreSchedule[] = $scoreWeek;
            }
            $scores[$id] = $scoreSchedule;
        }

        return $scores;
    }

    public function assignWeekSequence($departmentId, $group, $sequence)
    {
        $splitSequence = explode(',', $sequence);
        foreach($splitSequence as $seq) {
            $this->scheduleWeeks[$departmentId][] = $seq;
        }

        for($weeks = 0; $weeks < count($splitSequence); $weeks++) {
            $pharmacienId = $splitSequence[$weeks];
            for($days = 1; $days <= 5; $days++) {
                $i = $days+7*($weeks+$group*$this->weeksPerGroup);

                $day = &$this->availability[$pharmacienId]['days'][$i];

                if($day['AM'] == 0 && $day['PM'] == 0) {
                    $day['AM'] = 1;
                    $day['PM'] = 1;

                    $day['AM_department'] = $departmentId;
                    $day['PM_department'] = $departmentId;
                } else if($day['AM'] == 0) {
                    $day['AM'] = 1;
                    $day['AM_department'] = $departmentId;
                } else if($day['PM'] == 0) {
                    $day['PM'] = 1;
                    $day['PM_department'] = $departmentId;
                }
            }
        }
    }

    public function getAvailability()
    {
        return $this->availability;
    }

    public function getAllocatedWeeks($departmentId = null)
    {
        return is_null($departmentId) ? $this->allocatedWeeks : $this->allocatedWeeks[$departmentId];
    }

    public function getDaysTemplate()
    {
        return $this->daysTemplate;
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

    private function generateAvailability()
    {
        $this->generateDaysTemplate();

        $this->generateMainTemplate();

        $this->removeConstraintsFromAvailability();
        $this->removeAssignedShiftsFromAvailability();
        //TODO: filter par attributs aussi!! (maladie, vacances, maternité)
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

    private function removeConstraintsFromAvailability()
    {
        foreach ($this->pharmaciens as $pharmacien) {
            foreach ($pharmacien->constraints as $constraint) {
                $dayInSchedule = $constraint->start_datetime->diffInDays($this->schedule->start_date);

                $actualAvailability = $this->availability[$pharmacien->id]['days'][$dayInSchedule];

                $this->availability[$pharmacien->id]['days'][$dayInSchedule] = ConstraintConverter::make($constraint, $actualAvailability);
            }

        }
    }

    private function removeAssignedShiftsFromAvailability() {
        $this->assignedShifts->each(function ($shift) {
            $dayInSchedule = $shift->date->diffInDays($this->schedule->start_date);

            $actualAvailability = &$this->availability[$shift->user_id]['days'][$dayInSchedule];
            $actualAvailability['AM'] = 1;
            $actualAvailability['PM'] = 1;
            $actualAvailability['shift'] = $shift->shift_id;
        });
    }

    private function getScoreTable()
    {
        $this->departments->each(function ($department) {
            $departmentScore = [
                [$department->monday_am, $department->monday_pm],
                [$department->tuesday_am, $department->tuesday_pm],
                [$department->wednesday_am, $department->wednesday_pm],
                [$department->thursday_am, $department->thursday_pm],
                [$department->friday_am, $department->friday_pm]
            ];

            $this->scoreTable[$department->id] = $departmentScore;
        });
    }

    //TODO: change to private
    public function calculateAllocation() {
        $totalPlanning = $this->departments->mapWithKeys(function ($department) {
            return [$department->id => $department->users->map(function ($user) {
                return $user->pivot->planning_short;
            })->sum()];
        });

        foreach($this->departments as $department) {
            foreach($department->users as $user) {
                if($totalPlanning[$department->id] > 75) {
                    $planningShort = $user->pivot->planning_short;

                    $allocated = floor(($planningShort + 0.01) / 100 * $this->getWeeksCount());
                } else {
                    // BugFix temporaire dû à l'attribution de bonus/malus pour ratio de séquence allouées
                    $allocated = 1000;
                }

                $this->allocatedWeeks[$department->id][$user->id] = $allocated;
            }
        }

        return $this->allocatedWeeks;
    }
}