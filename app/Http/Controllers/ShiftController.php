<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftRequest;
use App\Models\Department;
use App\Models\Shift;
use App\Models\ShiftType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('read', Shift::class);

        $shifts = Shift::query()
            ->whereHas('department', function (Builder $query): void {
                $query->where('branch_id', auth()->user()->branch_id);
            })
            ->with(['department:id,name', 'shiftType:id,name'])
            ->orderBy('code')
            ->get(['id', 'department_id', 'shift_type_id', 'code', 'description', 'is_default']);

        return Inertia::render('shifts/index', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('write', Shift::class);

        $departments = Department::ownBranch()->orderBy('name')->get(['id', 'name']);
        $shiftTypes = ShiftType::ownBranch()->orderBy('name')->get(['id', 'name']);

        return Inertia::render('shifts/create', compact('departments', 'shiftTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShiftRequest $request): RedirectResponse
    {
        Gate::authorize('write', Shift::class);

        Shift::create([
            ...$request->validated(),
            'description' => '',
            'is_default' => false,
        ]);

        return redirect()->route('shifts.index');
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
    public function edit(Shift $shift): Response
    {
        Gate::authorize('write', Shift::class);

        $shift = Shift::query()
            ->whereHas('department', function (Builder $query): void {
                $query->where('branch_id', auth()->user()->branch_id);
            })
            ->findOrFail($shift->id);
        $departments = Department::ownBranch()->orderBy('name')->get(['id', 'name']);
        $shiftTypes = ShiftType::ownBranch()->orderBy('name')->get(['id', 'name']);

        return Inertia::render('shifts/edit', compact('shift', 'departments', 'shiftTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShiftRequest $request, Shift $shift): RedirectResponse
    {
        Gate::authorize('write', Shift::class);

        $shift = Shift::query()
            ->whereHas('department', function (Builder $query): void {
                $query->where('branch_id', auth()->user()->branch_id);
            })
            ->findOrFail($shift->id);

        $shift->update($request->validated());

        return redirect()->route('shifts.index');
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
