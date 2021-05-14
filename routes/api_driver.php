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

/*
|--------------------------------------------------------------------------
| 公共接口
|--------------------------------------------------------------------------
*/

Route::namespace('Api\Driver')->middleware([])->group(function () {
    Route::post('login', 'AuthController@login');
    Route::put('password-reset', 'AuthController@resetPassword');
    Route::post('password-reset/apply', 'AuthController@applyOfReset');
    Route::get('/version', 'VersionController@check');//版本检查
});
/*
|--------------------------------------------------------------------------
| 认证接口
|--------------------------------------------------------------------------
*/

Route::namespace('Api\Driver')->middleware(['companyValidate:driver', 'auth:driver'])->group(function () {
    Route::post('logout', 'AuthController@logout')->name('driver.logout');
    Route::get('me', 'AuthController@me');
    Route::put('refresh', 'AuthController@refresh');
    Route::put('my-password', 'AuthController@updatePassword');

    //主页统计
    Route::prefix('statistics')->group(function () {
        //主页
        Route::get('/', 'HomeController@home')->name('statistics.home');

    });

    //备忘录管理
    Route::prefix('memorandum')->group(function () {
        //列表查询
        Route::get('/', 'MemorandumController@index');
        //获取详情
        Route::get('/{id}', 'MemorandumController@show');
        //新增
        Route::post('/', 'MemorandumController@store');
        //修改
        Route::put('/{id}', 'MemorandumController@update');
        //删除
        Route::delete('/{id}', 'MemorandumController@destroy');
    });

    //取件线路任务 管理
    Route::prefix('tour-task')->group(function () {
        //列表查询
        Route::get('/', 'TourTaskController@index');
        //获取详情
        Route::get('/{id}', 'TourTaskController@show');
        //获取所有的订单特殊事项列表
        Route::get('/{id}/getSpecialRemarkList', 'TourTaskController@getSpecialRemarkList');
        //获取站点的订单特殊事项列表
        Route::get('/{id}/getBatchSpecialRemarkList', 'TourTaskController@getBatchSpecialRemarkList');
        //获取运单特殊事项
        Route::get('/{id}/getSpecialRemark', 'TourTaskController@getSpecialRemark');
        //获取订单特殊事项
        Route::get('/all-info', 'TourTaskController@getAllInfo');
        //获取运单列表
        Route::get('/{id}/getTrackingOrderList', 'TourTaskController@getTrackingOrderList');
    });

    //设备管理
    Route::prefix('device')->group(function () {
        //获取详情
        Route::get('/show', 'DeviceController@show');
        //绑定
        Route::put('/bind', 'DeviceController@bind');
        //解绑
        Route::put('/unBind', 'DeviceController@unBind');
    });

    //车辆管理
    Route::prefix('car')->group(function () {
        //列表查询
        Route::get('/', 'CarController@index');
        //详情
        Route::get('/{id}', 'CarController@show');
    });

    //异常管理
    Route::prefix('batch-exception')->group(function () {
        //列表查询
        Route::get('/', 'BatchExceptionController@index');
        //获取详情
        Route::get('/{id}', 'BatchExceptionController@show');
    });

    //包裹复核功能
    Route::prefix('order')->group(function () {
        //获取线路
        Route::get('/get-line', 'LineController@index');
        //获取取件线路
        Route::get('/get-tour', 'TourController@getTourList');
        //获取订单及包裹
        Route::get('/', 'TourTaskController@getOrderList');
    });

    //取件线路 管理
    Route::prefix('tour')->group(function () {
        //更改线路任务顺序 -- 手动优化
        Route::post('/update-batch-index', 'TourController@updateBatchIndex')->middleware('checktourredislock');
        //锁定-开始装货
        Route::put('/{id}/lock', 'TourController@lock');
        //延迟
        Route::post('/{id}/delay', 'TourController@delay');
        //锁定-开始装货
        Route::put('/{id}/unlock', 'TourController@unlock');
        //备注
        Route::put('/{id}/remark', 'TourController@remark');
        //更换车辆
        Route::put('/{id}/changeCar', 'TourController@changeCar');
        //司机出库前验证
        Route::put('/{id}/checkOutWarehouse', 'TourController@checkOutWarehouse');
        //司机出库
        Route::put('/{id}/outWarehouse', 'TourController@outWarehouse');
        //司机确认出库
        Route::put('/{id}/actual-out-warehouse', 'TourController@actualOutWarehouse');
        //获取站点列表
        Route::get('/{id}/getBatchList', 'TourController@getBatchList');
        //获取站点的订单列表
        Route::get('/{id}/getBatchTrackingOrderList', 'TourController@getBatchTrackingOrderList');
        //到达站点
        Route::put('/{id}/batchArrive', 'TourController@batchArrive');
        //获取站点详情
        Route::get('/{id}/getBatchInfo', 'TourController@getBatchInfo');
        //站点 异常上报
        Route::put('/{id}/batchException', 'TourController@batchException');
        //站点 取消取派
        Route::put('/{id}/batchCancel', 'TourController@batchCancel');
        //站点 签收验证
        Route::put('/{id}/checkBatchSign', 'TourController@checkBatchSign');
        //站点 签收
        Route::put('/{id}/batchSign', 'TourController@batchSign');
        //获取取件线路统计数据
        Route::get('/{id}/getTotalInfo', 'TourController@getTotalInfo');
        //司机入库
        Route::put('/{id}/inWarehouse', 'TourController@inWarehouse');
        //站点跳过
        Route::put('/{id}/batch-skip', 'TourController@batchSkip');
        //站点恢复
        Route::put('/{id}/batch-recovery', 'TourController@batchRecovery');
    });

    //库存管理
    Route::prefix('stock')->group(function () {
        //包裹分拣入库
        Route::put('/package-pick-out', 'StockController@allocate');
    });

    //库存管理
    Route::prefix('stock-in-log')->group(function () {
        //包裹分拣入库
        Route::get('/', 'StockInLogController@index');
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

    //线路追踪
    Route::prefix('route-tracking')->group(function () {
        //用户采集地址
        Route::post('collect', 'RouteTrackingController@storeByList');
        //用户采集地址(弃用)
        Route::post('list-collect', 'RouteTrackingController@store');
    });

    //费用管理
    Route::prefix('fee')->group(function () {
        //获取所有费用
        Route::get('getAllFeeList', 'FeeController@getAllFeeList');
    });

    //货主管理
    Route::prefix('merchant')->group(function () {
        //获取所有货主
        Route::get('/', 'MerchantController@index');
    });

    //顺带包裹编号规则管理
    Route::prefix('package-no-rule')->group(function () {
        //查询
        Route::get('/', 'PackageNoRuleController@index');
    });

    //充值管理
    Route::prefix('recharge')->group(function () {
        //充值查询
        Route::get('/', 'RechargeController@index');
        //充值详情
        Route::get('/{id}', 'RechargeController@show');
        //充值
        Route::post('/', 'RechargeController@recharge');
        //充值验证
        Route::post('/verify', 'RechargeController@verify');
        //获取外部用户信息
        Route::get('/out-user', 'RechargeController@getOutUser');
    });


    //入库异常管理
    Route::prefix('stock-exception')->group(function () {
        //详情
        Route::get('/', 'stockExceptionController@index');
        //上报
        Route::post('/', 'stockExceptionController@store');
    });

    //袋号管理
    Route::prefix('bag')->group(function () {
        //列表
        Route::get('/', 'BagController@index');
        //新增（扫描）
        Route::post('/', 'BagController@store');
        //详情
        Route::get('/{id}', 'BagController@show');
        //删除
        Route::delete('/{id}', 'BagController@destroy');
        //包裹装袋扫描
        Route::post('/{id}/pack', 'BagController@packPackage');
        //移除包裹
        Route::delete('/{id}/pack', 'BagController@removePackage');
        //包裹拆袋扫描
        Route::delete('/{id}/unpack', 'BagController@unpackPackage');
    });

    //车次管理
    Route::prefix('shift')->group(function () {
        //列表
        Route::get('/', 'ShiftController@index');
        //新增（扫描）
        Route::post('/', 'ShiftController@store');
        //详情
        Route::get('/{id}', 'ShiftController@show');
        //修改（扫描）
        Route::put('/{id}', 'ShiftController@update');
        //删除
        Route::delete('/{id}', 'ShiftController@destroy');
        //更换车辆
        Route::put('/{id}/changeCar', 'ShiftController@changeCar');
        //装车
        Route::post('/{id}/load', 'ShiftController@loadItem');
        //移除内容物
        Route::delete('/{id}/load', 'ShiftController@removeItem');
        //卸车
        Route::post('/{id}/unload', 'ShiftController@unloadItem');
        //批量卸车
        Route::post('/{id}/unloadList', 'ShiftController@unloadItemList');
        //司机出库
        Route::put('/{id}/outWarehouse', 'ShiftController@outWarehouse');
        //司机入库
        Route::put('/{id}/inWarehouse', 'ShiftController@inWarehouse');
    });

    //仓库管理
    Route::prefix('warehouse')->group(function () {
        //列表
        Route::get('/', 'WarehouseController@index');
    });
});
