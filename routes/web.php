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
    Route::post('users', 'UsersController@store')->name('users.store');
    Route::get('profile', 'UsersController@profile')->name('profile');
    
    //Workplaces
    Route::get('workplaces', 'WorkplacesController@index')->name('workplaces.index');
    Route::get('workplaces/create', 'WorkplacesController@create')->name('workplaces.create');
    Route::post('workplaces', 'WorkplacesController@store')->name('workplaces.store');
    Route::get('workplaces/{workplace}', 'WorkplacesController@show')->name('workplaces.show');
});
