<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Setting;
use App\Models\Triplet;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $departments = Department::whereIn('department_type_id', [1,3])->select(['id', 'name'])->get();

        $triplets = Triplet::all();

        $settings = Setting::whereIn('key', ['departments_order', 'triplets_order'])->get();

        return view('settings.index', compact('departments', 'triplets', 'settings'));
    }

    public function departments()
    {
        $departments = Department::with(['users' => function ($query) {
            $query->where('is_active', 1);
        }])->whereHas('departmentType', function ($departmentType) {
            $departmentType->whereIn('name', ['Clinique','Oncologie']);
        })->orderBy('name')->get();

        return view('settings.departments', compact('departments'));
    }

    public function updateDepartments(Request $request) {
        $setting = Setting::where('key', 'departments_order')->firstOrFail();

        $setting->update(['value' => json_encode($request->all())]);

        return "OK";
    }

    public function updateDepartmentUser(Request $request)
    {
        $departmentId = $request['departmentId'];
        $userId = $request['userId'];
        $userActive = $request['userActive'];
        $userPlanning = $request['userPlanning'];

        $department = Department::find($departmentId);
        $department->users()
            ->updateExistingPivot($userId, ['active' => $userActive, 'planning_short' => $userPlanning]);

        return "OK";
    }

    public function updateTriplets(Request $request) {
        $setting = Setting::where('key', 'triplets_order')->firstOrFail();

        $setting->update(['value' => json_encode($request->all())]);

        return "OK";
    }
}
