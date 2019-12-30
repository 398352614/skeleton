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

    Route::get('/tour/callback', 'TourController@callback');         //自动优化线路
});

//认证
Route::namespace('Api\Admin')->middleware(['auth:admin'])->group(function () {
    Route::get('me', 'AuthController@me');
    Route::post('logout', 'AuthController@loginout');

    //订单管理
    Route::prefix('order')->group(function () {
        //取件列表查询初始化
        Route::get('/initPickupIndex', 'OrderController@initPickupIndex');
        //派件列表查询初始化
        Route::get('/initPieIndex', 'OrderController@initPieIndex');
        //列表查询
        Route::get('/', 'OrderController@index');
        //获取详情
        Route::get('/{id}', 'OrderController@show');
        //新增初始化
        Route::get('/initStore', 'OrderController@initStore');
        //新增
        Route::post('/', 'OrderController@store');
        //修改
        Route::put('/', 'OrderController@update');
        //删除
        Route::delete('/', 'OrderController@destroy');
    });

    //司机管理
    Route::prefix('driver')->group(function () {
        Route::post('/driver-register', 'DriverController@driverRegister');
        Route::get('/driver-work', 'DriverController@driverWork');//获取司机工作日driverWork?driver_id=105
        Route::post('assgin-driverWork', 'DriverController@assginDriverWork');//给司机分配工作信息（也就是产品图上的审核）
        Route::get('/crop-type', 'DriverController@cropType');//获取合作方式
        Route::get('/driver-status', 'DriverController@driverStatus');//获取状态
        Route::post('/{id}/lock-driver', 'DriverController@lockDriver');//锁定或解锁司机

        //rest api 放在最后
        Route::get('/', 'DriverController@index')->name('driver.index');//司机列表?page=1&page_size=10&status=&crop_type=&keywords=
        Route::get('/{id}', 'DriverController@show')->name('driver.show');//司机详情
        Route::put('/{id}', 'DriverController@update')->name('driver.update');//司机修改
        Route::delete('/{id}', 'DriverController@destroy')->name('driver.destroy');//删除司机
    });

    //车辆管理
    Route::prefix('car')->group(function () {
        Route::put('/{id}/lock', 'CarController@lock')->name('car.lock');
        Route::get('/brands', 'CarController@getBrands')->name('car.brands');       // 获取品牌列表
        Route::post('/addbrand', 'CarController@addBrand')->name('car.addbrand');   // 添加品牌
        Route::get('/models', 'CarController@getModels')->name('car.models');       // 获取型号列表
        Route::post('/addmodel', 'CarController@addModel')->name('car.addmodel');   // 添加模型

        //rest api 放在最后
        Route::get('/', 'CarController@index')->name('car.index');
        Route::post('/', 'CarController@store')->name('car.store');
        Route::get('/{id}', 'CarController@show')->name('car.show');//车辆详情
        Route::put('/{id}', 'CarController@update')->name('car.update');//车辆修改
        Route::delete('/{id}', 'CarController@destroy')->name('car.destroy');//车辆删除

        // $router->post('car/lock', 'CarInfoController@lock'); //车辆锁定操作
    });

    //站点管理
    Route::prefix('batch')->group(function () {
        //rest api 放在最后
        Route::get('/', 'BatchController@index')->name('batch.index');
        Route::get('/{id}', 'BatchController@show')->name('batch.show');//批次详情
    });

    //线路任务管理
    Route::prefix('tour')->group(function () {
        Route::post('/update-batch-index', 'TourController@updateBatchIndex');         //更改线路任务顺序 -- 手动优化
        Route::post('/auto-op-tour', 'TourController@autoOpTour');         //自动优化线路

        //rest api 放在最后
        Route::get('/', 'TourController@index')->name('tour.index');
        Route::get('/{id}', 'TourController@show')->name('tour.show');
        Route::put('/{id}/assignDriver', 'TourController@assignDriver');               //分配司机
        Route::put('/{id}/cancelAssignDriver', 'TourController@cancelAssignDriver');   //取消分配司机
        Route::put('/{id}/assignCar', 'TourController@assignCar');                     //分配车辆
        Route::put('/{id}/cancelAssignCar', 'TourController@cancelAssignCar');         //取消分配车辆
    });

    //任务报告
    Route::prefix('report')->group(function () {
        //列表查询
        Route::get('/', 'ReportController@index');
        //获取详情
        Route::get('/{id}', 'ReportController@show');
    });

    //线路管理
    Route::prefix('line')->group(function () {
        //列表查询
        Route::get('/', 'LineController@index');
        //获取详情
        Route::get('/{id}', 'LineController@show');
        //新增
        Route::post('/', 'LineController@store');
        //修改
        Route::put('/{id}', 'LineController@update');
        //删除
        Route::delete('/{id}', 'LineController@destroy');
    });

    //仓库管理
    Route::prefix('warehouse')->group(function () {
        //列表查询
        Route::get('/', 'WareHouseController@index');
        //获取详情
        Route::get('/{id}', 'WareHouseController@show');
        //新增
        Route::post('/', 'WareHouseController@store');
        //修改
        Route::put('/{id}', 'WareHouseController@update');
        //删除
        Route::delete('/{id}', 'WareHouseController@destroy');
    });

    //公司信息
    Route::prefix('company-info')->group(function () {
        Route::get('/', 'CompanyController@index');
        Route::put('/', 'CompanyController@update');
    });

    //国家管理
    Route::prefix('country')->group(function () {
        Route::get('/', 'CountryController@index');
        Route::post('/', 'CountryController@store');
    });

    //公共接口
    Route::prefix('common')->group(function () {
        //获取具体地址经纬度
        Route::get('getLocation', 'CommonController@getLocation');
    });

    //上传接口
    Route::prefix('upload')->group(function () {
        //获取可上传的图片目录列表
        Route::get('getImageDirList', 'UploadController@getImageDirList');
        //图片上传
        Route::post('imageUpload', 'UploadController@imageUpload');
        //获取可上传的文件目录列表
        Route::get('getFileDirList', 'UploadController@getFileDirList');
        //文件上传
        Route::post('fileUpload', 'UploadController@fileUpload');
    });
});