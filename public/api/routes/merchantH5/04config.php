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
 * @apiDefine 04config 配置
 */

//认证
    /**
     * @api {get} /merchant 个人资料
     * @apiName 个人资料
     * @apiGroup 04config
     * @apiVersion 1.0.0
     * @apiUse auth
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {String} data.id 货主ID
     * @apiSuccess {String} data.company_id 货主公司ID
     * @apiSuccess {String} data.name 货主名称
     * @apiSuccess {String} data.email 货主邮箱
     * @apiSuccess {String} data.country 货主国家
     * @apiSuccess {String} data.merchant_group_id 商户组ID
     * @apiSuccess {String} data.contacter 货主联系人
     * @apiSuccess {String} data.phone 货主电话
     * @apiSuccess {String} data.address 货主ag_address
     * @apiSuccess {String} data.avatar 货主头像
     * @apiSuccess {String} data.status 货主状态1-启用2-禁用
     * @apiSuccess {String} data.timezone 时区
     * @apiSuccess {String} data.settlement_type 结算方式1-票结2-日结3-月结
     * @apiSuccess {String} data.settlement_type_name 结算方式1-票结2-日结3-月结
     * @apiSuccess {String} data.merchant_group_id 货主组ID
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":{"id":65,"company_id":3,"code":"00065","type":2,"name":"ERP\u56fd\u9645","below_warehouse":2,"warehouse_id":null,"short_name":"0","introduction":"Nederlands Express\uff0cNLE\u8377\u5170\u5feb\u9012\uff08\u4ee5\u4e0b\u7b80\u79f0NLE\uff09\u603b\u90e8\u4f4d\u4e8e\u8377\u5170\uff0c\u662f\u8377\u5170\u6700\u65e9\u4e14\u6700\u5927\u4e00\u5bb6\u4ece\u4e8b\u56fd\u9645\u7269\u6d41\u901f\u9012\u3001\u4ed3\u50a8\u8fd0\u8425\u3001\u7a7a\u8fd0\u3001\u8d27\u4ee3\u7b49\u7269\u6d41\u914d\u9001\u89e3\u51b3\u65b9\u6848\u7684\u4e13\u4e1a\u56fd\u9645\u7269\u6d41\u516c\u53f8\u3002","email":"erp@nle-tech.com","country":"NL","settlement_type":1,"merchant_group_id":53,"contacter":"\u8054\u7cfb\u4eba1","phone":"1312121211","address":"\u8be6\u7ec6\u5730\u57401","avatar":"\u5934\u50cf","invoice_title":"1","taxpayer_code":"0000-00-00","bank":"0000-00-00","bank_account":"0000-00-00","invoice_address":"0000-00-00","invoice_email":"0000-00-00","status":1,"created_at":"2020-07-14 16:45:36","updated_at":"2021-06-09 12:54:46","company_config":{"id":3,"company_id":3,"line_rule":1,"show_type":1,"address_template_id":1,"stock_exception_verify":2,"weight_unit":2,"currency_unit":3,"volume_unit":2,"map":"google","created_at":"2020-03-13 12:00:09","updated_at":"2021-06-08 06:14:09","scheduling_rule":1},"settlement_type_name":"\u7968\u7ed3","status_name":"\u542f\u7528","type_name":"\u8d27\u4e3b","country_name":"\u8377\u5170","additional_status":1,"advance_days":0,"appointment_days":10,"delay_time":0,"pickup_count":1,"pie_count":2,"merchant_group":{"id":53,"company_id":3,"name":"ERP\u56fd\u9645\u7ec4","transport_price_id":67,"count":3,"is_default":2,"additional_status":1,"advance_days":0,"appointment_days":10,"delay_time":0,"pickup_count":1,"pie_count":2,"created_at":"2020-12-28 03:26:41","updated_at":"2021-03-18 09:00:48","additional_status_name":"\u5f00\u542f"}},"msg":"successful"}
     */
    Route::get('me', 'AuthController@me');


    //登出
    Route::post('logout', 'AuthController@logout');


    /**
     * @api {put} /merchant_h5/password 修改密码
     * @apiName 修改密码
     * @apiGroup 04config
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiParam {String} origin_password    [必填]原密码
     * @apiParam {String} new_password    [必填]新密码
     * @apiParam {String} new_confirm_password    [必填]重复新密码
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     *
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[],"msg":"successful"}
     */
    Route::put('password', 'AuthController@updatePassword');


    /**
     * @api {put} /merchant 修改个人资料
     * @apiName 修改个人资料
     * @apiGroup 04config
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiParam {String} name    [必填]商户名称
     * @apiParam {String} contacter    [必填]商户联系人
     * @apiParam {String} phone    [必填]商户电话
     * @apiParam {String} address    [必填]商户ag_address
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     *
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[],"msg":"successful"}
     */
    //修改个人信息
    Route::put('', 'MerchantController@update');



