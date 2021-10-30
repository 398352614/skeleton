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
 * @apiDefine 01auth 用户认证
 */

/**
 * @api {post} /merchant_h5/login 登录
 * @apiName 登录
 * @apiGroup 01auth
 * @apiVersion 1.0.0
 * @apiUse auth
 *
 * @apiParam {String} username 用户名
 * @apiParam {String} password 密码
 *
 * @apiSuccess {Number} code    状态码，200：请求成功
 * @apiSuccess {String} msg   提示信息
 * @apiSuccess {Object} data    返回数据
 * @apiSuccess {String} data.username   用户名
 * @apiSuccess {String} data.company_id   公司ID
 * @apiSuccess {String} data.access_token   令牌
 * @apiSuccess {String} data.token_type   令牌类型
 * @apiSuccess {String} data.expires_in   令牌过期时间（秒）
 * @apiSuccess {Object} data.company_config   公司配置
 * @apiSuccess {String} data.company_config.id   公司配置ID
 * @apiSuccess {String} data.company_config.company_code   公司编号
 * @apiSuccess {String} data.company_config.name   公司名称
 * @apiSuccess {String} data.company_config.line_rule   线路规则1-邮编2-区域
 * @apiSuccess {String} data.company_config.show_type   展示方式1-全部展示2-按线路规则展示
 * @apiSuccess {String} data.company_config.address_template_id   地址模板ID
 * @apiSuccess {String} data.company_config.stock_exception_verify   是否开启入库异常审核1-开启2-关闭
 * @apiSuccess {String} data.company_config.weight_unit   重量单位
 * @apiSuccess {String} data.company_config.currency_unit   货币单位
 * @apiSuccess {String} data.company_config.volume_unit   体积单位
 * @apiSuccess {String} data.company_config.map   地图引擎
 * @apiSuccess {String} data.company_config.country   公司国家
 * @apiSuccess {String} data.company_config.country_en_name   公司国家英文名
 * @apiSuccess {String} data.company_config.country_cn_name   公司国家中文名
 * @apiSuccess {String} data.company_config.weight_unit_symbol   重量单位标志
 * @apiSuccess {String} data.company_config.currency_unit_symbol   货币单位标志
 * @apiSuccess {String} data.company_config.volume_unit_symbol   体积单位标志
 * @apiSuccessExample {json} Success-Response:
 * {"code":200,"data":{"username":"TEST-TMS","company_id":3,"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC90bXMtYXBpLnRlc3Q6MTAwMDJcL2FwaVwvbWVyY2hhbnRcL2xvZ2luIiwiaWF0IjoxNjIzMzA3NTQ3LCJleHAiOjE2MjQ1MTcxNDcsIm5iZiI6MTYyMzMwNzU0NywianRpIjoiRElndDJiV3I1QXpjbXpZZSIsInN1YiI6MTI1LCJwcnYiOiI5M2JkY2M1OGRkMDFjZTM2ZWM1NmUzMmI1YmI1ODBkODMwMzJmZDE4Iiwicm9sZSI6Im1lcmNoYW50In0.dzg39jx5CtudehicegkNXMPKjaVyK_-db1VXmyaEavI","token_type":"bearer","expires_in":1209600,"company_config":{"id":3,"company_code":"0003","name":"\u7ea2\u5154TMS","company_id":3,"line_rule":1,"show_type":1,"address_template_id":1,"stock_exception_verify":2,"weight_unit":2,"currency_unit":3,"volume_unit":2,"map":"google","created_at":"2020-03-13 12:00:09","updated_at":"2021-06-08 06:14:09","scheduling_rule":1,"weight_unit_symbol":"lb","currency_unit_symbol":"\u20ac","volume_unit_symbol":"m\u00b3","country":"NL","country_en_name":"Netherlands","country_cn_name":"\u8377\u5170"}},"msg":"successful"} */
//登录
Route::post('login', 'AuthController@login');

/**
 * @api {post} /merchant_h5/password/code 重置密码验证码
 * @apiName 重置密码验证码
 * @apiGroup 01auth
 * @apiVersion 1.0.0
 * @apiUse auth
 *
 * @apiParam {String} email 邮编
 *
 * @apiSuccess {Number} code    状态码，200：请求成功
 * @apiSuccess {String} msg   提示信息
 * @apiSuccess {Object} data    返回数据
 * @apiSuccessExample {json} Success-Response:
 * {"code":200,"data":"\u9a8c\u8bc1\u7801\u53d1\u9001\u6210\u529f","msg":"successful"}
 */
//修改密码
Route::post('password/code', 'RegisterController@applyOfReset');

//修改密码验证码
/**
 * @api {put} /merchant_h5/password/reset 重置密码
 * @apiName 重置密码
 * @apiGroup 01auth
 * @apiVersion 1.0.0
 * @apiUse auth
 *
 * @apiParam {String} email 邮编
 * @apiParam {String} code 验证码
 * @apiParam {String} new_password 新密码
 * @apiParam {String} confirm_new_password 重复新密码
 *
 * @apiSuccess {Number} code    状态码，200：请求成功
 * @apiSuccess {String} msg   提示信息
 * @apiSuccess {Object} data    返回数据
 * @apiSuccessExample {json} Success-Response:
 * {"code":200,"data":[],"msg":"successful"}
 */
Route::put('password/reset', 'RegisterController@resetPassword');

/**
 * @api {post} /merchant_h5/register 注册
 * @apiName 注册
 * @apiGroup 00
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} email 邮箱
 * @apiParam {string} password 密码
 * @apiParam {string} confirm_password 重复密码
 * @apiParam {string} code 注册验证码
 * @apiParam {string} name 公司名称
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": true,
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /merchant_h5/register/apply 注册验证码
 * @apiName 注册-验证码
 * @apiGroup 00
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} email 邮箱
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": "验证码发送成功",
 * "msg": "successful"
 * }
 */



