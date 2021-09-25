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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api'], function($router) {
    Route::get('/hello', function() {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    })->name('api.hello');
});

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('/login', 'App\Http\Controllers\Api\Auth\LoginController@store')->name('api.auth.login');
    Route::post('/register', 'App\Http\Controllers\Api\Auth\RegisterController@store')->name('api.auth.register');
    Route::post('/forgot', 'App\Http\Controllers\Api\Auth\ForgotPasswordController@store')->name('api.auth.forgot.password');


    Route::group(['middleware' => 'jwt.auth'], function() {
        Route::post('/change', 'App\Http\Controllers\Api\Auth\ChangePasswordController@store')->name('api.auth.change.password');
        Route::get('/logout', 'App\Http\Controllers\Api\Auth\LogoutController@get')->name('api.auth.logout');
        Route::get('/me', 'App\Http\Controllers\Api\Auth\UserController@show')->name('api.auth.user');

        Route::group(['middleware' => 'jwt.refresh'], function() {
            Route::get('/refresh', 'App\Http\Controllers\Api\Auth\RefreshTokenController@get')->name('api.auth.refresh');
        });

        Route::get('/protected', function() {
			return response()->json([
				'message' => 'Access to this item is only for authenticated user. Provide a token in your request!'
			]);
		});
    });
});

