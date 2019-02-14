<?php

namespace App\Http\Controllers;

use App\ConstraintType;
use Illuminate\Http\Request;

class SettingsConstraintTypesController extends Controller
{
    public function index()
    {
        $constraintTypes = ConstraintType::all();

        return view('settings.constraintTypes', compact('constraintTypes'));
    }

    public function update(Request $request)
    {
        $id = $request['id'];
        $status = $request['status'];

        $constraintType = ConstraintType::find($id);
        $constraintType->status = $status;
        $constraintType->save();

        return "OK";
    }
}
