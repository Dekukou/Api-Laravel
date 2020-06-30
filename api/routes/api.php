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

#USER

Route::post('user', 'UserController@register');
Route::post('auth', 'UserController@authenticate');

Route::get('users', 'UserController@getUsers');
Route::get('videos', 'VideoController@getVideos');
Route::get('videos/{user_id}', 'VideoController@getVideosByUserId');

Route::group(['middleware' => 'auth:api'], function() {
	Route::delete('user/{id}', 'UserController@deleteUser');
	Route::put('user/{id}', 'UserController@updateUser');
	Route::get('user/{id}', 'UserController@getUser');

	Route::post('video', 'VideoController@create');
	Route::put('video/{id}', 'VideoController@updateVideo');
	Route::patch('video/{id}', 'VideoController@encodeVideo');
	Route::delete('video/{id}', 'VideoController@deleteVideo');

	Route::post('video/{id}/comments', 'CommentController@create');
	Route::get('video/{id}/comments', 'CommentController@getComments');
});
