<?php

namespace App\Http\Controllers;

use App\Schedule;
use App\User;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function show($scheduleId)
    {
        $schedule = Schedule::findOrFail($scheduleId);
        $pharmaciens = User::with('assignedshifts.department')->ownBranch()->where('is_active', 1)->get();
        $shifts = $this->retreivingShifts($schedule, $pharmaciens);

        return view('calendar.show', [
            'schedule' => $schedule,
            'pharmaciens' => $pharmaciens,
            'shifts' => $shifts
        ]);
    }

    public function showByDepartment($scheduleId, $departmentId)
    {
        $schedule = Schedule::findOrFail($scheduleId);
        $pharmaciens = User::with(['assignedshifts.department'])->whereHas('departments', function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId);
        })->ownBranch()->get();
        $shifts = $this->retreivingShifts($schedule, $pharmaciens);

        return view('calendar.show', [
            'schedule' => $schedule,
            'pharmaciens' => $pharmaciens,
            'shifts' => $shifts
        ]);
    }

    private function retreivingShifts($schedule, $pharmaciens) {
        $shifts = [];
        $pharmaciens->each(function ($pharmacien) use ($schedule, &$shifts) {
            $shifts[$pharmacien->id] = [];
            for ($i = 0; $i <= $schedule->duration_in_weeks * 7; $i++) {
                $shifts[$pharmacien->id][$i] = null;
                $realDate = $schedule->start_date->addDays($i);

                $assignedShifts = $pharmacien->assignedshifts->filter(function ($shift) use ($realDate) {
                    return $shift->date == $realDate;
                });

                if ($assignedShifts->count() > 0) {
                    $shifts[$pharmacien->id][$i] = $assignedShifts->first()->department->code;
                }
            }
        });

        return $shifts;
    }
}
