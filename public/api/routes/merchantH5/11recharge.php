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
 * @apiDefine 11recharge 充值管理
 */

/**
 * @api {get} /merchant_h5/bill-recharge 充值记录查询
 * @apiName 账单查询
 * @apiGroup 53
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} begin_date 起始时间
 * @apiParam {string} end_date 截止时间
 * @apiParam {string} code 货主编号
 * @apiParam {string} verify_status 对账状态1-未对账2-已对账3-取消
 * @apiParam {string} mode 交易类型1-账号充值2-运费支付
 * @apiParam {string} status 支付状态1-未支付2-已支付3-已取消
 * @apiParam {string} bill_no 账单单号
 * @apiParam {string} verify_no 对账单号
 * @apiParam {string} object_no 系统编号
 * @apiSuccess {string} code
 * @apiSuccess {string} msg
 * @apiSuccess {string} data
 * @apiSuccess {string} data.payer_type 付款方类型
 * @apiSuccess {string} data.payer_type_name 付款方类型名称
 * @apiSuccess {string} data.payer_id 付款方ID
 * @apiSuccess {string} data.payer_name 付款方名称
 * @apiSuccess {string} data.payee_type 收款方类型
 * @apiSuccess {string} data.payee_type_name 收款方类型名称
 * @apiSuccess {string} data.payee_id 收款方ID
 * @apiSuccess {string} data.payee_name 收款方名称
 * @apiSuccess {string} data.operator_type 经办人类型
 * @apiSuccess {string} data.operator_id 经办人ID
 * @apiSuccess {string} data.operator_name 经办人名称
 * @apiSuccess {string} data.pay_type 付款方式1-银行转账2-支票3-现金4-余额
 * @apiSuccess {string} data.mode 交易类型1-账号充值2-运费支付
 * @apiSuccess {string} data.object_no 系统编号
 * @apiSuccess {string} data.object_type 系统编号类型1-订单2-包裹
 * @apiSuccess {string} data.expect_amount 费用
 * @apiSuccess {string} data.actual_amount 已支付费用
 * @apiSuccess {string} data.rest_amount 已支付费用
 * @apiSuccess {string} data.pay_type_name 支付类型名称
 * @apiSuccess {string} data.mode_name 费用类型名称
 * @apiSuccess {string} data.status 支付状态1-未支付2-已支付3-已取消
 * @apiSuccess {string} data.verify_no 对账编号
 * @apiSuccess {string} data.verify_status 对账状态1-未对账2-已对账3-拒绝
 * @apiSuccess {string} data.verify_status 对账状态名称
 * @apiSuccess {string} data.merchant_group_name 货主组名称
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * }
 */

/**
 * @api {get} /merchant_h5/bill-recharge/{id} 充值记录详情
 * @apiName 账单详情
 * @apiGroup 53
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 账单ID
 * @apiSuccess {string} code
 * @apiSuccess {string} msg
 * @apiSuccess {string} data
 * @apiSuccess {string} data.payer_type 付款方类型
 * @apiSuccess {string} data.payer_id 付款方ID
 * @apiSuccess {string} data.payer_name 付款方名称
 * @apiSuccess {string} data.payee_type 收款方类型
 * @apiSuccess {string} data.payee_id 收款方ID
 * @apiSuccess {string} data.payee_name 收款方名称
 * @apiSuccess {string} data.operator_type 经办人类型
 * @apiSuccess {string} data.operator_id 经办人ID
 * @apiSuccess {string} data.operator_name 经办人名称
 * @apiSuccess {string} data.pay_type 支付类型1-银行转账2-支票3-现金4-余额
 * @apiSuccess {string} data.mode 交易类型1-账号充值2-运费支付
 * @apiSuccess {string} data.object_no 系统编号
 * @apiSuccess {string} data.object_type 系统编号类型1-订单2-包裹
 * @apiSuccess {string} data.expect_amount 预计金额
 * @apiSuccess {string} data.actual_amount 实际金额
 * @apiSuccess {string} data.pay_type_name 支付类型名称
 * @apiSuccess {string} data.mode_name 交易类型名称
 * @apiSuccess {string} data.status 状态1-未支付2-已支付3-已取消
 * @apiSuccess {string} data.verify_no 对账编号
 * @apiSuccess {string} data.verify_status 对账状态1-未对账2-已对账3-拒绝
 * @apiSuccess {string} data.merchant_group_name 货主组名称
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * }
 */

/**
 * @api {post} /merchant_h5/bill/merchant-recharge 充值
 * @apiName 货主充值开单
 * @apiGroup 53
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} merchant_id 货主ID
 * @apiParam {string} pay_type 支付方式1-银行转账2-支票3-现金4-余额
 * @apiParam {string} expect_amount 充值金额
 * @apiParam {string} remark 备注
 * @apiParam {string} picture_list 图片列表
 * @apiSuccess {string} code
 * @apiSuccess {string} msg
 * @apiSuccess {string} data
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * }
 */
