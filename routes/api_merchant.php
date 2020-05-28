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
Route::namespace('Api\Merchant')->middleware(['companyValidate:merchant','auth:merchant'])->group(function () {
    Route::get('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout');
    Route::put('my-password', 'AuthController@updatePassword');
    Route::put('', 'MerchantController@update');
    Route::put('api', 'MerchantApiController@update');

    //订单管理
    Route::prefix('order')->group(function () {
        //取件列表查询初始化
        Route::get('/initPickupIndex', 'OrderController@initPickupIndex');
        //派件列表查询初始化
        Route::get('/initPieIndex', 'OrderController@initPieIndex');
        //查询初始化
        Route::get('/initIndex','OrderController@initIndex');
        //列表查询
        Route::get('/', 'OrderController@index');
        //获取详情
        Route::get('/{id}', 'OrderController@show');
        //新增初始化
        Route::get('/initStore', 'OrderController@initStore');
        //新增
        Route::post('/', 'OrderController@store');
        //修改
        Route::put('/{id}', 'OrderController@update');
        //获取可分配路线日期
        Route::get('/{id}/getTourDate', 'OrderController@getTourDate');
        //获取可分配路线日期(新增)
        Route::get('/getDate', 'OrderController@getDate');
        //获取可分配的站点列表
        //Route::get('/{id}/getBatchPageListByOrder', 'OrderController@getBatchPageListByOrder');
        //分配至站点
        Route::put('/{id}/assignToBatch', 'OrderController@assignToBatch');
        //从站点移除
        Route::delete('/{id}/removeFromBatch', 'OrderController@removeFromBatch');
        //删除
        Route::delete('/{id}', 'OrderController@destroy');
        //恢复
        Route::put('/{id}/recovery', 'OrderController@recovery');
        //彻底删除
        Route::delete('/{id}/actualDestroy', 'OrderController@actualDestroy');
    });

    //物流状态管理
    Route::prefix('order-trail')->group(function () {
        //rest api 放在最后
        Route::get('/', 'OrderTrailController@index')->name('order-trail.index');
    });

    //订单导入记录管理
    Route::prefix('order-import')->group(function () {
        //上传模板
        Route::post('/uploadTemplate', 'OrderImportController@uploadTemplate');
        //生成模板
        Route::get('/getTemplate', 'OrderImportController@templateExport');
        //获取模板说明
        Route::get('/getTemplateTips', 'OrderImportController@getTemplate');
        //批量导入
        Route::post('/import', 'OrderController@import');
        //批量新增
        Route::post('/storeByList', 'OrderController@storeByList');
        //列表查询
        Route::get('/log', 'OrderImportController@index');
        //记录详情
        Route::get('/log/{id}', 'OrderImportController@show');
        //检查
        Route::post('/check', 'OrderController@importCheckByList');
    });

    //主页统计
    Route::prefix('home')->group(function () {
        Route::get('/', 'HomeController@home');
        Route::get('/this-week-count', 'HomeController@thisWeekCount');
        Route::get('/last-week-count', 'HomeController@lastWeekCount');
        Route::get('/this-month-count', 'HomeController@thisMonthCount');
        Route::get('/last-month-count', 'HomeController@lastMonthCount');
        Route::get('/period-count', 'HomeController@periodCount');
        Route::get('/all-count', 'HomeController@all');
    });

    //运价管理
    Route::prefix('transport-price')->group(function () {
        Route::get('/', 'TransportPriceController@show');
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

    //取件线路
    Route::prefix('tour')->group(function () {
        //列表查询
        Route::get('/', 'tourController@index')->name('tour.index');
        //详情
        Route::get('/{id}', 'TourController@show')->name('tour.show');
        //追踪
        Route::get('/track', 'routeTrackingController@show')->name('tour.track');
        //路径
        Route::get('/driver', 'TourDriverController@getListByTourNo')->name('tour.driver');
    });
});
