<?php

namespace App\Http\Controllers;

use App\Constraint;
use App\ConstraintType;
use App\Http\Requests\ConstraintRequest;
use App\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConstraintsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fixedConstraints = Constraint::with('constraintType')->fixedConstraints()
            ->fromLoggedInUser()->get();


        $availabilityConstraints = Constraint::with('constraintType')->availabilityConstraints()
            ->fromLoggedInUser()->get();

        $schedules = Schedule::where('end_date', '>', Carbon::today())->get();
        $constraintTypes = ConstraintType::all();

        return view('constraints.index',
            compact('schedules', 'constraintTypes', 'availabilityConstraints', 'fixedConstraints')
        );
    }

    public function fetchFixed()
    {
        return Constraint::with('constraintType')->fixedConstraints()
            ->fromLoggedInUser()->get();
    }

    public function fetchAvailability()
    {
        return Constraint::with('constraintType')->availabilityConstraints()
            ->fromLoggedInUser()->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConstraintRequest $request)
    {
        $constraint = new Constraint([
            'user_id' => \Auth::user()->id,
            'start_datetime' => $request->get('start_datetime'),
            'end_datetime' => $request->get('end_datetime'),
            'constraint_type_id' => $request->get('constraint_type_id'),
            'weight' => $request->get('weight'),
            'comment' => $request->get('comment'),
            'status' => 0,
            'validated_by' => null,
            'number_of_occurrences' => null
        ]);

        $constraint->save();

        return $constraint;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Constraint::with('constraintType')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $constraint = Constraint::findOrFail($id);

        $constraint->update([
            'start_datetime' => $request->get('start_datetime'),
            'end_datetime' => $request->get('end_datetime'),
            'constraint_type_id' => $request->get('constraint_type_id'),
            'weight' => $request->get('weight'),
            'comment' => $request->get('comment'),
            'status' => 0,
            'validated_by' => null,
            'number_of_occurrences' => null
        ]);

        return $constraint;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
