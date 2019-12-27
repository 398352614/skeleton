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
});
