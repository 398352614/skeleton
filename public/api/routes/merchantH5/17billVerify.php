<?php
/**
 * @apiDefine 17billVerify 对账单
 */

/**
 * @api {get} /merchant_h5/bill-verify 对账单查询
 * @apiName 对账单查询
 * @apiGroup 17billVerify
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} begin_date 起始时间
 * @apiParam {string} end_date 截止时间
 * @apiParam {string} code 货主编号
 * @apiParam {string} status 对账状态1-未对账2-已对账3-取消
 * @apiParam {string} mode 交易类型1-账号充值2-运费支付
 * @apiParam {string} payer_name 付款方名称
 * @apiParam {string} verify_no 对账单号
 * @apiParam {string} pay_type 支付方式1-银行转账2-支票3-现金4-余额
 *
 * @apiSuccess {string} code
 * @apiSuccess {string} msg
 * @apiSuccess {string} data
 * @apiSuccess {string} data.name 货主名称
 * @apiSuccess {string} data.code 货主编号
 * @apiSuccess {string} data.pay_type 支付类型1-银行转账2-支票3-现金4-余额
 * @apiSuccess {string} data.mode 交易类型1-账号充值2-运费支付
 * @apiSuccess {string} data.object_no 系统编号
 * @apiSuccess {string} data.object_type 系统编号类型1-订单2-包裹
 * @apiSuccess {string} data.expect_amount 预计金额
 * @apiSuccess {string} data.actual_amount 实际金额
 * @apiSuccess {string} data.pay_type_name 支付类型名称
 * @apiSuccess {string} data.mode_name 交易类型名称
 * @apiSuccess {string} data.status 状态1-未支付2-已支付3-已取消
 * @apiSuccess {string} data.verify_status 对账状态1-未对账2-已对账3-拒绝
 * @apiSuccess {string} data.merchant_group_name 货主组名称
 * @apiSuccess {string} data.payer_type 付款方类型
 * @apiSuccess {string} data.payer_type 付款方类型名称
 * @apiSuccess {string} data.payer_id 付款方ID
 * @apiSuccess {string} data.create_date 创建方式
 * @apiSuccess {string} data.payer_name 付款方名称
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * }
 */

/**
 * @api {get} /merchant_h5/bill-verify/{id} 对账单详情
 * @apiName 对账单详情
 * @apiGroup 17billVerify
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 对账单ID
 * @apiSuccess {string} code
 * @apiSuccess {string} msg
 * @apiSuccess {string} data
 * @apiSuccess {string} data.verify_no 对账单号
 * @apiSuccess {string} data.begin_date 开始账期
 * @apiSuccess {string} data.end_date 结束账期
 * @apiSuccess {string} data.pay_type 支付类型1-银行转账2-支票3-现金4-余额
 * @apiSuccess {string} data.mode 交易类型1-账号充值2-运费支付
 * @apiSuccess {string} data.object_no 系统编号
 * @apiSuccess {string} data.object_type 系统编号类型1-订单2-包裹
 * @apiSuccess {string} data.expect_amount 预计金额
 * @apiSuccess {string} data.actual_amount 实际金额
 * @apiSuccess {string} data.pay_type_name 支付类型名称
 * @apiSuccess {string} data.mode_name 交易类型名称
 * @apiSuccess {string} data.status 状态1-未支付2-已支付3-已取消
 * @apiSuccess {string} data.verify_status 对账状态1-未对账2-已对账3-拒绝
 * @apiSuccess {string} data.merchant_group_name 货主组名称
 * @apiSuccess {object} data.bill_list 账单列表
 * @apiSuccess {string} data.bill_list.create_date 录单日期
 * @apiSuccess {string} data.bill_list.object_no 系统编号
 * @apiSuccess {string} data.bill_list.payer_name 付款方名称
 * @apiSuccess {string} data.bill_list.pay_type 付款方式
 * @apiSuccess {string} data.bill_list.pay_type_name 付款方式名称
 * @apiSuccess {string} data.bill_list.expect_amount 预计金额
 * @apiSuccess {string} data.bill_list.actual_amount 实际金额
 * @apiSuccess {string} data.bill_list.rest_amount 未交金额
 * @apiSuccess {string} data.bill_list.status 对账状态
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * }
 */
