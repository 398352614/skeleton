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
    Route::post('order', 'OrderController@store');//新增订单
    Route::post('cancel-order', 'OrderController@destroy');//删除订单
    Route::post('order-out-status', 'OrderController@updateOutStatus');//出库
    Route::post('post-code-date-list', 'LineController@getDateListByPostCode');//获取可选日期
    Route::post('order-dispatch-info', 'OrderController@getOrderDispatchInfo');//派送情况
    Route::post('update-order', 'OrderController@updateByApi');//修改订单
});
