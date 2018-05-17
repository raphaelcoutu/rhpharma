<?php

namespace App\Http\Controllers;

use App\AssignedShift;
use App\Constraint;
use App\Schedule;
use App\Shift;
use App\User;
use Carbon\Carbon;
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

    // API
    public function getUserData($userId, $date)
    {
        $parsedDate = Carbon::parse($date);

        $user = User::with(['constraints' => function ($query) use ($parsedDate) {
            $query->InDateInterval($parsedDate, $parsedDate);
        }, 'constraints.constraintType' => function ($query) {
            $query->select('id', 'name');
        }, 'assignedShifts' => function ($query) use ($parsedDate) {
            $query->where('date', $parsedDate);
        }])->find($userId);
        $shifts = Shift::orderBy('code')->get();

        return [
            'user' => $user,
            'assignedShifts' => $user->assignedShifts,
            'constraints' => $user->constraints,
            'shifts' => $shifts,
        ];
    }

    // API
    public function setUserData(Request $request)
    {
        $actualShifts = AssignedShift::select('shift_id')->where('date', $request->date)
            ->where('user_id', $request->user_id)->get()->pluck('shift_id');

        // On regarde les shiftsType identiques
        $intersect = $actualShifts->whereIn(null, $request->shifts);

        // On compare différentiellement chacun des collections avec l'intersect
        $shiftsToAdd = collect($request->shifts)->whereNotIn(null, $intersect);
        $actualShiftsToRemove = $actualShifts->whereNotIn(null, $intersect);

        if($shiftsToAdd->count() > 0) {
            $add = [];
            foreach($shiftsToAdd as $shift) {
                $add[] = [
                    'user_id' => $request->user_id,
                    'shift_id' => $shift,
                    'is_generated' => 0,
                    'is_published' => 0,
                    'date' => $request->date,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            AssignedShift::insert($add);
        }

        if($actualShiftsToRemove->count() > 0) {
            AssignedShift::where('user_id', $request->user_id)
                ->where('date', $request->date)
                ->whereIn('shift_id', $actualShiftsToRemove)
                ->delete();
        }
    }

    private function query($scheduleId, $departmentIds = null) {
        $schedule = Schedule::findOrFail($scheduleId);

        $pharmaciens = User::ownBranch()->where('is_active', 1);
        if($departmentIds) {
            $departmentIds = explode(",", $departmentIds);
            $pharmaciens = $pharmaciens->whereHas('departments', function ($query) use ($departmentIds) {
                $query->whereIn('department_id', $departmentIds);
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
