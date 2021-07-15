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
Route::namespace('Api\Consumer')->group(function () {
    //查询物流轨迹
    Route::get('package-trail', 'PackageTrailController@index');
    Route::get('company', 'CompanyController@index');
    Route::get('order-trail', 'OrderTrailController@index');
});
