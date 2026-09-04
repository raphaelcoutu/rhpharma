<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[Authorize('read', Holiday::class)]
    public function index(): InertiaResponse
    {
        return Inertia::render('holidays/index', [
            'holidays' => fn () => Holiday::byDate()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Holiday::create($request->validate([
            'description' => 'required',
            'date' => 'required',
        ]));

        return to_route('holidays.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Holiday $holiday): RedirectResponse
    {
        $holiday->update($request->validate([
            'description' => 'required',
            'date' => 'required',
        ]));

        return to_route('holidays.index');
    }
}
