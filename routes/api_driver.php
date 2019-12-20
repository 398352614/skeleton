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
Route::namespace('Api\Driver')->group(function () {
    //测试接口
    Route::prefix('test')->group(function () {
        //测试
        Route::get('/', 'TestController@index');
        //获取详情
        Route::get('/{id}/show', 'TestController@show');
        //新增
        Route::post('/', 'TestController@store');
        //修改
        Route::put('/{id}', 'TestController@update');
        //删除
        Route::put('/', 'TestController@update');
    });
});
