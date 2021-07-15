<?php

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


    Route::post('order', 'OrderController@store')->name('merchant_api.order.store');//新增订单

    Route::post('cancel-order', 'OrderController@destroy');//删除订单

    Route::post('order-update-address', 'OrderController@updateAddressDate');//修改订单地址日期
    Route::post('cancel-all-order', 'OrderController@destroyAll');//批量删除订单
    Route::post('order-out-status', 'OrderController@updateOutStatus');//出库


    Route::post('post-code-date-list', 'LineController@getDateListByPostCode');//获取可选日期


    Route::post('order-dispatch-info', 'OrderController@getOrderDispatchInfo');//派送情况

    Route::post('order-update-phone-date', 'OrderController@updateByApi');//修改订单
    Route::post('order-update-phone-date-list', 'OrderController@updateByApiList');//修改订单
    Route::post('package-info', 'PackageController@showByApi');//包裹查询
    Route::post('order-info', 'OrderController@showByApi');//订单查询
    Route::post('update-order-item-list', 'OrderController@updateItemList');//修改明细
    Route::post('/again-order-info', 'OrderController@getAgainInfo');//获取继续派送(再次取派)信息
    Route::post('/again-order', 'OrderController@again'); //继续派送(再次取派)
    Route::post('/end-order', 'OrderController@end');//终止派送
    Route::post('/order-update-second-date', 'OrderController@updateSecondDate');//修改派送日期
    Route::post('/get-all-line-range', 'LineController@getAllLineRange');//获取所有邮编
    Route::post('/order-update', 'OrderController@updateById');//修改订单
    Route::post('/location', 'AddressController@showByApi');//查询地址
});
