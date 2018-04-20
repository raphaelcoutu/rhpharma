<?php

namespace App\Builders;

use App\AssignedShift;
use App\Department;
use App\Schedule;
use App\Shift;
use App\User;
use Carbon\Carbon;

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

        $this->cleanUpAssignedShifts();

        //Récupérer les chiffres déjà assignés pour l'horaire en cours
        $this->assignedShifts = AssignedShift::where('date', '>=', $this->schedule->start_date)->where('date', '<=', $this->schedule->end_date)->get();

        // Récupérer les pharmaciens avec constraintes associées et les shifts déjà assignés (fériés, fin de semaines, etc..)
        $this->pharmaciens = User::with(['constraints' => function ($query) {
            $query->InDateInterval($this->schedule->start_date, $this->schedule->end_date)->where('status', 1);
        }, 'assignedShifts' => function ($query) {
            $query->InDateInterval($this->schedule->start_date, $this->schedule->end_date);
        }, 'assignedShifts.shift.shiftType', 'departments'])->select('id', 'firstname', 'lastname', 'workdays_per_week', 'is_manual')->where('is_active', 1)->where('branch_id', 1)->get();

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

                    if($this->isAvailableBetween($id, $day, '08:00', '12:00')) {
                        $scoreWeek += $this->scoreTable[$departmentId][$j][0];
                    }
                    if($this->isAvailableBetween($id, $day, '13:00', '16:00')) {
                        $scoreWeek += $this->scoreTable[$departmentId][$j][1];
                    }
                }
                $scoreSchedule[] = $scoreWeek;
            }
            $scores[$id] = $scoreSchedule;
        }

        return $scores;
    }

    private function isAvailableBetween($id, $day, $startTime, $endTime)
    {
        $assignedShifts = $this->pharmaciens->firstWhere('id', $id)->assignedShifts;
        $constraints = $this->availability[$id]['days'][$day]['constraints'];
        $realDate = $this->schedule->start_date->addDays($day)->toDateString();

        $startDateTime = \Carbon\Carbon::parse($realDate . ' ' . $startTime);
        $endDateTime = \Carbon\Carbon::parse($realDate . ' ' . $endTime);

        // Détection si le pharmacien a déjà un shift assigné au même intervalle de temps
        foreach($assignedShifts as $assignedShift) {
            $assignedStart = Carbon::parse($assignedShift->date->toDateString() . ' '
                . $assignedShift->shift->shiftType->start_time);
            $assignedEnd = Carbon::parse($assignedShift->date->toDateString() . ' '
                . $assignedShift->shift->shiftType->end_time);

            if(detectsIntervalCollision($assignedStart, $assignedEnd,
                $startDateTime, $endDateTime)) {
                return false;
            }
        }

        // Détection si le pharmacien a une contrainte au même intervalle de temps
        foreach($constraints as $constraint) {
            if(detectsIntervalCollision($constraint->start_datetime, $constraint->end_datetime,
                $startDateTime, $endDateTime)) {
                return false;
            }
        }

        return true;
    }

    public function assignWeekSequence($departmentId, $group, $sequence)
    {
        $splitSequence = explode(',', $sequence);
        foreach($splitSequence as $seq) {
            $this->scheduleWeeks[$departmentId][] = $seq;
        }

        // Sélectionner les shifts par département / mettre en ordre du plus grand intervalle
        $shiftsForDepartment = Shift::with('shiftType')->where('department_id', $departmentId)->get();
        $shiftsForDepartment->map(function ($shift) {
            $shift->interval = strtotime($shift->shiftType->end_time) - strtotime($shift->shiftType->start_time);
        })->sortByDesc('interval');

        $newAssignedShifts = [];

        for($weeks = 0; $weeks < count($splitSequence); $weeks++) {
            $pharmacienId = $splitSequence[$weeks];

            // Si la semaine attribuée est null (semaine remplie manuellement), on sort de la fonction
            if(is_null($pharmacienId) || $pharmacienId == "") continue;

            for($days = 1; $days <= 5; $days++) {
                $i = $days+7*($weeks+$group*$this->weeksPerGroup);
                $realDate = $this->schedule->start_date->addDays($i)->toDateString();

                $day = &$this->availability[$pharmacienId]['days'][$i];

                if($this->isAvailableBetween($pharmacienId, $i, '08:00', '16:30')) {
                    $newAssignedShifts[] = [
                        'user_id' => $pharmacienId,
                        'shift_id' => $shiftsForDepartment->first()->id,
                        'is_generated' => 1,
                        'is_published' => 0,
                        'date' => $realDate,
                        'created_at' => new \DateTime(),
                        'updated_at' => new \DateTime()
                    ];
                }
            }
        }

        AssignedShift::insert($newAssignedShifts);
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
                'date' => $this->schedule->start_date->addDays($i),
                'shifts' => [],
                'constraints' => []
            ];
        }

        $this->daysTemplate = $daysTemplate;
    }

    private function generateAvailability()
    {
        $this->generateDaysTemplate();

        $this->generateMainTemplate();

        $this->addConstraintsToUser();

        $this->addAssignedShiftsToUser();
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

    private function addConstraintsToUser()
    {
        foreach ($this->pharmaciens as $pharmacien) {
            foreach ($pharmacien->constraints as $constraint) {
                // Si la contrainte débute avant l'horaire, on ajuste les jours. Sinon, on laisse idem.
                if($constraint->start_datetime->lt($this->schedule->start_date)) {
                    $dayInSchedule = 0;
                    $duration = $constraint->end_datetime->diffInDays($constraint->start_datetime) + 1;
                    $duration -= $this->schedule->start_date->diffInDays($constraint->start_datetime) + 1;
                } else {
                    $dayInSchedule = $constraint->start_datetime->diffInDays($this->schedule->start_date);
                    $duration = $constraint->end_datetime->diffInDays($constraint->start_datetime) + 1;
                }

                // Ajouter la contrainte à chacun des jours qu'elle est impliquée.
                for ($day = $dayInSchedule; $day < ($dayInSchedule + $duration); $day++) {
                    $this->availability[$pharmacien->id]['days'][$day]['constraints'][] = $constraint;
                }

            }

        }
    }

    private function addAssignedShiftsToUser() {
        $this->assignedShifts->each(function ($shift) {
            $dayInSchedule = $shift->date->diffInDays($this->schedule->start_date);

            $this->availability[$shift->user_id]['days'][$dayInSchedule]['shifts'][] = $shift;
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

    private function cleanUpAssignedShifts()
    {
        // On fait le ménage
        AssignedShift::where('date', '>=', $this->schedule->start_date)
            ->where('date', '<=', $this->schedule->end_date)
            ->where('is_generated', 1)
            ->where('is_published', 0)
            ->delete();
    }
}