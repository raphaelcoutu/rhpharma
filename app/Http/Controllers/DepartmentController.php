<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Models\DepartmentType;
use App\Models\Workplace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        Gate::authorize('read', Department::class);

        $departments = Department::with(['workplace', 'departmentType'])
            ->ownBranch()
            ->select(['id', 'name', 'description', 'department_type_id', 'workplace_id'])
            ->orderBy('name')
            ->get();

        return Inertia::render('departments/index', [
            'departments' => $departments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(DepartmentRequest $request)
    {
        Department::create([
            ...$request->all(),
            'bonus_weeks' => 1,
            'malus_weeks' => 1,
            'bonus_pts' => 1,
            'malus_pts' => 1,
            'monday_am' => 1,
            'monday_pm' => 1,
            'tuesday_am' => 1,
            'tuesday_pm' => 1,
            'wednesday_am' => 1,
            'wednesday_pm' => 1,
            'thursday_am' => 1,
            'thursday_pm' => 1,
            'friday_am' => 1,
            'friday_pm' => 1,
        ]);

        return redirect()->route('departments.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $workplaces = Workplace::all();
        $departmentTypes = DepartmentType::all();

        return Inertia::render('departments/create', [
            'workplaces' => $workplaces,
            'departmentTypes' => $departmentTypes,
        ]);
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
     * @return Response
     */
    public function edit($id)
    {
        Gate::authorize('write', Department::class);

        $department = Department::with(['shifts.shiftType', 'users' => function ($query) {
            $query->where('is_active', 1);
        }])->findOrFail($id);
        $workplaces = Workplace::all();

        return Inertia::render('departments/edit', [
            'department' => $department,
            'departmentTypes' => DepartmentType::all(),
            'workplaces' => $workplaces,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(DepartmentRequest $request, Department $department)
    {
        $department->update($request->all());

        return redirect()->route('departments.index');
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
