<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ShiftsController;
use App\Http\Controllers\ExportsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\HolidaysController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SchedulesController;
use App\Http\Controllers\ShiftTypesController;
use App\Http\Controllers\WorkplacesController;
use App\Http\Controllers\ConstraintsController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\ConstraintTypesController;
use App\Http\Controllers\ConstraintImporterController;
use App\Http\Controllers\ConstraintsValidatorController;
use App\Http\Controllers\SettingsConstraintTypesController;

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
// Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
// Route::post('login', 'Auth\LoginController@login');
// Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::group(['middleware' => 'auth'], function () {

    //Branches
    Route::get('branches', [BranchesController::class, 'index'])->name('branches.index');

    //Users
    Route::get('users', [UsersController::class, 'index'])->name('users.index');
    Route::get('users/create', [UsersController::class, 'create'])->name('users.create');
    Route::get('users/{user}', [UsersController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::post('users', [UsersController::class, 'store'])->name('users.store');
    Route::put('users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::get('profile', [UsersController::class, 'profile'])->name('profile');

    //Roles
    Route::get('roles', [RolesController::class, 'index'])->name('roles.index');

    //Workplaces
    Route::get('workplaces', [WorkplacesController::class, 'index'])->name('workplaces.index');
    Route::get('workplaces/create', [WorkplacesController::class, 'create'])->name('workplaces.create');
    Route::post('workplaces', [WorkplacesController::class, 'store'])->name('workplaces.store');
    Route::get('workplaces/{workplace}', [WorkplacesController::class, 'show'])->name('workplaces.show');

    //Departments
    Route::get('departments', [DepartmentsController::class, 'index'])->name('departments.index');
    Route::get('departments/create', [DepartmentsController::class, 'create'])->name('departments.create');
    Route::post('departments', [DepartmentsController::class, 'store'])->name('departments.store');
    Route::get('departments/{department}/edit', [DepartmentsController::class, 'edit'])->name('departments.edit');
    Route::post('departments/{department}', [DepartmentsController::class, 'update'])->name('departments.update');

    //Schedules
    Route::get('schedules', [SchedulesController::class, 'index'])->name('schedules.index');
    Route::get('schedules/create', [SchedulesController::class, 'create'])->name('schedules.create');
    Route::get('schedules/{schedule}', [SchedulesController::class, 'show'])->name('schedules.show');
    Route::get('schedules/{schedule}/edit', [SchedulesController::class, 'edit'])->name('schedules.edit');
    Route::post('schedules', [SchedulesController::class, 'store'])->name('schedules.store');
    Route::put('schedules/{schedule}', [SchedulesController::class, 'update'])->name('schedules.update');

    //Holidays
    Route::get('holidays', [HolidaysController::class, 'index'])->name('holidays.index');

    //ConstraintImporter
    Route::get('constraintImporter', [ConstraintImporterController::class, 'index'])->name('constraintImporter.index');
    Route::get('constraintImporter/import{start?}{end?}', [ConstraintImporterController::class, 'import'])->name('constraintImporter.import');

    //ConstraintTypes
    Route::get('constraintTypes', [ConstraintTypesController::class, 'index'])->name('constraintTypes.index');
    Route::get('constraintTypes/create', [ConstraintTypesController::class, 'create'])->name('constraintTypes.create');
    Route::get('constraintTypes/{constraintType}/edit', [ConstraintTypesController::class, 'edit'])->name('constraintTypes.edit');
    Route::post('constraintTypes', [ConstraintTypesController::class, 'store'])->name('constraintTypes.store');
    Route::put('constraintTypes/{constraintType}', [ConstraintTypesController::class, 'update'])->name('constraintTypes.update');

    Route::get('constraints', [ConstraintsController::class, 'index'])->name('constraints.index');

    //ConstraintsValidator
    Route::get('constraintsValidator', [ConstraintsValidatorController::class, 'index'])->name('constraintsValidator.index');
    Route::get('constraintsValidator/history', [ConstraintsValidatorController::class, 'history'])->name('constraintsValidator.history');

    //Calendar
    Route::get('calendar/{schedule}', [CalendarController::class, 'show'])->name('calendar.show');
    Route::get('calendar/{schedule}/byDepartment/{department}', [CalendarController::class, 'showByDepartment'])->name('calendar.showByDepartment');

    //Shifts
    Route::get('shifts', [ShiftsController::class, 'index'])->name('shifts.index');
    Route::get('shifts/create', [ShiftsController::class, 'create'])->name('shifts.create');
    Route::post('shifts', [ShiftsController::class, 'store'])->name('shifts.store');
    Route::get('shifts/{shift}/edit', [ShiftsController::class, 'edit'])->name('shifts.edit');
    Route::post('shifts/{shift}', [ShiftsController::class, 'update'])->name('shifts.update');

    //ShiftTypes
    Route::get('shiftTypes', [ShiftTypesController::class, 'index'])->name('shiftTypes.index');
    Route::get('shiftTypes/create', [ShiftTypesController::class, 'create'])->name('shiftTypes.create');
    Route::post('shiftTypes', [ShiftTypesController::class, 'store'])->name('shiftTypes.store');
    Route::get('shiftTypes/{shiftType}/edit', [ShiftTypesController::class, 'edit'])->name('shiftTypes.edit');
    Route::post('shiftTypes/{shiftType}', [ShiftTypesController::class, 'update'])->name('shiftTypes.update');

    //Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('settings/constraintTypes', [SettingsConstraintTypesController::class, 'index'])->name('settings.constraintTypes');
    Route::get('settings/departments', [SettingsController::class, 'departments'])->name('settings.departments');

    //Exports
    Route::get('export/{schedule}', [ExportsController::class, 'export'])->name('export');

    if(App::environment('local')) {
        Route::get('build/{scheduleId}', function ($scheduleId) {
            $event = new \App\Events\UpdateBuildStatus($scheduleId, 3, 3);
            dispatch(new \App\Jobs\BuildClinicalDepartments($event));
        });
    }
});
