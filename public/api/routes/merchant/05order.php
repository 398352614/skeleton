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
     * @api {get} /merchant/{id} 订单详情
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
     * @apiSuccess {String} data.id    订单ID
     * @apiSuccess {String} data.company_id 公司ID
     * @apiSuccess {String} data.merchant_id 商户ID
     * @apiSuccess {String} data.merchant_id_name 商户名称
     * @apiSuccess {String} data.order_no 订单号
     * @apiSuccess {String} data.execution_date 取派日期
     * @apiSuccess {String} data.create_date 开单日期
     * @apiSuccess {String} data.out_order_no 外部订单号
     * @apiSuccess {String} data.mask_code 掩码
     * @apiSuccess {String} data.source 来源
     * @apiSuccess {String} data.source_name 来源名称
     * @apiSuccess {String} data.type 类型:1-取2-派3-取派
     * @apiSuccess {String} data.out_user_id 外部客户ID
     * @apiSuccess {String} data.nature 性质:1-包裹2-材料3-文件4-增值服务5-其他
     * @apiSuccess {String} data.settlement_type 结算类型1-寄付2-到付
     * @apiSuccess {String} data.settlement_amount 结算金额
     * @apiSuccess {String} data.replace_amount 代收货款
     * @apiSuccess {String} data.status 状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站
     * @apiSuccess {String} data.second_place_fullname 收件人姓名
     * @apiSuccess {String} data.second_place_phone 收件人电话
     * @apiSuccess {String} data.second_place_country 收件人国家
     * @apiSuccess {String} data.second_place_country_name 收件人国家名称
     * @apiSuccess {String} data.second_place_post_code 收件人邮编
     * @apiSuccess {String} data.second_place_house_number 收件人门牌号
     * @apiSuccess {String} data.second_place_city 收件人城市
     * @apiSuccess {String} data.second_place_street 收件人街道
     * @apiSuccess {String} data.second_place_address 收件人详细地址
     * @apiSuccess {String} data.place_fullname 发件人姓名
     * @apiSuccess {String} data.place_phone 发件人电话
     * @apiSuccess {String} data.place_country 发件人国家
     * @apiSuccess {String} data.place_country_name 发件人国家名称
     * @apiSuccess {String} data.place_province 发件人省份
     * @apiSuccess {String} data.place_post_code 发件人邮编
     * @apiSuccess {String} data.place_house_number 发件人门牌号
     * @apiSuccess {String} data.place_city 发件人城市
     * @apiSuccess {String} data.place_district 发件人区县
     * @apiSuccess {String} data.place_street 发件人街道
     * @apiSuccess {String} data.place_address 发件人详细地址
     * @apiSuccess {String} data.special_remark 特殊事项
     * @apiSuccess {String} data.remark 备注
     * @apiSuccess {String} data.starting_price 起步价
     * @apiSuccess {String} data.transport_price_type 运价方案ID
     * @apiSuccess {String} data.transport_price_type_name 运价方案名称
     * @apiSuccess {String} data.receipt_type 回单要求
     * @apiSuccess {String} data.receipt_type_name 回单要求名称
     * @apiSuccess {String} data.receipt_count 回单数量
     * @apiSuccess {String} data.created_at 创建时间
     * @apiSuccess {String} data.updated_at 修改时间
     * @apiSuccess {Object} data.package_list 包裹列表
     * @apiSuccess {String} data.package_list.id 包裹ID
     * @apiSuccess {String} data.package_list.company_id 公司ID
     * @apiSuccess {String} data.package_list.merchant_id 货主ID
     * @apiSuccess {String} data.package_list.order_no 订单编号
     * @apiSuccess {String} data.package_list.tracking_order_no 运单号
     * @apiSuccess {String} data.package_list.execution_date 取件日期
     * @apiSuccess {String} data.package_list.second_execution_date 派件日期
     * @apiSuccess {String} data.package_list.expiration_date 有效日期
     * @apiSuccess {String} data.package_list.expiration_status 超期状态1-未超期2-已超期3-超期已处理
     * @apiSuccess {String} data.package_list.type 类型1-取2-派
     * @apiSuccess {String} data.package_list.name 包裹名称
     * @apiSuccess {String} data.package_list.express_first_no 快递单号1
     * @apiSuccess {String} data.package_list.express_second_no 快递单号2
     * @apiSuccess {String} data.package_list.feature_logo 特性标志
     * @apiSuccess {String} data.package_list.feature 特性
     * @apiSuccess {String} data.package_list.out_order_no 外部标识
     * @apiSuccess {String} data.package_list.weight 重量
     * @apiSuccess {String} data.package_list.size 重量
     * @apiSuccess {String} data.package_list.actual_weight 实际重量
     * @apiSuccess {String} data.package_list.expect_quantity 预计数量
     * @apiSuccess {String} data.package_list.actual_quantity 实际数量
     * @apiSuccess {String} data.package_list.status 状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站
     * @apiSuccess {String} data.package_list.stage 阶段1-取件2-中转3-派件
     * @apiSuccess {String} data.package_list.sticker_no 贴单号
     * @apiSuccess {String} data.package_list.settlement_amount 结算金额
     * @apiSuccess {String} data.package_list.count_settlement_amount 估算运费
     * @apiSuccess {String} data.package_list.sticker_amount 贴单费用
     * @apiSuccess {String} data.package_list.delivery_amount 提货费用
     * @apiSuccess {String} data.package_list.remark 备注
     * @apiSuccess {String} data.package_list.is_auth 是否需要身份验证1-是2-否
     * @apiSuccess {String} data.package_list.auth_fullname 身份人姓名
     * @apiSuccess {String} data.package_list.auth_birth_date 身份人出身年月
     * @apiSuccess {String} data.package_list.created_at 创建时间
     * @apiSuccess {String} data.package_list.updated_at 修改时间
     * @apiSuccess {String} data.package_list.status_name 状态名称
     * @apiSuccess {String} data.package_list.type_name 类型名称
     * @apiSuccess {Object} data.material_list 材料列表
     * @apiSuccess {String} data.material_list.id 材料ID
     * @apiSuccess {String} data.material_list.company_id 公司ID
     * @apiSuccess {String} data.material_list.merchant_id 商户ID
     * @apiSuccess {String} data.material_list.tour_no 取件线路编号
     * @apiSuccess {String} data.material_list.batch_no 站点编号
     * @apiSuccess {String} data.material_list.tracking_order_no 运单号
     * @apiSuccess {String} data.material_list.order_no 订单编号
     * @apiSuccess {String} data.material_list.execution_date 取派日期
     * @apiSuccess {String} data.material_list.name 材料名称
     * @apiSuccess {String} data.material_list.code 材料代码
     * @apiSuccess {String} data.material_list.out_order_no 外部标识
     * @apiSuccess {String} data.material_list.expect_quantity 预计数量
     * @apiSuccess {String} data.material_list.actual_quantity 实际数量
     * @apiSuccess {String} data.material_list.pack_type 包装类型
     * @apiSuccess {String} data.material_list.type 类型
     * @apiSuccess {String} data.material_list.weight 重量
     * @apiSuccess {String} data.material_list.size 体积
     * @apiSuccess {String} data.material_list.remark 备注
     * @apiSuccess {String} data.material_list.created_at 创建时间
     * @apiSuccess {String} data.material_list.updated_at 修改时间
     * @apiSuccess {String} data.material_list.type_name 类型名称
     * @apiSuccess {Object} data.amount_list 费用列表
     * @apiSuccess {String} data.amount_list.id 费用ID
     * @apiSuccess {String} data.amount_list.company_id 公司ID
     * @apiSuccess {String} data.amount_list.order_no 订单号
     * @apiSuccess {String} data.amount_list.expect_amount 预计金额
     * @apiSuccess {String} data.amount_list.actual_amount 实际金额
     * @apiSuccess {String} data.amount_list.type 运费类型
     * @apiSuccess {String} data.amount_list.remark 备注
     * @apiSuccess {String} data.amount_list.in_total 计入总费用1-计入2-不计入
     * @apiSuccess {String} data.amount_list.status 状态1-预产生2-已产生3-已支付4-已取消
     * @apiSuccess {String} data.amount_list.created_at 创建时间
     * @apiSuccess {String} data.amount_list.updated_at 修改时间
     * @apiSuccess {String} data.amount_list.type_name 类型名称
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":{"id":4206,"company_id":3,"merchant_id":3,"merchant_id_name":"\u6b27\u4e9a\u5546\u57ce","order_no":"SMAAAEL0001","execution_date":"2021-06-10","out_order_no":"","create_date":"2021-06-09","mask_code":"","source":"2","source_name":"\u6279\u91cf\u5bfc\u5165","type":3,"out_user_id":"12036","nature":1,"settlement_type":0,"settlement_amount":"10.00","replace_amount":"0.00","status":1,"second_place_fullname":"EVA","second_place_phone":"636985217","second_place_country":"","second_place_country_name":null,"second_place_post_code":"9746TN","second_place_house_number":"3-91","second_place_city":"","second_place_street":"","second_place_address":"9746TN 3-91","place_fullname":"test","place_phone":"123654789","place_country":"NL","place_country_name":"\u8377\u5170","place_province":"","place_post_code":"1183GT","place_house_number":"1","place_city":"","place_district":"","place_street":"","place_address":"1 1183GT","special_remark":"","remark":"","unique_code":"","starting_price":"10.00","transport_price_type":"1","transport_price_type_name":"\u9636\u68af\u4e58\u79ef\u503c\u8ba1\u7b97\uff08\u56fa\u5b9a\u8d39\u7528+\uff08\u6bcf\u5355\u4f4d\u91cd\u91cf\u4ef7\u683c*\u91cd\u91cf\u4ef7\u683c\uff09*\uff08\u6bcf\u5355\u4f4d\u91cc\u7a0b\u4ef7\u683c*\u91cc\u7a0b\u4ef7\u683c\uff09\uff09","receipt_type":0,"receipt_type_name":null,"receipt_count":0,"created_at":"2021-06-10 10:03:51","updated_at":"2021-06-10 10:03:51","package_list":[{"id":5080,"company_id":3,"merchant_id":3,"order_no":"SMAAAEL0001","tracking_order_no":"YD00030005577","execution_date":"2021-06-10","second_execution_date":"2021-06-10","expiration_date":null,"expiration_status":1,"type":3,"name":"","express_first_no":"10181","express_second_no":"","feature_logo":"","feature":1,"out_order_no":"","weight":"0.00","size":1,"actual_weight":"","expect_quantity":1,"actual_quantity":0,"status":1,"stage":1,"sticker_no":"","settlement_amount":"0.00","count_settlement_amount":"0.00","sticker_amount":null,"delivery_amount":null,"remark":"","is_auth":2,"auth_fullname":"","auth_birth_date":null,"created_at":"2021-06-10 10:03:51","updated_at":"2021-06-10 10:03:51","status_name":"\u672a\u53d6\u6d3e","type_name":"\u63d0\u8d27->\u7f51\u70b9->\u914d\u9001"}],"material_list":[{"id":831,"company_id":3,"merchant_id":3,"tour_no":"","batch_no":"","tracking_order_no":"YD00030005577","order_no":"SMAAAEL0001","execution_date":"2021-06-10","name":"","code":"102","out_order_no":"","expect_quantity":1,"actual_quantity":0,"pack_type":1,"type":1,"weight":"1.00","size":"1.00","unit_price":"1.00","remark":"","created_at":"2021-06-10 10:03:51","updated_at":"2021-06-10 10:03:51","type_name":"\u5305\u88c5\u6750\u6599","pack_type_name":"\u7eb8\u7bb1"}],"amount_list":[{"id":2005,"company_id":3,"order_no":"SMAAAEL0001","expect_amount":"0.00","actual_amount":"0.00","type":0,"remark":"","in_total":1,"status":2,"created_at":"2021-06-10 10:03:51","updated_at":"2021-06-10 10:03:51","type_name":null},{"id":2006,"company_id":3,"order_no":"SMAAAEL0001","expect_amount":"0.00","actual_amount":"0.00","type":1,"remark":"","in_total":1,"status":2,"created_at":"2021-06-10 10:03:51","updated_at":"2021-06-10 10:03:51","type_name":"\u57fa\u7840\u8fd0\u8d39"},{"id":2007,"company_id":3,"order_no":"SMAAAEL0001","expect_amount":"0.00","actual_amount":"0.00","type":2,"remark":"","in_total":1,"status":2,"created_at":"2021-06-10 10:03:51","updated_at":"2021-06-10 10:03:51","type_name":"\u8d27\u7269\u4ef7\u503c"},{"id":2008,"company_id":3,"order_no":"SMAAAEL0001","expect_amount":"0.00","actual_amount":"0.00","type":3,"remark":"","in_total":1,"status":2,"created_at":"2021-06-10 10:03:51","updated_at":"2021-06-10 10:03:51","type_name":"\u4fdd\u4ef7\u8d39"},{"id":2009,"company_id":3,"order_no":"SMAAAEL0001","expect_amount":"0.00","actual_amount":"0.00","type":4,"remark":"","in_total":1,"status":2,"created_at":"2021-06-10 10:03:51","updated_at":"2021-06-10 10:03:51","type_name":"\u5305\u88c5\u8d39"},{"id":2010,"company_id":3,"order_no":"SMAAAEL0001","expect_amount":"0.00","actual_amount":"0.00","type":5,"remark":"","in_total":1,"status":2,"created_at":"2021-06-10 10:03:51","updated_at":"2021-06-10 10:03:51","type_name":"\u9001\u8d27\u8d39"},{"id":2011,"company_id":3,"order_no":"SMAAAEL0001","expect_amount":"0.00","actual_amount":"0.00","type":6,"remark":"","in_total":1,"status":2,"created_at":"2021-06-10 10:03:51","updated_at":"2021-06-10 10:03:51","type_name":"\u4e0a\u697c\u8d39"},{"id":2012,"company_id":3,"order_no":"SMAAAEL0001","expect_amount":"0.00","actual_amount":"0.00","type":7,"remark":"","in_total":1,"status":2,"created_at":"2021-06-10 10:03:51","updated_at":"2021-06-10 10:03:51","type_name":"\u63a5\u8d27\u8d39"},{"id":2013,"company_id":3,"order_no":"SMAAAEL0001","expect_amount":"0.00","actual_amount":"0.00","type":8,"remark":"","in_total":1,"status":2,"created_at":"2021-06-10 10:03:51","updated_at":"2021-06-10 10:03:51","type_name":"\u88c5\u5378\u8d39"},{"id":2014,"company_id":3,"order_no":"SMAAAEL0001","expect_amount":"0.00","actual_amount":"0.00","type":9,"remark":"","in_total":1,"status":2,"created_at":"2021-06-10 10:03:51","updated_at":"2021-06-10 10:03:51","type_name":"\u5176\u4ed6\u8d39\u7528"},{"id":2015,"company_id":3,"order_no":"SMAAAEL0001","expect_amount":"0.00","actual_amount":"0.00","type":10,"remark":"","in_total":1,"status":2,"created_at":"2021-06-10 10:03:51","updated_at":"2021-06-10 10:03:51","type_name":"\u4ee3\u6536\u8d27\u6b3e"},{"id":2016,"company_id":3,"order_no":"SMAAAEL0001","expect_amount":"0.00","actual_amount":"0.00","type":11,"remark":"","in_total":1,"status":2,"created_at":"2021-06-10 10:03:51","updated_at":"2021-06-10 10:03:51","type_name":"\u8d27\u6b3e\u624b\u7eed\u8d39"}]},"msg":"successful"}
     */
    Route::get('/{id}', 'OrderController@show');

    /**
     * @api {post} /merchant/order 订单新增
     * @apiName 订单新增
     * @apiGroup 05order
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiParam {String} order_no 订单号
     * @apiParam {String} execution_date 取派日期
     * @apiParam {String} second_execution_date 取派日期
     * @apiParam {String} create_date 开单日期
     * @apiParam {String} out_order_no 外部订单号
     * @apiParam {String} mask_code 掩码
     * @apiParam {String} source 来源
     * @apiParam {String} source_name 来源名称
     * @apiParam {String} type 类型:1-取2-派3-取派
     * @apiParam {String} out_user_id 外部客户ID
     * @apiParam {String} nature 性质:1-包裹2-材料3-文件4-增值服务5-其他
     * @apiParam {String} settlement_type 结算类型1-寄付2-到付
     * @apiParam {String} settlement_amount 结算金额
     * @apiParam {String} replace_amount 代收货款
     * @apiParam {String} status 状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站
     * @apiParam {String} second_place_fullname 收件人姓名
     * @apiParam {String} second_place_phone 收件人电话
     * @apiParam {String} second_place_country 收件人国家
     * @apiParam {String} second_place_country_name 收件人国家名称
     * @apiParam {String} second_place_post_code 收件人邮编
     * @apiParam {String} second_place_house_number 收件人门牌号
     * @apiParam {String} second_place_city 收件人城市
     * @apiParam {String} second_place_street 收件人街道
     * @apiParam {String} second_place_address 收件人详细地址
     * @apiParam {String} place_fullname 发件人姓名
     * @apiParam {String} place_phone 发件人电话
     * @apiParam {String} place_country 发件人国家
     * @apiParam {String} place_country_name 发件人国家名称
     * @apiParam {String} place_province 发件人省份
     * @apiParam {String} place_post_code 发件人邮编
     * @apiParam {String} place_house_number 发件人门牌号
     * @apiParam {String} place_city 发件人城市
     * @apiParam {String} place_district 发件人区县
     * @apiParam {String} place_street 发件人街道
     * @apiParam {String} place_address 发件人详细地址
     * @apiParam {String} special_remark 特殊事项
     * @apiParam {String} remark 备注
     * @apiParam {String} starting_price 起步价
     * @apiParam {String} transport_price_type 运价方案ID
     * @apiParam {String} receipt_type 回单要求
     * @apiParam {String} receipt_type_name 回单要求名称
     * @apiParam {String} receipt_count 回单数量
     * @apiParam {Object} package_list 包裹列表
     * @apiParam {String} package_list.expiration_date 有效日期
     * @apiParam {String} package_list.name 包裹名称
     * @apiParam {String} package_list.express_first_no 快递单号1
     * @apiParam {String} package_list.express_second_no 快递单号2
     * @apiParam {String} package_list.feature_logo 特性标志
     * @apiParam {String} package_list.out_order_no 外部标识
     * @apiParam {String} package_list.weight 重量
     * @apiParam {String} package_list.size 重量
     * @apiParam {String} package_list.actual_weight 实际重量
     * @apiParam {String} package_list.expect_quantity 预计数量
     * @apiParam {String} package_list.actual_quantity 实际数量
     * @apiParam {String} package_list.sticker_no 贴单号
     * @apiParam {String} package_list.settlement_amount 结算金额
     * @apiParam {String} package_list.count_settlement_amount 估算运费
     * @apiParam {String} package_list.sticker_amount 贴单费用
     * @apiParam {String} package_list.delivery_amount 提货费用
     * @apiParam {String} package_list.remark 备注
     * @apiParam {String} package_list.is_auth 是否需要身份验证1-是2-否
     * @apiParam {String} package_list.auth_fullname 身份人姓名
     * @apiParam {String} package_list.auth_birth_date 身份人出身年月
     * @apiParam {Object} material_list 材料列表
     * @apiParam {String} material_list.execution_date 取派日期
     * @apiParam {String} material_list.name 材料名称
     * @apiParam {String} material_list.code 材料代码
     * @apiParam {String} material_list.out_order_no 外部标识
     * @apiParam {String} material_list.expect_quantity 预计数量
     * @apiParam {String} material_list.actual_quantity 实际数量
     * @apiParam {String} material_list.pack_type 包装类型
     * @apiParam {String} material_list.type 类型
     * @apiParam {String} material_list.weight 重量
     * @apiParam {String} material_list.size 体积
     * @apiParam {String} material_list.remark 备注
     * @apiParam {Object} amount_list 费用列表
     * @apiParam {String} amount_list.id 费用ID
     * @apiParam {String} amount_list.expect_amount 预计金额
     * @apiParam {String} amount_list.actual_amount 实际金额
     * @apiParam {String} amount_list.type 运费类型
     * @apiParam {String} amount_list.remark 备注
     *
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {String} data.id    ID
     * @apiSuccess {String} data.order_no    订单号
     * @apiSuccess {String} data.out_order_no    外部订单号
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":{"id":4207,"order_no":"SMAAAEM0001","out_order_no":"DEVV21904566802"},"msg":"successful"}
     */
    //新增
    Route::post('/', 'OrderController@store');

    /**
     * @api {put} /merchant/order/{id} 订单修改
     * @apiName 订单修改
     * @apiGroup 05order
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiParam {String} id 订单ID
     * @apiParam {String} order_no 订单号
     * @apiParam {String} execution_date 取派日期
     * @apiParam {String} second_execution_date 取派日期
     * @apiParam {String} create_date 开单日期
     * @apiParam {String} out_order_no 外部订单号
     * @apiParam {String} mask_code 掩码
     * @apiParam {String} source 来源
     * @apiParam {String} source_name 来源名称
     * @apiParam {String} type 类型:1-取2-派3-取派
     * @apiParam {String} out_user_id 外部客户ID
     * @apiParam {String} nature 性质:1-包裹2-材料3-文件4-增值服务5-其他
     * @apiParam {String} settlement_type 结算类型1-寄付2-到付
     * @apiParam {String} settlement_amount 结算金额
     * @apiParam {String} replace_amount 代收货款
     * @apiParam {String} status 状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站
     * @apiParam {String} second_place_fullname 收件人姓名
     * @apiParam {String} second_place_phone 收件人电话
     * @apiParam {String} second_place_country 收件人国家
     * @apiParam {String} second_place_country_name 收件人国家名称
     * @apiParam {String} second_place_post_code 收件人邮编
     * @apiParam {String} second_place_house_number 收件人门牌号
     * @apiParam {String} second_place_city 收件人城市
     * @apiParam {String} second_place_street 收件人街道
     * @apiParam {String} second_place_address 收件人详细地址
     * @apiParam {String} place_fullname 发件人姓名
     * @apiParam {String} place_phone 发件人电话
     * @apiParam {String} place_country 发件人国家
     * @apiParam {String} place_country_name 发件人国家名称
     * @apiParam {String} place_province 发件人省份
     * @apiParam {String} place_post_code 发件人邮编
     * @apiParam {String} place_house_number 发件人门牌号
     * @apiParam {String} place_city 发件人城市
     * @apiParam {String} place_district 发件人区县
     * @apiParam {String} place_street 发件人街道
     * @apiParam {String} place_address 发件人详细地址
     * @apiParam {String} special_remark 特殊事项
     * @apiParam {String} remark 备注
     * @apiParam {String} starting_price 起步价
     * @apiParam {String} transport_price_type 运价方案ID
     * @apiParam {String} receipt_type 回单要求
     * @apiParam {String} receipt_type_name 回单要求名称
     * @apiParam {String} receipt_count 回单数量
     * @apiParam {Object} package_list 包裹列表
     * @apiParam {String} package_list.expiration_date 有效日期
     * @apiParam {String} package_list.name 包裹名称
     * @apiParam {String} package_list.express_first_no 快递单号1
     * @apiParam {String} package_list.express_second_no 快递单号2
     * @apiParam {String} package_list.feature_logo 特性标志
     * @apiParam {String} package_list.out_order_no 外部标识
     * @apiParam {String} package_list.weight 重量
     * @apiParam {String} package_list.size 重量
     * @apiParam {String} package_list.actual_weight 实际重量
     * @apiParam {String} package_list.expect_quantity 预计数量
     * @apiParam {String} package_list.actual_quantity 实际数量
     * @apiParam {String} package_list.sticker_no 贴单号
     * @apiParam {String} package_list.settlement_amount 结算金额
     * @apiParam {String} package_list.count_settlement_amount 估算运费
     * @apiParam {String} package_list.sticker_amount 贴单费用
     * @apiParam {String} package_list.delivery_amount 提货费用
     * @apiParam {String} package_list.remark 备注
     * @apiParam {String} package_list.is_auth 是否需要身份验证1-是2-否
     * @apiParam {String} package_list.auth_fullname 身份人姓名
     * @apiParam {String} package_list.auth_birth_date 身份人出身年月
     * @apiParam {Object} material_list 材料列表
     * @apiParam {String} material_list.execution_date 取派日期
     * @apiParam {String} material_list.name 材料名称
     * @apiParam {String} material_list.code 材料代码
     * @apiParam {String} material_list.out_order_no 外部标识
     * @apiParam {String} material_list.expect_quantity 预计数量
     * @apiParam {String} material_list.actual_quantity 实际数量
     * @apiParam {String} material_list.pack_type 包装类型
     * @apiParam {String} material_list.type 类型
     * @apiParam {String} material_list.weight 重量
     * @apiParam {String} material_list.size 体积
     * @apiParam {String} material_list.remark 备注
     * @apiParam {Object} amount_list 费用列表
     * @apiParam {String} amount_list.id 费用ID
     * @apiParam {String} amount_list.expect_amount 预计金额
     * @apiParam {String} amount_list.actual_amount 实际金额
     * @apiParam {String} amount_list.type 运费类型
     * @apiParam {String} amount_list.remark 备注
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据

     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[],"msg":"successful"}
     */
    //修改
    Route::put('/{id}', 'OrderController@update');

    /**
     * @api {delete} /merchant/{id} 订单删除
     * @apiName 订单删除
     * @apiGroup 05order
     * @apiVersion 1.0.0
     * @apiUse auth
     *
     * @apiParam {String} id 订单ID
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[],"msg":"successful"}
     */
    //删除
    Route::delete('/{id}', 'OrderController@destroy');

//    //获取继续派送(再次取派)信息
//    Route::get('/{id}/again-info', 'OrderController@getAgainInfo');
//    //继续派送(再次取派)
//    Route::put('/{id}/again', 'OrderController@again');
//    //终止派送
//    Route::put('/{id}/end', 'OrderController@end');

    /**
     * @api {delete} /merchant/{id} 通过订单获取可选日期
     * @apiName 通过订单获取可选日期
     * @apiGroup 05order
     * @apiVersion 1.0.0
     * @apiUse auth
     *
     * @apiParam {String} id 订单ID
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    日期数组
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":["2021-06-11","2021-06-13","2021-06-16","2021-06-18","2021-06-20"],"msg":"successful"}
     */
    //获取可分配路线日期
    Route::get('/{id}/get-date', 'OrderController@getAbleDateList');

    /**
     * @api {delete} /merchant/{id} 订单删除
     * @apiName 订单删除
     * @apiGroup 05order
     * @apiVersion 1.0.0
     * @apiUse auth
     *
     * @apiParam {String} id 订单ID
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[],"msg":"successful"}
     */
    //通过地址获取可分配的路线日期列表
    Route::get('/get-date', 'OrderController@getAbleDateListByAddress');
    //分配至站点
    Route::put('/{id}/assign-batch', 'OrderController@assignToBatch');
    //从站点移除
    Route::delete('/{id}/remove-batch', 'OrderController@removeFromBatch');
    //批量打印面单
    Route::get('/bill', 'OrderController@orderBillPrint')->name('order.print');

});

