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
});

//认证
Route::namespace('Api\Admin')->middleware(['auth:admin'])->group(function () {
    Route::get('me', 'AuthController@me');
    Route::post('logout', 'AuthController@loginout');

    //订单管理
    Route::prefix('order')->group(function () {
        //列表查询初始化
        Route::get('/initIndex', 'OrderController@initIndex');
        //列表查询
        Route::get('/', 'OrderController@index');
        //获取详情
        Route::get('/{id}', 'OrderController@show');
        //新增
        Route::post('/', 'OrderController@store');
        //修改
        Route::put('/', 'OrderController@update');
        //删除
        Route::delete('/', 'OrderController@destroy');
    });
});