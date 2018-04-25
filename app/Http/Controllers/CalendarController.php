<?php

namespace App\Http\Controllers;

use App\AssignedShift;
use App\Constraint;
use App\Schedule;
use App\User;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function show($scheduleId)
    {
        list($schedule, $pharmaciens, $shifts) = $this->query($scheduleId);

        return view('calendar.show', [
            'schedule' => $schedule,
            'pharmaciens' => $pharmaciens,
            'shifts' => $shifts
        ]);
    }

    public function showByDepartment($scheduleId, $departmentId)
    {

        list($schedule, $pharmaciens, $shifts) = $this->query($scheduleId, $departmentId);

        return view('calendar.show', [
            'schedule' => $schedule,
            'pharmaciens' => $pharmaciens,
            'shifts' => $shifts
        ]);
    }

    private function query($scheduleId, $departmentId = null) {
        $schedule = Schedule::findOrFail($scheduleId);

        $pharmaciens = User::ownBranch()->where('is_active', 1);
        if($departmentId) {
            $pharmaciens = $pharmaciens->whereHas('departments', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            });
        }
        $pharmaciens = $pharmaciens->orderBy('lastname')->get();


        $shifts = $this->createShifts($schedule, $pharmaciens);
        $assignedShifts = AssignedShift::with('shift')->InDateInterval($schedule->start_date, $schedule->end_date)->get();
        $constraints = Constraint::with('constraintType')->whereHas('constraintType', function ($query) {
            $query->where('is_group_constraint', 0);
        })->InDateInterval($schedule->start_date, $schedule->end_date)
            ->where('status', 1)
            ->get();

        $assignedShifts->each(function ($assignedShift) use (&$shifts, $schedule) {
            $diff = $assignedShift->date->diffInDays($schedule->start_date);
            $shifts[$assignedShift->user_id][$diff][] = $assignedShift;
        });

        $constraints->each(function ($constraint) use (&$shifts, $schedule) {
            // On met l'heure de fin de la contrainte à 23h59 pour être certain que tous les jours soient affichés
            // Exemple : Contrainte de moins de 24h mais débutant le soir la veille
            $constraintDuration = $constraint->end_datetime->copy()->setTime(23,59)->diffInDays($constraint->start_datetime->copy()->setTime(0,0)) + 1;

            // On ajuste si la contrainte débutait avant le début de l'horaire
            if($constraint->start_datetime->lt($schedule->start_date)) {
                $diffToScheduleStart = 0;
                $constraintDuration -= $schedule->start_date->diffInDays($constraint->start_datetime->setTime(0,0));
            } else {
                $diffToScheduleStart = $constraint->start_datetime->diffInDays($schedule->start_date);
            }

            // On ajuste la durée si la contrainte continue après la fin de l'horaire
            if($constraint->end_datetime->gt($schedule->end_date->setTime(23,59))) {
                $constraintDuration -= $constraint->end_datetime->diffInDays($schedule->end_date);
            }

            for ($i = 0; $i < $constraintDuration; $i++) {
                if($constraint->day !== NULL && $i % 7 !== $constraint->day) {
                    $shifts[$constraint->user_id][$diffToScheduleStart+$i][] = null;
                } else {
                    $shifts[$constraint->user_id][$diffToScheduleStart+$i][] = $constraint;
                }
            }
        });

        return [$schedule, $pharmaciens, $shifts];
    }

    private function createShifts($schedule, $pharmaciens) {
        $shifts = [];
        $pharmaciens->each(function ($pharmacien) use ($schedule, &$shifts) {
            $shifts[$pharmacien->id] = [];
            for ($i = 0; $i < $schedule->duration_in_weeks * 7; $i++) {
                $shifts[$pharmacien->id][$i] = null;
            }
        });
        return $shifts;
    }
}
