<?php

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
Route::get('jobs', 'JobController@index');
Route::get('jobs/{id}', 'JobController@show');
Route::post('jobs', 'JobController@store');
Route::put('jobs/{id}', 'JobController@update');
Route::put('jobs/{id}/work', 'JobController@working');
Route::delete('job/{id}', 'JobController@delete');