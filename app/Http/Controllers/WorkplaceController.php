<?php

namespace App\Http\Controllers;

use App\Models\Workplace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class WorkplaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('read', Workplace::class);

        $workplaces = Workplace::withCount(['departments' => function (Builder $query): void {
            $query->ownBranch();
        }])->orderBy('name')->get();

        return Inertia::render('workplaces/index', compact('workplaces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('write', Workplace::class);

        return Inertia::render('workplaces/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('write', Workplace::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:workplaces,name'],
            'code' => ['required', 'string', 'max:5', 'unique:workplaces,code'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'province' => ['required', 'string'],
            'country' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
        ]);

        Workplace::create($validated);

        return redirect()->route('workplaces.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Workplace $workplace): Response
    {
        Gate::authorize('read', Workplace::class);

        $workplace->load(['departments' => function (HasMany $query): void {
            $query->ownBranch()->with('departmentType');
        }]);

        return Inertia::render('workplaces/show', compact('workplace'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Workplace $workplace): Response
    {
        Gate::authorize('write', Workplace::class);

        return Inertia::render('workplaces/edit', compact('workplace'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Workplace $workplace): RedirectResponse
    {
        Gate::authorize('write', Workplace::class);

        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('workplaces', 'name')->ignore($workplace)],
            'code' => ['required', 'string', 'max:5', Rule::unique('workplaces', 'code')->ignore($workplace)],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'province' => ['required', 'string'],
            'country' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
        ]);

        $workplace->update($validated);

        return redirect()->route('workplaces.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(404);
    }
}
