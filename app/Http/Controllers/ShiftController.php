<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftRequest;
use App\Models\Department;
use App\Models\Shift;
use App\Models\ShiftType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    #[Authorize('read', Shift::class)]
    public function index()
    {
        $shifts = Shift::with(['department', 'shiftType'])->orderBy('code')->get();

        return view('shifts.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
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
     * @param  Request  $request
     * @return Response
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
     * @return Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    #[Authorize('write', Shift::class)]
    public function edit(Shift $shift)
    {
        $departments = Department::all();
        $shiftTypes = ShiftType::all();

        return view('shifts.edit', ['shift' => $shift, 'departments' => $departments,
            'shiftTypes' => $shiftTypes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
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
     * @return Response
     */
    public function destroy($id)
    {
        abort(404);
    }
}
