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
Route::namespace('Api\Admin')->middleware(['companyValidate:admin', 'auth:admin'])->group(function () {
    Route::get('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout');
    Route::put('my-password', 'AuthController@updatePassword');


    //主页统计
    Route::prefix('home')->group(function () {
        Route::get('/', 'HomeController@home');
        Route::get('/this-week-count', 'HomeController@thisWeekCount');
        Route::get('/last-week-count', 'HomeController@lastWeekCount');
        Route::get('/this-month-count', 'HomeController@thisMonthCount');
        Route::get('/last-month-count', 'HomeController@lastMonthCount');
        Route::get('/period-count', 'HomeController@periodCount');
    });


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
        //获取可分配路线日期
        Route::get('/{id}/getTourDate', 'OrderController@getTourDate');
        //获取可分配路线日期(新增)
        Route::get('/get-date', 'OrderController@getDate');
        //获取可分配的站点列表
        Route::get('/{id}/getBatchPageListByOrder', 'OrderController@getBatchPageListByOrder');
        //分配至站点
        Route::put('/{id}/assignToBatch', 'OrderController@assignToBatch');
        //从站点移除
        Route::delete('/{id}/removeFromBatch', 'OrderController@removeFromBatch');
        //批量订单从站点移除
        Route::delete('/removeListFromBatch', 'OrderController@removeListFromBatch');
        //删除
        Route::delete('/{id}', 'OrderController@destroy');
        //批量删除
        Route::delete('/list', 'OrderController@destroyByList');
        //恢复
        Route::put('/{id}/recovery', 'OrderController@recovery');
        //彻底删除
        Route::delete('/{id}/actualDestroy', 'OrderController@actualDestroy');
        //批量订单分配至指定取件线路
        Route::put('/assignListTour', 'OrderController@assignListTour');
        //批量打印
        Route::get('/orderPrintAll', 'OrderController@orderPrintAll');
        //订单导出表格
        Route::get('/order-excel', 'OrderController@orderExport');
        //获取所有线路（邮编及区域）
        Route::get('/get-line', 'OrderController@getLineList');
        //同步订单状态列表
        Route::post('synchronize-status-list', 'OrderController@synchronizeStatusList');
        //订单第三方对接日志
        Route::get('/{id}/third-party-log', 'ThirdPartyLogController@index');
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

    Route::prefix('additional-package')->group(function () {
        //列表查询
        Route::get('/', 'AdditionalPackageController@index');
        //获取详情
        Route::get('/{id}', 'AdditionalPackageController@show');
    });

    //订单导入记录管理
    Route::prefix('order-import')->group(function () {
        //上传模板
        Route::post('/uploadTemplate', 'OrderImportController@uploadTemplate');
        //生成模板
        Route::get('/getTemplate', 'OrderImportController@templateExport');
        //批量导入
        Route::post('/import', 'OrderController@import');
        //批量新增
        Route::post('/storeByList', 'OrderController@storeByList');
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
        Route::get('/brands', 'CarBrandController@index')->name('carBrand.brands');  // 获取品牌列表
        Route::get('/allBrands', 'CarBrandController@getAll')->name('carBrand.getAll');  // 获取品牌列表
        Route::post('/addbrand', 'CarBrandController@store')->name('carBrand.store'); // 添加品牌
        Route::get('/models', 'CarModelController@getListByBrand')->name('carModel.getListByBrand'); // 获取型号列表
        Route::get('/allModels/{id}', 'CarModelController@getAll')->name('carModel.getAll');  // 获取所有品牌列表
        Route::post('/addmodel', 'CarModelController@store')->name('carModel.store');   // 添加模型

        //rest api 放在最后
        Route::get('/', 'CarController@index')->name('car.index');
        Route::get('/init', 'CarController@init')->name('car.init');   // 初始化
        Route::post('/', 'CarController@store')->name('car.store');
        Route::get('/{id}', 'CarController@show')->name('car.show'); //车辆详情
        Route::put('/{id}', 'CarController@update')->name('car.update'); //车辆修改
        Route::delete('/{id}', 'CarController@destroy')->name('car.destroy'); //车辆删除

        Route::get('/track', 'RouteTrackingController@show')->name('car.track-show');  // 车辆追踪
        Route::get('/all-track', 'RouteTrackingController@index')->name('car.track-index');  // 所有车辆追踪

        // $router->post('car/lock', 'CarInfoController@lock');
    });

    //站点管理
    Route::prefix('batch')->group(function () {
        //rest api 放在最后
        Route::get('/', 'BatchController@index')->name('batch.index');
        Route::get('/{id}', 'BatchController@show')->name('batch.show');       //批次详情
        Route::put('/{id}/cancel', 'BatchController@cancel');                        //取消取派
        Route::get('/{id}/getTourList', 'BatchController@getTourList');              //获取取件线路列表
        Route::get('/{id}/getTourDate', 'BatchController@getTourDate'); //获取可分配路线日期
        Route::put('/{id}/assign-tour', 'BatchController@assignToTour');            //分配站点至取件线路
        Route::put('/assign-list-tour', 'BatchController@assignListToTour');   //批量分配站点至取件线路
        Route::delete('/{id}/removeFromTour', 'BatchController@removeFromTour');     //移除站点
        Route::delete('/removeListFromTour', 'BatchController@removeListFromTour');     //批量移除站点
        Route::get('/{id}/get-date', 'BatchController@getLineDate'); //获取可分配路线日期
        Route::get('/get-line', 'BatchController@getLineList'); //根据线路规则获取线路
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
        Route::post('/update-batch-index', 'TourController@updateBatchIndex');         //更改线路任务顺序 -- 手动优化
        Route::post('/auto-op-tour', 'TourController@autoOpTour');         //自动优化线路

        //rest api 放在最后
        Route::get('/getAddOrderPageList', 'TourController@getAddOrderPageList');
        Route::get('/', 'TourController@index')->name('tour.index');
        Route::get('/{id}', 'TourController@show')->name('tour.show');
        Route::put('/{id}/assignDriver', 'TourController@assignDriver');               //分配司机
        Route::put('/{id}/cancelAssignDriver', 'TourController@cancelAssignDriver');   //取消分配司机
        Route::put('/{id}/assignCar', 'TourController@assignCar');                     //分配车辆
        Route::put('/{id}/cancelAssignCar', 'TourController@cancelAssignCar');         //取消分配车辆
        Route::put('/{id}/unlock', 'TourController@unlock');         //取消待出库
        Route::get('/{id}/excel', 'TourController@batchExport'); //导出投递站点excel
        Route::get('/{id}/txt', 'TourController@cityExport'); //导出投递城市txt
        Route::get('/{id}/png', 'TourController@mapExport'); //导出站点地图png
        Route::get('/{id}/tour-excel', 'TourController@tourExport'); //导出任务报告
        Route::get('/{id}/plan-excel', 'TourController@planExport'); //导出计划
        Route::put('/{id}/assign', 'TourController@assignTourToTour');   //分配线路
        Route::get('/{id}/getLineDate', 'TourController@getLineDate');   //获取可分配日期
        Route::get('/getListJoinByLineId', 'TourController@getListJoinByLineId');   //获取可加入的取件线路列表

    });

    //取件线路-司机
    Route::prefix('tour-driver')->group(function () {
        Route::get('/{tour_no}', 'TourDriverController@getListByTourNo');
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
        //通过日期，获取线路列表
        Route::get('/getListByDate', 'LineController@getListByDate');
        /****************************************邮编线路**************************************/
        //列表查询
        Route::get('/', 'LineController@postcodeIndex');
        //获取详情
        Route::get('/{id}', 'LineController@postcodeShow');
        //新增
        Route::post('/', 'LineController@postcodeStore');
        //修改
        Route::put('/{id}', 'LineController@postcodeUpdate');
        //删除
        Route::delete('/{id}', 'LineController@postcodeDestroy');
        //导入
        Route::post('/import', 'LineController@postcodeLineImport');

        //商户线路范围配置
        Route::get('/{id}/merchant-line-range', 'MerchantLineRangeController@show');
        Route::put('/{id}/merchant-line-range', 'MerchantLineRangeController@createOrUpdate');

        /****************************************区域线路**************************************/
        //列表查询
        Route::get('/area', 'LineController@areaIndex');
        //获取详情
        Route::get('/area/{id}', 'LineController@areaShow');
        //新增
        Route::post('/area', 'LineController@areaStore');
        //修改
        Route::put('/area/{id}', 'LineController@areaUpdate');
        //删除
        Route::delete('/area/{id}', 'LineController@areaDestroy');

        //批量修改状态
        Route::put('/statusByList', 'LineController@statusByList');

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
        //获取地址模板列表
        Route::get('/getAddressTemplateList', 'CompanyConfigController@getAddressTemplateList');
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
        Route::put('/{id}/password', 'EmployeeController@resetPassword'); //修改员工密码
        Route::put('/{id}/move-to/{parentId}', 'EmployeeController@move');
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
        Route::get('/initStore', 'CountryController@initStore');
        Route::post('/', 'CountryController@store');
        Route::delete('/{id}', 'CountryController@destroy');
    });

    //公共接口
    Route::prefix('common')->group(function () {
        //获取具体地址经纬度
        Route::get('getLocation', 'CommonController@getLocation');
        //获取所有国家列表
        Route::get('getCountryList', 'CommonController@getCountryList');
        //获取指定国家地址
        Route::get('getCountryAddress/{country}', 'CommonController@getCountryAddress');
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
        //初始化
        Route::get('/init', 'MerchantController@init');
        //获取费用列表
        Route::get('/getFeeList', 'MerchantController@getFeeList');
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
        //商户导出
        Route::get('/excel', 'MerchantController@excel');
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
        Route::put('/transportPrice', 'MerchantGroupController@updatePrice');
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
        Route::get('/', 'VersionController@index'); //版本列表
        Route::post('/', 'VersionController@store'); //版本新增
        Route::put('/{id}', 'VersionController@update'); //版本修改
        Route::delete('/{id}', 'VersionController@delete'); //版本删除
    });

    //单号规则管理
    Route::prefix('order-no-rule')->group(function () {
        Route::get('/', 'OrderNoRuleController@index');
        Route::get('/{id}', 'OrderNoRuleController@show');
        Route::get('/initStore', 'OrderNoRuleController@initStore');
        Route::post('/', 'OrderNoRuleController@store');
        Route::put('/{id}', 'OrderNoRuleController@update');
        Route::delete('/{id}', 'OrderNoRuleController@destroy');
    });

    //打印模板
    Route::prefix('print-template')->group(function () {
        Route::get('/init', 'PrintTemplateController@init');        //详情
        Route::get('/show', 'PrintTemplateController@show');        //详情
        Route::put('/update', 'PrintTemplateController@update');    //修改
    });

    //费用管理
    Route::prefix('fee')->group(function () {
        Route::get('/', 'FeeController@index');             //列表查询
        Route::get('/init', 'FeeController@init');             //初始化
        Route::get('/{id}', 'FeeController@show');          //详情
        Route::post('/', 'FeeController@store');            //新增
        Route::put('/{id}', 'FeeController@update');        //修改
        Route::delete('/{id}', 'FeeController@destroy');    //删除
    });

    //放假管理
    Route::prefix('holiday')->group(function () {
        Route::get('/', 'HolidayController@index');             //列表查询
        Route::get('/{id}', 'HolidayController@show');          //详情
        Route::post('/', 'HolidayController@store');            //新增
        Route::put('/{id}', 'HolidayController@update');        //修改
        Route::delete('/{id}', 'HolidayController@destroy');    //删除
        //启用/禁用
        Route::put('/{id}/status', 'HolidayController@status');

        Route::get('/merchantIndex', 'HolidayController@merchantIndex');                //获取商户列表
        Route::post('/{id}/storeMerchantList', 'HolidayController@storeMerchantList');       //新增商户列表
        Route::delete('/{id}/destroyMerchant', 'HolidayController@destroyMerchant');    //删除商户
    });


    //worker
    Route::prefix('worker')->group(function () {
        //绑定
        Route::post('/bind/{client_id}', 'WorkerController@bind');
    });

    //统计第三方请求次数
    Route::prefix('api-times')->group(function () {
        //绑定
        Route::get('/', 'ApiTimesController@index');
    });

    //充值管理
    Route::prefix('recharge')->group(function () {
        //充值查询
        Route::get('/', 'RechargeController@index');
        //充值详情
        Route::get('/{id}', 'RechargeController@show');
        //充值审核
        Route::put('/{id}', 'RechargeController@verify');
    });
});
