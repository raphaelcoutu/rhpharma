<?php

namespace App\Http\Controllers;


use App\Models\Jobs\GenerateStatsByDepartments;
use App\Models\Statistic;

class ScheduleStatDepartmentController extends Controller
{
    public function create($scheduleId)
    {
        GenerateStatsByDepartments::dispatch($scheduleId);
    }

    public function show($scheduleId)
    {
        return Statistic::where('type', 'department')
            ->where('schedule_id', $scheduleId)
            ->orderByDesc('id')
            ->first();
    }
}
