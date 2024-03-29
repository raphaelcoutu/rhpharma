<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('read', User::class);

        $users = User::ownBranch()->orderBy('lastname')->with('branch')->get();

        return view('users.index', compact('users'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('write', User::class);

        $branches = Branch::select(['id', 'name'])->get();
        $roles = Role::all();

        return view('users.create', compact('branches', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = new User($request->all());
        $user->password = bcrypt(uniqid("rhpharma_"));
        $user->save();

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('read', User::class);

        $user = User::with('departments')->findOrFail($id);
        $departments = Department::whereIn('department_type_id', [1,3])->orderBy('name')->get();

        return view('users.show', compact('user', 'departments'));
    }

    public function profile()
    {
        $user = \Auth::user();

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('write', User::class);

        $user = User::findOrFail($id);
        $branches = Branch::select(['id', 'name'])->get();
        $roles = Role::all();

        return view('users.edit', compact('user', 'branches', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $roles = collect($request->roles)->filter(function($role) {
            return $role == 1;
        })->keys();

        $user->roles()->sync($roles);

        $user->update($request->all());

        return redirect()->route('users.show', ['user' => $id]);
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
