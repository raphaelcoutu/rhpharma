<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $rules = [
        'name' => 'required|min:3|unique:branches'
    ];

    public function index()
    {
        Gate::authorize('read', Branch::class);

        $branches = Branch::withCount('users')->get();

        return view('branches.index', compact('branches'));
    }

    public function fetch()
    {
        return Branch::all();
    }


    public function store(Request $request)
    {
        $request->validate($this->rules);

        Branch::create(['name' => $request->input(['name'])]);

    }

    public function edit($id)
    {
        return Branch::find($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate($this->rules);

        Branch::findOrFail($id)->update($request->all());
    }

    public function destroy($id)
    {
        //
    }
}
