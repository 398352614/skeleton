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
    //Route::post('password-reset', 'RegisterController@resetPassword');
    //Route::post('password-reset/apply', 'RegisterController@applyOfReset');
});

/*
|--------------------------------------------------------------------------
| 认证接口
|--------------------------------------------------------------------------
*/

Route::namespace('Api\Driver')->middleware(['auth:driver'])->group(function () {
    Route::post('logout', 'AuthController@logout');
    Route::get('me', 'AuthController@me');

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
        Route::get('/getSpecialRemarkList', 'TourTaskController@getSpecialRemarkList');
        //获取站点的订单特殊事项列表
        Route::get('/getBatchSpecialRemarkList', 'TourTaskController@getBatchSpecialRemarkList');
        //获取订单特殊事项
        Route::get('/getSpecialRemark', 'TourTaskController@getSpecialRemark');
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
        //锁定-开始装货
        Route::put('/{id}/lock', 'TourController@lock');
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
        //司机入库
        Route::put('/{id}/inWarehouse', 'TourController@inWarehouse');
    });
});
