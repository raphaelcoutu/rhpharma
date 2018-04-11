<?php

namespace App\Http\Controllers;

use App\AssignedShift;
use App\Schedule;
use App\User;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function show($scheduleId)
    {
        $schedule = Schedule::findOrFail($scheduleId);
        $pharmaciens = User::ownBranch()->where('is_active', 1)->get();
        $shifts = $this->createShifts($schedule, $pharmaciens);

        $assignedShifts = AssignedShift::with('shift')->InDateInterval($schedule->start_date, $schedule->end_date)->get();

        $assignedShifts->each(function ($assignedShift) use (&$shifts, $schedule) {
            $diff = $assignedShift->date->diffInDays($schedule->start_date);
            $shifts[$assignedShift->user_id][$diff][] = $assignedShift;
        });

        return view('calendar.show', [
            'schedule' => $schedule,
            'pharmaciens' => $pharmaciens,
            'shifts' => $shifts
        ]);
    }

    public function showByDepartment($scheduleId, $departmentId)
    {
        $schedule = Schedule::findOrFail($scheduleId);
        $pharmaciens = User::ownBranch()->where('is_active', 1)->whereHas('departments', function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId);
        })->get();
        $shifts = $this->createShifts($schedule, $pharmaciens);

        $assignedShifts = AssignedShift::with('shift')->InDateInterval($schedule->start_date, $schedule->end_date)->get();

        $assignedShifts->each(function ($assignedShift) use (&$shifts, $schedule) {
            $diff = $assignedShift->date->diffInDays($schedule->start_date);
            $shifts[$assignedShift->user_id][$diff][] = $assignedShift;
        });

        return view('calendar.show', [
            'schedule' => $schedule,
            'pharmaciens' => $pharmaciens,
            'shifts' => $shifts
        ]);
    }

    private function createShifts($schedule, $pharmaciens) {
        $shifts = [];
        $pharmaciens->each(function ($pharmacien) use ($schedule, &$shifts) {
            $shifts[$pharmacien->id] = [];
            for ($i = 0; $i <= $schedule->duration_in_weeks * 7; $i++) {
                $shifts[$pharmacien->id][$i] = null;
            }
        });
        return $shifts;
    }
}
