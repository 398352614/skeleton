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
    //登录
    Route::post('login', 'AuthController@login');
    //修改密码验证码
    Route::put('password/reset', 'RegisterController@resetPassword');
    //修改密码
    Route::post('password/code', 'RegisterController@applyOfReset');
    //注册
    Route::post('register', 'RegisterController@register');
    //注册验证码
    Route::post('register/apply', 'RegisterController@applyOfRegister');
    //重置验证码
    Route::put('password-reset/verify', 'RegisterController@verifyResetCode');
});

//认证
Route::namespace('Api\Merchant')->middleware(['auth:merchant'])->group(function () {
    //个人信息
    Route::get('', 'AuthController@me');
    //登出
    Route::post('logout', 'AuthController@logout');
    //修改密码
    Route::put('password', 'AuthController@updatePassword');
    //修改个人信息
    Route::put('', 'MerchantController@update');
    //切换时区
    Route::put('timezone', 'AuthController@updateTimezone');
    //公司信息
    Route::get('company', 'CompanyController@show');
    //获取可选日期
    Route::post('order-dispatch-info/{id}', 'LineController@getOrderDispatchInfo');

    //订单管理
    Route::prefix('order')->group(function () {
        //订单统计
        Route::get('/count', 'OrderController@ordercount');
        //列表查询
        Route::get('/', 'OrderController@index');
        //获取详情
        Route::get('/{id}', 'OrderController@show');
        //新增
        Route::post('/', 'OrderController@store');
        //修改
        Route::put('/{id}', 'OrderController@update');


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
        //表格导出
        Route::get('/excel', 'OrderController@orderExcelExport')->name('order.index');
        //批量打印面单
        Route::get('/bill', 'OrderController@orderBillPrint')->name('order.print');
        //获取网点
        Route::get('/warehouse', 'OrderController@getWarehouse')->name('order.store');
        //订单轨迹
        Route::get('/{id}/trail', 'OrderTrailController@show')->name('order-trail.index');
        //运价估算
        Route::post('/price-count', 'OrderController@priceCount')->name('order.price-count');
    });

    //地址管理
    Route::prefix('address')->group(function () {
        //列表查询
        Route::get('/', 'AddressController@index');
        //获取详情
        Route::get('/{id}', 'AddressController@show');
        //新增
        Route::post('/', 'AddressController@store');
        //修改
        Route::put('/{id}', 'AddressController@update');
        //删除
        Route::delete('/{id}', 'AddressController@destroy');
    });


    //运价管理
    Route::prefix('transport-price')->group(function () {
        Route::get('/', 'TransportPriceController@show');
    });

    //公共接口
    Route::prefix('common')->group(function () {
        //获取具体地址经纬度
        Route::get('/location', 'CommonController@getLocation');
        //获取所有国家列表
        Route::get('/country', 'CommonController@getCountryList');

        //字典
        Route::get('/dictionary', 'CommonController@dictionary');
        //获取所有线路范围
        Route::get('/line-range', 'LineController@getAllLineRange');
        //获取具体地址经纬度
        Route::post('/location', 'AddressController@showByApi');
    });

    //费用管理
    Route::prefix('fee')->group(function () {
        //列表查询
        Route::get('/', 'FeeController@index')->name('fee.index');
        //详情
        Route::get('/{id}', 'FeeController@show')->name('fee.index');
    });

    //轮播图管理
    Route::prefix('carousel')->group(function () {
        //列表查询
        Route::get('/', 'CarouselController@index')->name('carousel.index');
        //详情
        Route::get('/{id}', 'CarouselController@show')->name('carousel.index');
    });

    //文章管理
    Route::prefix('article')->group(function () {
        //列表查询
        Route::get('/', 'ArticleController@index')->name('article.index');
        //详情
        Route::get('/{id}', 'ArticleController@show')->name('article.index');
    });

    //条款管理
    Route::prefix('service-agreement')->group(function () {
        //列表查询
        Route::get('/', 'ServiceAgreementController@index')->name('service-agreement.index');
        //详情
        Route::get('/{id}', 'ServiceAgreementController@show')->name('service-agreement.index');
    });

    //账单
    Route::prefix('bill')->group(function () {
        //充值列表
        Route::get('/recharge', 'BillController@index')->name('bill.merchant-index');
        //充值详情
        Route::get('/recharge/{id}', 'BillController@show')->name('bill.merchant-index');
        //充值
        Route::post('/recharge', 'BillController@merchantRecharge')->name('bill.merchant-recharge');
//        //充值完成
//        Route::put('/recharge/pay', 'BillController@pay')->name('bill.pay');
    });

    //账目
    Route::prefix('ledger')->group(function () {
        //列表
        Route::get('/', 'LedgerController@show')->name('ledger.index');
//        //修改
//        Route::post('/{id}', 'LedgerController@update')->name('ledger.update');
    });

    //支付管理
    Route::prefix('payment')->group(function () {
        //列表查询
        Route::post('/paypal', 'PaypalController@store')->name('paypal.pay');
        //详情
        Route::put('/paypal', 'PaypalController@pay')->name('paypal.pay');
    });
});
