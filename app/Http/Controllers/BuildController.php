<?php

namespace App\Http\Controllers;

use App\Events\UpdateBuildStatus;
use App\Http\Requests\BuildStatusRequest;
use App\Models\BuildMessage;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class BuildController extends Controller
{
    public function updateStatus(BuildStatusRequest $request): JsonResponse
    {
        Gate::authorize('write', Schedule::class);

        $data = $request->validated();
        $schedule = $this->findSchedule((int) $data['scheduleId']);

        event(new UpdateBuildStatus(
            $schedule->id,
            $data['buildStep'],
            (int) $data['status'],
        ));

        return response()->json(['status' => 'accepted'], 202);
    }

    public function status(int $scheduleId): JsonResponse
    {
        Gate::authorize('write', Schedule::class);

        $schedule = $this->findSchedule($scheduleId);
        $messages = $schedule->buildMessages()
            ->orderBy('id')
            ->limit(100)
            ->get()
            ->map(fn (BuildMessage $message): array => [
                'id' => $message->id,
                'timestamp' => $message->created_at?->format('Y/m/d H:i:s'),
                'message' => $message->message,
            ])
            ->values();

        return response()->json([
            'statuses' => [
                'holidays' => $schedule->status_holidays,
                'weekends' => $schedule->status_weekends,
                'lastEvening' => $schedule->status_last_evening,
                'clinical' => $schedule->status_clinical_departments,
            ],
            'statisticsStatus' => $schedule->status_statistics,
            'messages' => $messages,
        ]);
    }

    private function findSchedule(int $scheduleId): Schedule
    {
        return Schedule::where('branch_id', auth()->user()->branch_id)->findOrFail($scheduleId);
    }
}
