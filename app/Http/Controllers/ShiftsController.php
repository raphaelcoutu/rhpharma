<?php

namespace App\Http\Controllers;


use App\Models\Department;
use App\Http\Requests\ShiftRequest;
use App\Models\Shift;
use App\Models\ShiftType;

class ShiftsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('read', Shift::class);

        $shifts = Shift::with(['department', 'shiftType'])->orderBy('code')->get();

        return view('shifts.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::all();
        $shiftTypes = ShiftType::all();
        return view('shifts.create', compact('departments', 'shiftTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShiftRequest $request)
    {
        Shift::create($request->all());

        return redirect('shifts');
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
    public function edit(Shift $shift)
    {
        $this->authorize('write', Shift::class);

        $departments = Department::all();
        $shiftTypes = ShiftType::all();

        return view('shifts.edit', ['shift' => $shift, 'departments' => $departments,
            'shiftTypes' => $shiftTypes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShiftRequest $request, Shift $shift)
    {
        $shift->update($request->all());

        return redirect('shifts');
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
