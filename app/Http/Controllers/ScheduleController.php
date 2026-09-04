<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\Constraint;
use App\Models\Department;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        Gate::authorize('read', Schedule::class);

        $schedules = Schedule::orderedDesc()->paginate(15);

        $constraints_in_schedule = [];

        if (! $schedules->empty()) {
            $constraints = Constraint::unvalidated()->inDateInterval($schedules->last()->start_date, $schedules->first()->end_date)->get();
            foreach ($schedules as $schedule) {
                $collision = 0;
                foreach ($constraints as $constraint) {
                    if (detectsIntervalCollision($constraint->start_datetime, $constraint->end_datetime,
                        $schedule->start_date->setTime(0, 0), $schedule->end_date->setTime(23, 59))) {
                        $collision++;
                    }
                }

                $constraints_in_schedule[$schedule->id] = $collision;
            }
        }

        return view('schedules.index', compact('schedules', 'constraints_in_schedule'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('schedules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(ScheduleRequest $request)
    {
        Gate::authorize('write', Schedule::class);

        $request['branch_id'] = \Auth::user()->branch->id;

        Schedule::create($request->all());

        return redirect()->route('schedules.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        Gate::authorize('write', Schedule::class);

        $schedule = Schedule::with('conflicts.department')->findOrFail($id);

        $departments = Department::orderBy('name')->get();

        $constraints_count = Constraint::unvalidated()->inDateInterval($schedule->start_date, $schedule->end_date)->count();

        return view('schedules.show', compact('schedule', 'constraints_count', 'departments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        Gate::authorize('write', Schedule::class);

        $schedule = Schedule::findOrFail($id);

        return view('schedules.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(ScheduleRequest $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update($request->all());

        return redirect()->route('schedules.index');
    }

    public function updateNotes(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->notes = $request->notes;
        $schedule->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        abort(404);
    }
}
