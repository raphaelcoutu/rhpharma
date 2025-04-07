<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        Gate::authorize('read', User::class);

        $users = User::orderBy('lastname')->with('branch')->get();

        return Inertia::render('users/index', compact('users'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        Gate::authorize('write', User::class);

        $branches = Branch::select(['id', 'name'])->get();
        $roles = Role::all();

        return Inertia::render('users/create', [
            'branches' => $branches,
            'roles' => $roles
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('read', User::class);

        $user = User::with('departments')->findOrFail($id);
        $departments = Department::whereIn('department_type_id', [1, 3])->orderBy('name')->get();

        return view('users.show', compact('user', 'departments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Inertia\Response
     */
    public function edit($id)
    {
        Gate::authorize('read', User::class);

        $user = User::with('roles')->findOrFail($id);
        $branches = Branch::select(['id', 'name'])->get();
        $roles = Role::all();

        return Inertia::render('users/edit', [
                'user' => $user,
                'branches' => $branches,
                'roles' => $roles
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $user->roles()->sync($request->roles);

        $user->update($request->all());

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
