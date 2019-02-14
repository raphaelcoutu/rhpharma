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
        if(!$request->massUpdate) {
            $constraintType = ConstraintType::find($request->data['id']);
            $constraintType->status = $request->data['status'];
            $constraintType->save();
        } else {
            ConstraintType::where('status','!=', 2)->update(['status' => 2]);
        }

        return "OK";
    }
}
