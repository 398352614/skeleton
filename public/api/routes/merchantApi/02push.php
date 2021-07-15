<?php

use Illuminate\Support\Facades\Route;

/**
 * @apiDefine auth
 */

/**
 * @apiDefine 02order 推送通知
 */

/**
 * @api {post} /push 状态转变
 * @apiName 状态转变
 * @apiGroup 02order
 * @apiHeader {string} language 语言cn-中文en-英文。
 * @apiHeaderExample {json} Header-Example:
 * {
 *       "language": "en"
 *     }
 * @apiDescription 地址栏不是真实的推送地址。推送地址由第三方提供，所有的推送都会推送到第三方提供的地址。目前仅提供简略模式，只推送状态不推送相关数据。对于请求返回，仅验证返回值中ret是否为1，1表示推送成功。如果返回值中没有ret或者不需要第三方推送记录，那么TMS将不会解析推送后的返回值。
 * @apiVersion 1.0.0
 * @apiParam {String} type 类型:签收assign-batch，出库out-warehouse
 * @apiParam {String} data
 * @apiParam {String} data.order_no 订单号
 * @apiParam {String} data.out_order_no 外部订单号
 * @apiParam {String} data.order_status 订单状态1-待受理2-运输中3-已完成4-失败5回收站
 * @apiParam {String} data.package_list 包裹列表
 * @apiParam {String} data.package_list.express_first_no 包裹号
 * @apiParam {String} data.package_list.out_order_no 外部包裹号
 * @apiParam {String} data.package_list.stage 包裹阶段1-取件2-派件3-中转
 * @apiParam {String} data.package_list.status 包裹状态是包裹阶段的细分过程，其中只有取件和派件阶段拥有包裹状态1-未取派2-取派中3-已签收4-取派失败5-回收站。中转阶段的包裹仅显示为最后更新的取件或派件阶段的状态。
 * @apiParamExample {json} Param-Response:
 * {"type":"assign-batch","data":[{"order_no":"TMS0001","out_order_no":"ERP0001","order_status":4,"package_list":[{"express_first_no":"TMSPA001","out_order_no":"ERPPA001","package_status":4,"package_stage":1}]}]}
 * @apiSuccess {Number} ret    状态码，1:成功
 * @apiSuccessExample {json} Success-Response:
 * {"ret":1}
 */

