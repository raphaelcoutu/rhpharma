<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\WorkplaceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ConstraintController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ConstraintTypeController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ConstraintImporterController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

// Authentication Routes...
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset Routes...
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset']);

Route::group(['middleware' => 'auth'], function () {

    //Branches
    Route::get('branches', [BranchController::class, 'index'])->name('branches.index');

    //Users
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');

    //Roles
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');

    //Workplaces
    Route::get('workplaces', [WorkplaceController::class, 'index'])->name('workplaces.index');
    Route::get('workplaces/create', [WorkplaceController::class, 'create'])->name('workplaces.create');
    Route::post('workplaces', [WorkplaceController::class, 'store'])->name('workplaces.store');
    Route::get('workplaces/{workplace}', [WorkplaceController::class, 'show'])->name('workplaces.show');

    //Departments
    Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');

    //Schedules
    Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::get('schedules/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');
    Route::get('schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::post('schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::put('schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');

    //Holidays
    Route::get('holidays', [HolidayController::class, 'index'])->name('holidays.index');

    //ConstraintImporter
    Route::get('constraintImporter', [ConstraintImporterController::class, 'index'])->name('constraintImporter.index');
    Route::get('constraintImporter/import{start?}{end?}', [ConstraintImporterController::class, 'import'])->name('constraintImporter.import');

    //ConstraintTypes
    Route::get('constraintTypes', [ConstraintTypeController::class, 'index'])->name('constraintTypes.index');
    Route::get('constraintTypes/create', [ConstraintTypeController::class, 'create'])->name('constraintTypes.create');
    Route::get('constraintTypes/{constraintType}/edit', [ConstraintTypeController::class, 'edit'])->name('constraintTypes.edit');
    Route::post('constraintTypes', [ConstraintTypeController::class, 'store'])->name('constraintTypes.store');
    Route::put('constraintTypes/{constraintType}', [ConstraintTypeController::class, 'update'])->name('constraintTypes.update');

    Route::get('constraints', [ConstraintController::class, 'index'])->name('constraints.index');

    //ConstraintsValidator
    Route::get('constraintsValidator', [ConstraintsValidatorController::class, 'index'])->name('constraintsValidator.index');
    Route::get('constraintsValidator/history', [ConstraintsValidatorController::class, 'history'])->name('constraintsValidator.history');

    //Calendar
    Route::get('calendar/{schedule}', [CalendarController::class, 'show'])->name('calendar.show');
    Route::get('calendar/{schedule}/byDepartment/{department}', [CalendarController::class, 'showByDepartment'])->name('calendar.showByDepartment');

    //Shifts
    Route::get('shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::get('shifts/create', [ShiftController::class, 'create'])->name('shifts.create');
    Route::post('shifts', [ShiftController::class, 'store'])->name('shifts.store');
    Route::get('shifts/{shift}/edit', [ShiftController::class, 'edit'])->name('shifts.edit');
    Route::post('shifts/{shift}', [ShiftController::class, 'update'])->name('shifts.update');

    //ShiftTypes
    Route::get('shiftTypes', [ShiftTypeController::class, 'index'])->name('shiftTypes.index');
    Route::get('shiftTypes/create', [ShiftTypeController::class, 'create'])->name('shiftTypes.create');
    Route::post('shiftTypes', [ShiftTypeController::class, 'store'])->name('shiftTypes.store');
    Route::get('shiftTypes/{shiftType}/edit', [ShiftTypeController::class, 'edit'])->name('shiftTypes.edit');
    Route::post('shiftTypes/{shiftType}', [ShiftTypeController::class, 'update'])->name('shiftTypes.update');

    //Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('settings/constraintTypes', [SettingConstraintTypeController::class, 'index'])->name('settings.constraintTypes');
    Route::get('settings/departments', [SettingController::class, 'departments'])->name('settings.departments');

    //Exports
    Route::get('export/{schedule}', [ExportController::class, 'export'])->name('export');

    if(App::environment('local')) {
        Route::get('build/{scheduleId}', function ($scheduleId) {
            $event = new \App\Events\UpdateBuildStatus($scheduleId, 3, 3);
            dispatch(new \App\Jobs\BuildClinicalDepartments($event));
        });
    }
});
