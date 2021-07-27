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
Route::namespace('Api\Merchant')->middleware(['companyValidate:merchant', 'auth:merchant'])->group(function () {
    Route::get('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout');
    Route::put('my-password', 'AuthController@updatePassword');
    Route::put('', 'MerchantController@update');
    Route::put('api', 'MerchantApiController@update');

    //订单管理
    Route::prefix('order')->group(function () {
        //订单统计
        Route::get('/count', 'OrderController@ordercount');
        //查询初始化
        Route::get('/initIndex', 'OrderController@initIndex');
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
        //获取继续派送(再次取派)信息
        Route::get('/{id}/again-info', 'OrderController@getAgainInfo');
        //继续派送(再次取派)
        Route::put('/{id}/again', 'OrderController@again');
        //终止派送
        Route::put('/{id}/end', 'OrderController@end');
        //删除
        Route::delete('/{id}', 'OrderController@destroy');
        //订单追踪
        Route::get('/{id}/track', 'OrderController@track');
        //批量更新电话日期
        Route::post('/update-phone-date-list', 'OrderController@updateByApiList');
        //获取订单的运单列表
        Route::get('/{id}/tracking-order', 'OrderController@getTrackingOrderList');
        //修改订单地址
        Route::put('/{id}/update-address-date', 'OrderController@updateAddressDate');
        //获取可分配路线日期
        Route::get('/{id}/get-date', 'OrderController@getAbleDateList');
        //通过地址获取可分配的路线日期列表
        Route::get('/get-date', 'OrderController@getAbleDateListByAddress');
        //分配至站点
        Route::put('/{id}/assign-batch', 'OrderController@assignToBatch');
        //从站点移除
        Route::delete('/{id}/remove-batch', 'OrderController@removeFromBatch');
    });

    //运单管理
    Route::prefix('tracking-order')->group(function () {
        //查询初始化
        Route::get('/init-index', 'TrackingOrderController@initIndex');
        //运单统计
        Route::get('/count', 'TrackingOrderController@trackingOrderCount');
        //列表查询
        Route::get('/', 'TrackingOrderController@index');
    });

    Route::prefix('package')->group(function () {
        //列表查询
        Route::get('/', 'PackageController@index');
        //获取详情
        Route::get('/{id}', 'PackageController@show');
    });

    Route::prefix('material')->group(function () {
        //列表查询
        Route::get('/', 'MaterialController@index');
        //获取详情
        Route::get('/{id}', 'MaterialController@show');
    });

    //物流状态管理
    Route::prefix('order-trail')->group(function () {
        //rest api 放在最后
        Route::get('/{order_no}', 'OrderTrailController@index')->name('order-trail.index');
    });

    //订单导入记录管理
    Route::prefix('order-import')->middleware(['importCheck'])->group(function () {
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
    Route::prefix('address')->group(function () {
        Route::get('/', 'AddressController@index');//列表查询
        Route::get('/{id}', 'AddressController@show');//获取详情
        Route::post('/', 'AddressController@store');//新增
        Route::put('/{id}', 'AddressController@update');//修改
        Route::delete('/{id}', 'AddressController@destroy');//删除
    });

    //公共接口
    Route::prefix('common')->group(function () {
        //获取具体地址经纬度
        Route::get('getLocation', 'CommonController@getLocation');
        //获取所有国家列表
        Route::get('getCountryList', 'CommonController@getCountryList');
        //获取所有邮编列表
        Route::get('get-postcode', 'CommonController@getPostcode');
        //字典
        Route::get('dictionary', 'CommonController@dictionary');
        //获取所有线路范围
        Route::get('line-range', 'LineController@getAllLineRange');
        //获取具体地址经纬度
        Route::post('/location', 'AddressController@showByApi');
    });

    //取件线路
    Route::prefix('tour')->group(function () {
        //列表查询
        Route::get('/', 'TourController@index')->name('tour.index');
        //详情
        Route::get('/{id}', 'TourController@show')->name('tour.show');
        //追踪
        Route::get('/track', 'RouteTrackingController@show')->name('tour.track');
        //路径
        Route::get('/driver', 'TourDriverController@getListByTourNo')->name('tour.driver');
    });
});
