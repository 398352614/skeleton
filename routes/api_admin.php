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
    // 翻译
    Route::get('/translate', 'AuthController@validation');
    // 获取定制化界面
    Route::get('/customize', 'CompanyCustomizeController@showByUrl');
});

//认证
Route::namespace('Api\Admin')->middleware(['companyValidate:admin', 'auth:admin', 'permission:admin'])->group(function () {
    //个人信息
    Route::get('me', 'AuthController@me');
    //登出
    Route::post('logout', 'AuthController@logout');
    //修改密码
    Route::put('my-password', 'AuthController@updatePassword');
    //获取当前用户权限
    Route::get('/permission', 'AuthController@getPermission');
    //切换时区
    Route::put('/timezone', 'AuthController@updateTimezone');

    //主页统计
    Route::prefix('statistics')->group(function () {
        //主页
        Route::get('/', 'HomeController@home')->name('statistics.home');
        //本周统计
        Route::get('/this-week', 'HomeController@thisWeekCount')->name('statistics.home');
        //上周统计
        Route::get('/last-week', 'HomeController@lastWeekCount')->name('statistics.home');
        //本月统计
        Route::get('/this-month', 'HomeController@thisMonthCount')->name('statistics.home');
        //上月统计
        Route::get('/last-month', 'HomeController@lastMonthCount')->name('statistics.home');
        //时间段统计
        Route::get('/period', 'HomeController@periodCount')->name('statistics.home');
        //货主统计详情
        Route::get('/merchant', 'HomeController@merchantCount')->name('statistics.home');
        //货主统计概览
        Route::get('/merchant-total', 'HomeController@merchantTotalCount')->name('statistics.home');
        //今日概览
        Route::get('/today-overview', 'HomeController@todayOverview')->name('statistics.home');
        //任务结果概览
        Route::get('/result-overview', 'HomeController@resultOverview')->name('statistics.home');
        //订单分析
        Route::get('/order-analysis', 'HomeController@orderAnalysis')->name('statistics.home');
        //获取快捷方式列表
        Route::get('/short-cut', 'HomeController@getShortCut')->name('statistics.home');
        //预约任务
        Route::get('/reservation', 'HomeController@reservation')->name('statistics.home');
        //流程图
        Route::get('/flow', 'HomeController@flow')->name('statistics.home');
    });

    //订单管理
    Route::prefix('order')->group(function () {
        //查询初始化
        Route::get('/init', 'OrderController@initIndex')->name('order.index');
        //订单统计
        Route::get('/count', 'OrderController@ordercount')->name('order.index');
        //列表查询
        Route::get('/', 'OrderController@index')->name('order.index');
        //获取详情
        Route::get('/{id}', 'OrderController@show')->name('order.show');
        //订单新增初始化
        Route::get('/init', 'OrderController@initStore')->name('order.store');
        //订单新增
        Route::post('/', 'OrderController@store')->name('order.store');
        //模板导出
        Route::get('/template', 'OrderImportController@templateExport')->name('order.template-export');
        //批量导入
        Route::post('/import', 'OrderImportController@import')->name('order.import-list');
        //批量新增
        Route::post('/list', 'OrderImportController@storeByList')->name('order.store-list');
        //获取订单的运单列表
        Route::get('/{id}/tracking-order', 'OrderController@getTrackingOrderList')->name('order.index');
        //获取订单的运单轨迹列表
        Route::get('/{id}/tracking-order-trail', 'OrderController@getTrackingOrderTrailList')->name('order.tracking-order-trail');
        //订单轨迹
        Route::get('/{order_no}/trail', 'OrderTrailController@index')->name('order.trail');
        //修改
        Route::put('/{id}', 'OrderController@update')->name('order.update');
        //获取继续派送(再次取派)信息
        Route::get('/{id}/again-info', 'OrderController@getAgainInfo')->name('order.again');
        //继续派送(再次取派)
        Route::put('/{id}/again', 'OrderController@again')->name('order.again');
        //终止派送
        Route::put('/{id}/end', 'OrderController@end')->name('order.end');
        //删除
        Route::delete('/{id}', 'OrderController@destroy')->name('order.destroy');
        //批量删除
        Route::delete('/list', 'OrderController@destroyAll')->name('order.destroy');
        //批量打印
        Route::get('/pdf', 'OrderController@orderPrintAll')->name('order.print');
        //批量打印面单
        Route::get('/bill', 'OrderController@orderBillPrint')->name('order.print');
        //订单导出表格
        Route::get('/excel', 'OrderController@orderExport')->name('order.export');
        //同步订单状态列表
        Route::post('/synchronize-status-list', 'OrderController@synchronizeStatusList')->name('order.synchronize');
        //订单第三方对接日志
        Route::get('/{id}/third-party-log', 'ThirdPartyLogController@index')->name('order.third-party-log');
        //无效化已完成订单（用以新增同号订单）
        Route::get('/{id}/neutralize', 'OrderController@neutralize')->name('order.neutralize');
        //运价估算
        Route::post('/price-count', 'OrderController@priceCount')->name('order.price-count');
        //获取网点
        Route::get('/warehouse', 'OrderController@getWarehouse')->name('order.store');
        //通过地址获取可分配的路线日期列表
        Route::get('/get-date', 'OrderController@getAbleDateListByAddress');
    });

    //订单客服
    Route::prefix('order-customer')->group(function () {
        //客服记录列表
        Route::get('/', 'OrderCustomerRecordController@list')->name('order.customer');
        //客服记录新增
        Route::post('/', 'OrderCustomerRecordController@store')->name('order.create-customer');
        //客服记录删除
        Route::delete('/{id}', 'OrderCustomerRecordController@delete')->name('order.delete-customer');
    });

    //订单导入
    Route::prefix('order-import')->group(function () {
        //获取模板
        Route::get('/template', 'OrderImportController@templateExport')->name('order.import-list');
        //导入
        Route::post('/', 'OrderImportController@import')->name('order.import-list');
        //检查
        Route::post('/check', 'OrderImportController@importCheck')->name('order.import-list');
        //批量新增
        Route::post('/list', 'OrderImportController@createByList')->name('order.import-list');
    });

    //订单回单
    Route::prefix('order-receipt')->group(function () {
        //回单列表
        Route::get('/', 'OrderReceiptController@list')->name('order.receipt');
        //回单新增
        Route::post('/', 'OrderReceiptController@store')->name('order.create-receipt');
        //回单更新
        Route::put('/{id}', 'OrderReceiptController@update')->name('order.update-receipt');
        //回单删除
        Route::delete('/{id}', 'OrderReceiptController@delete')->name('order.delete-receipt');
    });

    //订单默认配置
    Route::prefix('order-config')->group(function () {
        //获取配置
        Route::get('/', 'OrderDefaultConfigController@detail')->name('order-default-config.show');
        //更新配置
        Route::put('/', 'OrderDefaultConfigController@update')->name('order-default-config.update');
    });

    //订单费用管理
    Route::prefix('order-amount')->group(function () {
        //查询
        Route::get('/', 'OrderAmountController@index')->name('order.index');
        //详情
        Route::get('/{id}', 'OrderAmountController@show')->name('order.index');
        //新增
        Route::post('/', 'OrderAmountController@store')->name('order.index');
        //修改
        Route::put('/{id}', 'OrderAmountController@update')->name('order.index');
        //删除
        Route::delete('/{id}', 'OrderAmountController@destroy')->name('order.index');
    });

    //物流查询
    Route::prefix('trail')->group(function () {
        //列表查询
        Route::get('/order/{order_no}', 'OrderTrailController@index')->name('trail.index');
        //列表查询
        Route::get('/tracking-order/{tracking_order_no}', 'TrackingOrderTrailController@index')->name('trail.index');
        //列表查询
        Route::get('/package/{express_first_no}', 'PackageTrailController@index')->name('trail.index');
    });

    //订单轨迹管理
    Route::prefix('order-trail')->group(function () {
        //列表查询
        Route::get('/{order_no}', 'OrderTrailController@index')->name('order-trail.index');
    });

    //运单轨迹管理
    Route::prefix('tracking-order-trail')->group(function () {
        //列表查询
        Route::get('/{tracking_order_no}', 'TrackingOrderTrailController@index')->name('tracking-order-trail.index');
        //列表新增
        Route::post('/{tracking_order_no}', 'TrackingOrderTrailController@store')->name('order.tracking-order-trail-store');
        //列表查询
        Route::delete('/{id}', 'TrackingOrderTrailController@destroy')->name('order.tracking-order-trail-destroy');
    });

    Route::prefix('package')->group(function () {
        //列表查询
        Route::get('/', 'PackageController@index')->name('package.index');
        //获取详情
        Route::get('/{id}', 'PackageController@show')->name('package.show');
        //填充包裹信息
        Route::put('fill-package', 'PackageController@fillWeightInfo');
    });

    Route::prefix('package-trail')->group(function () {
        //列表查询
        Route::get('/', 'PackageTrailController@index')->name('package.index');
    });

    //库存管理
    Route::prefix('stock')->group(function () {
        //列表查询
        Route::get('/', 'StockController@index')->name('stock.index');
    });

    //入库日志管理
    Route::prefix('stock-in-log')->group(function () {
        //列表查询
        Route::get('/', 'StockInLogController@index')->name('stock-in-log.index');
    });

    //出库日志管理
    Route::prefix('stock-out-log')->group(function () {
        //列表查询
        Route::get('/', 'StockOutLogController@index')->name('stock-out-log.index');
    });


    Route::prefix('material')->group(function () {
        //列表查询
        Route::get('/', 'MaterialController@index')->name('material.index');
        //获取详情
        Route::get('/{id}', 'MaterialController@show')->name('material.show');
    });

    Route::prefix('additional-package')->group(function () {
        //列表查询
        Route::get('/', 'AdditionalPackageController@index')->name('additional-package.index');
        //获取详情
        Route::get('/{id}', 'AdditionalPackageController@show')->name('additional-package.show');
    });

    //运单管理
    Route::prefix('tracking-order')->group(function () {
        //查询初始化
        Route::get('/init', 'TrackingOrderController@initIndex')->name('tracking-order.index');
        //运单统计
        Route::get('/count', 'TrackingOrderController@trackingOrderCount')->name('tracking-order.index');
        //获取线路列表
        Route::get('/get-line', 'TrackingOrderController@getLineList')->name('tracking-order.index');
        //列表查询
        Route::get('/', 'TrackingOrderController@index')->name('tracking-order.index');
        //获取详情
        Route::get('/{id}', 'TrackingOrderController@show')->name('tracking-order.show');
        //运单轨迹
        Route::get('/{tracking_order_no}/trail', 'TrackingOrderTrailController@index')->name('tracking-order.trail');
        //获取可分配路线日期
        Route::get('/{id}/get-date', 'TrackingOrderController@getAbleDateList')->where('id', '[-]?[0-9]+');
        //获取可分配的站点列表
        Route::get('/{id}/get-batch', 'TrackingOrderController@getAbleBatchList')->name('tracking-order.assign-batch');
        //分配至站点
        Route::put('/{id}/assign-batch', 'TrackingOrderController@assignToBatch')->name('tracking-order.assign-batch');
        //从站点移除
        Route::delete('/{id}/remove-batch', 'TrackingOrderController@removeFromBatch')->name('tracking-order.remove-batch');
        //批量运单从站点移除
        Route::delete('/remove-batch', 'TrackingOrderController@removeListFromBatch')->name('tracking-order.remove-batch');
        //获取可加单线路任务
        Route::get('/get-tour', 'TourController@getAddOrderPageList')->name('tracking-order.assign-tour');
        //批量运单分配至指定线路任务
        Route::put('/assign-tour', 'TrackingOrderController@assignListTour')->name('tracking-order.assign-tour');
        //批量打印
        Route::get('/print', 'TrackingOrderController@orderPrintAll')->name('tracking-order.print');
        //运单导出表格
        Route::get('/order-excel', 'TrackingOrderController@trackingOrderExport')->name('tracking-order.order-excel');
        //运单第三方对接日志
        Route::get('/{id}/third-party-log', 'ThirdPartyLogController@index')->name('tracking-order.third-party-log');
        //修改出库状态
        Route::put('/out-status', 'TrackingOrderController@changeOutStatus')->name('tracking-order.out-status');
    });

    //司机管理
    Route::prefix('driver')->group(function () {
        //司机新增
        Route::post('/register', 'DriverController@driverRegister')->name('driver.store');
        //获取状态
        Route::get('/status', 'DriverController@driverStatus')->name('driver.lock');
        //锁定或解锁司机
        Route::post('/{id}/lock', 'DriverController@lockDriver')->name('driver.lock');
        //修改司机密码
        Route::put('/{id}/update-password', 'DriverController@resetPassword')->name('driver.reset-password');

        //司机列表
        Route::get('/', 'DriverController@index')->name('driver.index');
        //司机详情
        Route::get('/{id}', 'DriverController@show')->name('driver.index');
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
        Route::get('/brands', 'CarBrandController@index')->name('car.index');
        // 添加品牌
        Route::post('/brand', 'CarBrandController@store')->name('car.index');
        // 获取型号列表
        Route::get('/models', 'CarModelController@getListByBrand')->name('car.index');
        // 添加模型
        Route::post('/model', 'CarModelController@store')->name('car.index');
        //车辆查询
        Route::get('/', 'CarController@index')->name('car.index');
        // 初始化
        Route::get('/init', 'CarController@init')->name('car.store');
        //车辆新增
        Route::post('/', 'CarController@store')->name('car.store');
        //车辆详情
        Route::get('/{id}', 'CarController@show')->name('car.index');
        //车辆修改
        Route::put('/{id}', 'CarController@update')->name('car.update');
        //车辆删除
        Route::delete('/{id}', 'CarController@destroy')->name('car.destroy');
        // 导出里程
        Route::get('/{id}/distance', 'CarController@distanceExport')->name('car.export-distance');
        // 导出信息
        Route::get('/{id}/info', 'CarController@infoExport')->name('car.export-info');
    });

    //智能管车
    Route::prefix('car-management')->group(function () {
        //车辆追踪
        Route::get('/track', 'RouteTrackingController@show')->name('car-management.index');
        //所有车辆追踪
        Route::get('/all-track', 'RouteTrackingController@index')->name('car-management.index');
    });

    //车辆事故
    Route::prefix('car-accident')->group(function () {
        //车辆事故列表
        Route::get('/', 'CarAccidentController@index')->name('car-accident.index');
        //车辆事故新增
        Route::post('/', 'CarAccidentController@store')->name('car-accident.store');
        //车辆事故详情
        Route::get('/{id}', 'CarAccidentController@detail')->name('car-accident.detail');
        //车辆事故更新
        Route::put('/{id}', 'CarAccidentController@update')->name('car-accident.update');
        //批量删除
        Route::delete('/list', 'CarAccidentController@destroyAll')->name('car-accident.destroy');
    });

    //车辆维护
    Route::prefix('car-maintain')->group(function () {
        //车辆维护列表
        Route::get('/', 'CarMaintainController@index')->name('car-maintain.index');
        //车辆维护新增
        Route::post('/', 'CarMaintainController@store')->name('car-maintain.store');
        //车辆维护详情
        Route::get('/{id}', 'CarMaintainController@detail')->name('car-maintain.detail');
        //车辆维护更新
        Route::put('/{id}', 'CarMaintainController@update')->name('car-maintain.update');
        //维护数据导出
        Route::get('/export', 'CarMaintainController@export')->name('car-maintain.export');
        //批量删除
        Route::delete('/list', 'CarMaintainController@destroyAll')->name('car-maintain.destroy');
        //批量收票
        Route::put('/ticket', 'CarMaintainController@ticketAll')->name('car-maintain.ticket');
    });

    //备品管理
    Route::prefix('spare-parts')->group(function () {
        //备品列表
        Route::get('/', 'SparePartsController@index')->name('spare-parts.index');
        //备品新增
        Route::post('/', 'SparePartsController@store')->name('spare-parts.store');
        //备品修改
        Route::put('/{id}', 'SparePartsController@update')->name('spare-parts.update');
        //备品删除
        Route::delete('/{id}', 'SparePartsController@delete')->name('spare-parts.delete');
        //新增初始化
        Route::get('/init', 'SparePartsController@init')->name('spare-parts.init');
        //库存列表
        Route::get('/stock', 'SparePartsStockController@index')->name('spare-parts.stock');
        //新增入库
        Route::post('/stock', 'SparePartsStockController@store')->name('spare-parts.createStock');
        //领用记录
        Route::get('/record', 'SparePartsRecordController@index')->name('spare-parts.record');
        //备品领取
        Route::post('/record', 'SparePartsRecordController@store')->name('spare-parts.createRecord');
        //领取作废
        Route::put('/record/{id}', 'SparePartsRecordController@invalid')->name('spare-parts.invalid');
    });

    //设备管理
    Route::prefix('device')->group(function () {
        //设备列表
        Route::get('/', 'DeviceController@index')->name('device.index');
        //设备详情
        Route::get('/{id}', 'DeviceController@show')->name('device.index');
        //设备新增
        Route::post('/', 'DeviceController@store')->name('device.store');
        //设备修改
        Route::put('/{id}', 'DeviceController@update')->name('device.update');
        //设备删除
        Route::delete('/{id}', 'DeviceController@destroy')->name('device.destroy');
        //司机列表
        Route::get('/driver', 'DeviceController@getDriverPageList')->name('device.bind');
        //绑定
        Route::put('/{id}/bind', 'DeviceController@bind')->name('device.bind');
        //解绑
        Route::put('/{id}/unBind', 'DeviceController@unBind')->name('device.unBind');
    });

    //站点管理
    Route::prefix('batch')->group(function () {
        //站点查询
        Route::get('/', 'BatchController@index')->name('batch.index');
        //批次详情
        Route::get('/{id}', 'BatchController@show')->name('batch.show');
        //取消取派
        Route::put('/{id}/cancel', 'BatchController@cancel')->name('batch.cancel');
        //获取可分配路线日期
        Route::get('/{id}/get-date', 'BatchController@getDateList')->name('batch.assign-tour');
        //根据线路规则获取线路
        Route::get('/get-line', 'BatchController@getLineList')->name('batch.assign-tour');
        //获取线路任务列表
        Route::get('/{id}/get-tour', 'BatchController@getTourList')->name('batch.assign-tour');
        //分配站点至线路任务
        Route::put('/{id}/assign-tour', 'BatchController@assignToTour')->name('batch.assign-tour');
        //批量分配站点至线路任务
        Route::put('/assign-tour', 'BatchController@assignListToTour')->name('batch.assign-tour');
        //移除站点
        Route::delete('/{id}/remove', 'BatchController@removeFromTour')->name('batch.remove');
        //批量移除站点
        Route::delete('/remove', 'BatchController@removeListFromTour')->name('batch.remove');
    });


    //站点 异常管理
    Route::prefix('batch-exception')->group(function () {
        //列表查询
        Route::get('/', 'BatchExceptionController@index')->name('batch-exception.index');
        //获取详情
        Route::get('/{id}', 'BatchExceptionController@show')->name('batch-exception.show');
        //处理
        Route::put('/{id}/deal', 'BatchExceptionController@deal')->name('batch-exception.deal');
    });

    //线路任务管理
    Route::prefix('tour')->group(function () {
        //更改线路任务顺序 -- 手动优化
        Route::post('/update-batch-index', 'TourController@updateBatchIndex')->name('tour.intelligent-scheduling');
        //自动优化线路
        Route::post('/auto-op-tour', 'TourController@autoOpTour')->name('tour.intelligent-scheduling');
        //线路任务查询
        Route::get('/', 'TourController@index')->name('tour.index');
        //线路任务详情
        Route::get('/{id}', 'TourController@show')->name('tour.index');
        //获取详情
        Route::get('/report/{id}', 'ReportController@show')->name('tour.show');
        //分配司机
        Route::put('/{id}/assign-driver', 'TourController@assignDriver')->name('assign-driver|tour.cancel-driver');
        //取消分配司机
        Route::put('/{id}/cancel-driver', 'TourController@cancelAssignDriver')->name('assign-driver|tour.cancel-driver');
        //分配车辆
        Route::put('/{id}/assign-car', 'TourController@assignCar')->name('tour.assign-car|tour.cancel-car');
        //取消分配车辆
        Route::put('/{id}/cancel-car', 'TourController@cancelAssignCar')->name('tour.assign-car|tour.cancel-car');
        //取消待出库
        Route::put('/{id}/unlock', 'TourController@unlock')->name('tour.unlock');
        //导出投递站点excel
        Route::get('/batch-excel', 'TourController@batchExport')->name('tour.batch-excel');
        //导出计划
        Route::get('/{id}/plan-excel', 'TourController@planExport')->name('tour.intelligent-scheduling');
        //获取可分配日期
        Route::get('/{id}/get-date', 'TourController@getLineDate')->name('tour.assign');
        //获取可加入的线路任务列表
        Route::get('/by-line', 'TourController@getListJoinByLineId')->name('tour.assign');
        //分配线路
        Route::put('/{id}/assign', 'TourController@assignTourToTour')->name('tour.assign');
        //线路追踪
        Route::get('/track', 'RouteTrackingController@show')->name('tour.track');
        //线路追踪
        Route::get('/all-track', 'RouteTrackingController@index')->name('tour.track');
        //导出站点地图
        Route::get('/{id}/batchPng', 'TourController@batchPng');
    });

    //线路任务-司机
    Route::prefix('tour-driver')->group(function () {
        Route::get('/{tour_no}', 'TourDriverController@getListByTourNo');
    });

    //延迟管理
    Route::prefix('delay')->group(function () {
        Route::get('/init', 'TourDelayController@init')->name('delay.index');
        Route::get('/', 'TourDelayController@index')->name('delay.index');
    });

    //任务报告
    Route::prefix('report')->group(function () {
        //列表查询
        Route::get('/', 'ReportController@index')->name('report.index');
        //获取详情
        Route::get('/{id}', 'ReportController@show')->name('report.show');
        //导出任务报告
        Route::get('/{id}/tour-excel', 'TourController@tourExport')->name('report.report-excel');
    });

    //线路管理
    Route::prefix('line')->group(function () {
        //通过日期，获取线路列表
        Route::get('/by-date', 'LineController@getListByDate');
        /****************************************邮编线路**************************************/
        //列表查询
        Route::get('/', 'LineController@postcodeIndex')->name('line.post-code-index');
        //获取详情
        Route::get('/{id}', 'LineController@postcodeShow')->name('line.post-code-index');
        //新增
        Route::post('/', 'LineController@postcodeStore')->name('line.post-code-store');
        //修改
        Route::put('/{id}', 'LineController@postcodeUpdate')->name('line.post-code-update');
        //删除
        Route::delete('/{id}', 'LineController@postcodeDestroy')->name('line.post-code-destroy');
        //导入
        Route::post('/import', 'LineController@postcodeLineImport')->name('line.post-code-import');
        //货主线路范围详情
        Route::get('/{id}/merchant-group-line-range', 'MerchantGroupLineRangeController@show')->name('line.post-code-merchant-config');
        //货主线路范围修改
        Route::put('/{id}/merchant-group-line-range', 'MerchantGroupLineRangeController@createOrUpdate')->name('line.post-code-merchant-config');
        /****************************************区域线路**************************************/
        //列表查询
        Route::get('/area', 'LineController@areaIndex')->name('line.area-index');
        //获取详情
        Route::get('/area/{id}', 'LineController@areaShow')->name('line.area-show');
        //新增
        Route::post('/area', 'LineController@areaStore')->name('line.area-store');
        //修改
        Route::put('/area/{id}', 'LineController@areaUpdate')->name('line.area-update');
        //删除
        Route::delete('/area/{id}', 'LineController@areaDestroy')->name('line.area-destroy');

        //批量修改状态
        Route::put('/status', 'LineController@statusByList')->name('line.status');

        //流程测试
        Route::get('/test', 'LineController@test')->name('line.test');
    });

    //网点管理
    Route::prefix('warehouse')->group(function () {
        //列表查询
        Route::get('/', 'WareHouseController@index')->name('warehouse.index');
        //获取详情
        Route::get('/{id}', 'WareHouseController@show')->name('warehouse.index');
        //新增
        Route::post('/', 'WareHouseController@store')->name('warehouse.store');
        //修改
        Route::put('/{id}', 'WareHouseController@update')->name('warehouse.update');
        //删除
        Route::delete('/{id}', 'WareHouseController@destroy')->name('warehouse.destroy');
        //树节点
        Route::get('/tree', 'WareHouseController@tree')->name('warehouse.index');
        //移动节点
        Route::put('/{id}/move/{parent}', 'WareHouseController@move')->name('warehouse.index');
        //查看线路
        Route::get('/{id}/line', 'WareHouseController@getLineList')->name('warehouse.index');
        //查看可选线路
        Route::get('/{id}/all-line', 'WareHouseController@getAbleLineList')->name('warehouse.index');
        //加入线路
        Route::post('/{id}/line', 'WareHouseController@addLineList')->name('warehouse.index');
        //移除线路
        Route::delete('/{id}/all-line', 'WareHouseController@removeLineList')->name('warehouse.index');
        //新增线路
        Route::post('/{id}/line', 'WareHouseController@addLineList')->name('warehouse.update');
        //删除线路
        Route::delete('/{id}/line', 'WareHouseController@removeLineList')->name('warehouse.update');
    });

    //公司信息
    Route::prefix('company-info')->group(function () {
        Route::get('/', 'CompanyController@index')->name('company-info.index');
        Route::put('/', 'CompanyController@update')->name('company-info.update');
    });

    //公司配置
    Route::prefix('company-config')->group(function () {
        //获取详情
        Route::get('/show', 'CompanyConfigController@show');
        //获取地址模板列表
        Route::get('/address-template', 'CompanyConfigController@getAddressTemplateList')->name('company-config.show');
        //修改
        Route::put('/update', 'CompanyConfigController@update')->name('company-config.update');
        //计量单位设置
        Route::get('/unit', 'CompanyConfigController@unit_show')->name('company-config.unit');
        Route::put('/unit', 'CompanyConfigController@unit_update')->name('company-config.unit');
        //调度规则
        Route::get('/rule', 'CompanyConfigController@rule_show')->name('company-config.rule');
        Route::put('/rule', 'CompanyConfigController@rule_update')->name('company-config.rule');
    });

    Route::prefix('special-scenes-config')->group(function () {
        //获取详情
        Route::get('/show', 'CompanyConfigController@show')->name('special-scenes-config.show');
        //修改
        Route::put('/update', 'CompanyConfigController@update')->name('special-scenes-config.update');
    });

    //员工管理
    Route::prefix('employees')->group(function () {
        //员工列表
        Route::get('/', 'EmployeeController@index')->name('employees.index');
        //员工详情
        Route::get('/{id}', 'EmployeeController@show')->name('employees.index');
        //员工修改
        Route::put('/{id}', 'EmployeeController@update')->name('employees.update');
        //员工新增
        Route::post('/', 'EmployeeController@store')->name('employees.store');
        //员工删除
        Route::delete('/{id}', 'EmployeeController@destroy')->name('employees.destroy');
        //禁止登录
        Route::put('/{id}/forbid-login/{enabled}', 'EmployeeController@setLogin')->name('employees.set-login');
        //批量启用禁用
        Route::put('/forbid-login', 'EmployeeController@setLoginByList')->name('employees.set-login');
        //修改员工密码
        Route::put('/{id}/password', 'EmployeeController@resetPassword')->name('employees.reset-password');
    });

    //角色管理/权限管理
    Route::prefix('role')->group(function () {
        /************************************角色************************************************/
        //列表查询
        Route::get('/', 'RoleController@index')->name('role.index');
        //获取详情
        Route::get('/{id}', 'RoleController@show')->name('role.index');
        //新增
        Route::post('/', 'RoleController@store')->name('role.store');
        //修改
        Route::put('/{id}', 'RoleController@update')->name('role.update');
        //删除
        Route::delete('/{id}', 'RoleController@destroy')->name('role.destroy');
        /************************************权限************************************************/
        //获取当前角色权限树
        Route::get('/{id}/permission-tree', 'RoleController@getRolePermissionTree')->name('role.permission');
        //给角色分配权限
        Route::put('/{id}/assign-permission', 'RoleController@assignPermission')->name('role.permission');
        /************************************用户************************************************/
        //获取员工列表
        Route::get('/employee-list', 'RoleController@getEmployeeList')->name('role.employee-list');
        //获取指定角色的员工列表
        Route::get('/{id}/employee-list', 'RoleController@getRoleEmployeeList')->name('role.employee-list');
        //员工分配角色
        Route::put('/{id}/assign-employee-list', 'RoleController@assignEmployeeList')->name('role.assign-employee-list');
        //员工移除角色
        Route::delete('/{id}/remove-employee-list', 'RoleController@removeEmployeeList')->name('role.employee-list');
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
        Route::get('/', 'AddressController@index')->name('address.index');
        //获取详情
        Route::get('/{id}', 'AddressController@show')->name('address.index');
        //新增
        Route::post('/', 'AddressController@store')->name('address.store');
        //修改
        Route::put('/{id}', 'AddressController@update')->name('address.update');
        //删除
        Route::delete('/{id}', 'AddressController@destroy')->name('address.destroy');
        //导入模板
        Route::get('/excel-template', 'AddressController@excelTemplate')->name('address.import');
        //导入
        Route::post('/excel', 'AddressController@import')->name('address.import');
        //批量新增
        Route::post('/list', 'AddressController@storeByList')->name('address.import');
        //检查
        Route::post('/excel-check', 'AddressController@importCheckByList')->name('address.import');
        //导出
        Route::get('/excel', 'AddressController@excelExport')->name('address.export');
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
        //获取邮编信息
        Route::get('/postcode', 'CommonController@getPostcode');
        //获取线路列表
        Route::get('/get-line', 'TrackingOrderController@getLineList');
        //获取用户列表
        Route::get('/merchant', 'MerchantController@index');
        //获取用户组列表
        Route::get('/merchant-group', 'MerchantGroupController@index');
        //获取司机列表
        Route::get('/driver', 'DriverController@index');
        //获取权限组列表
        Route::get('/role', 'RoleController@index');
        //获取网点列表
        Route::get('/warehouse', 'WareHouseController@index');
        //获取车辆列表
        Route::get('/car', 'CarController@index');
        //获取运价列表
        Route::get('/transport-price', 'TransportPriceController@index')->name('transport-price.index');
        //获取地址列表
        Route::get('/get-address', 'AddressController@index');
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
        //文件上传
        Route::post('file', 'UploadController@fileUpload');
    });

    //路线追踪相关
    Route::prefix('route-tracking')->group(function () {
        Route::get('route', 'RouteTrackingController@route')->name('route-tracking.route');
    });

    //货主管理
    Route::prefix('merchant')->group(function () {
        //列表查询
        Route::get('/', 'MerchantController@index')->name('merchant.index');
        //获取详情
        Route::get('/{id}', 'MerchantController@show')->name('merchant.index');
        //初始化
        Route::get('/init', 'MerchantController@init')->name('merchant.index');
        //新增
        Route::post('/', 'MerchantController@store')->name('merchant.store');
        //修改
        Route::put('/{id}', 'MerchantController@update')->name('merchant.update');
        //修改密码
        Route::put('/{id}/password', 'MerchantController@updatePassword')->name('merchant.update-password');
        //启用/禁用
        Route::put('/{id}/status', 'MerchantController@status')->name('merchant.status');
        //批量启用禁用
        Route::put('/status', 'MerchantController@statusByList')->name('merchant.status');
        //货主导出
        Route::get('/excel', 'MerchantController@excel')->name('merchant.excel');
    });

    //货主授权API管理
    Route::prefix('merchant-api')->group(function () {
        //列表查询
        Route::get('/', 'MerchantApiController@index')->name('merchant-api.index');
        //获取详情
        Route::get('/{id}', 'MerchantApiController@show')->name('merchant-api.index');
        //新增
        Route::post('/', 'MerchantApiController@store')->name('merchant-api.store');
        //修改
        Route::put('/{id}', 'MerchantApiController@update')->name('merchant-api.update');
        //删除
        Route::delete('/{id}', 'MerchantApiController@destroy')->name('merchant-api.destroy');
        //启用/禁用
        Route::put('/{id}/status', 'MerchantApiController@status')->name('merchant-api.status');
    });

    //货主组管理
    Route::prefix('merchant-group')->group(function () {
        //列表查询
        Route::get('/', 'MerchantGroupController@index')->name('merchant-group.index');
        //获取详情
        Route::get('/{id}', 'MerchantGroupController@show')->name('merchant-group.index');
        //新增
        Route::post('/', 'MerchantGroupController@store')->name('merchant-group.store');
        //修改
        Route::put('/{id}', 'MerchantGroupController@update')->name('merchant-group.update');
        //删除
        Route::delete('/{id}', 'MerchantGroupController@destroy')->name('merchant-group.destroy');
        //组内成员
        Route::get('/{id}/member', 'MerchantGroupController@indexOfMerchant')->name('merchant-group.member');
        //批量修改运价方案
        Route::put('/transport-price', 'MerchantGroupController@updatePrice')->name('merchant-group.transport-price');
        //获取费用列表
        Route::get('/fee', 'MerchantGroupController@getFeeList')->name('merchant-group.config');
        //配置
        Route::put('/{id}/config', 'MerchantGroupController@config')->name('merchant-group.config');
        //状态修改
        Route::put('/{id}/status', 'MerchantGroupController@status')->name('merchant-group.status');
    });

    //运价管理
    Route::prefix('transport-price')->group(function () {
        //列表查询
        Route::get('/', 'TransportPriceController@index')->name('transport-price.index');
        //获取详情
        Route::get('/{id}', 'TransportPriceController@show')->name('transport-price.index');
        //新增
        Route::post('/', 'TransportPriceController@store')->name('transport-price.store');
        //修改
        Route::put('/{id}', 'TransportPriceController@update')->name('transport-price.update');
        //启用/禁用
        Route::put('/{id}/status', 'TransportPriceController@status')->name('transport-price.status');
        //运价估算
        Route::post('/{id}/test', 'TransportPriceController@priceCount')->name('transport-price.test');
        //运价操作日志
        Route::get('/{id}/log', 'TransportPriceController@operationLogIndex')->name('transport-price.log');
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
        //查询
        Route::get('/', 'OrderNoRuleController@index')->name('order-no-rule.index');
        //详情
        Route::get('/{id}', 'OrderNoRuleController@show')->name('order-no-rule.index');
        //初始化
        Route::get('/init', 'OrderNoRuleController@initStore')->name('order-no-rule.store');
        //新增
        Route::post('/', 'OrderNoRuleController@store')->name('order-no-rule.store');
        //修改
        Route::put('/{id}', 'OrderNoRuleController@update')->name('order-no-rule.update');
        //删除
        Route::delete('/{id}', 'OrderNoRuleController@destroy')->name('order-no-rule.destroy');
    });

    //顺带包裹编号规则管理
    Route::prefix('package-no-rule')->group(function () {
        //查询
        Route::get('/', 'PackageNoRuleController@index')->name('package-no-rule.index');
        //详情
        Route::get('/{id}', 'PackageNoRuleController@show')->name('package-no-rule.index');
        //新增
        Route::post('/', 'PackageNoRuleController@store')->name('package-no-rule.store');
        //修改
        Route::put('/{id}', 'PackageNoRuleController@update')->name('package-no-rule.update');
        //删除
        Route::delete('/{id}', 'PackageNoRuleController@destroy')->name('package-no-rule.destroy');
    });

    //打印模板
    Route::prefix('print-template')->group(function () {
        //初始化
        Route::get('/init', 'PrintTemplateController@init')->name('print-template.show');
        //详情
        Route::get('/show', 'PrintTemplateController@show')->name('print-template.show');
        //修改
        Route::put('/update', 'PrintTemplateController@update')->name('print-template.update');
    });

    //订单打印模板
    Route::prefix('order-bill-template')->group(function () {
        //初始化
        Route::get('/', 'OrderTemplateController@index')->name('print-template.show');
        //详情
        Route::get('/{id}', 'OrderTemplateController@show')->name('print-template.show');
        //修改
        Route::put('/{id}', 'OrderTemplateController@update')->name('print-template.update');
        //设置默认
        Route::put('/{id}/default', 'OrderTemplateController@changeDefault')->name('print-template.update');
    });

    //费用管理
    Route::prefix('fee')->group(function () {
        //列表查询
        Route::get('/', 'FeeController@index')->name('fee.index');
        //初始化
        Route::get('/init', 'FeeController@init')->name('fee.index');
        //详情
        Route::get('/{id}', 'FeeController@show')->name('fee.index');
        //新增
        Route::post('/', 'FeeController@store')->name('fee.store');
        //修改
        Route::put('/{id}', 'FeeController@update')->name('fee.update');
        //删除
        Route::delete('/{id}', 'FeeController@destroy')->name('fee.destroy');
    });

    //放假管理
    Route::prefix('holiday')->group(function () {
        //列表查询
        Route::get('/', 'HolidayController@index')->name('holiday.index');
        //详情
        Route::get('/{id}', 'HolidayController@show')->name('holiday.index');
        //新增
        Route::post('/', 'HolidayController@store')->name('holiday.store');
        //修改
        Route::put('/{id}', 'HolidayController@update')->name('holiday.update');
        //删除
        Route::delete('/{id}', 'HolidayController@destroy')->name('holiday.destroy');
        //启用/禁用
        Route::put('/{id}/status', 'HolidayController@status')->name('holiday.status');
        //获取货主列表
        Route::get('/merchant', 'HolidayController@merchantIndex')->name('holiday.merchant-index');
        //新增货主列表
        Route::post('/{id}/merchant', 'HolidayController@storeMerchantList')->name('holiday.merchant-store');

        Route::delete('/{id}/merchant', 'HolidayController@destroyMerchant')->name('holiday.merchant-destroy');
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
        Route::get('/statistics', 'RechargeStatisticsController@index')->name('recharge.index');
        //充值统计查询
        Route::get('/statistics/{id}', 'RechargeStatisticsController@show')->name('recharge.index');
        //充值查询
        Route::get('/', 'RechargeController@index')->name('recharge.index');
        //充值详情
        Route::get('/{id}', 'RechargeController@show')->name('recharge.show');
        //充值审核
        Route::put('/statistics/{id}', 'RechargeStatisticsController@verify')->name('recharge.verify');
    });

    //入库异常管理
    Route::prefix('stock-exception')->group(function () {
        //查询
        Route::get('/', 'stockExceptionController@index')->name('stock-exception.index');
        //详情
        Route::get('/{id}', 'stockExceptionController@show')->name('stock-exception.index');
        //审核
        Route::put('/{id}/deal', 'stockExceptionController@deal')->name('stock-exception.deal');
    });

    //地图配置
    Route::prefix('map-config')->group(function () {
        //详情
        Route::get('/', 'MapConfigController@show')->name('holiday.update');
        //修改
        Route::put('/', 'MapConfigController@update')->name('holiday.update');
    });

    //地图配置
    Route::prefix('company-customize')->group(function () {
        //详情
        Route::get('/', 'CompanyCustomizeController@show')->name('company-config.update');
        //修改
        Route::put('/', 'CompanyCustomizeController@update')->name('company-config.update');
    });

    //邮件模板
    Route::prefix('email-template')->group(function () {
        //列表
        Route::get('/', 'EmailTemplateController@index')->name('email-template.index');
        //新增
        Route::post('/', 'EmailTemplateController@store')->name('email-template.store');
        //详情
        Route::get('/{id}', 'EmailTemplateController@detail')->name('email-template.detail');
        //修改
        Route::put('/{id}', 'EmailTemplateController@update')->name('email-template.update');
        //删除
        Route::delete('/{id}', 'EmailTemplateController@destroy')->name('email-template.destroy');
    });

    //袋号管理
    Route::prefix('bag')->group(function () {
        //列表
        Route::get('/', 'BagController@index')->name('bag.index');
        //新增（扫描）
        Route::post('/', 'BagController@store')->name('bag.store');
        //详情
        Route::get('/{id}', 'BagController@show')->name('bag.show');
        //修改（扫描）
        Route::put('/{id}', 'BagController@update')->name('bag.update');
        //删除
        Route::delete('/{id}', 'BagController@destroy')->name('bag.destroy');
    });

    //车次管理
    Route::prefix('shift')->group(function () {
        //列表
        Route::get('/', 'BagController@index')->name('bag.index');
        //新增（扫描）
        Route::post('/', 'BagController@store')->name('bag.store');
        //详情
        Route::get('/{id}', 'BagController@show')->name('bag.show');
        //修改（扫描）
        Route::put('/{id}', 'BagController@update')->name('bag.update');
        //删除
        Route::delete('/{id}', 'BagController@destroy')->name('bag.destroy');
    });

    //转运单管理
    Route::prefix('tracking-package')->group(function () {
        //列表
        Route::get('/', 'TrackingPackageController@index');
    });

    //账目
    Route::prefix('ledger')->group(function () {
        //列表
        Route::get('/', 'LedgerController@index')->name('ledger.index');
        //修改
        Route::post('/{id}', 'LedgerController@update')->name('ledger.update');
        //日志
        Route::get('/{id}/log', 'LedgerController@log')->name('ledger.update');
    });

    //账单
    Route::prefix('bill')->group(function () {
        //列表
        Route::get('/', 'BillController@index')->name('bill.index');
        //充值
        Route::post('/merchant-recharge', 'BillController@merchantRecharge')->name('bill.merchant-recharge');
        //列表
        Route::post('/{id}/verify', 'BillController@verify')->name('bill.verify');
        //列表
        Route::get('/{id}', 'BillController@show')->name('bill.show');
    });

    //对账单
    Route::prefix('bill-verify')->group(function () {
        //列表
        Route::get('/', 'BillVerifyController@index')->name('bill-verify.index');
        //详情
        Route::get('/{id}', 'BillVerifyController@show')->name('bill-verify.index');
        //新增
        Route::post('/', 'BillVerifyController@store')->name('bill-verify.store');
//        //修改审核单
//        Route::put('/{id}', 'BillVerifyController@updateById')->name('bill-verify.update');
        //审核
        Route::post('/{id}/verify', 'BillVerifyController@verify')->name('bill-verify.verify');
        //删除
        Route::delete('/{id}', 'BillVerifyController@destroy')->name('bill-verify.destroy');
        //删除
        Route::post('/auto/{id}', 'BillVerifyController@autoStore')->name('bill-verify.store');
    });

    //流水
    Route::prefix('journal')->group(function () {
        //列表
        Route::get('/', 'JournalController@index')->name('journal.index');

    });
});
