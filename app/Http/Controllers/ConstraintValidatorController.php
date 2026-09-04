<?php

namespace App\Http\Controllers;

use App\Models\Constraint;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class ConstraintValidatorController extends Controller
{
    /**
     * List all constraints to validate
     */
    #[Authorize('read', Constraint::class)]
    public function index()
    {
        $schedule = null;

        $constraints = Constraint::with(['constraintType', 'user']);
        if (request('schedule')) {
            $schedule = Schedule::select(['id', 'start_date', 'end_date'])->findOrFail(request('schedule'));

            $constraints = $constraints->inInterval($schedule->start_date, $schedule->end_date);
        }

        $constraints = $constraints->where('status', 0)
            ->whereNull('number_of_occurrences')
            ->orderBy('start_datetime')
            ->get();

        return view('constraintsValidator.index', compact('constraints', 'schedule'));
    }

    #[Authorize('write', Constraint::class)]
    public function update(Request $request, $id)
    {
        $constraint = Constraint::findOrFail($id);
        $constraint->update($request->all());

        return 'OK';
    }

    #[Authorize('read', Constraint::class)]
    public function history(Request $request)
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

        return view('constraintsValidator.history', compact('constraints'));
    }
}
