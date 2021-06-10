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
 * @apiDefine 02common 公共接口
 */




/**
 * @api {put} /merchant/timezone 切换时区
 * @apiGroup 02common
 * @apiName 切换时区
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {String} timezone    [必填]时区
 * @apiParamExample {json} Param-Response:
 * {"timezone":"GMT+00:00"}
 * @apiSuccess {Number} code    状态码，200：请求成功
 * @apiSuccess {String} msg   提示信息
 * @apiSuccess {Object} data    返回数据
 *
 * @apiSuccessExample {json} Success-Response:
 * {"code":200,"data":[],"msg":"successful"}
 */
Route::put('/timezone', 'AuthController@updateTimezone');

/**
 * @api {get} /merchant/company 获取公司信息
 * @apiGroup 02common
 * @apiName 获取公司信息
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {Number} code    状态码，200：请求成功
 * @apiSuccess {String} msg   提示信息
 * @apiSuccess {Object} data    返回数据
 * @apiSuccess {String} data.id    公司ID
 * @apiSuccess {String} data.company_code    公司编号
 * @apiSuccess {String} data.email    邮箱
 * @apiSuccess {String} data.name    公司名称
 * @apiSuccess {String} data.contacts    公司联系人
 * @apiSuccess {String} data.phone    公司电话
 * @apiSuccess {String} data.country    公司国家
 * @apiSuccess {String} data.address    公司ag_address
 * @apiSuccess {String} data.lat    返纬度
 * @apiSuccess {String} data.lon    经度
 * @apiSuccess {String} data.created_at    创建时间
 * @apiSuccess {String} data.updated_at    修改时间
 * @apiSuccess {String} data.web_site    企业网址
 * @apiSuccess {String} data.system_name    系统名称
 * @apiSuccess {String} data.logo_url    企业Logo
 * @apiSuccess {String} data.login_logo_url    登录页Logo
 * @apiSuccess {String} data.country_name    国家名称
 *
 * @apiSuccessExample {json} Success-Response:
 * {"code":200,"data":{"id":3,"company_code":"0003","email":"827193289@qq.com","name":"\u7ea2\u5154TMS","contacts":"tms@nle-tech.com","phone":"17533332222","country":"NL","address":"1183GT 199","lat":"52.25347699","lon":"4.62897256","created_at":"2020-03-13 13:00:09","updated_at":"2021-06-08 06:52:08","web_site":"https:\/\/www.iconfont.cn\/manage","system_name":"\u7ea2\u5154","logo_url":"https:\/\/dev-tms.nle-tech.com\/storage\/admin\/images\/3\/driver\/2021060411165760b9ef89b1065.png","login_logo_url":"https:\/\/dev-tms.nle-tech.com\/storage\/admin\/images\/3\/driver\/2021041606031960790c878e268.jpg","country_name":"\u8377\u5170"},"msg":"successful"}
 */
Route::put('company', 'CompanyController@show');
