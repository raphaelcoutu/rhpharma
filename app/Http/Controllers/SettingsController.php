<?php

namespace App\Http\Controllers;

use App\Department;
use App\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $departments = Department::whereHas('departmentType', function ($query) {
            $query->where('name', 'Clinical');
        })->select(['id', 'name'])->get();

        $order = Setting::where('key', 'generation_order')->firstOrFail()->value;

        return view('settings.index', compact('departments', 'order'));
    }

    public function updateOrder(Request $request) {
        $orderSetting = Setting::where('key', 'generation_order')->firstOrFail();

        $orderSetting->update(['value' => json_encode($request->all())]);

        return "OK";
    }
}
