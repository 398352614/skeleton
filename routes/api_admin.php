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
Route::namespace('Api\Admin')->group(function () {
    Route::post('login', 'AuthController@login');

    Route::post('register', 'RegisterController@store');
    Route::post('register/apply', 'RegisterController@applyOfRegister');

    Route::post('password-reset', 'RegisterController@resetPassword');
    Route::post('password-reset/apply', 'RegisterController@applyOfReset');
});

//认证
Route::namespace('Api\Admin')->middleware(['auth:admin'])->group(function () {
    Route::get('me', 'AuthController@me');
    Route::post('logout', 'AuthController@loginout');

    //订单管理
    Route::prefix('order')->group(function () {
        //列表查询初始化
        Route::get('/initIndex', 'OrderController@initIndex');
        //列表查询
        Route::get('/', 'OrderController@index');
        //获取详情
        Route::get('/{id}', 'OrderController@show');
        //新增
        Route::post('/', 'OrderController@store');
        //修改
        Route::put('/', 'OrderController@update');
        //删除
        Route::delete('/', 'OrderController@destroy');
    });

    Route::prefix('driver')->group(function () {
        Route::post('/driver-register', 'DriverController@driverRegister');
        Route::get('/driver-work', 'DriverController@driverWork');//获取司机工作日driverWork?driver_id=105
        Route::post('assgin-driverWork', 'DriverController@assginDriverWork');//给司机分配工作信息（也就是产品图上的审核）
        Route::get('/crop-type', 'DriverController@cropType');//获取合作方式
        Route::get('/driver-status', 'DriverController@driverStatus');//获取状态
        Route::post('/lock-driver', 'DriverController@lockDriver');//锁定或解锁司机

        //rest api 放在最后
        Route::get('/', 'DriverController@index')->name('driver.index');//司机列表?page=1&page_size=10&status=&crop_type=&keywords=
        Route::get('/{id}', 'DriverController@show')->name('driver.show');//司机详情
        Route::delete('/{id}', 'DriverController@destroy')->name('driver.destroy');//删除司机
    });
});