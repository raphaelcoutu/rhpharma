<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $roles = Role::all();

        $permissions = Permission::orderBy('code')->get();

        return view('roles.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $roles
     * @return Response
     */
    public function show(Role $roles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return Response
     */
    public function edit(Role $role)
    {
        $this->authorize('write', Role::class);

        $permissions = Permission::all();

        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Role  $roles
     * @return Response
     */
    public function update(Request $request, Role $role)
    {
        $role->name = $request->name;
        $role->description = $request->description ?? '';

        $permissions = collect($request->permissions)->filter(function ($perm) {
            return $perm == 1;
        })->keys();

        $role->permissions()->sync($permissions);

        $role->save();

        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $roles
     * @return Response
     */
    public function destroy(Role $roles)
    {
        //
    }
}
