<?php

namespace App\Http\Controllers;

use App\Models\Constraint;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ConstraintValidatorController extends Controller
{
    /**
     * List all constraints to validate
     */
    #[Authorize('read', Constraint::class)]
    public function index(Request $request): Response
    {
        Gate::authorize('read', Constraint::class);

        $schedule = null;

        $constraints = Constraint::with(['constraintType', 'user']);
        if ($request->filled('schedule')) {
            $schedule = Schedule::select(['id', 'start_date', 'end_date'])->findOrFail($request->integer('schedule'));

            $constraints = $constraints->inDateInterval($schedule->start_date, $schedule->end_date);
        }

        $constraints = $constraints->where('status', 0)
            ->whereNull('number_of_occurrences')
            ->orderBy('start_datetime')
            ->get();

        return Inertia::render('constraintsValidator/index', [
            'constraints' => $constraints,
            'validatorId' => $request->user()->id,
            'schedule' => $schedule === null ? null : [
                'id' => $schedule->id,
                'start_date' => $schedule->start_date_string,
                'end_date' => $schedule->end_date_string,
            ],
        ]);
    }

    #[Authorize('write', Constraint::class)]
    public function update(Request $request, int $id): JsonResponse
    {
        $constraint = Constraint::findOrFail($id);
        $constraint->update($request->validate([
            'status' => ['required', 'integer', 'in:1,2'],
            'validated_by' => ['required', 'integer', 'exists:users,id'],
        ]));

        return response()->json(['status' => 'ok']);
    }

    #[Authorize('read', Constraint::class)]
    public function history(Request $request): Response
    {
        $limit = $request->limit ?? 100;
        $order = $request->order ?? 'desc';

        $constraints = Constraint::with(['constraintType', 'user', 'validator']);

        if (isset($request->user)) {
            $constraints = $constraints->where('user_id', $request->user);
        }

        if (isset($request->status)) {
            $constraints = $constraints->where('status', $request->status);
        } else {
            $constraints = $constraints->where('status', '!=', 0);
        }

        if (isset($request->validator)) {
            $constraints = $constraints->where('validated_by', $request->validator);
        }

        $constraints = $constraints->orderBy('updated_at', $order)
            ->limit($limit)
            ->get();

        return Inertia::render('constraintsValidator/history', [
            'constraints' => $constraints,
        ]);
    }
}
