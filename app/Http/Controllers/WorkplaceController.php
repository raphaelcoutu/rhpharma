<?php

namespace App\Http\Controllers;

use App\Models\Workplace;
use Illuminate\Http\Request;

class WorkplaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('read', Workplace::class);

        $workplaces = Workplace::withCount(['departments' => function($query) {
            $query->ownBranch();
        }])->get();

        return view('workplaces.index', compact('workplaces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('workplaces.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('write', Workplace::class);

        $this->validate($request, [
            'name' => 'required|unique:workplaces',
            'code' => 'required|unique:workplaces',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'country' => 'required',
            'postal_code' => 'required'
        ]);

        Workplace::create($request->all());

        return redirect('workplaces');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $workplace = Workplace::with(['departments' => function($query) {
            $query->ownBranch();
        }])->findOrFail($id);

        return view('workplaces.show', compact('workplace'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
