<?php
/**
 * @apiDefine 16paypal paypal支付
 */

/**
 * @api {post} /merchant_h5/payment/paypal 发起支付
 * @apiName 发起支付
 * @apiGroup 16paypal
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} bill_no 发起支付
 * @apiSuccess {string} code
 * @apiSuccess {string} msg
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 支付单id
 * @apiSuccess {string} data.approvalUrl 支付链接
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": "PAYID-MGOF25Q3WC32184TD472071G",
 * "approvalUrl": "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=EC-4M742043RG2316106"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /merchant_h5/payment/paypal 完成支付
 * @apiName 完成支付
 * @apiGroup 16paypal
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} payment_id 支付单id
 * @apiParam {string} payer_id 支付方id
 * @apiParam {string} status 状态2成功3失败
 * @apiParam {string} amount 金额

 * @apiSuccess {string} code
 * @apiSuccess {string} msg
 * @apiSuccess {string} data
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * }
 */
