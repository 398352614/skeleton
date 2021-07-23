<?php

use Illuminate\Support\Facades\Route;


/**
 * 10trackingOrder运单管理
 * 11trackingOrderTrail运单轨迹
 * 12orderAmount订单费用
 * 13orderReceipt回单
 * 14orderCostumer客服记录
 * 15package包裹
 * 16material材料
 * 17additionalPackage顺带包裹
 * 18orderTrail订单轨迹
 * 19packageTrail包裹轨迹
 * 20tour线路任务
 * 21batch站点管理
 * 22report任务报告
 * 23delay延迟记录
 * 24batchException站点异常
 * 25recharge充值管理
 * 26stock库存管理
 * 27stockInLog入库记录
 * 28stockOutLog出库记录
 * 29stockException入库异常
 * 30driver司机管理
 * 31car车辆管理
 * 32carAccident车辆事故
 * 33carMaintain车辆维修
 * 34spareParts备品品类
 * 35sparePartsRecord备品领取记录
 * 36sparePartsStock备品库存
 * 37merchant货主
 * 38merchant货主组
 * 39merchantApi货主对接
 * 40address客户地址
 * 41employee员工
 * 42permission权限
 * 43orderDefault订单默认设置
 * 44billConfig面单设置
 * 45noRole单号规则
 * 46mapConfig地图引擎
 * 47warehouse网点管理
 * 48line调度规则
 * 49postcode邮编
 * 50area区域
 * 51transportPrice运价配置
 * 52Fee增值服务
 * 53device设备管理
 * 54holiday假期配置
 * 55additionalRule顺带规则
 * 54warehouseConfig网点配置
 * 55company企业管理
 * 56emailTemplate邮件模板
 */

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
 * @apiDefine 09price 运价信息
 */

/**
 * @api {get} /merchant/transport-price 运价查询
 * @apiName 运价查询
 * @apiGroup 09price
 * @apiVersion 1.0.0
 * @apiUse auth
 *
 * @apiSuccess {Number} code    状态码，200：请求成功
 * @apiSuccess {String} msg   提示信息
 * @apiSuccess {Object} data    返回数据
 * @apiSuccess {String} data.id id 运价方案ID
 * @apiSuccess {String} data.company_id 公司ID
 * @apiSuccess {String} data.name 名称
 * @apiSuccess {String} data.starting_price 起步价
 * @apiSuccess {String} data.type 类型1-阶梯乘积2-阶梯固定
 * @apiSuccess {String} data.remark 特别说明
 * @apiSuccess {String} data.status 状态1-启用2-禁用
 * @apiSuccess {String} data.created_at 创建时间
 * @apiSuccess {String} data.updated_at 修改时间
 * @apiSuccess {String} data.type_name 类型名称
 *
 * @apiSuccess {Object} data.weight_list 重量费用列表
 * @apiSuccess {String} data.weight_list.id 重量费用ID
 * @apiSuccess {String} data.weight_list.company_id 公司ID
 * @apiSuccess {String} data.weight_list.transport_price_id 运价ID
 * @apiSuccess {String} data.weight_list.start 运价ID
 * @apiSuccess {String} data.weight_list.end 截止重量
 * @apiSuccess {String} data.weight_list.price 加价
 * @apiSuccess {String} data.weight_list.created_at 创建时间
 * @apiSuccess {String} data.weight_list.updated_at 修改时间

 * @apiSuccess {Object} data.km_list 里程费用列表
 * @apiSuccess {String} data.km_list.id 里程费用ID
 * @apiSuccess {String} data.km_list.company_id 公司ID
 * @apiSuccess {String} data.km_list.transport_price_id 运价ID
 * @apiSuccess {String} data.km_list.start 起始公里
 * @apiSuccess {String} data.km_list.end 截止公里
 * @apiSuccess {String} data.km_list.price 加价
 * @apiSuccess {String} data.km_list.created_at 创建时间
 * @apiSuccess {String} data.km_list.updated_at 修改时间

 * @apiSuccessExample {json} Success-Response:
 * {"code":200,"data":{"id":67,"company_id":3,"name":"测试名称3","starting_price":"10.00","type":2,"remark":"","status":1,"created_at":"2021-02-03 08:13:54","updated_at":"2021-02-03 08:13:54","type_name":"阶梯固定值计算（固定费用+（重量价格档）*（里程价格档））","km_list":[{"id":157,"company_id":3,"transport_price_id":67,"start":0,"end":100,"price":"0.00","created_at":"2021-02-03 08:13:54","updated_at":"2021-02-03 08:13:54"},{"id":158,"company_id":3,"transport_price_id":67,"start":100,"end":999999999,"price":"10.00","created_at":"2021-02-03 08:13:54","updated_at":"2021-02-03 08:13:54"}],"weight_list":[{"id":180,"company_id":3,"transport_price_id":69,"start":0,"end":5,"price":"2.00","created_at":"2021-02-04 04:38:06","updated_at":"2021-02-04 04:38:06"},{"id":181,"company_id":3,"transport_price_id":69,"start":5,"end":999999999,"price":"5.00","created_at":"2021-02-04 04:38:06","updated_at":"2021-02-04 04:38:06"}],"special_time_list":[]},"msg":"successful"} */
