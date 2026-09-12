<?php

namespace App\Http\Controllers;

use App\Builders\BuildStatus;
use App\Jobs\GenerateStatsByDepartments;
use App\Models\Schedule;
use App\Models\Statistic;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ScheduleStatDepartmentController extends Controller
{
    public function create(int $scheduleId): JsonResponse
    {
        Gate::authorize('write', Schedule::class);

        $schedule = $this->findSchedule($scheduleId);

        if ($schedule->status_statistics !== BuildStatus::Build) {
            $schedule->forceFill(['status_statistics' => BuildStatus::Build])->save();
            GenerateStatsByDepartments::dispatch($schedule->id);
        }

        return response()->json(['status' => 'accepted'], 202);
    }

    public function show(int $scheduleId): JsonResponse
    {
        $this->findSchedule($scheduleId);

        return response()->json(Statistic::where('type', 'department')
            ->where('schedule_id', $scheduleId)
            ->orderByDesc('id')
            ->first());
    }

    private function findSchedule(int $scheduleId): Schedule
    {
        return Schedule::where('branch_id', auth()->user()->branch_id)->findOrFail($scheduleId);
    }
}
