<?php

namespace App\Http\Controllers;

use App\Models\AssignedShift;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CalendarController extends Controller
{
    public function show($scheduleId)
    {
        $data = $this->query($scheduleId);

        return view('calendar.show', $data);
    }

    public function showByDepartment($scheduleId, $departmentId)
    {
        $data = $this->query($scheduleId, $departmentId);

        return view('calendar.show', $data);
    }

    // API
    public function getUserData(Request $request)
    {
        $parsedDate = Carbon::parse($request->date);

        $user = User::with(['constraints' => function ($query) use ($parsedDate) {
            $query->InDateInterval($parsedDate, $parsedDate)->whereHas('constraintType', function ($constraintType) {
                $constraintType->where('is_group_constraint', 0);
            });
        }, 'constraints.constraintType' => function ($query) {
            $query->select(['id', 'name', 'code']);
        }, 'assignedShifts' => function ($query) use ($parsedDate) {
            $query->where('date', $parsedDate);
        }])->find($request->userId);
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
            ->where('user_id', $request->user_id)->pluck('shift_id');

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

        return AssignedShift::with('shift')
            ->where('user_id', $request->user_id)
                ->where('date', $request->date)->get();
    }

    public function getShifts()
    {
        return Shift::orderBy('code')->get();
    }

    public function setSelectedData(Request $request)
    {
        foreach ($request['selected'] as $selected) {
            AssignedShift::where('user_id', $selected['user_id'])
                ->where('date', Carbon::parse($selected['date']))
                ->delete();
        }

        $add = [];
        foreach($request['shifts'] as $shift) {
            foreach($request['selected'] as $selected) {
                $add[] = [
                    'user_id' => $selected['user_id'],
                    'shift_id' => $shift,
                    'is_generated' => 0,
                    'is_published' => 0,
                    'date' => $selected['date'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        AssignedShift::insert($add);

        return $add;
    }

    private function query($scheduleId, $departmentIds = null) {
        $schedule = Schedule::findOrFail($scheduleId);

        $users = User::with(['assignedShifts' => function($query) use ($schedule) {
            $query->InDateInterval($schedule->start_date, $schedule->end_date);
        }, 'constraints' => function($query) use ($schedule) {
            $query->InDateInterval($schedule->start_date, $schedule->end_date)
                ->where('status', 1)
                ->whereHas('constraintType', function ($query) {
                    $query->where('is_group_constraint', 0);
                });
        }, 'assignedShifts.shift', 'constraints.constraintType'])->ownBranch()->where('is_active', 1);

        if($departmentIds) {
            $departmentIds = explode(",", $departmentIds);
            $users = $users->whereHas('departments', function ($query) use ($departmentIds) {
                $query->whereIn('department_id', $departmentIds);
            });
        }

        $users = $users->orderBy('lastname')->orderBy('firstname')->get();

        $departments = Department::orderBy('name')->get();

        return [
            'schedule' => $schedule,
            'users' => $users,
            'departments' => $departments
        ];
    }
}
