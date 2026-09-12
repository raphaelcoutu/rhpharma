<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConstraintTypeRequest;
use App\Models\ConstraintType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ConstraintTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('read', ConstraintType::class);

        $constraintTypes = ConstraintType::ownBranch()
            ->withCount('criteria')
            ->orderBy('name')
            ->get();

        return Inertia::render('constraint-types/index', [
            'constraintTypes' => $constraintTypes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('write', ConstraintType::class);

        return Inertia::render('constraint-types/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConstraintTypeRequest $request): RedirectResponse
    {
        Gate::authorize('write', ConstraintType::class);

        ConstraintType::create([
            ...$request->validated(),
            'branch_id' => $request->user()->branch->id,
        ]);

        return redirect()->route('constraintTypes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): never
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConstraintType $constraintType): Response
    {
        Gate::authorize('write', ConstraintType::class);
        $constraintType = ConstraintType::ownBranch()->findOrFail($constraintType->id);

        return Inertia::render('constraint-types/edit', [
            'constraintType' => $constraintType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConstraintTypeRequest $request, ConstraintType $constraintType): RedirectResponse
    {
        Gate::authorize('write', ConstraintType::class);
        $constraintType = ConstraintType::ownBranch()->findOrFail($constraintType->id);
        $constraintType->update($request->validated());

        return redirect()->route('constraintTypes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): never
    {
        abort(404);
    }
}
