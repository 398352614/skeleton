<?php

use Illuminate\Support\Facades\Route;

/**
 * @apiDefine auth
 * @apiHeader {string} language 语言cn-中文en-英文。
 * @apiHeader {string} Authorization [必填]令牌，以bearer加空格加令牌为格式。
 * @apiHeaderExample {json} Header-Example:
 * {
 *       "language": "en"
 *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9kZXYtdG1zLm5sZS10ZWNoLmNvbTo0NDNcL2FwaVwvYWRtaW5cL2xvZ2luIiwiaWF0IjoxNTkxMjU4NDAzLCJleHAiOjE1OTI0NjgwMDMsIm5iZiI6MTU5MTI1ODQwMywianRpIjoidGV2MG1hQlM1T0lDVm5JRCIsInN1YiI6NjEsInBydiI6IjMyOTYzYTYwNmMyZjE3MWYxYzE0MzMxZTc2OTc2NmNkNTkxMmVkMTUiLCJyb2xlIjoiZW1wbG95ZWUifQ.8NVjy4OyITV3Cu3k3m_BwNc5Yqf2Ld-ibRQ7r9Q82kw"
 *     }
 */

/**
 * @apiDefine page
 * @apiParam {String} per_page 每页显示条数
 * @apiParam {String} page 页码
 */

/**
 * @apiDefine 05order 订单管理
 */

/**
 * @apiDefine meta
 * @apiSuccess {Object} data    返回数据
 * @apiSuccess {Object} data.links 跳转信息
 * @apiSuccess {String} data.links.first   第一页
 * @apiSuccess {String} data.links.last   最后一页
 * @apiSuccess {String} data.links.prev   前一页
 * @apiSuccess {String} data.links.next   后一页
 *
 * @apiSuccess {Object} data.meta 页码信息
 * @apiSuccess {String} data.meta.current_page   当前页码
 * @apiSuccess {String} data.meta.from   起始条数
 * @apiSuccess {String} data.meta.last_page   末页页码
 * @apiSuccess {String} data.meta.path   地址
 * @apiSuccess {String} data.meta.per_page   每页显示条数
 * @apiSuccess {String} data.meta.to   终止条数
 * @apiSuccess {String} data.meta.total   总条数
 */

//订单管理
Route::prefix('order')->group(function () {
    //订单统计
    /**
     * @api {get} /merchant 订单统计
     * @apiName 订单统计
     * @apiGroup 05order
     * @apiVersion 1.0.0
     * @apiUse auth
     *
     * @apiParam {String} type 类型1-取件2-派件3-取派
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    订单量，以订单状态排序
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[15,2,0,7,2,4],"msg":"successful"}
     */
    Route::get('/count', 'OrderController@ordercount');
    //列表查询
    /**
     * @api {get} /merchant 订单查询
     * @apiName 订单查询
     * @apiGroup 05order
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiUse page
     * @apiParam {String} type 类型1-取件2-派件3-取派
     * @apiParam {String} status 状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站
     * @apiParam {String} begin_date 起始时间
     * @apiParam {String} end_date 终止时间
     * @apiParam {String} source 来源
     * @apiParam {String} post_code 邮编
     * @apiParam {String} keyword 订单编号，外部订单号，客户编号
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data 返回信息
     * @apiUse meta
     * @apiSuccess {Object} data.data
     * @apiSuccess {String} data.data.id
     * @apiSuccess {String} data.data.company_id
     * @apiSuccess {String} data.data.merchant_id
     * @apiSuccess {String} data.data.merchant_id_name
     * @apiSuccess {String} data.data.order_no
     * @apiSuccess {String} data.data.source
     * @apiSuccess {String} data.data.source_name
     * @apiSuccess {String} data.data.mask_code
     * @apiSuccess {String} data.data.list_mode
     * @apiSuccess {String} data.data.type
     * @apiSuccess {String} data.data.type_name
     * @apiSuccess {String} data.data.out_user_id
     * @apiSuccess {String} data.data.express_first_no
     * @apiSuccess {String} data.data.express_second_no
     * @apiSuccess {String} data.data.status
     * @apiSuccess {String} data.data.status_name
     * @apiSuccess {String} data.data.out_status
     * @apiSuccess {String} data.data.out_status_name
     * @apiSuccess {String} data.data.execution_date
     * @apiSuccess {String} data.data.batch_no
     * @apiSuccess {String} data.data.tour_no
     * @apiSuccess {String} data.data.line_id
     * @apiSuccess {String} data.data.line_name
     * @apiSuccess {String} data.data.out_order_no
     * @apiSuccess {String} data.data.out_group_order_no
     * @apiSuccess {String} data.data.exception_label
     * @apiSuccess {String} data.data.exception_label_name
     * @apiSuccess {String} data.data.place_post_code
     * @apiSuccess {String} data.data.exception_stage_name
     * @apiSuccess {String} data.data.place_house_number
     * @apiSuccess {String} data.data.driver_name
     * @apiSuccess {String} data.data.driver_phone
     * @apiSuccess {String} data.data.starting_price
     * @apiSuccess {String} data.data.transport_price_type
     * @apiSuccess {String} data.data.transport_price_type_name
     * @apiSuccess {String} data.data.receipt_type
     * @apiSuccess {String} data.data.receipt_type_name
     * @apiSuccess {String} data.data.receipt_count
     * @apiSuccess {String} data.data.create_date
     * @apiSuccess {String} data.data.created_at
     * @apiSuccess {String} data.data.updated_at
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":{"data":[{"id":4171,"company_id":3,"merchant_id":65,"merchant_id_name":"ERP\u56fd\u9645","order_no":"SMAAADW0001","source":"1","source_name":"\u624b\u52a8\u6dfb\u52a0","mask_code":"C178","list_mode":1,"type":1,"type_name":"\u63d0\u8d27->\u7f51\u70b9","out_user_id":"904566","express_first_no":"","express_second_no":"","status":1,"status_name":"\u5f85\u53d7\u7406","out_status":1,"out_status_name":"\u662f","execution_date":"2021-06-11","batch_no":null,"tour_no":null,"line_id":null,"line_name":null,"out_order_no":"DEVV21904566802","out_group_order_no":null,"exception_label":1,"exception_label_name":"\u6b63\u5e38","place_post_code":"9746TN","exception_stage_name":"","place_house_number":"3-91","driver_name":null,"driver_phone":null,"starting_price":"10.00","transport_price_type":"2","transport_price_type_name":"\u9636\u68af\u56fa\u5b9a\u503c\u8ba1\u7b97\uff08\u56fa\u5b9a\u8d39\u7528+\uff08\u91cd\u91cf\u4ef7\u683c\u6863\uff09*\uff08\u91cc\u7a0b\u4ef7\u683c\u6863\uff09\uff09","receipt_type":1,"receipt_type_name":"\u539f\u5355\u8fd4\u56de","receipt_count":0,"create_date":null,"created_at":"2021-01-16 08:56:51","updated_at":"2021-06-10 11:47:58"},{"id":4165,"company_id":3,"merchant_id":65,"merchant_id_name":"ERP\u56fd\u9645","order_no":"SMAAADQ0001","source":"1","source_name":"\u624b\u52a8\u6dfb\u52a0","mask_code":"C178","list_mode":1,"type":1,"type_name":"\u63d0\u8d27->\u7f51\u70b9","out_user_id":"904566","express_first_no":"","express_second_no":"","status":1,"status_name":"\u5f85\u53d7\u7406","out_status":1,"out_status_name":"\u662f","execution_date":"2021-06-11","batch_no":null,"tour_no":null,"line_id":null,"line_name":null,"out_order_no":"DEVV21904566802","out_group_order_no":null,"exception_label":1,"exception_label_name":"\u6b63\u5e38","place_post_code":"9746TN","exception_stage_name":"","place_house_number":"3-91","driver_name":null,"driver_phone":null,"starting_price":"10.00","transport_price_type":"2","transport_price_type_name":"\u9636\u68af\u56fa\u5b9a\u503c\u8ba1\u7b97\uff08\u56fa\u5b9a\u8d39\u7528+\uff08\u91cd\u91cf\u4ef7\u683c\u6863\uff09*\uff08\u91cc\u7a0b\u4ef7\u683c\u6863\uff09\uff09","receipt_type":1,"receipt_type_name":"\u539f\u5355\u8fd4\u56de","receipt_count":0,"create_date":null,"created_at":"2021-01-16 07:56:51","updated_at":"2021-06-09 19:27:46"},{"id":3495,"company_id":3,"merchant_id":65,"merchant_id_name":"ERP\u56fd\u9645","order_no":"SMAAAKKD0001","source":"1","source_name":"\u624b\u52a8\u6dfb\u52a0","mask_code":"","list_mode":1,"type":1,"type_name":"\u63d0\u8d27->\u7f51\u70b9","out_user_id":"","express_first_no":"","express_second_no":"","status":1,"status_name":"\u5f85\u53d7\u7406","out_status":1,"out_status_name":"\u662f","execution_date":"2021-05-19","batch_no":null,"tour_no":null,"line_id":null,"line_name":null,"out_order_no":"","out_group_order_no":null,"exception_label":1,"exception_label_name":"\u6b63\u5e38","place_post_code":"1183GT","exception_stage_name":"","place_house_number":"13","driver_name":null,"driver_phone":null,"starting_price":"10.00","transport_price_type":"2","transport_price_type_name":"\u9636\u68af\u56fa\u5b9a\u503c\u8ba1\u7b97\uff08\u56fa\u5b9a\u8d39\u7528+\uff08\u91cd\u91cf\u4ef7\u683c\u6863\uff09*\uff08\u91cc\u7a0b\u4ef7\u683c\u6863\uff09\uff09","receipt_type":1,"receipt_type_name":"\u539f\u5355\u8fd4\u56de","receipt_count":0,"create_date":"2021-05-17","created_at":"2021-05-17 14:18:11","updated_at":"2021-05-17 14:18:11"},{"id":3346,"company_id":3,"merchant_id":65,"merchant_id_name":"ERP\u56fd\u9645","order_no":"SMAAAKFU0001","source":"3","source_name":"\u7b2c\u4e09\u65b9","mask_code":"","list_mode":1,"type":1,"type_name":"\u63d0\u8d27->\u7f51\u70b9","out_user_id":"904566","express_first_no":"","express_second_no":"","status":1,"status_name":"\u5f85\u53d7\u7406","out_status":1,"out_status_name":"\u662f","execution_date":"2021-04-19","batch_no":null,"tour_no":null,"line_id":null,"line_name":null,"out_order_no":"152","out_group_order_no":"","exception_label":1,"exception_label_name":"\u6b63\u5e38","place_post_code":"3600","exception_stage_name":"","place_house_number":"2","driver_name":null,"driver_phone":null,"starting_price":"10.00","transport_price_type":"2","transport_price_type_name":"\u9636\u68af\u56fa\u5b9a\u503c\u8ba1\u7b97\uff08\u56fa\u5b9a\u8d39\u7528+\uff08\u91cd\u91cf\u4ef7\u683c\u6863\uff09*\uff08\u91cc\u7a0b\u4ef7\u683c\u6863\uff09\uff09","receipt_type":1,"receipt_type_name":"\u539f\u5355\u8fd4\u56de","receipt_count":0,"create_date":null,"created_at":"2021-04-14 14:28:55","updated_at":"2021-04-14 14:28:55"},{"id":3315,"company_id":3,"merchant_id":65,"merchant_id_name":"ERP\u56fd\u9645","order_no":"SMAAAKEQ0001","source":"3","source_name":"\u7b2c\u4e09\u65b9","mask_code":"","list_mode":1,"type":1,"type_name":"\u63d0\u8d27->\u7f51\u70b9","out_user_id":"904566","express_first_no":"","express_second_no":"","status":1,"status_name":"\u5f85\u53d7\u7406","out_status":1,"out_status_name":"\u662f","execution_date":"2021-04-19","batch_no":null,"tour_no":null,"line_id":null,"line_name":null,"out_order_no":"151","out_group_order_no":"","exception_label":1,"exception_label_name":"\u6b63\u5e38","place_post_code":"3600","exception_stage_name":"","place_house_number":"2","driver_name":null,"driver_phone":null,"starting_price":"10.00","transport_price_type":"2","transport_price_type_name":"\u9636\u68af\u56fa\u5b9a\u503c\u8ba1\u7b97\uff08\u56fa\u5b9a\u8d39\u7528+\uff08\u91cd\u91cf\u4ef7\u683c\u6863\uff09*\uff08\u91cc\u7a0b\u4ef7\u683c\u6863\uff09\uff09","receipt_type":1,"receipt_type_name":"\u539f\u5355\u8fd4\u56de","receipt_count":0,"create_date":null,"created_at":"2021-04-12 11:17:16","updated_at":"2021-04-12 11:17:16"},{"id":3231,"company_id":3,"merchant_id":65,"merchant_id_name":"ERP\u56fd\u9645","order_no":"SMAAAKBK0001","source":"1","source_name":"\u624b\u52a8\u6dfb\u52a0","mask_code":"","list_mode":1,"type":1,"type_name":"\u63d0\u8d27->\u7f51\u70b9","out_user_id":"","express_first_no":"","express_second_no":"","status":1,"status_name":"\u5f85\u53d7\u7406","out_status":1,"out_status_name":"\u662f","execution_date":"2021-04-09","batch_no":null,"tour_no":null,"line_id":null,"line_name":null,"out_order_no":"","out_group_order_no":null,"exception_label":1,"exception_label_name":"\u6b63\u5e38","place_post_code":"6712GD","exception_stage_name":"","place_house_number":"48C","driver_name":null,"driver_phone":null,"starting_price":"10.00","transport_price_type":"2","transport_price_type_name":"\u9636\u68af\u56fa\u5b9a\u503c\u8ba1\u7b97\uff08\u56fa\u5b9a\u8d39\u7528+\uff08\u91cd\u91cf\u4ef7\u683c\u6863\uff09*\uff08\u91cc\u7a0b\u4ef7\u683c\u6863\uff09\uff09","receipt_type":1,"receipt_type_name":"\u539f\u5355\u8fd4\u56de","receipt_count":0,"create_date":"2021-04-08","created_at":"2021-04-08 16:05:56","updated_at":"2021-04-08 16:05:56"},{"id":3180,"company_id":3,"merchant_id":65,"merchant_id_name":"ERP\u56fd\u9645","order_no":"SMAAAJZN0001","source":"1","source_name":"\u624b\u52a8\u6dfb\u52a0","mask_code":"","list_mode":1,"type":1,"type_name":"\u63d0\u8d27->\u7f51\u70b9","out_user_id":"","express_first_no":"","express_second_no":"","status":1,"status_name":"\u5f85\u53d7\u7406","out_status":1,"out_status_name":"\u662f","execution_date":"2021-04-07","batch_no":null,"tour_no":null,"line_id":null,"line_name":null,"out_order_no":"","out_group_order_no":null,"exception_label":1,"exception_label_name":"\u6b63\u5e38","place_post_code":"2153PJ","exception_stage_name":"","place_house_number":"20","driver_name":null,"driver_phone":null,"starting_price":"10.00","transport_price_type":"2","transport_price_type_name":"\u9636\u68af\u56fa\u5b9a\u503c\u8ba1\u7b97\uff08\u56fa\u5b9a\u8d39\u7528+\uff08\u91cd\u91cf\u4ef7\u683c\u6863\uff09*\uff08\u91cc\u7a0b\u4ef7\u683c\u6863\uff09\uff09","receipt_type":1,"receipt_type_name":"\u539f\u5355\u8fd4\u56de","receipt_count":0,"create_date":"2021-04-07","created_at":"2021-04-07 13:03:35","updated_at":"2021-04-07 13:03:35"},{"id":3119,"company_id":3,"merchant_id":65,"merchant_id_name":"ERP\u56fd\u9645","order_no":"SMAAAJXI0001","source":"1","source_name":"\u624b\u52a8\u6dfb\u52a0","mask_code":"","list_mode":1,"type":1,"type_name":"\u63d0\u8d27->\u7f51\u70b9","out_user_id":"","express_first_no":"","express_second_no":"","status":1,"status_name":"\u5f85\u53d7\u7406","out_status":1,"out_status_name":"\u662f","execution_date":"2021-04-01","batch_no":null,"tour_no":null,"line_id":null,"line_name":null,"out_order_no":"","out_group_order_no":null,"exception_label":1,"exception_label_name":"\u6b63\u5e38","place_post_code":"2153PJ","exception_stage_name":"","place_house_number":"20","driver_name":null,"driver_phone":null,"starting_price":"10.00","transport_price_type":"2","transport_price_type_name":"\u9636\u68af\u56fa\u5b9a\u503c\u8ba1\u7b97\uff08\u56fa\u5b9a\u8d39\u7528+\uff08\u91cd\u91cf\u4ef7\u683c\u6863\uff09*\uff08\u91cc\u7a0b\u4ef7\u683c\u6863\uff09\uff09","receipt_type":1,"receipt_type_name":"\u539f\u5355\u8fd4\u56de","receipt_count":0,"create_date":null,"created_at":"2021-03-31 10:28:50","updated_at":"2021-03-31 10:28:51"},{"id":3118,"company_id":3,"merchant_id":65,"merchant_id_name":"ERP\u56fd\u9645","order_no":"SMAAAJXH0001","source":"1","source_name":"\u624b\u52a8\u6dfb\u52a0","mask_code":"","list_mode":1,"type":1,"type_name":"\u63d0\u8d27->\u7f51\u70b9","out_user_id":"","express_first_no":"","express_second_no":"","status":1,"status_name":"\u5f85\u53d7\u7406","out_status":1,"out_status_name":"\u662f","execution_date":"2021-03-31","batch_no":null,"tour_no":null,"line_id":null,"line_name":null,"out_order_no":"","out_group_order_no":null,"exception_label":1,"exception_label_name":"\u6b63\u5e38","place_post_code":"2153PJ","exception_stage_name":"","place_house_number":"20","driver_name":null,"driver_phone":null,"starting_price":"10.00","transport_price_type":"2","transport_price_type_name":"\u9636\u68af\u56fa\u5b9a\u503c\u8ba1\u7b97\uff08\u56fa\u5b9a\u8d39\u7528+\uff08\u91cd\u91cf\u4ef7\u683c\u6863\uff09*\uff08\u91cc\u7a0b\u4ef7\u683c\u6863\uff09\uff09","receipt_type":1,"receipt_type_name":"\u539f\u5355\u8fd4\u56de","receipt_count":0,"create_date":null,"created_at":"2021-03-31 10:27:47","updated_at":"2021-03-31 10:27:47"},{"id":2834,"company_id":3,"merchant_id":65,"merchant_id_name":"ERP\u56fd\u9645","order_no":"SMAAAJPI0001","source":"3","source_name":"\u7b2c\u4e09\u65b9","mask_code":"","list_mode":1,"type":1,"type_name":"\u63d0\u8d27->\u7f51\u70b9","out_user_id":"904566","express_first_no":"","express_second_no":"","status":1,"status_name":"\u5f85\u53d7\u7406","out_status":1,"out_status_name":"\u662f","execution_date":"2021-03-19","batch_no":null,"tour_no":null,"line_id":null,"line_name":null,"out_order_no":"144","out_group_order_no":"","exception_label":1,"exception_label_name":"\u6b63\u5e38","place_post_code":"2642BR","exception_stage_name":"","place_house_number":"45","driver_name":null,"driver_phone":null,"starting_price":"10.00","transport_price_type":"2","transport_price_type_name":"\u9636\u68af\u56fa\u5b9a\u503c\u8ba1\u7b97\uff08\u56fa\u5b9a\u8d39\u7528+\uff08\u91cd\u91cf\u4ef7\u683c\u6863\uff09*\uff08\u91cc\u7a0b\u4ef7\u683c\u6863\uff09\uff09","receipt_type":1,"receipt_type_name":"\u539f\u5355\u8fd4\u56de","receipt_count":0,"create_date":null,"created_at":"2021-03-17 13:43:07","updated_at":"2021-03-17 13:43:08"}],"links":{"first":"http:\/\/tms-api.test:10002\/api\/merchant\/order?page=1","last":"http:\/\/tms-api.test:10002\/api\/merchant\/order?page=4","prev":null,"next":"http:\/\/tms-api.test:10002\/api\/merchant\/order?page=2"},"meta":{"current_page":1,"from":1,"last_page":4,"path":"http:\/\/tms-api.test:10002\/api\/merchant\/order","per_page":"10","to":10,"total":32}},"msg":"successful"}
     */
    Route::get('/', 'OrderController@index');

    //获取详情
    /**
     * @api {get} /merchant/:id 订单详情
     * @apiName 订单详情
     * @apiGroup 05order
     * @apiVersion 1.0.0
     * @apiUse auth
     *
     * @apiParam {Number} id 订单ID
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {String} data.data1    返回数据


     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[],"msg":"successful"}
     */
    Route::get('/{id}', 'OrderController@show');
    //新增
    Route::post('/', 'OrderController@store');
    //修改
    Route::put('/{id}', 'OrderController@update');
    //删除
    Route::delete('/{id}', 'OrderController@destroy');

    //获取继续派送(再次取派)信息
    Route::get('/{id}/again-info', 'OrderController@getAgainInfo');
    //继续派送(再次取派)
    Route::put('/{id}/again', 'OrderController@again');
    //终止派送
    Route::put('/{id}/end', 'OrderController@end');

    //订单追踪
    //Route::get('/{id}/track', 'OrderController@track');
    //批量更新电话日期
    //Route::post('/update-phone-date-list', 'OrderController@updateByApiList');
    //获取订单的运单列表
    //Route::get('/{id}/tracking-order', 'OrderController@getTrackingOrderList');
    //修改订单地址
    //Route::put('/{id}/update-address-date', 'OrderController@updateAddressDate');
    //获取可分配路线日期
    Route::get('/{id}/get-date', 'OrderController@getAbleDateList');
    //通过地址获取可分配的路线日期列表
    Route::get('/get-date', 'OrderController@getAbleDateListByAddress');
    //分配至站点
    Route::put('/{id}/assign-batch', 'OrderController@assignToBatch');
    //从站点移除
    Route::delete('/{id}/remove-batch', 'OrderController@removeFromBatch');
    //批量打印面单
    Route::get('/bill', 'OrderController@orderBillPrint')->name('order.print');

});

