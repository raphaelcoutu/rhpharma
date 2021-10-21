<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

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
        $this->authorize('read', Branch::class);

        $branches = Branch::withCount('users')->get();

        return view('branches.index', compact('branches'));
    }

    public function fetch()
    {
        return Branch::all();
    }


    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        Branch::create(['name' => $request->input(['name'])]);

    }

    public function edit($id)
    {
        return Branch::find($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules);

        Branch::findOrFail($id)->update($request->all());
    }

    public function destroy($id)
    {
        //
    }
}
