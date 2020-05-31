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


Route::post('login','AuthenticationController@login')->name('login');
Route::post('registration','AuthenticationController@registration');

Route::middleware('auth:sanctum')->group(function(){
    Route::group(['prefix' => 'contact'], function () {
        Route::get('list','ContactController@list');
        Route::get('single/{id}','ContactController@single');
        Route::post('add','ContactController@add');
        Route::post('edit/{id}','ContactController@edit');
        Route::get('delete/{id}','ContactController@delete');
    });
});
