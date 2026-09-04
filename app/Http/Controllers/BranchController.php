<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('read', Branch::class);

        return Inertia::render('branches/index', [
            'branches' => fn () => Branch::withCount('users')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('write', Branch::class);

        Branch::create($request->validate([
            'name' => ['required', 'min:3', 'unique:branches'],
        ]));

        return to_route('branches.index');
    }

    public function update(Request $request, Branch $branch): RedirectResponse
    {
        Gate::authorize('write', Branch::class);

        $branch->update($request->validate([
            'name' => [
                'required',
                'min:3',
                Rule::unique('branches')->ignore($branch),
            ],
        ]));

        return to_route('branches.index');
    }

    public function destroy($id)
    {
        //
    }
}
