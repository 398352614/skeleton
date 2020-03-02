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
    Route::put('password-reset', 'RegisterController@resetPassword');
    Route::post('password-reset/apply', 'RegisterController@applyOfReset');
    Route::put('password-reset/verify', 'RegisterController@verifyResetCode');
    Route::get('/tour/callback', 'TourController@callback');         //自动优化线路

    Route::prefix('tour')->group(function () {
        Route::get('unlock-redis', 'TourController@unlockRedis'); // 取消 redis 锁
    });
});

//认证
Route::namespace('Api\Admin')->middleware(['auth:admin'])->group(function () {
    Route::get('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout');
    Route::put('my-password', 'AuthController@updatePassword');


    //主页统计
    Route::prefix('home')->group(function () {
        Route::get('/', 'HomeController@home');
        Route::get('/this-week-count', 'HomeController@thisWeekcount');
        Route::get('/last-week-count', 'HomeController@lastWeekcount');
        Route::get('/this-month-count', 'HomeController@thisMonthcount');
        Route::get('/last-month-count', 'HomeController@lastMonthcount');
        Route::get('/period-count', 'HomeController@periodCount');
    });


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
        Route::put('/{id}', 'OrderController@update');
        //获取可分配路线日期
        Route::get('/{id}/getTourDate','OrderController@getTourDate');
        //获取可分配的站点列表
        Route::get('/{id}/getBatchPageListByOrder', 'OrderController@getBatchPageListByOrder');
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

    //订单导入记录管理
    Route::prefix('order-import')->group(function () {
        //上传模板
        Route::post('/uploadTemplate','OrderImportController@uploadTemplate');
        //获取模板
        Route::get('/getTemplate','OrderImportController@getTemplate');
        //批量导入
        Route::post('/import','OrderController@orderImport');
        //批量新增
        Route::post('/storeByList','OrderController@storeByList');
        //列表查询
        Route::get('/log', 'OrderImportController@index');
        //记录详情
        Route::get('/log/{id}', 'OrderImportController@show');
    });

    //司机管理
    Route::prefix('driver')->group(function () {
        Route::post('/driver-register', 'DriverController@driverRegister'); //司机新增
        Route::get('/driver-work', 'DriverController@driverWork'); //获取司机工作日driverWork?driver_id=105
        Route::post('assgin-driverWork', 'DriverController@assginDriverWork'); //给司机分配工作信息（也就是产品图上的审核）
        Route::get('/crop-type', 'DriverController@cropType'); //获取合作方式
        Route::get('/driver-status', 'DriverController@driverStatus'); //获取状态
        Route::post('/{id}/lock-driver', 'DriverController@lockDriver'); //锁定或解锁司机
        Route::put('/{id}/reset-password', 'DriverController@resetPassword')->name('driver.reset-password'); //修改司机密码

        //rest api 放在最后
        Route::get('/', 'DriverController@index')->name('driver.index'); //司机列表?page=1&page_size=10&status=&crop_type=&keywords=
        Route::get('/{id}', 'DriverController@show')->name('driver.show'); //司机详情
        Route::put('/{id}', 'DriverController@update')->name('driver.update'); //司机修改
        Route::delete('/{id}', 'DriverController@destroy')->name('driver.destroy'); //删除司机
    });

    //车辆管理
    Route::prefix('car')->group(function () {
        Route::put('/{id}/lock', 'CarController@lock')->name('car.lock'); //车辆锁定操作
        Route::get('/brands', 'CarController@getBrands')->name('car.brands');       // 获取品牌列表
        Route::post('/addbrand', 'CarController@addBrand')->name('car.addbrand');   // 添加品牌
        Route::get('/models', 'CarController@getModels')->name('car.models');       // 获取型号列表
        Route::post('/addmodel', 'CarController@addModel')->name('car.addmodel');   // 添加模型

        //rest api 放在最后
        Route::get('/', 'CarController@index')->name('car.index');
        Route::post('/', 'CarController@store')->name('car.store');
        Route::get('/{id}', 'CarController@show')->name('car.show'); //车辆详情
        Route::put('/{id}', 'CarController@update')->name('car.update'); //车辆修改
        Route::delete('/{id}', 'CarController@destroy')->name('car.destroy'); //车辆删除

        // $router->post('car/lock', 'CarInfoController@lock');
    });

    //站点管理
    Route::prefix('batch')->group(function () {
        //rest api 放在最后
        Route::get('/', 'BatchController@index')->name('batch.index');
        Route::get('/{id}', 'BatchController@show')->name('batch.show');       //批次详情
        Route::put('/{id}/cancel', 'BatchController@cancel');                        //取消取派
        Route::get('/{id}/getTourList', 'BatchController@getTourList');              //获取取件线路列表
        Route::put('/{id}/assignToTour', 'BatchController@assignToTour');            //分配站点至取件线路
        Route::delete('/{id}/removeFromTour', 'BatchController@removeFromTour');     //移除站点
    });

    //物流状态管理
    Route::prefix('order-trail')->group(function () {
        //rest api 放在最后
        Route::get('/', 'OrderTrailController@index')->name('order-trail.index');
    });

    //站点 异常管理
    Route::prefix('batch-exception')->group(function () {
        //列表查询
        Route::get('/', 'BatchExceptionController@index');
        //获取详情
        Route::get('/{id}', 'BatchExceptionController@show');
        //处理
        Route::put('/{id}/deal', 'BatchExceptionController@deal');
    });

    //线路任务管理
    Route::prefix('tour')->group(function () {
        Route::post('/update-batch-index', 'TourController@updateBatchIndex')->middleware('checktourredislock');         //更改线路任务顺序 -- 手动优化
        Route::post('/auto-op-tour', 'TourController@autoOpTour')->middleware('checktourredislock');         //自动优化线路

        //rest api 放在最后
        Route::get('/', 'TourController@index')->name('tour.index');
        Route::get('/{id}', 'TourController@show')->name('tour.show');
        Route::put('/{id}/assignDriver', 'TourController@assignDriver');               //分配司机
        Route::put('/{id}/cancelAssignDriver', 'TourController@cancelAssignDriver');   //取消分配司机
        Route::put('/{id}/assignCar', 'TourController@assignCar');                     //分配车辆
        Route::put('/{id}/cancelAssignCar', 'TourController@cancelAssignCar');         //取消分配车辆
        Route::put('/{id}/unlock', 'TourController@unlock');         //取消待出库
        Route::get('/{id}/excel', 'TourController@batchExcel');//导出投递站点excel
        Route::get('/{id}/txt', 'TourController@cityTxt');//导出投递城市txt
        Route::get('/{id}/png', 'TourController@batchPng');//导出站点地图png
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

    //公司配置
    Route::prefix('company-config')->group(function () {
        //获取详情
        Route::get('/show', 'CompanyConfigController@show');
        //修改
        Route::put('/update', 'CompanyConfigController@update');

    });

    //员工管理
    Route::prefix('employees')->group(function () {
        Route::get('/', 'EmployeeController@index');
        Route::get('/{id}', 'EmployeeController@show');
        Route::put('/{id}', 'EmployeeController@update');
        Route::post('/', 'EmployeeController@store');
        Route::delete('/{id}', 'EmployeeController@destroy');
        Route::put('/{id}/forbid-login/{enabled}', 'EmployeeController@setLogin');
        Route::put('/{id}/password', 'EmployeeController@resetPassword');//修改员工密码
    });

    //组织管理
    Route::prefix('institutions')->group(function () {
        Route::get('/', 'InstitutionController@index');
        Route::get('/{id}', 'InstitutionController@show');
        Route::get('/{id}/employees', 'InstitutionController@indexOfEmployees');
        Route::put('/{id}/move-to/{parentId}', 'InstitutionController@move');
        Route::put('/{id}', 'InstitutionController@update');
        Route::post('/', 'InstitutionController@store');
        Route::delete('/{id}', 'InstitutionController@destroy');
    });

    //客户管理 - 收货方管理
    Route::prefix('receiver-address')->group(function () {
        //列表查询
        Route::get('/', 'ReceiverAddressController@index');
        //获取详情
        Route::get('/{id}', 'ReceiverAddressController@show');
        //新增
        Route::post('/', 'ReceiverAddressController@store');
        //修改
        Route::put('/{id}', 'ReceiverAddressController@update');
        //删除
        Route::delete('/{id}', 'ReceiverAddressController@destroy');
    });

    //发件人地址管理
    Route::prefix('sender-address')->group(function () {
        Route::get('/', 'SenderAddressController@index'); //发件人地址查询
        Route::get('/{id}', 'SenderAddressController@show'); //发件人地址详情
        Route::post('/', 'SenderAddressController@store'); //发件人地址新增
        Route::put('/{id}', 'SenderAddressController@update'); //发件人地址修改
        Route::delete('/{id}', 'SenderAddressController@destroy'); //发件人地址删除
    });


    //国家管理
    Route::prefix('country')->group(function () {
        Route::get('/', 'CountryController@index');
        Route::post('/', 'CountryController@store');
        Route::delete('/{id}', 'CountryController@destroy');
    });

    //来源管理
    Route::prefix('source')->group(function () {
        Route::get('/', 'SourceController@index');
        Route::post('/', 'SourceController@store');
        Route::delete('/{id}', 'SourceController@destroy');
    });

    //公共接口
    Route::prefix('common')->group(function () {
        //获取具体地址经纬度
        Route::get('getLocation', 'CommonController@getLocation');
        //获取所有国家列表
        Route::get('getCountryList', 'CommonController@getCountryList');
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

    //路线追踪相关
    Route::prefix('route-tracking')->group(function () {
        Route::get('route', 'RouteTrackingController@route')->name('route-tracking.route');
    });

    //商户管理
    Route::prefix('merchant')->group(function () {
        //列表查询
        Route::get('/', 'MerchantController@index');
        //获取详情
        Route::get('/{id}', 'MerchantController@show');
        //新增
        Route::post('/', 'MerchantController@store');
        //修改
        Route::put('/{id}', 'MerchantController@update');
        //修改密码
        Route::put('/{id}/updatePassword', 'MerchantController@updatePassword');
        //启用/禁用
        Route::put('/{id}/status', 'MerchantController@status');
        //批量启用禁用
        Route::put('/statusByList', 'MerchantController@statusByList');
    });

    //商户授权API管理
    Route::prefix('merchant-api')->group(function () {
        //获取详情
        Route::get('/{merchant_id}', 'MerchantApiController@show');
        //修改
        Route::put('/{merchant_id}', 'MerchantApiController@update');

    });


    //商户组管理
    Route::prefix('merchant-group')->group(function () {
        //列表查询
        Route::get('/', 'MerchantGroupController@index');
        //获取详情
        Route::get('/{id}', 'MerchantGroupController@show');
        //新增
        Route::post('/', 'MerchantGroupController@store');
        //修改
        Route::put('/{id}', 'MerchantGroupController@update');
        //删除
        Route::delete('/{id}', 'MerchantGroupController@destroy');
        //组内成员
        Route::get('/{id}/indexOfMerchant', 'MerchantGroupController@indexOfMerchant');
        //批量修改运价方案
        Route::put('/transportPrice','MerchantGroupController@updatePrice');

    });

    //运价管理
    Route::prefix('transport-price')->group(function () {
        //列表查询
        Route::get('/', 'TransportPriceController@index');
        //获取详情
        Route::get('/{id}', 'TransportPriceController@show');
        //新增
        Route::post('/', 'TransportPriceController@store');
        //修改
        Route::put('/{id}', 'TransportPriceController@update');
        //启用/禁用
        Route::put('/{id}/status', 'TransportPriceController@status');
        //价格测试
        Route::get('/{id}/getPriceResult', 'TransportPriceController@getPriceResult');
    });

    Route::prefix('version')->group(function () {
        //版本管理
        Route::get('/', 'VersionController@index');//版本列表
        Route::post('/', 'VersionController@store');//版本新增
        Route::put('/{id}', 'VersionController@update');//版本修改
        Route::delete('/{id}', 'VersionController@delete');//版本删除
    });
});
