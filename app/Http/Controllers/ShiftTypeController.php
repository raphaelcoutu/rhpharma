<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftTypeRequest;
use App\Models\ShiftType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ShiftTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('read', ShiftType::class);

        $shiftTypes = ShiftType::ownBranch()
            ->select(['id', 'name', 'start_time', 'end_time'])
            ->orderBy('name')
            ->get();

        return Inertia::render('shiftTypes/index', [
            'shiftTypes' => $shiftTypes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('write', ShiftType::class);

        return Inertia::render('shiftTypes/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShiftTypeRequest $request): RedirectResponse
    {
        Gate::authorize('write', ShiftType::class);

        ShiftType::create([
            ...$request->validated(),
            'branch_id' => $request->user()->branch->id,
        ]);

        return redirect()->route('shiftTypes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit(ShiftType $shiftType): Response
    {
        Gate::authorize('write', ShiftType::class);
        $shiftType = ShiftType::ownBranch()->findOrFail($shiftType->id);

        return Inertia::render('shiftTypes/edit', [
            'shiftType' => $shiftType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShiftTypeRequest $request, ShiftType $shiftType): RedirectResponse
    {
        Gate::authorize('write', ShiftType::class);
        $shiftType = ShiftType::ownBranch()->findOrFail($shiftType->id);

        $shiftType->update($request->validated());

        return redirect()->route('shiftTypes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        abort(404);
    }
}
