<?php

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

    //ConstraintTypes
    Route::get('constraintTypes', 'ConstraintTypesController@index')->name('constraintTypes.index');
    Route::get('constraintTypes/create', 'ConstraintTypesController@create')->name('constraintTypes.create');
    Route::post('constraintTypes', 'ConstraintTypesController@store')->name('constraintTypes.store');

    Route::get('constraints', 'ConstraintsController@index')->name('constraints.index');

    //ConstraintsValidator
    Route::get('constraintsValidator', 'ConstraintsValidatorController@index')->name('constraintsValidator.index');
    Route::get('constraintsValidator/history', 'ConstraintsValidatorController@history')->name('constraintsValidator.history');

    //Calendar
    Route::get('calendar/{schedule}', 'CalendarController@show')->name('calendar.show');
    Route::get('calendar/{schedule}/byDepartment/{department}', 'CalendarController@showByDepartment')->name('calendar.showByDepartment');

    //Settings
    Route::get('settings', 'SettingsController@index')->name('settings.index');
});
