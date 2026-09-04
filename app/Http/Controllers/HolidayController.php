<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    #[Authorize('read', Holiday::class)]
    public function index()
    {
        $holidays = Holiday::byDate()->get();

        return view('holidays.index', compact('holidays'));
    }

    public function fetch()
    {
        return Holiday::byDate()->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'date' => 'required',
        ]);

        Holiday::create($request->all());

        return response()->noContent();
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
     * @return Response
     */
    public function edit($id)
    {
        return Holiday::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // $request->validate($this->rules);

        Holiday::findOrFail($id)->update($request->all());

        return response()->noContent();
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
