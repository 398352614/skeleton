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
//公共接口
Route::namespace('Api\Merchant')->group(function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'RegisterController@store');
    Route::post('register/apply', 'RegisterController@applyOfRegister'); // 暂时无用
    Route::put('password-reset', 'RegisterController@resetPassword'); //暂时无用
    Route::post('password-reset/apply', 'RegisterController@applyOfReset'); // 暂时无用
    Route::put('password-reset/verify', 'RegisterController@verifyResetCode');
});

//认证
Route::namespace('Api\Merchant')->middleware(['auth:merchant'])->group(function () {
    Route::get('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout');
    Route::put('my-password', 'AuthController@updatePassword');
});
