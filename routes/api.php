<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuildController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ConflictController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ConstraintController;
use App\Http\Controllers\ConstraintTypeController;
use App\Http\Controllers\DepartmentUserController;
use App\Http\Controllers\ConstraintValidatorController;
use App\Http\Controllers\SettingConstraintTypeController;
use App\Http\Controllers\ScheduleStatDepartmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth'], function () {

    // Branches
    Route::get('branches', [BranchController::class, 'fetch']);
    Route::post('branches/store', [BranchController::class, 'store']);
    Route::get('branches/{id}', [BranchController::class, 'edit']);
    Route::put('branches/{id}', [BranchController::class, 'update']);

    // Calendar
    Route::get('calendar/getShifts', [CalendarController::class, 'getShifts']);
    Route::get('calendar/getUserData', [CalendarController::class, 'getUserData']);
    Route::post('calendar/setUserData', [CalendarController::class, 'setUserData']);
    Route::post('calendar/setSelectedData', [CalendarController::class, 'setSelectedData']);

    // Conflicts
    Route::get('conflicts/{scheduleId}', [ConflictController::class, 'fetch']);

    // Constraints
    Route::get('constraints/fixed', [ConstraintController::class, 'fetchFixed']);
    Route::get('constraints/availability', [ConstraintController::class, 'fetchAvailability']);
    Route::get('constraints/{id}/edit', [ConstraintController::class, 'edit']);
    Route::post('constraints/store', [ConstraintController::class, 'store']);
    Route::put('constraints/{id}/update', [ConstraintController::class, 'update']);

    // ConstraintTypes
    Route::get('constraintTypes', [ConstraintTypeController::class, 'fetch']);

    // ConstraintValidator
    Route::put('constraintsValidator/{id}', [ConstraintValidatorController::class, 'update']);

    // Departments-Users
    Route::get('departmentUsers/{id}', [DepartmentUserController::class, 'fetch']);
    Route::post('departmentUsers/{id}/store', [DepartmentUserController::class, 'store']);
    Route::delete('departmentUsers/{id}', [DepartmentUserController::class, 'destroy']);

    // Holidays
    Route::get('holidays', [HolidayController::class, 'fetch']);
    Route::get('holidays/{id}', [HolidayController::class, 'edit']);
    Route::post('holidays/store', [HolidayController::class, 'store']);
    Route::patch('holidays/{id}', [HolidayController::class, 'update']);

    // Schedule
    Route::post('schedules/updateStatus', [BuildController::class, 'updateStatus']);
    Route::put('schedules/{id}/updateNotes', [ScheduleController::class, 'updateNotes']);

    // Schedule-Stats-Departments
    Route::get('scheduleStatDepartment/{scheduleId}', [ScheduleStatDepartmentController::class, 'show']);
    Route::get('scheduleStatDepartment/{scheduleId}/create', [ScheduleStatDepartmentController::class, 'create']);

    // Settings
    Route::patch('settings/departments', [SettingController::class, 'updateDepartments']);
    Route::patch('settings/triplets', [SettingController::class, 'updateTriplets']);

    // Settings-ConstraintTypes
    Route::patch('settings/constraintTypes', [SettingConstraintTypeController::class, 'update']);

    // Settings-Department-User
    Route::patch('settings/departmentUser', [SettingController::class, 'updateDepartmentUser']);
});
