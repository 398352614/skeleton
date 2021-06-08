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

//认证
Route::namespace('Api\Merchant')->middleware(['auth:merchant_api'])->group(function () {

    Route::post('me', 'AuthController@me');

    /**
     * @api {post} /api/merchant_api/order 新增订单
     * @apiGroup 订单管理
     * @apiName 新增订单
     * @apiPermission merchant
     * @apiVersion 1.0.0
     * @apiDescription 通过表单信息新增一个订单
     * @apiParam {String} key[必填]  秘钥：从管理员端新增货主时，会自动生成一个key，在资料管理-API对接管理中，可查询对应key，用以确认货主身份。
     * @apiParam {String} sign[必填]  签名：签名是以secret和data以一定加密方式形成的签名，每次请求都会验证key和sign以验证数据可靠。key或sign任一项不正确，请求都将被拒绝。
     * 从管理员端新增货主时，会自动生成一个secret，在资料管理-API对接管理中，可查询对应secret。
     * sign的生成规则为：1，平铺data内的数组，生成一个字符串；2，将1的结果与secret连接起来；3，对2的结果其进行url编码；4，将3的结果全部转化为大写。
     * @apiParam {String} timestamp[必填]  时间戳：发送请求时的时间戳。
     * @apiParam {String} data[必填]  数据
     * @apiParam {String} data.type[必填]  订单类型：1-提货->网点；2-网点->配送；3->提货->网点->配送；4-提货->配送。
     * @apiParam {String} data.merchant_id[必填]  货主ID
     * @apiParam {String} data.out_user_id[选填]  外部客户ID：可将对接系统的客户ID储存到此字段以便查询。
     * @apiParam {String} data.out_order_no[选填]  外部订单号：可将对接系统的订单号储存到此字段以便查询。
     * @apiParam {String} data.transport_mode[选填]  运输方式：1-整车2-零担
     * @apiParam {String} data.control_mode[选填]  控货方式：1-无2-等通知放货。等通知放货：等

     *
     *
     *
     * @apiSuccess {Number} ret    状态码，1：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSampleRequest off
     * @apiSuccessExample {json} Success-Response:
     * {"ret":1,"msg":"","data":[]}
     * @apiErrorExample {json} Error-Response:
     * {"ret":0,"msg":"提错提示"}
     */
    Route::post('order', 'OrderController@store')->name('merchant_api.order.store');//新增订单

    /**
     * @api {post} /api/merchant_api/me 账户信息
     * @apiGroup 账号管理
     * @apiName 账户信息
     * @apiPermission merchant
     * @apiVersion 1.0.0
     * @apiDescription 获取本货主的基本信息
     * @apiParam {String} $param1  参数说明
     * @apiSuccess {Number} ret    状态码，1：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSampleRequest off
     * @apiSuccessExample {json} Success-Response:
     * {"ret":1,"msg":"","data":[]}
     * @apiErrorExample {json} Error-Response:
     * {"ret":0,"msg":"提错提示"}
     */
    Route::post('cancel-order', 'OrderController@destroy');//删除订单

    Route::post('order-update-address', 'OrderController@updateAddressDate');//修改订单地址日期
    Route::post('cancel-all-order', 'OrderController@destroyAll');//批量删除订单
    Route::post('order-out-status', 'OrderController@updateOutStatus');//出库

    /**
     * @api {post} /api/merchant_api/post-code-date-list 获取可选日期
     * @apiGroup 订单新增
     * @apiName 获取可选日期
     * @apiPermission merchant
     * @apiVersion 1.0.0
     * @apiDescription 通过地址，获取可下单的日期
     * @apiParam {String} receiver_post_code  邮编
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSampleRequest off
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"msg":"","data":[]}
     * @apiErrorExample {json} Error-Response:
     * {"code":1000,"msg":"提错提示"}
     */
    Route::post('post-code-date-list', 'LineController@getDateListByPostCode');//获取可选日期

    /**
     * @api {post} /api/merchant_api/me 账户信息
     * @apiGroup 账号管理
     * @apiName 账户信息
     * @apiPermission admin
     * @apiVersion 1.0.0
     * @apiDescription 获取本货主的基本信息
     * @apiParam {String} $param1  参数说明
     * @apiSuccess {Number} ret    状态码，1：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSampleRequest off
     * @apiSuccessExample {json} Success-Response:
     * {"ret":1,"msg":"","data":[]}
     * @apiErrorExample {json} Error-Response:
     * {"ret":0,"msg":"提错提示"}
     */
    Route::post('order-dispatch-info', 'OrderController@getOrderDispatchInfo');//派送情况

    Route::post('order-update-phone-date', 'OrderController@updateByApi');//修改订单
    Route::post('order-update-phone-date-list', 'OrderController@updateByApiList');//修改订单
    Route::post('package-info', 'PackageController@showByApi');//包裹查询
    Route::post('order-info', 'OrderController@showByApi');//订单查询
    Route::post('update-order-item-list', 'OrderController@updateItemList');//修改明细
    Route::post('/again-order-info', 'OrderController@getAgainInfo');//获取继续派送(再次取派)信息
    Route::post('/again-order', 'OrderController@again'); //继续派送(再次取派)
    Route::post('/end-order', 'OrderController@end');//终止派送
    Route::post('/order-update-second-date', 'OrderController@updateSecondDate');//修改派送日期
    Route::post('/get-all-line-range', 'LineController@getAllLineRange');//获取所有邮编
});
