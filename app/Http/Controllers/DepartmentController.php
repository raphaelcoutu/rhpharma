<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Requests\DepartmentRequest;
use App\Models\Workplace;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('read', Department::class);

        $departments = Department::select(['id', 'name', 'description', 'department_type_id', 'workplace_id'])
            ->ownBranch()->with(['workplace', 'departmentType'])->orderBy('name')->get();

        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $workplaces = Workplace::all();
        return view('departments.create', compact('workplaces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
        Department::create($request->all());

        return redirect('departments');
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
        $this->authorize('write', Department::class);

        $department = Department::with(['shifts.shiftType', 'users' => function ($query) {
            $query->where('is_active', 1);
        }])->findOrFail($id);
        $workplaces = Workplace::all();

        return view('departments.edit', ['department' => $department, 'workplaces' => $workplaces]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentRequest $request, Department $department)
    {
        $department->update($request->all());

        return redirect('departments');
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
