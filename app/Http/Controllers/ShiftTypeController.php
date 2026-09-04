<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftTypeRequest;
use App\Models\ShiftType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShiftTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->authorize('read', ShiftType::class);

        $shiftTypes = ShiftType::ownBranch()->orderBy('name')->get();

        return view('shiftTypes.index', compact('shiftTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('shiftTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(ShiftTypeRequest $request)
    {
        ShiftType::create($request->all());

        return redirect('shiftTypes');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(ShiftType $shiftType)
    {
        $this->authorize('write', ShiftType::class);

        return view('shiftTypes.edit', ['shiftType' => $shiftType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(ShiftTypeRequest $request, ShiftType $shiftType)
    {
        $shiftType->update($request->all());

        return redirect('shiftTypes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
