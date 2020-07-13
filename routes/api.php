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

Route::group(['middleware' => ['api']], function () {
    Route::post('auth/signup', 'AuthController@signup');
    Route::post('auth/signin', 'AuthController@signin');
    Route::post('auth/signout', 'AuthController@signout');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('/profile', 'UserController@profile');
        Route::post('/note', 'NoteController@create');
        Route::get('/note', 'NoteController@index');
        Route::get('/note/{id}', 'NoteController@show');
        Route::put('/note/{id}', 'NoteController@update');
        Route::delete('/note/{id}', 'NoteController@delete');
    });
});
