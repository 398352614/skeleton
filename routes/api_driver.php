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

Route::namespace('Api\Driver')->middleware(['companyValidate:driver','auth:driver'])->group(function () {
    Route::post('logout', 'AuthController@logout');
    Route::get('me', 'AuthController@me');
    Route::put('my-password', 'AuthController@updatePassword');

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
        //获取订单特殊事项
        Route::get('/{id}/getSpecialRemark', 'TourTaskController@getSpecialRemark');
    });

    //车辆管理
    Route::prefix('car')->group(function () {
        //列表查询
        Route::get('/', 'CarController@index');
    });

    //异常管理
    Route::prefix('batch-exception')->group(function () {
        //列表查询
        Route::get('/', 'BatchExceptionController@index');
        //获取详情
        Route::get('/{id}', 'BatchExceptionController@show');
    });


    //取件线路 管理
    Route::prefix('tour')->group(function () {
        //更改线路任务顺序 -- 手动优化
        Route::post('/update-batch-index', 'TourController@updateBatchIndex')->middleware('checktourredislock');
        //锁定-开始装货
        Route::put('/{id}/lock', 'TourController@lock');
        //锁定-开始装货
        Route::put('/{id}/unlock', 'TourController@unlock');
        //备注
        Route::put('/{id}/remark', 'TourController@remark');
        //更换车辆
        Route::put('/{id}/changeCar', 'TourController@changeCar');
        //司机出库
        Route::put('/{id}/outWarehouse', 'TourController@outWarehouse');
        //获取站点列表
        Route::get('/{id}/getBatchList', 'TourController@getBatchList');
        //获取站点的订单列表
        Route::get('/{id}/getBatchOrderList', 'TourController@getBatchOrderList');
        //到达站点
        Route::put('/{id}/batchArrive', 'TourController@batchArrive');
        //获取站点详情
        Route::get('/{id}/getBatchInfo', 'TourController@getBatchInfo');
        //站点 异常上报
        Route::put('/{id}/batchException', 'TourController@batchException');
        //站点 取消取派
        Route::put('/{id}/batchCancel', 'TourController@batchCancel');
        //站点 签收
        Route::put('/{id}/batchSign', 'TourController@batchSign');
        //获取取件线路统计数据
        Route::get('/{id}/getTotalInfo', 'TourController@getTotalInfo');
        //司机入库
        Route::put('/{id}/inWarehouse', 'TourController@inWarehouse');
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
});
