<?php

use Illuminate\Support\Facades\Route;

/**
 * @apiDefine auth
 */

/**
 * @apiDefine 02order 推送通知
 */

/**
 * @api {post} /assign-batch 状态转变
 * @apiName 签收
 * @apiGroup 02order
 * @apiHeader {string} language 语言cn-中文en-英文。
 * @apiHeaderExample {json} Header-Example:
 * {
 *       "language": "en"
 *     }
 * @apiDescription 地址栏仅为请求类型，不是真实的推送地址。推送地址由第三方提供，所有的推送都会推送到该地址，但数据格式会由于类型不同而不同。对于请求返回，仅验证返回值中ret是否为1，1表示推送成功。如果返回值中没有ret或者不需要第三方推送记录，那么TMS将不会解析推送后的返回值。
 * @apiVersion 1.0.0
 * @apiParam {String} type 类型:签收assign-batch，出库out-warehouse
 * @apiParam {String} data
 * @apiParam {String} data.order_no 订单号
 * @apiParam {String} data.out_order_no 外部订单号
 * @apiParam {String} data.order_status 订单状态1-待受理2-运输中3-已完成4-已失败5回收站
 * @apiParam {String} data.package_list 包裹列表
 * @apiParam {String} data.package_list.express_first_no 包裹号
 * @apiParam {String} data.package_list.out_order_no 外部包裹号
 * @apiParam {String} data.package_list.stage 包裹阶段1-取件2-派件3-中转
 * @apiParam {String} data.package_list.status 包裹状态分为两种，是包裹阶段的细分过程，其中取件和派件都有以下状态：1-待受理2-已接单3-已装货4-在途5-已签收6-取消7-回收站。中转包裹则为以下状态：1-待装袋2-待装车3-待发车4-运输中5-已到车6-已卸货7-已拆袋
 * @apiParamExample {json} Param-Response:
 * {"type":"assign-batch","data":[{"order_no":"TMS0001","out_order_no":"ERP0001","order_status":4,"package_list":[{"express_first_no":"TMSPA001","out_order_no":"ERPPA001","package_status":4,"package_stage":1}]}]}
 * @apiSuccess {Number} ret    状态码，1:成功
 * @apiSuccessExample {json} Success-Response:
 * {"ret":1}
 */

