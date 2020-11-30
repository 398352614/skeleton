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
    //登录
    Route::post('login', 'AuthController@login');
    //注册
    Route::post('register', 'RegisterController@store');
    //注册验证码
    Route::post('register/apply', 'RegisterController@applyOfRegister');
    //重置密码
    Route::put('password-reset', 'RegisterController@resetPassword');
    //重置密码验证码
    Route::post('password-reset/apply', 'RegisterController@applyOfReset');
    //重置密码验证
    Route::put('password-reset/verify', 'RegisterController@verifyResetCode');
    //自动优化线路
    Route::get('/tour/callback', 'TourController@callback');
    // 取消 redis 锁
    Route::get('/tour/unlock-redis', 'TourController@unlockRedis');
});

//认证
Route::namespace('Api\Admin')->middleware(['companyValidate:admin', 'auth:admin'])->group(function () {
    //个人信息
    Route::get('me', 'AuthController@me');
    //登出
    Route::post('logout', 'AuthController@logout');
    //修改密码
    Route::put('my-password', 'AuthController@updatePassword');

    //主页统计
    Route::prefix('statistics')->group(function () {
        //主页
        Route::get('/', 'HomeController@home');
        //本周统计
        Route::get('/this-week', 'HomeController@thisWeekCount');
        //上周统计
        Route::get('/last-week', 'HomeController@lastWeekCount');
        //本月统计
        Route::get('/this-month', 'HomeController@thisMonthCount');
        //上月统计
        Route::get('/last-month', 'HomeController@lastMonthCount');
        //时间段统计
        Route::get('/period', 'HomeController@periodCount');
        //商户统计详情
        Route::get('/merchant', 'HomeController@merchantCount');
        //商户统计概览
        Route::get('/merchant-total', 'HomeController@merchantTotalCount');
    });

    //订单管理
    Route::prefix('order-import')->group(function () {
        //订单新增初始化
        Route::get('/init', 'OrderController@initStore');
        //订单新增
        Route::post('/', 'OrderController@store');
        //订单导入模板
        Route::get('/template', 'OrderImportController@templateExport');
        //批量导入
        Route::post('/import', 'OrderController@import');
        //批量新增
        Route::post('/list', 'OrderController@storeByList');
    });

    //订单管理
    Route::prefix('order')->group(function () {
        //查询初始化
        Route::get('/init', 'OrderController@initIndex');
        //订单统计
        Route::get('/count', 'OrderController@ordercount');
        //列表查询
        Route::get('/', 'OrderController@index');
        //获取详情
        Route::get('/{id}', 'OrderController@show');
        //获取订单的运单列表
        Route::get('/{id}/tracking-order', 'OrderController@getTrackingOrderList');
        //修改
        Route::put('/{id}', 'OrderController@update');
        //获取再次取派信息
        Route::get('/{id}/again-info', 'OrderController@getAgainInfo');
        //再次取派
        Route::put('/{id}/again', 'OrderController@again');
        //终止派送
        Route::put('/{id}/end', 'OrderController@end');
        //删除
        Route::delete('/{id}', 'OrderController@destroy');
        //批量删除
        Route::delete('/list', 'OrderController@destroyAll');
        //批量打印
        Route::get('/pdf', 'OrderController@orderPrintAll');
        //订单导出表格
        Route::get('/excel', 'OrderController@orderExport');
        //同步订单状态列表
        Route::post('/synchronize-status-list', 'OrderController@synchronizeStatusList');
        //订单第三方对接日志
        Route::get('/{id}/third-party-log', 'ThirdPartyLogController@index');
        //无效化已完成订单（用以新增同号订单）
        Route::get('/{id}/neutralize', 'OrderController@neutralize');
        //获取可加单取件线路
        Route::get('/get-tour', 'TourController@getAddOrderPageList');
    });

    //订单轨迹管理
    Route::prefix('order-trail')->group(function () {
        //列表查询
        Route::get('/{order_no}', 'OrderTrailController@index');
    });

    //运单轨迹管理
    Route::prefix('tracking-order-trail')->group(function () {
        //列表查询
        Route::get('/{tracking_order_no}', 'TrackingOrderTrailController@index');
    });

    Route::prefix('package')->group(function () {
        //列表查询
        Route::get('/', 'PackageController@index');
        //获取详情
        Route::get('/{id}', 'PackageController@show');
    });

    //库存管理
    Route::prefix('stock')->group(function () {
        //列表查询
        Route::get('/', 'StockController@index');
        //获取日志
        Route::get('/{express_first_no}/log', 'StockController@getStockLogList');
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

    //运单管理
    Route::prefix('tracking-order')->group(function () {
        //查询初始化
        Route::get('/init', 'TrackingOrderController@initIndex');
        //运单统计
        Route::get('/count', 'TrackingOrderController@trackingOrderCount');
        //获取线路列表
        Route::get('/get-line', 'TrackingOrderController@getLineList');
        //列表查询
        Route::get('/', 'TrackingOrderController@index');
        //获取详情
        Route::get('/{id}', 'TrackingOrderController@show');
        //获取可分配路线日期
        Route::get('/{id}/get-date', 'TrackingOrderController@getAbleDateList');
        //获取可分配的站点列表
        Route::get('/{id}/get-batch', 'TrackingOrderController@getAbleBatchList');
        //分配至站点
        Route::put('/{id}/assign-batch', 'TrackingOrderController@assignToBatch');
        //从站点移除
        Route::delete('/{id}/remove-batch', 'TrackingOrderController@removeFromBatch');
        //批量运单从站点移除
        Route::delete('/remove-batch', 'TrackingOrderController@removeListFromBatch');
        //批量运单分配至指定取件线路
        Route::put('/assign-list', 'TrackingOrderController@assignListTour');
        //批量打印
        Route::get('/print', 'TrackingOrderController@orderPrintAll');
        //运单导出表格
        Route::get('/order-excel', 'TrackingOrderController@trackingOrderExport');
        //运单第三方对接日志
        Route::get('/{id}/third-party-log', 'ThirdPartyLogController@index');
    });

    //物流状态管理
    Route::prefix('tracking-order-trail')->group(function () {
        //rest api 放在最后
        Route::get('/{tracking_order_no}', 'TrackingOrderTrailController@index')->name('tracking-order-trail.index');
    });

    //司机管理
    Route::prefix('driver')->group(function () {
        //司机新增
        Route::post('/register', 'DriverController@driverRegister');
        //获取状态
        Route::get('/status', 'DriverController@driverStatus');
        //锁定或解锁司机
        Route::post('/{id}/lock', 'DriverController@lockDriver');
        //修改司机密码
        Route::put('/{id}/update-password', 'DriverController@resetPassword')->name('driver.reset-password');

        //司机列表
        Route::get('/', 'DriverController@index')->name('driver.index');
        //司机详情
        Route::get('/{id}', 'DriverController@show')->name('driver.show');
        //司机修改
        Route::put('/{id}', 'DriverController@update')->name('driver.update');
        //删除司机
        Route::delete('/{id}', 'DriverController@destroy')->name('driver.destroy');
    });

    //车辆管理
    Route::prefix('car')->group(function () {
        //车辆锁定操作
        Route::put('/{id}/lock', 'CarController@lock')->name('car.lock');
        // 获取品牌列表
        Route::get('/brands', 'CarBrandController@index')->name('carBrand.brands');
        // 添加品牌
        Route::post('/brand', 'CarBrandController@store')->name('carBrand.store');
        // 获取型号列表
        Route::get('/models', 'CarModelController@getListByBrand')->name('carModel.getListByBrand');
        // 添加模型
        Route::post('/model', 'CarModelController@store')->name('carModel.store');
        //车辆查询
        Route::get('/', 'CarController@index')->name('car.index');
        // 初始化
        Route::get('/init', 'CarController@init')->name('car.init');
        //车辆新增
        Route::post('/', 'CarController@store')->name('car.store');
        //车辆详情
        Route::get('/{id}', 'CarController@show')->name('car.show');
        //车辆修改
        Route::put('/{id}', 'CarController@update')->name('car.update');
        //车辆删除
        Route::delete('/{id}', 'CarController@destroy')->name('car.destroy');
        // 车辆追踪
        Route::get('/track', 'RouteTrackingController@show')->name('car.track-show');
        // 所有车辆追踪
        Route::get('/all-track', 'RouteTrackingController@index')->name('car.track-index');
        // 导出里程
        Route::get('/{id}/distance', 'CarController@distanceExport')->name('car.distance');
        // 导出里程
        Route::get('/{id}/info', 'CarController@infoExport')->name('car.info');
    });

    //设备管理
    Route::prefix('device')->group(function () {
        //司机列表
        Route::get('/driver', 'DeviceController@getDriverPageList');
        //设备列表
        Route::get('/', 'DeviceController@index');
        //设备详情
        Route::get('/{id}', 'DeviceController@show');
        //设备新增
        Route::post('/', 'DeviceController@store');
        //设备修改
        Route::put('/{id}', 'DeviceController@update');
        //设备删除
        Route::delete('/{id}', 'DeviceController@destroy');
        //绑定
        Route::put('/{id}/bind', 'DeviceController@bind');
        //解绑
        Route::put('/{id}/unBind', 'DeviceController@unBind');
    });

    //站点管理
    Route::prefix('batch')->group(function () {
        //站点查询
        Route::get('/', 'BatchController@index')->name('batch.index');
        //批次详情
        Route::get('/{id}', 'BatchController@show')->name('batch.show');
        //取消取派
        Route::put('/{id}/cancel', 'BatchController@cancel');
        //获取取件线路列表
        Route::get('/{id}/get-tour', 'BatchController@getTourList');
        //获取可分配路线日期
        Route::get('/{id}/get-date', 'BatchController@getDateList');
        //分配站点至取件线路
        Route::put('/{id}/assign-tour', 'BatchController@assignToTour');
        //批量分配站点至取件线路
        Route::put('/assign-tour', 'BatchController@assignListToTour');
        //移除站点
        Route::delete('/{id}/remove', 'BatchController@removeFromTour');
        //批量移除站点
        Route::delete('/remove', 'BatchController@removeListFromTour');
        //根据线路规则获取线路
        Route::get('/get-line', 'BatchController@getLineList');
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
        //更改线路任务顺序 -- 手动优化
        Route::post('/update-batch-index', 'TourController@updateBatchIndex');
        //自动优化线路
        Route::post('/auto-op-tour', 'TourController@autoOpTour');
        //线路任务查询
        Route::get('/', 'TourController@index')->name('tour.index');
        //线路任务详情
        Route::get('/{id}', 'TourController@show')->name('tour.show');
        //分配司机
        Route::put('/{id}/assign-driver', 'TourController@assignDriver');
        //取消分配司机
        Route::put('/{id}/cancel-driver', 'TourController@cancelAssignDriver');
        //分配车辆
        Route::put('/{id}/assign-car', 'TourController@assignCar');
        //取消分配车辆
        Route::put('/{id}/cancel-car', 'TourController@cancelAssignCar');
        //取消待出库
        Route::put('/{id}/unlock', 'TourController@unlock');
        //导出投递站点excel
        Route::get('/batch-excel', 'TourController@batchExport');
        //导出任务报告
        Route::get('/{id}/tour-excel', 'TourController@tourExport');
        //导出计划
        Route::get('/{id}/plan-excel', 'TourController@planExport');
        //分配线路
        Route::put('/{id}/assign', 'TourController@assignTourToTour');
        //获取可分配日期
        Route::get('/{id}/get-date', 'TourController@getLineDate');
        //获取可加入的取件线路列表
        Route::get('/by-line', 'TourController@getListJoinByLineId');

    });

    //取件线路-司机
    Route::prefix('tour-driver')->group(function () {
        Route::get('/{tour_no}', 'TourDriverController@getListByTourNo');
    });

    //取件线路-司机
    Route::prefix('delay')->group(function () {
        Route::get('/init', 'TourDelayController@init');
        Route::get('/', 'TourDelayController@index');
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
        Route::get('/by-date', 'LineController@getListByDate');
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

        //商户线路范围详情
        Route::get('/{id}/merchant-line-range', 'MerchantLineRangeController@show');
        //商户线路范围修改
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
        Route::put('/status', 'LineController@statusByList');

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
        Route::get('/address-template', 'CompanyConfigController@getAddressTemplateList');
        //修改
        Route::put('/update', 'CompanyConfigController@update');
    });

    //员工管理
    Route::prefix('employees')->group(function () {
        //员工列表
        Route::get('/', 'EmployeeController@index');
        //员工详情
        Route::get('/{id}', 'EmployeeController@show');
        //员工修改
        Route::put('/{id}', 'EmployeeController@update');
        //员工新增
        Route::post('/', 'EmployeeController@store');
        //员工删除
        Route::delete('/{id}', 'EmployeeController@destroy');
        //禁止登录
        Route::put('/{id}/forbid-login/{enabled}', 'EmployeeController@setLogin');
        //修改员工密码
        Route::put('/{id}/password', 'EmployeeController@resetPassword');
        //员工移动
        Route::put('/{id}/move-to/{parentId}', 'EmployeeController@move');
    });

    //组织管理
    Route::prefix('institutions')->group(function () {
        //组织查询
        Route::get('/', 'InstitutionController@index');
        //组织详情
        Route::get('/{id}', 'InstitutionController@show');
        //组织成员
        Route::get('/{id}/member', 'InstitutionController@indexOfEmployees');
        //移动组织
        Route::put('/{id}/move-to/{parentId}', 'InstitutionController@move');
        //组织修改
        Route::put('/{id}', 'InstitutionController@update');
        //组织新增
        Route::post('/', 'InstitutionController@store');
        //组织删除
        Route::delete('/{id}', 'InstitutionController@destroy');
    });

    //客户管理 - 收货方管理
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


    //国家管理
    Route::prefix('country')->group(function () {
        Route::get('/', 'CountryController@index');
        Route::get('/init', 'CountryController@initStore');
        Route::post('/', 'CountryController@store');
        Route::delete('/{id}', 'CountryController@destroy');
    });

    //公共接口
    Route::prefix('common')->group(function () {
        //字典
        Route::get('/dictionary', 'CommonController@dictionary');
        //获取具体地址经纬度
        Route::get('/location', 'CommonController@getLocation');
        //获取所有国家列表
        Route::get('/country', 'CommonController@getCountryList');
        //获取指定国家地址
        Route::get('/address/{country}', 'CommonController@getCountryAddress');

        Route::get('/postcode', 'CommonController@getPostcode');

        Route::get('/dictionary', 'CommonController@dictionary');
    });

    //上传接口
    Route::prefix('upload')->group(function () {
        //获取可上传的图片目录列表
        Route::get('image-dir', 'UploadController@getImageDirList');
        //图片上传
        Route::post('image', 'UploadController@imageUpload');
        //获取可上传的文件目录列表
        Route::get('file-dir', 'UploadController@getFileDirList');
        //下载
        Route::post('file-download', 'UploadController@fileDownload');

        Route::post('file', 'UploadController@fileUpload');
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
        Route::get('/fee', 'MerchantController@getFeeList');
        //新增
        Route::post('/', 'MerchantController@store');
        //修改
        Route::put('/{id}', 'MerchantController@update');
        //修改密码
        Route::put('/{id}/update-password', 'MerchantController@updatePassword');
        //启用/禁用
        Route::put('/{id}/status', 'MerchantController@status');
        //批量启用禁用
        Route::put('/status', 'MerchantController@statusByList');
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
        Route::get('/{id}/member', 'MerchantGroupController@indexOfMerchant');
        //批量修改运价方案
        Route::put('/transport-price', 'MerchantGroupController@updatePrice');
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
        Route::get('/{id}/test', 'TransportPriceController@getPriceResult');
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
        Route::get('/init', 'OrderNoRuleController@initStore');
        Route::post('/', 'OrderNoRuleController@store');
        Route::put('/{id}', 'OrderNoRuleController@update');
        Route::delete('/{id}', 'OrderNoRuleController@destroy');
    });

    //打印模板
    Route::prefix('print-template')->group(function () {
        //初始化
        Route::get('/init', 'PrintTemplateController@init');
        //详情
        Route::get('/show', 'PrintTemplateController@show');
        //修改
        Route::put('/update', 'PrintTemplateController@update');
    });

    //费用管理
    Route::prefix('fee')->group(function () {
        //列表查询
        Route::get('/', 'FeeController@index');
        //初始化
        Route::get('/init', 'FeeController@init');
        //详情
        Route::get('/{id}', 'FeeController@show');
        //新增
        Route::post('/', 'FeeController@store');
        //修改
        Route::put('/{id}', 'FeeController@update');
        //删除
        Route::delete('/{id}', 'FeeController@destroy');
    });

    //放假管理
    Route::prefix('holiday')->group(function () {
        //列表查询
        Route::get('/', 'HolidayController@index');
        //详情
        Route::get('/{id}', 'HolidayController@show');
        //新增
        Route::post('/', 'HolidayController@store');
        //修改
        Route::put('/{id}', 'HolidayController@update');
        //删除
        Route::delete('/{id}', 'HolidayController@destroy');
        //启用/禁用
        Route::put('/{id}/status', 'HolidayController@status');
        //获取商户列表
        Route::get('/merchant', 'HolidayController@merchantIndex');
        //新增商户列表
        Route::post('/{id}/merchant', 'HolidayController@storeMerchantList');

        Route::delete('/{id}/merchant', 'HolidayController@destroyMerchant');
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
        //充值统计查询
        Route::get('/statistics', 'RechargeStatisticsController@index');
        //充值统计查询
        Route::get('/statistics/{id}', 'RechargeStatisticsController@show');
        //充值查询
        Route::get('/', 'RechargeController@index');
        //充值详情
        Route::get('/{id}', 'RechargeController@show');
        //充值审核
        Route::put('/statistics/{id}', 'RechargeStatisticsController@verify');
    });
});
