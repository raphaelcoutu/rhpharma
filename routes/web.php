<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::group(['middleware' => 'auth'], function () {

    //Branches
    Route::get('branches', 'BranchesController@index')->name('branches.index');

    //Users
    Route::get('users', 'UsersController@index')->name('users.index');
    Route::get('users/create', 'UsersController@create')->name('users.create');
    Route::get('users/{user}', 'UsersController@show')->name('users.show');
    Route::get('users/{user}/edit', 'UsersController@edit')->name('users.edit');
    Route::post('users', 'UsersController@store')->name('users.store');
    Route::put('users/{user}', 'UsersController@update')->name('users.update');
    Route::get('profile', 'UsersController@profile')->name('profile');

    //Roles
    Route::get('roles', 'RolesController@index')->name('roles.index');

    //Workplaces
    Route::get('workplaces', 'WorkplacesController@index')->name('workplaces.index');
    Route::get('workplaces/create', 'WorkplacesController@create')->name('workplaces.create');
    Route::post('workplaces', 'WorkplacesController@store')->name('workplaces.store');
    Route::get('workplaces/{workplace}', 'WorkplacesController@show')->name('workplaces.show');

    //Departments
    Route::get('departments', 'DepartmentsController@index')->name('departments.index');
    Route::get('departments/create', 'DepartmentsController@create')->name('departments.create');
    Route::post('departments', 'DepartmentsController@store')->name('departments.store');
    Route::get('departments/{department}/edit', 'DepartmentsController@edit')->name('departments.edit');
    Route::post('departments/{department}', 'DepartmentsController@update')->name('departments.update');

    //Schedules
    Route::get('schedules', 'SchedulesController@index')->name('schedules.index');
    Route::get('schedules/create', 'SchedulesController@create')->name('schedules.create');
    Route::get('schedules/{schedule}', 'SchedulesController@show')->name('schedules.show');
    Route::get('schedules/{schedule}/edit', 'SchedulesController@edit')->name('schedules.edit');
    Route::post('schedules', 'SchedulesController@store')->name('schedules.store');
    Route::put('schedules/{schedule}', 'SchedulesController@update')->name('schedules.update');

    //Holidays
    Route::get('holidays', 'HolidaysController@index')->name('holidays.index');

    //ConstraintImporter
    Route::get('constraintImporter', 'ConstraintImporterController@index')->name('constraintImporter.index');
    Route::get('constraintImporter/import{start?}{end?}', 'ConstraintImporterController@import')->name('constraintImporter.import');

    //ConstraintTypes
    Route::get('constraintTypes', 'ConstraintTypesController@index')->name('constraintTypes.index');
    Route::get('constraintTypes/create', 'ConstraintTypesController@create')->name('constraintTypes.create');
    Route::get('constraintTypes/{constraintType}/edit', 'ConstraintTypesController@edit')->name('constraintTypes.edit');
    Route::post('constraintTypes', 'ConstraintTypesController@store')->name('constraintTypes.store');
    Route::put('constraintTypes/{constraintType}', 'ConstraintTypesController@update')->name('constraintTypes.update');

    Route::get('constraints', 'ConstraintsController@index')->name('constraints.index');

    //ConstraintsValidator
    Route::get('constraintsValidator', 'ConstraintsValidatorController@index')->name('constraintsValidator.index');
    Route::get('constraintsValidator/history', 'ConstraintsValidatorController@history')->name('constraintsValidator.history');

    //Calendar
    Route::get('calendar/{schedule}', 'CalendarController@show')->name('calendar.show');
    Route::get('calendar/{schedule}/byDepartment/{department}', 'CalendarController@showByDepartment')->name('calendar.showByDepartment');

    //Shifts
    Route::get('shifts', 'ShiftsController@index')->name('shifts.index');
    Route::get('shifts/create', 'ShiftsController@create')->name('shifts.create');
    Route::post('shifts', 'ShiftsController@store')->name('shifts.store');
    Route::get('shifts/{shift}/edit', 'ShiftsController@edit')->name('shifts.edit');
    Route::post('shifts/{shift}', 'ShiftsController@update')->name('shifts.update');

    //ShiftTypes
    Route::get('shiftTypes', 'ShiftTypesController@index')->name('shiftTypes.index');
    Route::get('shiftTypes/create', 'ShiftTypesController@create')->name('shiftTypes.create');
    Route::post('shiftTypes', 'ShiftTypesController@store')->name('shiftTypes.store');
    Route::get('shiftTypes/{shiftType}/edit', 'ShiftTypesController@edit')->name('shiftTypes.edit');
    Route::post('shiftTypes/{shiftType}', 'ShiftTypesController@update')->name('shiftTypes.update');

    //Settings
    Route::get('settings', 'SettingsController@index')->name('settings.index');
    Route::get('settings/constraintTypes', 'SettingsConstraintTypesController@index')->name('settings.constraintTypes');
    Route::get('settings/departments', 'SettingsController@departments')->name('settings.departments');

    //Exports
    Route::get('export/{schedule}', 'ExportsController@export')->name('export');

    if(App::environment('local')) {
        Route::get('build/{scheduleId}', function ($scheduleId) {
            $event = new \App\Events\UpdateBuildStatus($scheduleId, 3, 3);
            dispatch(new \App\Jobs\BuildClinicalDepartments($event));
        });
    }
});
