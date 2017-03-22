<?php

namespace App\Http\Controllers;

use App\Schedule;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('read', Schedule::class);

        $schedules = Schedule::ordered()->get();
        
        return view('schedules.index', compact('schedules'));
    }

    public function fetch()
    {
        return Schedule::ordered()->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('write', Schedule::class);
        
        $this->validate($request, [
            'name' => 'required',
            'constraint_limit_date' => 'required|date|after:today',
            'start_date' => 'required|date|after:constraint_limit_date',
            'end_date' => 'required|date|after:start_date'
        ], [
            'name.required' => 'Le nom de l\'horaire est requis.',
            'constraint_limit_date.required' => 'La date de limite pour les contraintes est requise.',
            'start_date.required' => 'La date de début est requise.',
            'end_date.required' => 'Le date de fin est requise.',
        ]);

        $request['branch_id'] = \Auth::user()->branch->id;


        Schedule::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
