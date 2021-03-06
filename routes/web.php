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

namespace Illuminate\Support\Facades;

Route::group(['namespace' => 'Mattermost', 'middleware' => ['mattermost']], function() {
    Route::post('/team', 'TeamController@router');
    Route::post('/pr', 'ProjectController@router');
    Route::post('/t', 'TaskController@router');
});
