<?php

namespace App\Http\Controllers;

use App\Models\ConstraintType;
use App\Http\Requests\ConstraintTypeRequest;
use Illuminate\Http\Request;

class ConstraintTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('read', ConstraintType::class);

        $constraintTypes = ConstraintType::ownBranch()->orderBy('name')->get();

        return view('constraintTypes.index', compact('constraintTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('write', ConstraintType::class);

        return view('constraintTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConstraintTypeRequest $request)
    {
        $request['branch_id'] = \Auth::user()->branch->id;

        ConstraintType::create($request->all());

        return redirect('constraintTypes');
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
    public function edit(ConstraintType $constraintType)
    {
        return view('constraintTypes.edit', compact('constraintType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ConstraintTypeRequest $request, $id)
    {
        $constraintType = ConstraintType::findOrFail($id);
        $constraintType->update($request->all());

        return redirect('constraintTypes');
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
