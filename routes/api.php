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
Route::namespace('Api')->group(function () {
    //测试接口
    Route::prefix('test')->group(function () {
        //测试
        Route::get('/', 'TestController@index');
        //获取详情
        Route::get('/{id}', 'TestController@show');
        //新增
        Route::post('/', 'TestController@store');
        //修改
        Route::put('/{id}', 'TestController@update');
        //删除
        Route::delete('/{id}', 'TestController@destroy');
        //计算日期
        Route::get('/calDate', 'TestController@calDate');
        //批量修改
        Route::get('/updateAll', 'TestController@updateAll');
        //批量修改
        Route::get('/hex/{id}', 'TestController@hex');

        Route::get('/letter', 'TestController@incrementLetter');
        Route::get('/getPath', 'TestController@getPath');
        Route::post('/push-test', 'TestsController@testPush');         //自动优化线路
    });
});
