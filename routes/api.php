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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication Routes...
Route::post('login', 'API\Auth\LoginController@login');
Route::post('logout', 'API\Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::post('register', 'API\Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'API\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'API\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'API\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'API\Auth\ResetPasswordController@reset');

//Send Message From User
Route::post('/send', 'API\ChatController@setMessages');

Route::post('/user/login', 'API\UserController@postLogin');
Route::post('/user/logout', 'API\UserController@postLogout');