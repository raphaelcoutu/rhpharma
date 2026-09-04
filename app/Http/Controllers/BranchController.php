<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class BranchController extends Controller
{
    protected $rules = [
        'name' => 'required|min:3|unique:branches',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('read', Branch::class);

        $branches = Branch::withCount('users')->get();

        return Inertia::render('branches/index', compact('branches'));
    }

    public function fetch(): Collection
    {
        return Branch::withCount('users')->get();
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
