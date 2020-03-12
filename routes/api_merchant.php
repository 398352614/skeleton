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
    Route::put('', 'MerchantController@update');
    Route::put('api','merchantApiController@update');

    //主页统计
    Route::prefix('home')->group(function () {
        Route::get('/', 'HomeController@home');
        Route::get('/this-week-count', 'HomeController@thisWeekcount');
        Route::get('/last-week-count', 'HomeController@lastWeekcount');
        Route::get('/this-month-count', 'HomeController@thisMonthcount');
        Route::get('/last-month-count', 'HomeController@lastMonthcount');
        Route::get('/period-count', 'HomeController@periodCount');
    });

    //运价管理
    Route::prefix('transport-price')->group(function () {
        Route::get('/', 'TransportPriceController@me');
    });

    //API管理
    Route::prefix('api')->group(function () {
        Route::get('/', 'MerchantApiController@show');//获取详情
        Route::put('/', 'MerchantApiController@update');//修改
    });

    //收件人地址管理
    Route::prefix('receiver-address')->group(function () {
        Route::get('/', 'ReceiverAddressController@index');//列表查询
        Route::get('/{id}', 'ReceiverAddressController@show');//获取详情
        Route::post('/', 'ReceiverAddressController@store');//新增
        Route::put('/{id}', 'ReceiverAddressController@update');//修改
        Route::delete('/{id}', 'ReceiverAddressController@destroy');//删除
    });

    //发件人地址管理
    Route::prefix('sender-address')->group(function () {
        Route::get('/', 'SenderAddressController@index'); //查询
        Route::get('/{id}', 'SenderAddressController@show'); //详情
        Route::post('/', 'SenderAddressController@store'); //新增
        Route::put('/{id}', 'SenderAddressController@update'); //修改
        Route::delete('/{id}', 'SenderAddressController@destroy'); //删除
    });

    //公共接口
    Route::prefix('common')->group(function () {
        //获取具体地址经纬度
        Route::get('getLocation', 'CommonController@getLocation');
        //获取所有国家列表
        Route::get('getCountryList', 'CommonController@getCountryList');
    });
});
