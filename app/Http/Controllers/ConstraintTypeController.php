<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConstraintTypeRequest;
use App\Models\ConstraintType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConstraintTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
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
     * @return Response
     */
    public function create()
    {
        $this->authorize('write', ConstraintType::class);

        return view('constraintTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
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
    public function edit(ConstraintType $constraintType)
    {
        return view('constraintTypes.edit', compact('constraintType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
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
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
