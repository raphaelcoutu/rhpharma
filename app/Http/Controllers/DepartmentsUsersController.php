<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DepartmentsUsersController extends Controller
{
    public function fetch($id)
    {
        $user = User::with('departments')->findOrFail($id);

        return $user->departments;
    }

    public function store($id, Request $request)
    {
        $this->validate($request, [
            'department_id' => 'required',
            'history' => 'required|numeric|min:0|max:99.99',
            'planning_long' => 'required|numeric|min:0|max:99.99',
            'planning_short' => 'required|numeric|min:0|max:99.99'
        ]);

        User::findOrFail($id)->departments()->syncWithoutDetaching([
            $request['department_id'] => [
                'history' => $request['history'],
                'planning_long' => $request['planning_long'],
                'planning_short' => $request['planning_short'],
                'active' => true
            ]
        ]);
    }

    public function destroy($id, Request $request)
    {
        return User::findOrFail($id)->departments()->detach($request['department_id']);
    }
}
