<?php

namespace App\Http\Controllers;

use App\Department;
use App\Setting;
use App\Triplet;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $departments = Department::whereHas('departmentType', function ($query) {
            $query->where('name', 'Clinical');
        })->select(['id', 'name'])->get();

        $triplets = Triplet::all();

        $settings = Setting::whereIn('key', ['departments_order', 'triplets_order'])->get();

        return view('settings.index', compact('departments', 'triplets', 'settings'));
    }

    public function updateDepartments(Request $request) {
        $setting = Setting::where('key', 'departments_order')->firstOrFail();

        $setting->update(['value' => json_encode($request->all())]);

        return "OK";
    }

    public function updateTriplets(Request $request) {
        $setting = Setting::where('key', 'triplets_order')->firstOrFail();

        $setting->update(['value' => json_encode($request->all())]);

        return "OK";
    }
}
