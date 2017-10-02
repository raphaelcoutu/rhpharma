<?php

use App\User;
use Illuminate\Http\Request;

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

    //Schedules
    Route::get('schedules', 'SchedulesController@fetch');
    Route::post('schedules/store', 'SchedulesController@store');

    //Holidays
    Route::get('holidays', 'HolidaysController@fetch');
    Route::get('holidays/{id}', 'HolidaysController@edit');
    Route::post('holidays/store', 'HolidaysController@store');
    Route::patch('holidays/{id}', 'HolidaysController@update');
});
