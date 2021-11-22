<?php
/**
 * @apiDefine 16paypal paypal支付
 */

/**
 * @api {get} /merchant_h5/paypal 余额查询
 * @apiName 余额查询
 * @apiGroup 16paypal
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} msg
 * @apiSuccess {string} data
 * @apiSuccess {string} data.user_name 货主名称
 * @apiSuccess {string} data.code 货主编号
 * @apiSuccess {string} data.credit 信用额度
 * @apiSuccess {string} data.balance 账户余额
 * @apiSuccess {string} data.status 状态1-限制2-不限制
 * @apiSuccess {string} data.phone 手机号
 * @apiSuccess {string} data.email 邮箱
 * @apiSuccess {string} data.create_date 注册日期
 * @apiSuccess {string} data.merchant_group_id 货主组ID
 * @apiSuccess {string} data.merchant_group_name 货主组名称
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * }
 */

/**
 * @api {get} /merchant_h5/paypal 余额查询
 * @apiName 余额查询
 * @apiGroup 16paypal
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} msg
 * @apiSuccess {string} data
 * @apiSuccess {string} data.user_name 货主名称
 * @apiSuccess {string} data.code 货主编号
 * @apiSuccess {string} data.credit 信用额度
 * @apiSuccess {string} data.balance 账户余额
 * @apiSuccess {string} data.status 状态1-限制2-不限制
 * @apiSuccess {string} data.phone 手机号
 * @apiSuccess {string} data.email 邮箱
 * @apiSuccess {string} data.create_date 注册日期
 * @apiSuccess {string} data.merchant_group_id 货主组ID
 * @apiSuccess {string} data.merchant_group_name 货主组名称
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * }
 */
