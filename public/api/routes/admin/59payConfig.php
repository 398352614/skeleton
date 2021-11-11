<?php
/**
 * @apiDefine 59 支付配置
 */

/**
 * @api {get} /admin/pay-config 支付详情
 * @apiName 支付详情
 * @apiGroup 59
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 地址ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.waiting_time 等待时间（秒）
 * @apiSuccess {string} data.paypal_sandbox_mode 沙盒模式1-开启2-关闭
 * @apiSuccess {string} data.paypal_client_id paypal应用ID
 * @apiSuccess {string} data.paypal_client_secret paypal应用秘钥
 * @apiSuccess {string} data.paypal_status paypal状态1-启用2-禁用
 * @apiSuccess {string} data.paypal_status_name 状态名称
 * @apiSuccess {string} data.paypal_sandbox_mode_name 沙盒模式名称
 * @apiSuccess {string} data.waiting_time_human 等待时间（可读）
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 3,
 * "company_id": 3,
 * "waiting_time": 3600,
 * "paypal_sandbox_mode": 1,
 * "paypal_client_id": "2",
 * "paypal_client_secret": "123",
 * "paypal_status": 1,
 * "created_at": "2021-11-09 10:35:46",
 * "updated_at": "2021-11-09 10:45:24",
 * "paypal_status_name": "是",
 * "paypal_sandbox_mode_name": "开启",
 * "waiting_time_human": "1小时"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/pay-config 支付修改
 * @apiName 支付修改
 * @apiGroup 59
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} data.waiting_time 等待时间（秒）
 * @apiParam {string} data.paypal_sandbox_mode 沙盒模式1-开启2-关闭
 * @apiParam {string} data.paypal_client_id paypal应用ID
 * @apiParam {string} data.paypal_client_secret paypal应用秘钥
 * @apiParam {string} data.paypal_status paypal状态1-启用2-禁用
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": "",
 * "msg": "successful"
 * }
 */
