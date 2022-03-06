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

Route::get('/get_info', 'ApiController@getInfo');
Route::get('/load_message', 'ApiController@loadMessage');
Route::post('/change_no_read', 'ApiController@changeNoRead');
Route::post('/save_message', 'ApiController@saveMessage');
Route::post('/upload', 'ApiController@upload');
Route::get('/get_list', 'ApiController@getList');
