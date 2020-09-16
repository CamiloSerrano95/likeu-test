<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Routes for auth
Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');
Route::post('me', 'AuthController@me');

// Routes for user
Route::prefix('user')->group(function() {
    Route::get('all', 'UserController@all');
    Route::get('/{id}', 'UserController@getById');
    Route::post('create', 'UserController@store');
    Route::put('update/{id}', 'UserController@update');
    Route::delete('delete/{id}', 'UserController@destroy');
});

Route::prefix('client')->group(function() {
    Route::get('all', 'ClientController@all');
    Route::get('/{id}', 'ClientController@getById');
    Route::post('create', 'ClientController@store');
    Route::put('update/{id}', 'ClientController@update');
    Route::delete('delete/{id}', 'ClientController@destroy');
});

Route::prefix('schedule')->group(function() {
    Route::get('all', 'ScheduleController@all');
    Route::get('/{id}', 'ScheduleController@getById');
    Route::post('create', 'ScheduleController@store');
    Route::put('update/{id}', 'ScheduleController@update');
    Route::delete('delete/{id}', 'ScheduleController@destroy');
    Route::put('update-status/{id}', 'ScheduleController@updateStatus');
});
