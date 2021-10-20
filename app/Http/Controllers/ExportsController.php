<?php

namespace App\Http\Controllers;

use App\Exports\Excel;
use App\Models\Schedule;
use App\Models\User;

class ExportsController extends Controller
{
    public function export(Schedule $schedule)
    {
        $users = User::with(['constraints' => function ($query) use ($schedule) {
            $query->InDateInterval($schedule->start_date, $schedule->end_date)->where('status', 1);
        }, 'assignedShifts' => function ($query) use ($schedule) {
            $query->InDateInterval($schedule->start_date, $schedule->end_date);
        },'constraints.constraintType', 'assignedShifts.shift'])
            ->where('is_active', 1)
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->get();

        $export = new Excel($schedule, $users);

        return $export->download();
    }
}
