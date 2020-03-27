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

//认证
Route::namespace('Api\Merchant')->middleware(['auth:merchant_api'])->group(function () {
    Route::post('me', 'AuthController@me');
    Route::post('order', 'OrderController@store');
    Route::post('cancel-order', 'OrderController@destroy');
});
