<?php


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

    //Branches
    Route::get('branches', 'BranchesController@fetch');
    Route::post('branches/store', 'BranchesController@store');
    Route::get('branches/{id}', 'BranchesController@edit');
    Route::patch('branches/{id}', 'BranchesController@update');

    //Calendar
    Route::get('calendar/getUserData', 'CalendarController@getUserData');
    Route::post('calendar', 'CalendarController@setUserData');

    //Conflicts
    Route::get('conflicts/{scheduleId}', 'ConflictsController@fetch');

    //Constraints
    Route::get('constraints/fixed', 'ConstraintsController@fetchFixed');
    Route::get('constraints/availability', 'ConstraintsController@fetchAvailability');
    Route::get('constraints/{id}/edit', 'ConstraintsController@edit');
    Route::post('constraints/store', 'ConstraintsController@store');
    Route::put('constraints/{id}/update', 'ConstraintsController@update');

    //ConstraintTypes
    Route::get('constraintTypes', 'ConstraintTypesController@fetch');

    //ConstraintValidator
    Route::put('constraintsValidator/{id}', 'ConstraintsValidatorController@update');

    //Holidays
    Route::get('holidays', 'HolidaysController@fetch');
    Route::get('holidays/{id}', 'HolidaysController@edit');
    Route::post('holidays/store', 'HolidaysController@store');
    Route::patch('holidays/{id}', 'HolidaysController@update');

    //Schedule
    Route::post('schedules/updateStatus', 'BuildController@updateStatus');

    //Settings
    Route::patch('settings/departments', 'SettingsController@updateDepartments');
    Route::patch('settings/triplets', 'SettingsController@updateTriplets');

    //Settings-Department-User
    Route::patch('settings/departmentUser', 'SettingsController@updateDepartmentUser');

    //Users-Departments
    Route::get('usersDepartments/{id}', 'UsersDepartmentsController@fetch');
    Route::post('usersDepartments/{id}/store', 'UsersDepartmentsController@store');
    Route::delete('usersDepartments/{id}', 'UsersDepartmentsController@destroy');
});
