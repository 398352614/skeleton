<?php
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
 * @apiDefine 00 用户认证
 */
/**
 * @apiDefine 01 网点管理
 */
/**
 * @apiDefine 02 订单管理
 */
/**
 * @apiDefine 03 线路管理
 */
/**
 * @apiDefine 04 公司信息
 */
/**
 * @apiDefine 05 公共接口
 */
/**
 * @apiDefine 06 车辆管理
 */
/**
 * @apiDefine 07 站点管理
 */
/**
 * @apiDefine 08 取件线路管理
 */
/**
 * @apiDefine 09 司机管理
 */
/**
 * @apiDefine 10 国家管理
 */
/**
 * @apiDefine 11 站点异常管理
 */
/**
 * @apiDefine 12 上传
 */
/**
 * @apiDefine 13 地址管理
 */
/**
 * @apiDefine 14 主页
 */
/**
 * @apiDefine 15 公司管理
 */
/**
 * @apiDefine 16 员工管理
 */
/**
 * @apiDefine 17 组织管理
 */
/**
 * @apiDefine 18 货主管理
 */
/**
 * @apiDefine 19 货主组管理
 */
/**
 * @apiDefine 20 运价管理
 */
/**
 * @apiDefine 21 取件线路追踪
 */
/**
 * @apiDefine 22 权限组管理
 */
/**
 * @apiDefine 23 订单导入管理
 */
/**
 * @apiDefine 24 单号规则管理
 */
/**
 * @apiDefine 25 打印管理
 */
/**
 * @apiDefine 26 版本管理
 */
/**
 * @apiDefine 27 费用管理
 */
/**
 * @apiDefine 28 放假管理
 */
/**
 * @apiDefine 29 包裹管理
 */
/**
 * @apiDefine 30 材料管理
 */
/**
 * @apiDefine 31 充值管理
 */
/**
 * @apiDefine 32 设备管理
 */
/**
 * @apiDefine 33 运单管理
 */
/**
 * @apiDefine 34 运单物流管理
 */
/**
 * @apiDefine 35 订单物流管理
 */
/**
 * @apiDefine 36 库存管理
 */
/**
 * @apiDefine 37 包裹编号规则管理
 */
/**
 * @apiDefine 38 货主API对接管理
 */
/**
 * @apiDefine 39 入库异常管理
 */
/**
 * @apiDefine 40 权限组管理
 */
/**
 * @apiDefine 41 车辆维护
 */
/**
 * @apiDefine 42 车辆事故
 */
/**
 * @apiDefine 43 订单面单模板
 */
/**
 * @apiDefine 44 备品管理
 */
/**
 * @apiDefine 45 订单费用管理
 */
/**
 * @apiDefine 46 客服记录
 */
/**
 * @apiDefine 47 回单记录
 */
/**
 * @apiDefine 48 订单默认配置
 */
/**
 * @apiDefine 49 地图引擎配置
 */
/**
 * @apiDefine 50 邮件模板配置
 */
/**
 * @apiDefine 51 包裹物流管理
 */
/**
 * @api {post} /admin/login 登录
 * @apiName 登录
 * @apiGroup 00
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} username 用户名（邮箱）
 * @apiParam {string} password 密码
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.username
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.access_token token
 * @apiSuccess {string} data.token_type
 * @apiSuccess {string} data.expires_in
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "username": "h947136@qq.com",
 * "company_id": 1,
 * "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC90bXMtYXBpLnRlc3RcL2FwaVwvYWRtaW5cL2xvZ2luIiwiaWF0IjoxNTc3NDEzODc3LCJleHAiOjE1Nzg2MjM0NzcsIm5iZiI6MTU3NzQxMzg3NywianRpIjoiQ3JJSHZpVlFJNnNLVGp2MiIsInN1YiI6MSwicHJ2IjoiMzI5NjNhNjA2YzJmMTcxZjFjMTQzMzFlNzY5NzY2Y2Q1OTEyZWQxNSIsInJvbGUiOiJlbXBsb3llZSJ9.ozFwfckzz1rgldvKUUxPBHZceu5-V9IP49vb_UhNT8s",
 * "token_type": "bearer",
 * "expires_in": 1209600
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/register 注册
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
 * @api {post} /admin/register/apply 注册验证码
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

/**
 * @api {put} /admin/password-reset 找回密码
 * @apiName 找回密码
 * @apiGroup 00
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} email 邮箱
 * @apiParam {string} new_password 新密码
 * @apiParam {string} confirm_new_password 重复新密码
 * @apiParam {string} code 重置验证码
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [],
 * "msg": "success"
 * }
 */

/**
 * @api {post} /admin/password-reset/apply 找回密码验证码
 * @apiName 找回密码-验证码
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

/**
 * @api {put} /admin/my-password 修改密码
 * @apiName 修改密码
 * @apiGroup 00
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} origin_password 原密码
 * @apiParam {string} new_password 新密码
 * @apiParam {string} new_confirm_password 重复新密码
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [],
 * "msg": "success"
 * }
 */

/**
 * @api {get} /admin/permission 获取当前用户权限
 * @apiName 获取当前用户权限
 * @apiGroup 00
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.permission_list 功能列表
 * @apiSuccess {string} data.permission_list.id
 * @apiSuccess {string} data.permission_list.parent_id
 * @apiSuccess {string} data.permission_list.name
 * @apiSuccess {string} data.permission_list.route_as
 * @apiSuccess {string} data.permission_list.type
 * @apiSuccess {string} data.menu_list 菜单列表
 * @apiSuccess {string} data.menu_list.id
 * @apiSuccess {string} data.menu_list.parent_id
 * @apiSuccess {string} data.menu_list.name
 * @apiSuccess {string} data.menu_list.route_as
 * @apiSuccess {string} data.menu_list.type
 * @apiSuccess {string} data.menu_list.children
 * @apiSuccess {string} data.menu_list.children.id
 * @apiSuccess {string} data.menu_list.children.parent_id
 * @apiSuccess {string} data.menu_list.children.name
 * @apiSuccess {string} data.menu_list.children.route_as
 * @apiSuccess {string} data.menu_list.children.type
 * @apiSuccess {string} data.menu_list.children.children
 * @apiSuccess {string} data.menu_list.children.children.id
 * @apiSuccess {string} data.menu_list.children.children.parent_id
 * @apiSuccess {string} data.menu_list.children.children.name
 * @apiSuccess {string} data.menu_list.children.children.route_as
 * @apiSuccess {string} data.menu_list.children.children.type
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "permission_list": [
 * {
 * "id": 6,
 * "parent_id": 5,
 * "name": "查看",
 * "route_as": "statistics.home",
 * "type": 2
 * },
 * {
 * "id": 9,
 * "parent_id": 238,
 * "name": "查看",
 * "route_as": "order.index",
 * "type": 2
 * },
 * {
 * "id": 10,
 * "parent_id": 238,
 * "name": "详情",
 * "route_as": "order.show",
 * "type": 2
 * },
 * {
 * "id": 11,
 * "parent_id": 238,
 * "name": "编辑",
 * "route_as": "order.update",
 * "type": 2
 * },
 * {
 * "id": 12,
 * "parent_id": 238,
 * "name": "删除",
 * "route_as": "order.destroy",
 * "type": 2
 * },
 * {
 * "id": 13,
 * "parent_id": 238,
 * "name": "订单轨迹",
 * "route_as": "order-trail.index",
 * "type": 2
 * },
 * {
 * "id": 14,
 * "parent_id": 238,
 * "name": "对接日志",
 * "route_as": "order.third-party-log",
 * "type": 2
 * },
 * {
 * "id": 16,
 * "parent_id": 238,
 * "name": "终止派送",
 * "route_as": "order.end",
 * "type": 2
 * },
 * {
 * "id": 18,
 * "parent_id": 238,
 * "name": "再次派送",
 * "route_as": "order.again",
 * "type": 2
 * },
 * {
 * "id": 19,
 * "parent_id": 238,
 * "name": "订单无效化",
 * "route_as": "order.neutralize",
 * "type": 2
 * },
 * {
 * "id": 20,
 * "parent_id": 238,
 * "name": "导出",
 * "route_as": "order.export",
 * "type": 2
 * },
 * {
 * "id": 21,
 * "parent_id": 238,
 * "name": "打印",
 * "route_as": "order.print",
 * "type": 2
 * },
 * {
 * "id": 24,
 * "parent_id": 23,
 * "name": "查看",
 * "route_as": "package.index",
 * "type": 2
 * },
 * {
 * "id": 26,
 * "parent_id": 25,
 * "name": "查看",
 * "route_as": "material.index",
 * "type": 2
 * },
 * {
 * "id": 28,
 * "parent_id": 27,
 * "name": "查看",
 * "route_as": "additional-package.index",
 * "type": 2
 * },
 * {
 * "id": 30,
 * "parent_id": 29,
 * "name": "查看",
 * "route_as": "order-trail.index|tracking-order-trail.index",
 * "type": 2
 * },
 * {
 * "id": 33,
 * "parent_id": 32,
 * "name": "查看",
 * "route_as": "tour.index",
 * "type": 2
 * },
 * {
 * "id": 34,
 * "parent_id": 32,
 * "name": "分配司机",
 * "route_as": "assign-driver|tour.cancel-driver",
 * "type": 2
 * },
 * {
 * "id": 35,
 * "parent_id": 32,
 * "name": "分配车辆",
 * "route_as": "tour.assign-car|cancel-car",
 * "type": 2
 * },
 * {
 * "id": 36,
 * "parent_id": 32,
 * "name": "智能调度",
 * "route_as": "tour.update-batch-index|tour.auto-op-tour",
 * "type": 2
 * },
 * {
 * "id": 37,
 * "parent_id": 32,
 * "name": "导出",
 * "route_as": "batch-excel",
 * "type": 2
 * },
 * {
 * "id": 38,
 * "parent_id": 32,
 * "name": "更换线路",
 * "route_as": "tour.assign",
 * "type": 2
 * },
 * {
 * "id": 39,
 * "parent_id": 32,
 * "name": "线路追踪",
 * "route_as": "",
 * "type": 2
 * },
 * {
 * "id": 40,
 * "parent_id": 32,
 * "name": "任务报告查看",
 * "route_as": "tour.show",
 * "type": 2
 * },
 * {
 * "id": 41,
 * "parent_id": 32,
 * "name": "任务报告导出",
 * "route_as": "tour-excel",
 * "type": 2
 * },
 * {
 * "id": 43,
 * "parent_id": 42,
 * "name": "查看",
 * "route_as": "tracking-order.index",
 * "type": 2
 * },
 * {
 * "id": 44,
 * "parent_id": 42,
 * "name": "查看记录",
 * "route_as": "tracking-order.show",
 * "type": 2
 * },
 * {
 * "id": 45,
 * "parent_id": 42,
 * "name": "查看轨迹",
 * "route_as": "tracking-order-trail.index",
 * "type": 2
 * },
 * {
 * "id": 46,
 * "parent_id": 42,
 * "name": "站点移除",
 * "route_as": "remove-batch",
 * "type": 2
 * },
 * {
 * "id": 47,
 * "parent_id": 42,
 * "name": "分配站点",
 * "route_as": "assign-batch",
 * "type": 2
 * },
 * {
 * "id": 48,
 * "parent_id": 42,
 * "name": "导出",
 * "route_as": "tracking-order.order-excel",
 * "type": 2
 * },
 * {
 * "id": 49,
 * "parent_id": 42,
 * "name": "线路加单",
 * "route_as": "tracking-order.assign-tour",
 * "type": 2
 * },
 * {
 * "id": 51,
 * "parent_id": 50,
 * "name": "查看",
 * "route_as": "batch.index",
 * "type": 2
 * },
 * {
 * "id": 52,
 * "parent_id": 50,
 * "name": "更换线路",
 * "route_as": "batch.assign-tour",
 * "type": 2
 * },
 * {
 * "id": 53,
 * "parent_id": 50,
 * "name": "移除线路",
 * "route_as": "batch.remove",
 * "type": 2
 * },
 * {
 * "id": 54,
 * "parent_id": 50,
 * "name": "取消取派",
 * "route_as": "batch.cancel",
 * "type": 2
 * },
 * {
 * "id": 57,
 * "parent_id": 56,
 * "name": "查看",
 * "route_as": "delay.index",
 * "type": 2
 * },
 * {
 * "id": 59,
 * "parent_id": 58,
 * "name": "查看",
 * "route_as": "batch-exception.index",
 * "type": 2
 * },
 * {
 * "id": 60,
 * "parent_id": 58,
 * "name": "详情",
 * "route_as": "batch-exception.show",
 * "type": 2
 * },
 * {
 * "id": 61,
 * "parent_id": 58,
 * "name": "处理",
 * "route_as": "batch-exception.deal",
 * "type": 2
 * },
 * {
 * "id": 63,
 * "parent_id": 202,
 * "name": "查看",
 * "route_as": "device.index",
 * "type": 2
 * },
 * {
 * "id": 65,
 * "parent_id": 202,
 * "name": "新增",
 * "route_as": "device.store",
 * "type": 2
 * },
 * {
 * "id": 66,
 * "parent_id": 202,
 * "name": "编辑",
 * "route_as": "device.update",
 * "type": 2
 * },
 * {
 * "id": 67,
 * "parent_id": 202,
 * "name": "删除",
 * "route_as": "device.destroy",
 * "type": 2
 * },
 * {
 * "id": 69,
 * "parent_id": 68,
 * "name": "查看",
 * "route_as": "car.track-show|car.track-index",
 * "type": 2
 * },
 * {
 * "id": 72,
 * "parent_id": 71,
 * "name": "查看",
 * "route_as": "recharge.index",
 * "type": 2
 * },
 * {
 * "id": 73,
 * "parent_id": 71,
 * "name": "审核",
 * "route_as": "recharge.verify",
 * "type": 2
 * },
 * {
 * "id": 76,
 * "parent_id": 75,
 * "name": "查看",
 * "route_as": "stock.index",
 * "type": 2
 * },
 * {
 * "id": 78,
 * "parent_id": 77,
 * "name": "查看",
 * "route_as": "stock-out-log.index",
 * "type": 2
 * },
 * {
 * "id": 80,
 * "parent_id": 79,
 * "name": "查看",
 * "route_as": "stock-in-log.index",
 * "type": 2
 * },
 * {
 * "id": 82,
 * "parent_id": 81,
 * "name": "查看",
 * "route_as": "stock-exception.index",
 * "type": 2
 * },
 * {
 * "id": 83,
 * "parent_id": 81,
 * "name": "审核",
 * "route_as": "stock-exception.deal",
 * "type": 2
 * },
 * {
 * "id": 87,
 * "parent_id": 209,
 * "name": "查看",
 * "route_as": "line.post-code-index",
 * "type": 2
 * },
 * {
 * "id": 88,
 * "parent_id": 209,
 * "name": "新增",
 * "route_as": "line.post-code-store",
 * "type": 2
 * },
 * {
 * "id": 89,
 * "parent_id": 209,
 * "name": "编辑",
 * "route_as": "line.post-code-update",
 * "type": 2
 * },
 * {
 * "id": 90,
 * "parent_id": 209,
 * "name": "删除",
 * "route_as": "line.post-code-destroy",
 * "type": 2
 * },
 * {
 * "id": 91,
 * "parent_id": 209,
 * "name": "高级配置",
 * "route_as": "line.post-code-merchant-config",
 * "type": 2
 * },
 * {
 * "id": 92,
 * "parent_id": 209,
 * "name": "修改状态",
 * "route_as": "line.status",
 * "type": 2
 * },
 * {
 * "id": 95,
 * "parent_id": 94,
 * "name": "查看",
 * "route_as": "driver.index",
 * "type": 2
 * },
 * {
 * "id": 96,
 * "parent_id": 94,
 * "name": "详情",
 * "route_as": "driver.show",
 * "type": 2
 * },
 * {
 * "id": 97,
 * "parent_id": 94,
 * "name": "新增",
 * "route_as": "driver.store",
 * "type": 2
 * },
 * {
 * "id": 98,
 * "parent_id": 94,
 * "name": "编辑",
 * "route_as": "driver.update",
 * "type": 2
 * },
 * {
 * "id": 99,
 * "parent_id": 94,
 * "name": "删除",
 * "route_as": "driver.destroy",
 * "type": 2
 * },
 * {
 * "id": 101,
 * "parent_id": 100,
 * "name": "查看",
 * "route_as": "car.index",
 * "type": 2
 * },
 * {
 * "id": 102,
 * "parent_id": 100,
 * "name": "详情",
 * "route_as": "car.show",
 * "type": 2
 * },
 * {
 * "id": 103,
 * "parent_id": 100,
 * "name": "编辑",
 * "route_as": "car.update",
 * "type": 2
 * },
 * {
 * "id": 104,
 * "parent_id": 100,
 * "name": "删除",
 * "route_as": "car.destroy",
 * "type": 2
 * },
 * {
 * "id": 107,
 * "parent_id": 106,
 * "name": "查看",
 * "route_as": "merchant.index",
 * "type": 2
 * },
 * {
 * "id": 108,
 * "parent_id": 106,
 * "name": "详情",
 * "route_as": "merchant.show",
 * "type": 2
 * },
 * {
 * "id": 109,
 * "parent_id": 106,
 * "name": "编辑",
 * "route_as": "merchant.update",
 * "type": 2
 * },
 * {
 * "id": 110,
 * "parent_id": 106,
 * "name": "新增",
 * "route_as": "merchant.store",
 * "type": 2
 * },
 * {
 * "id": 112,
 * "parent_id": 111,
 * "name": "查看",
 * "route_as": "merchant-group.index",
 * "type": 2
 * },
 * {
 * "id": 113,
 * "parent_id": 111,
 * "name": "详情",
 * "route_as": "merchant-group.show",
 * "type": 2
 * },
 * {
 * "id": 114,
 * "parent_id": 111,
 * "name": "编辑",
 * "route_as": "merchant-group.update",
 * "type": 2
 * },
 * {
 * "id": 115,
 * "parent_id": 111,
 * "name": "删除",
 * "route_as": "merchant-group.destroy",
 * "type": 2
 * },
 * {
 * "id": 117,
 * "parent_id": 116,
 * "name": "查看",
 * "route_as": "merchant-api.index",
 * "type": 2
 * },
 * {
 * "id": 118,
 * "parent_id": 116,
 * "name": "详情",
 * "route_as": "merchant-api.show",
 * "type": 2
 * },
 * {
 * "id": 119,
 * "parent_id": 116,
 * "name": "新增",
 * "route_as": "merchant-api.store",
 * "type": 2
 * },
 * {
 * "id": 120,
 * "parent_id": 116,
 * "name": "编辑",
 * "route_as": "merchant-api.update",
 * "type": 2
 * },
 * {
 * "id": 121,
 * "parent_id": 116,
 * "name": "删除",
 * "route_as": "merchant-api.destroy",
 * "type": 2
 * },
 * {
 * "id": 123,
 * "parent_id": 122,
 * "name": "查看",
 * "route_as": "address.index",
 * "type": 2
 * },
 * {
 * "id": 124,
 * "parent_id": 122,
 * "name": "详情",
 * "route_as": "address.show",
 * "type": 2
 * },
 * {
 * "id": 125,
 * "parent_id": 122,
 * "name": "新增",
 * "route_as": "address.store",
 * "type": 2
 * },
 * {
 * "id": 126,
 * "parent_id": 122,
 * "name": "编辑",
 * "route_as": "address.update",
 * "type": 2
 * },
 * {
 * "id": 127,
 * "parent_id": 122,
 * "name": "删除",
 * "route_as": "address.destroy",
 * "type": 2
 * },
 * {
 * "id": 131,
 * "parent_id": 130,
 * "name": "查看",
 * "route_as": "company-info.index",
 * "type": 2
 * },
 * {
 * "id": 132,
 * "parent_id": 130,
 * "name": "编辑",
 * "route_as": "company-info.update",
 * "type": 2
 * },
 * {
 * "id": 134,
 * "parent_id": 133,
 * "name": "查看",
 * "route_as": "company-config.show",
 * "type": 2
 * },
 * {
 * "id": 135,
 * "parent_id": 133,
 * "name": "编辑",
 * "route_as": "company-config.update",
 * "type": 2
 * },
 * {
 * "id": 137,
 * "parent_id": 136,
 * "name": "查看",
 * "route_as": "warehouse.index",
 * "type": 2
 * },
 * {
 * "id": 138,
 * "parent_id": 136,
 * "name": "详情",
 * "route_as": "warehouse.show",
 * "type": 2
 * },
 * {
 * "id": 139,
 * "parent_id": 136,
 * "name": "新增",
 * "route_as": "warehouse.store",
 * "type": 2
 * },
 * {
 * "id": 140,
 * "parent_id": 136,
 * "name": "编辑",
 * "route_as": "warehouse.update",
 * "type": 2
 * },
 * {
 * "id": 141,
 * "parent_id": 136,
 * "name": "删除",
 * "route_as": "warehouse.destroy",
 * "type": 2
 * },
 * {
 * "id": 144,
 * "parent_id": 143,
 * "name": "查看",
 * "route_as": "mployees.index",
 * "type": 2
 * },
 * {
 * "id": 145,
 * "parent_id": 143,
 * "name": "详情",
 * "route_as": "employees.show",
 * "type": 2
 * },
 * {
 * "id": 146,
 * "parent_id": 143,
 * "name": "新增",
 * "route_as": "employees.store",
 * "type": 2
 * },
 * {
 * "id": 147,
 * "parent_id": 143,
 * "name": "编辑",
 * "route_as": "employees.update",
 * "type": 2
 * },
 * {
 * "id": 148,
 * "parent_id": 143,
 * "name": "删除",
 * "route_as": "employees.destroy",
 * "type": 2
 * },
 * {
 * "id": 158,
 * "parent_id": 157,
 * "name": "查看",
 * "route_as": "order-no-rule.index",
 * "type": 2
 * },
 * {
 * "id": 159,
 * "parent_id": 157,
 * "name": "详情",
 * "route_as": "order-no-rule.show",
 * "type": 2
 * },
 * {
 * "id": 160,
 * "parent_id": 157,
 * "name": "新增",
 * "route_as": "order-no-rule.store",
 * "type": 2
 * },
 * {
 * "id": 161,
 * "parent_id": 157,
 * "name": "编辑",
 * "route_as": "order-no-rule.update",
 * "type": 2
 * },
 * {
 * "id": 162,
 * "parent_id": 157,
 * "name": "删除",
 * "route_as": "order-no-rule.destroy",
 * "type": 2
 * },
 * {
 * "id": 164,
 * "parent_id": 163,
 * "name": "查看",
 * "route_as": "print-template.show",
 * "type": 2
 * },
 * {
 * "id": 165,
 * "parent_id": 163,
 * "name": "编辑",
 * "route_as": "print-template.update",
 * "type": 2
 * },
 * {
 * "id": 167,
 * "parent_id": 166,
 * "name": "查看",
 * "route_as": "fee.index",
 * "type": 2
 * },
 * {
 * "id": 168,
 * "parent_id": 166,
 * "name": "详情",
 * "route_as": "fee.show",
 * "type": 2
 * },
 * {
 * "id": 169,
 * "parent_id": 166,
 * "name": "新增",
 * "route_as": "fee.store",
 * "type": 2
 * },
 * {
 * "id": 170,
 * "parent_id": 166,
 * "name": "编辑",
 * "route_as": "fee.update",
 * "type": 2
 * },
 * {
 * "id": 171,
 * "parent_id": 166,
 * "name": "删除",
 * "route_as": "fee.destroy",
 * "type": 2
 * },
 * {
 * "id": 173,
 * "parent_id": 172,
 * "name": "查看",
 * "route_as": "transport-price.index",
 * "type": 2
 * },
 * {
 * "id": 174,
 * "parent_id": 172,
 * "name": "详情",
 * "route_as": "transport-price.show",
 * "type": 2
 * },
 * {
 * "id": 175,
 * "parent_id": 172,
 * "name": "新增",
 * "route_as": "transport-price.store",
 * "type": 2
 * },
 * {
 * "id": 176,
 * "parent_id": 172,
 * "name": "编辑",
 * "route_as": "transport-price.update",
 * "type": 2
 * },
 * {
 * "id": 177,
 * "parent_id": 172,
 * "name": "启用/禁用",
 * "route_as": "transport-price.status",
 * "type": 2
 * },
 * {
 * "id": 180,
 * "parent_id": 179,
 * "name": "查看",
 * "route_as": "package-no-rule.index",
 * "type": 2
 * },
 * {
 * "id": 181,
 * "parent_id": 179,
 * "name": "详情",
 * "route_as": "package-no-rule.show",
 * "type": 2
 * },
 * {
 * "id": 182,
 * "parent_id": 179,
 * "name": "新增",
 * "route_as": "package-no-rule.store",
 * "type": 2
 * },
 * {
 * "id": 183,
 * "parent_id": 179,
 * "name": "编辑",
 * "route_as": "package-no-rule.update",
 * "type": 2
 * },
 * {
 * "id": 184,
 * "parent_id": 179,
 * "name": "删除",
 * "route_as": "package-no-rule.destroy",
 * "type": 2
 * },
 * {
 * "id": 192,
 * "parent_id": 191,
 * "name": "查看",
 * "route_as": "holiday.index",
 * "type": 2
 * },
 * {
 * "id": 193,
 * "parent_id": 191,
 * "name": "详情",
 * "route_as": "holiday.show",
 * "type": 2
 * },
 * {
 * "id": 194,
 * "parent_id": 191,
 * "name": "新增",
 * "route_as": "holiday.store",
 * "type": 2
 * },
 * {
 * "id": 195,
 * "parent_id": 191,
 * "name": "编辑",
 * "route_as": "holiday.update",
 * "type": 2
 * },
 * {
 * "id": 196,
 * "parent_id": 191,
 * "name": "删除",
 * "route_as": "holiday.destroy",
 * "type": 2
 * },
 * {
 * "id": 197,
 * "parent_id": 238,
 * "name": "订单同步",
 * "route_as": "order.synchronize",
 * "type": 2
 * },
 * {
 * "id": 198,
 * "parent_id": 42,
 * "name": "打印",
 * "route_as": "tracking-order.print",
 * "type": 2
 * },
 * {
 * "id": 199,
 * "parent_id": 42,
 * "name": "对接日志",
 * "route_as": "tracking-order.third-party-log",
 * "type": 2
 * },
 * {
 * "id": 200,
 * "parent_id": 42,
 * "name": "修改出库状态",
 * "route_as": "tracking-order.out-status",
 * "type": 2
 * },
 * {
 * "id": 201,
 * "parent_id": 50,
 * "name": "详情",
 * "route_as": "batch.show",
 * "type": 2
 * },
 * {
 * "id": 203,
 * "parent_id": 202,
 * "name": "绑定",
 * "route_as": "device.bind",
 * "type": 2
 * },
 * {
 * "id": 204,
 * "parent_id": 202,
 * "name": "解绑",
 * "route_as": "device.unBind",
 * "type": 2
 * },
 * {
 * "id": 205,
 * "parent_id": 71,
 * "name": "详情",
 * "route_as": "recharge.show",
 * "type": 2
 * },
 * {
 * "id": 206,
 * "parent_id": 81,
 * "name": "详情",
 * "route_as": "stock-exception.show",
 * "type": 2
 * },
 * {
 * "id": 207,
 * "parent_id": 209,
 * "name": "详情",
 * "route_as": "line.post-code-show",
 * "type": 2
 * },
 * {
 * "id": 208,
 * "parent_id": 209,
 * "name": "导入",
 * "route_as": "line.post-code-import",
 * "type": 2
 * },
 * {
 * "id": 211,
 * "parent_id": 210,
 * "name": "查看",
 * "route_as": "line.area-index",
 * "type": 2
 * },
 * {
 * "id": 212,
 * "parent_id": 210,
 * "name": "新增",
 * "route_as": "line.area-show",
 * "type": 2
 * },
 * {
 * "id": 213,
 * "parent_id": 210,
 * "name": "编辑",
 * "route_as": "line.area-store",
 * "type": 2
 * },
 * {
 * "id": 214,
 * "parent_id": 210,
 * "name": "删除",
 * "route_as": "line.area-update",
 * "type": 2
 * },
 * {
 * "id": 215,
 * "parent_id": 210,
 * "name": "修改状态",
 * "route_as": "line.area-destroy",
 * "type": 2
 * },
 * {
 * "id": 216,
 * "parent_id": 94,
 * "name": "锁定/解锁",
 * "route_as": "driver.lock",
 * "type": 2
 * },
 * {
 * "id": 217,
 * "parent_id": 94,
 * "name": "修改密码",
 * "route_as": "update-password",
 * "type": 2
 * },
 * {
 * "id": 218,
 * "parent_id": 100,
 * "name": "新增",
 * "route_as": "car.store",
 * "type": 2
 * },
 * {
 * "id": 219,
 * "parent_id": 100,
 * "name": "解锁/锁定",
 * "route_as": "car.lock",
 * "type": 2
 * },
 * {
 * "id": 220,
 * "parent_id": 100,
 * "name": "导出里程",
 * "route_as": "export-distance",
 * "type": 2
 * },
 * {
 * "id": 221,
 * "parent_id": 100,
 * "name": "导出信息",
 * "route_as": "export-info",
 * "type": 2
 * },
 * {
 * "id": 222,
 * "parent_id": 106,
 * "name": "修改密码",
 * "route_as": "merchant.update-password",
 * "type": 2
 * },
 * {
 * "id": 223,
 * "parent_id": 106,
 * "name": "启用/禁用",
 * "route_as": "merchant.status",
 * "type": 2
 * },
 * {
 * "id": 224,
 * "parent_id": 106,
 * "name": "导出",
 * "route_as": "merchant.excel",
 * "type": 2
 * },
 * {
 * "id": 225,
 * "parent_id": 111,
 * "name": "新增",
 * "route_as": "merchant-group.store",
 * "type": 2
 * },
 * {
 * "id": 226,
 * "parent_id": 111,
 * "name": "修改运价",
 * "route_as": "merchant-group.transport-price",
 * "type": 2
 * },
 * {
 * "id": 227,
 * "parent_id": 111,
 * "name": "配置",
 * "route_as": "merchant-group.config",
 * "type": 2
 * },
 * {
 * "id": 228,
 * "parent_id": 111,
 * "name": "启用/禁用",
 * "route_as": "merchant-group.status",
 * "type": 2
 * },
 * {
 * "id": 229,
 * "parent_id": 116,
 * "name": "启用/禁用",
 * "route_as": "merchant-api.status",
 * "type": 2
 * },
 * {
 * "id": 230,
 * "parent_id": 143,
 * "name": "启用/禁用",
 * "route_as": "employees.set-login",
 * "type": 2
 * },
 * {
 * "id": 231,
 * "parent_id": 143,
 * "name": "修改密码",
 * "route_as": "employees.reset-password",
 * "type": 2
 * },
 * {
 * "id": 232,
 * "parent_id": 172,
 * "name": "价格测试",
 * "route_as": "transport-price.test",
 * "type": 2
 * },
 * {
 * "id": 233,
 * "parent_id": 191,
 * "name": "启用/禁用",
 * "route_as": "holiday.status",
 * "type": 2
 * },
 * {
 * "id": 234,
 * "parent_id": 191,
 * "name": "货主查询",
 * "route_as": "holiday.merchant-index",
 * "type": 2
 * },
 * {
 * "id": 235,
 * "parent_id": 191,
 * "name": "货主新增",
 * "route_as": "holiday.merchant-store",
 * "type": 2
 * },
 * {
 * "id": 236,
 * "parent_id": 191,
 * "name": "货主删除",
 * "route_as": "holiday.merchant-destroy",
 * "type": 2
 * }
 * ],
 * "menu_list": [
 * {
 * "id": 1,
 * "parent_id": 0,
 * "name": "首页",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 4,
 * "parent_id": 1,
 * "name": "首页",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 5,
 * "parent_id": 4,
 * "name": "数据概览",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * }
 * ]
 * },
 * {
 * "id": 7,
 * "parent_id": 0,
 * "name": "订单管理",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 8,
 * "parent_id": 7,
 * "name": "订单",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 238,
 * "parent_id": 8,
 * "name": "订单",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * },
 * {
 * "id": 22,
 * "parent_id": 7,
 * "name": "内容物",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 23,
 * "parent_id": 22,
 * "name": "包裹",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 25,
 * "parent_id": 22,
 * "name": "材料",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 27,
 * "parent_id": 22,
 * "name": "顺带包裹",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * },
 * {
 * "id": 29,
 * "parent_id": 7,
 * "name": "物流查询",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * },
 * {
 * "id": 70,
 * "parent_id": 0,
 * "name": "财务管理",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 240,
 * "parent_id": 70,
 * "name": "财务管理",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 71,
 * "parent_id": 240,
 * "name": "现金充值",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * }
 * ]
 * },
 * {
 * "id": 74,
 * "parent_id": 0,
 * "name": "库存管理",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 241,
 * "parent_id": 74,
 * "name": "库存",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 75,
 * "parent_id": 241,
 * "name": "库存",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 77,
 * "parent_id": 241,
 * "name": "出库记录",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 79,
 * "parent_id": 241,
 * "name": "入库记录",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 81,
 * "parent_id": 241,
 * "name": "入库异常",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * }
 * ]
 * },
 * {
 * "id": 84,
 * "parent_id": 0,
 * "name": "资料管理",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 85,
 * "parent_id": 84,
 * "name": "线路规划",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 86,
 * "parent_id": 85,
 * "name": "线路规划",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 209,
 * "parent_id": 86,
 * "name": "邮编",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 210,
 * "parent_id": 86,
 * "name": "区域",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * }
 * ]
 * },
 * {
 * "id": 93,
 * "parent_id": 84,
 * "name": "车队管理",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 94,
 * "parent_id": 93,
 * "name": "司机",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 100,
 * "parent_id": 93,
 * "name": "车辆",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * },
 * {
 * "id": 105,
 * "parent_id": 84,
 * "name": "往来单位",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 106,
 * "parent_id": 105,
 * "name": "用户",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 111,
 * "parent_id": 105,
 * "name": "用户组",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 116,
 * "parent_id": 105,
 * "name": "API对接",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 122,
 * "parent_id": 105,
 * "name": "地址",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * }
 * ]
 * },
 * {
 * "id": 128,
 * "parent_id": 0,
 * "name": "配置管理",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 129,
 * "parent_id": 128,
 * "name": "企业信息",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 130,
 * "parent_id": 129,
 * "name": "公司信息",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 133,
 * "parent_id": 129,
 * "name": "高级配置",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 136,
 * "parent_id": 129,
 * "name": "网点",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * },
 * {
 * "id": 142,
 * "parent_id": 128,
 * "name": "员工",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 143,
 * "parent_id": 142,
 * "name": "员工管理",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 149,
 * "parent_id": 142,
 * "name": "权限组管理",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * },
 * {
 * "id": 156,
 * "parent_id": 128,
 * "name": "参数配置",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 157,
 * "parent_id": 156,
 * "name": "单号规则",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 163,
 * "parent_id": 156,
 * "name": "打印配置",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 166,
 * "parent_id": 156,
 * "name": "增值服务",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 172,
 * "parent_id": 156,
 * "name": "运价",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * },
 * {
 * "id": 178,
 * "parent_id": 128,
 * "name": "特殊配置",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 179,
 * "parent_id": 178,
 * "name": "顺带规则",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 191,
 * "parent_id": 178,
 * "name": "假期",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * }
 * ]
 * },
 * {
 * "id": 239,
 * "parent_id": 0,
 * "name": "运输管理",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 31,
 * "parent_id": 239,
 * "name": "运输任务",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 32,
 * "parent_id": 31,
 * "name": "任务",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 42,
 * "parent_id": 31,
 * "name": "运单",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 50,
 * "parent_id": 31,
 * "name": "站点",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * },
 * {
 * "id": 55,
 * "parent_id": 239,
 * "name": "异常管理",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 56,
 * "parent_id": 55,
 * "name": "延迟记录",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 58,
 * "parent_id": 55,
 * "name": "派送异常",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * },
 * {
 * "id": 62,
 * "parent_id": 239,
 * "name": "车辆设备",
 * "route_as": "",
 * "type": 1,
 * "children": [
 * {
 * "id": 68,
 * "parent_id": 62,
 * "name": "智能管车",
 * "route_as": "",
 * "type": 1
 * },
 * {
 * "id": 202,
 * "parent_id": 62,
 * "name": "设备绑定",
 * "route_as": "",
 * "type": 1
 * }
 * ]
 * }
 * ]
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/timezone 切换时区
 * @apiName 切换时区
 * @apiGroup 00
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} timezone 时区
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/warehouse 网点查询
 * @apiName 网点查询
 * @apiGroup 01
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} country 国家
 * @apiParam {string} acceptance_type 网点功能1-取-2派3-仓配一体
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 网点ID
 * @apiSuccess {string} data.data.name 网点名
 * @apiSuccess {string} data.data.contacter 联系人
 * @apiSuccess {string} data.data.phone 电话
 * @apiSuccess {string} data.data.country 国家
 * @apiSuccess {string} data.data.post_code 邮编
 * @apiSuccess {string} data.data.house_number 门牌号
 * @apiSuccess {string} data.data.city 城市
 * @apiSuccess {string} data.data.street 街道
 * @apiSuccess {string} data.data.address 地址
 * @apiSuccess {string} data.data.lon 经度
 * @apiSuccess {string} data.data.lat 纬度
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.data.updated_at 更新时间
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.data.warehouse_name 网点名
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 12,
 * "name": "麓谷企业广场6",
 * "contacter": "胡洋铭",
 * "phone": "17570715315",
 * "country": "NL",
 * "post_code": "1",
 * "house_number": "808",
 * "city": "长沙",
 * "street": "岳麓区",
 * "address": "C3",
 * "lon": "",
 * "lat": "",
 * "created_at": "2019-12-26 05:32:31",
 * "updated_at": "2019-12-27 03:11:02"
 * },
 * {
 * "name": "麓谷企业广场1",
 * "contacter": "胡洋铭",
 * "phone": "17570715315",
 * "country": "NL",
 * "post_code": "1",
 * "house_number": "808",
 * "city": "长沙",
 * "street": "岳麓区",
 * "address": "C3",
 * "lon": "",
 * "lat": "",
 * "created_at": "2019-12-26 06:36:50",
 * "updated_at": "2019-12-26 06:36:50"
 * },
 * {
 * "name": "麓谷企业广场",
 * "contacter": "胡洋铭",
 * "phone": "17570715315",
 * "country": "NL",
 * "post_code": "1",
 * "house_number": "808",
 * "city": "长沙",
 * "street": "岳麓区",
 * "address": "C3",
 * "lon": "",
 * "lat": "",
 * "created_at": "2019-12-26 10:05:44",
 * "updated_at": "2019-12-26 10:05:44"
 * },
 * {
 * "name": "麓谷企业广场5",
 * "contacter": "胡洋铭",
 * "phone": "17570715315",
 * "country": "NL",
 * "post_code": "1",
 * "house_number": "808",
 * "city": "长沙",
 * "street": "岳麓区",
 * "address": "C3",
 * "lon": "",
 * "lat": "",
 * "created_at": "2019-12-27 02:54:11",
 * "updated_at": "2019-12-27 02:54:11"
 * },
 * {
 * "name": "麓谷企业广场7",
 * "contacter": "胡洋铭",
 * "phone": "17570715315",
 * "country": "NL",
 * "post_code": "1000",
 * "house_number": "808",
 * "city": "长沙",
 * "street": "岳麓区",
 * "address": "C3",
 * "lon": "",
 * "lat": "",
 * "created_at": "2019-12-27 03:04:25",
 * "updated_at": "2019-12-27 03:04:25"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/warehouse?page=1",
 * "last": "http://tms-api.test/api/admin/warehouse?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/warehouse",
 * "per_page": 15,
 * "to": 5,
 * "total": 5
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/warehouse/{id} 网点详情
 * @apiName 网点详情
 * @apiGroup 01
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 网点ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.name
 * @apiSuccess {string} data.contacter
 * @apiSuccess {string} data.phone
 * @apiSuccess {string} data.country
 * @apiSuccess {string} data.post_code
 * @apiSuccess {string} data.house_number
 * @apiSuccess {string} data.city
 * @apiSuccess {string} data.street
 * @apiSuccess {string} data.address
 * @apiSuccess {string} data.lon 经度
 * @apiSuccess {string} data.lat 纬度
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "name": "麓谷企业广场6",
 * "contacter": "胡洋铭",
 * "phone": "17570715315",
 * "country": "NL",
 * "post_code": "1",
 * "house_number": "808",
 * "city": "长沙",
 * "street": "岳麓区",
 * "address": "C3",
 * "lon": "",
 * "lat": "",
 * "created_at": "2019-12-26 05:32:31",
 * "updated_at": "2019-12-27 03:11:02"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/warehouse 网点新增
 * @apiName 网点新增
 * @apiGroup 01
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 网点名称（不能重名）
 * @apiParam {string} fullname 联系人
 * @apiParam {string} phone 电话
 * @apiParam {string} country 国家
 * @apiParam {string} post_code 邮编
 * @apiParam {string} house_number 门牌号
 * @apiParam {string} city 城市
 * @apiParam {string} street 街道
 * @apiParam {string} address 地址
 * @apiParam {string} lon 经度
 * @apiParam {string} lat 纬度
 * @apiParam {string} type 类型1-加盟2-自营
 * @apiParam {string} is_center 是否为分拨中心1-是2-否
 * @apiParam {string} acceptance_type 承接类型列表1-取件2-派件3-直送
 * @apiParam {string} line_ids 线路ID列表
 * @apiParam {string} company_name 公司
 * @apiParam {string} email 邮箱
 * @apiParam {string} avatar 头像
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

/**
 * @api {delete} /admin/warehouse/{id} 网点删除
 * @apiName 网点删除
 * @apiGroup 01
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 网点ID
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

/**
 * @api {put} /admin/warehouse/{id} 网点修改
 * @apiName 网点修改
 * @apiGroup 01
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 网点ID
 * @apiParam {string} name 网点名称（不能重名）
 * @apiParam {string} contacter 联系人
 * @apiParam {string} phone 电话
 * @apiParam {string} country 国家
 * @apiParam {string} post_code 邮编
 * @apiParam {string} house_number 门牌号
 * @apiParam {string} city 城市
 * @apiParam {string} street 街道
 * @apiParam {string} address 地址
 * @apiParam {string} lon 经度
 * @apiParam {string} lat 纬度
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

/**
 * @api {get} /admin/warehouse/tree 树节点
 * @apiName 树节点
 * @apiGroup 01
 * @apiVersion 1.0.0
 * @apiUse auth
 */

/**
 * @api {put} /admin/warehouse/:id/move/:parent 移动节点
 * @apiName 移动节点
 * @apiGroup 01
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 网点ID
 * @apiParam {string} parent 父级网点ID
 */

/**
 * @api {get} /admin/warehouse/{id}/line 获取网点的线路列表
 * @apiName 获取网点的线路列表
 * @apiGroup 01
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 网点ID
 * @apiParam {string} per_page 每页显示条数
 * @apiParam {string} page 页数
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.name 线路名称
 * @apiSuccess {string} data.data.country
 * @apiSuccess {string} data.data.country_name
 * @apiSuccess {string} data.data.can_skip_batch
 * @apiSuccess {string} data.data.warehouse_id
 * @apiSuccess {string} data.data.pickup_max_count
 * @apiSuccess {string} data.data.pie_max_count
 * @apiSuccess {string} data.data.is_increment
 * @apiSuccess {string} data.data.order_deadline
 * @apiSuccess {string} data.data.appointment_days
 * @apiSuccess {string} data.data.creator_name
 * @apiSuccess {string} data.data.remark
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.data.line_range
 * @apiSuccess {string} data.data.work_day_list 工作时间
 * @apiSuccess {string} data.data.coordinate_list
 * @apiSuccess {string} data.data.merchant_group_count_list
 * @apiSuccess {string} data.data.status
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1018,
 * "company_id": 3,
 * "name": "Arnhem(2)",
 * "country": "NL",
 * "country_name": "荷兰",
 * "can_skip_batch": 2,
 * "warehouse_id": 4,
 * "pickup_max_count": 30,
 * "pie_max_count": 30,
 * "is_increment": 2,
 * "order_deadline": "00:00:00",
 * "appointment_days": 30,
 * "creator_name": "TMS测试",
 * "remark": "",
 * "created_at": "2020-08-11 05:13:48",
 * "updated_at": "2020-12-08 09:32:42",
 * "line_range": "4001-4014;6500-6559;6800-6939;4103-4103;4117-4117;4142-4142;4033-4033;4200-4229",
 * "work_day_list": "星期二",
 * "coordinate_list": null,
 * "merchant_group_count_list": null,
 * "status": 1
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test:10002/api/admin/warehouse/27/line?page=1",
 * "last": "http://tms-api.test:10002/api/admin/warehouse/27/line?page=11",
 * "prev": "http://tms-api.test:10002/api/admin/warehouse/27/line?page=1",
 * "next": "http://tms-api.test:10002/api/admin/warehouse/27/line?page=3"
 * },
 * "meta": {
 * "current_page": 2,
 * "from": 2,
 * "last_page": 11,
 * "path": "http://tms-api.test:10002/api/admin/warehouse/27/line",
 * "per_page": "1",
 * "to": 2,
 * "total": 11
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/warehouse/{id}/all-line 获取网点可选的线路列表
 * @apiName 获取网点可选的线路列表
 * @apiGroup 01
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 网点ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.name 线路名称
 * @apiSuccess {string} data.data.country
 * @apiSuccess {string} data.data.country_name
 * @apiSuccess {string} data.data.can_skip_batch
 * @apiSuccess {string} data.data.warehouse_id
 * @apiSuccess {string} data.data.pickup_max_count
 * @apiSuccess {string} data.data.pie_max_count
 * @apiSuccess {string} data.data.is_increment
 * @apiSuccess {string} data.data.order_deadline
 * @apiSuccess {string} data.data.appointment_days
 * @apiSuccess {string} data.data.creator_name
 * @apiSuccess {string} data.data.remark
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.data.line_range
 * @apiSuccess {string} data.data.work_day_list 工作时间
 * @apiSuccess {string} data.data.coordinate_list
 * @apiSuccess {string} data.data.merchant_group_count_list
 * @apiSuccess {string} data.data.status
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1018,
 * "company_id": 3,
 * "name": "Arnhem(2)",
 * "country": "NL",
 * "country_name": "荷兰",
 * "can_skip_batch": 2,
 * "warehouse_id": 4,
 * "pickup_max_count": 30,
 * "pie_max_count": 30,
 * "is_increment": 2,
 * "order_deadline": "00:00:00",
 * "appointment_days": 30,
 * "creator_name": "TMS测试",
 * "remark": "",
 * "created_at": "2020-08-11 05:13:48",
 * "updated_at": "2020-12-08 09:32:42",
 * "line_range": "4001-4014;6500-6559;6800-6939;4103-4103;4117-4117;4142-4142;4033-4033;4200-4229",
 * "work_day_list": "星期二",
 * "coordinate_list": null,
 * "merchant_group_count_list": null,
 * "status": 1
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test:10002/api/admin/warehouse/27/line?page=1",
 * "last": "http://tms-api.test:10002/api/admin/warehouse/27/line?page=11",
 * "prev": "http://tms-api.test:10002/api/admin/warehouse/27/line?page=1",
 * "next": "http://tms-api.test:10002/api/admin/warehouse/27/line?page=3"
 * },
 * "meta": {
 * "current_page": 2,
 * "from": 2,
 * "last_page": 11,
 * "path": "http://tms-api.test:10002/api/admin/warehouse/27/line",
 * "per_page": "1",
 * "to": 2,
 * "total": 11
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/warehouse/{id}/line 网点新增线路
 * @apiName 网点新增线路
 * @apiGroup 01
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} line_ids 线路ID列表
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

/**
 * @api {delete} /admin/warehouse/{id}/line 网点移除线路
 * @apiName 网点移除线路
 * @apiGroup 01
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} line_ids 线路ID列表
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

/**
 * @api {get} /admin/order/pickup-count 取件订单查询初始化
 * @apiName 取件订单查询初始化
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.no_take 待分配
 * @apiSuccess {string} data.assign 已分配
 * @apiSuccess {string} data.wait_out 待出库
 * @apiSuccess {string} data.taking 取派中
 * @apiSuccess {string} data.singed 已签收
 * @apiSuccess {string} data.cancel_count 取消取派
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.exception_count 异常订单
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "no_take": 2,
 * "assign": 0,
 * "wait_out": 0,
 * "taking": 0,
 * "singed": 8,
 * "cancel_count": 3
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/order 订单查询
 * @apiName 订单查询
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} status 状态1-未取派2-取派中3-已完成4-取派失败5-回收站
 * @apiParam {string} type 类型（1-取，2-派,3-取派）
 * @apiParam {string} begin_date 开始日期
 * @apiParam {string} end_date 结束日期
 * @apiParam {string} keyword 关键字(订单号或外部订单号)
 * @apiParam {string} merchant_id 货主ID
 * @apiParam {string} exception_label 异常标志(1-正常2-异常)
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.merchant_id
 * @apiSuccess {string} data.data.merchant_id_name 货主名称
 * @apiSuccess {string} data.data.order_no 订单号
 * @apiSuccess {string} data.data.source
 * @apiSuccess {string} data.data.source_name 来源名称
 * @apiSuccess {string} data.data.mask_code 掩码
 * @apiSuccess {string} data.data.list_mode 模式
 * @apiSuccess {string} data.data.type
 * @apiSuccess {string} data.data.type_name 类型名称
 * @apiSuccess {string} data.data.out_user_id 外部客户ID
 * @apiSuccess {string} data.data.status
 * @apiSuccess {string} data.data.status_name 状态名称
 * @apiSuccess {string} data.data.out_status
 * @apiSuccess {string} data.data.out_status_name 出库状态名称
 * @apiSuccess {string} data.data.execution_date 取派日期
 * @apiSuccess {string} data.data.out_order_no 外部订单号
 * @apiSuccess {string} data.data.exception_label 异常标签1-正常2-异常
 * @apiSuccess {string} data.data.exception_label_name
 * @apiSuccess {string} data.data.place_post_code
 * @apiSuccess {string} data.data.place_house_number
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "company_id": 1,
 * "order_no": "TMS00010000000000035",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2019-12-29",
 * "batch_no": "BATCH00010000000000015",
 * "tour_no": "TOUR0001000018",
 * "out_order_no": "1",
 * "source": "ERP",
 * "receiver_post_code": "1000",
 * "receiver_house_number": "20",
 * "driver_name": null,
 * "created_at": "2019-12-26 09:23:11",
 * "updated_at": "2019-12-26 09:23:11"
 * },
 * {
 * "id": 2,
 * "company_id": 1,
 * "order_no": "TMS00010000000000036",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2019-12-29",
 * "batch_no": "BATCH00010000000000015",
 * "tour_no": "TOUR0001000018",
 * "out_order_no": "2",
 * "source": "ERP",
 * "receiver_post_code": "1000",
 * "receiver_house_number": "20",
 * "driver_name": null,
 * "created_at": "2019-12-26 09:23:26",
 * "updated_at": "2019-12-26 09:23:26"
 * },
 * {
 * "id": 3,
 * "company_id": 1,
 * "order_no": "TMS00010000000000037",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2019-12-29",
 * "batch_no": "BATCH00010000000000016",
 * "tour_no": "TOUR0001000019",
 * "out_order_no": "3",
 * "source": "ERP",
 * "receiver_post_code": "1001",
 * "receiver_house_number": "20",
 * "driver_name": null,
 * "created_at": "2019-12-26 09:23:41",
 * "updated_at": "2019-12-26 09:23:42"
 * },
 * {
 * "id": 4,
 * "company_id": 1,
 * "order_no": "TMS00010000000000038",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2019-12-29",
 * "batch_no": "BATCH00010000000000017",
 * "tour_no": "TOUR0001000019",
 * "out_order_no": "4",
 * "source": "ERP",
 * "receiver_post_code": "1000",
 * "receiver_house_number": "20",
 * "driver_name": null,
 * "created_at": "2019-12-26 09:23:53",
 * "updated_at": "2019-12-26 09:23:54"
 * },
 * {
 * "id": 5,
 * "company_id": 1,
 * "order_no": "TMS00010000000000039",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2019-12-29",
 * "batch_no": "BATCH00010000000000018",
 * "tour_no": "TOUR0001000020",
 * "out_order_no": "41",
 * "source": "ERP",
 * "receiver_post_code": "1000",
 * "receiver_house_number": "20",
 * "driver_name": null,
 * "created_at": "2019-12-26 09:30:44",
 * "updated_at": "2019-12-26 09:30:44"
 * },
 * {
 * "id": 6,
 * "company_id": 1,
 * "order_no": "TMS00010000000000040",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2019-12-29",
 * "batch_no": "BATCH00010000000000018",
 * "tour_no": "TOUR0001000020",
 * "out_order_no": "42",
 * "source": "ERP",
 * "receiver_post_code": "1000",
 * "receiver_house_number": "20",
 * "driver_name": null,
 * "created_at": "2019-12-26 09:30:55",
 * "updated_at": "2019-12-26 09:30:55"
 * },
 * {
 * "id": 7,
 * "company_id": 1,
 * "order_no": "TMS00010000000000041",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2019-12-29",
 * "batch_no": "BATCH00010000000000019",
 * "tour_no": "TOUR0001000021",
 * "out_order_no": "43",
 * "source": "ERP",
 * "receiver_post_code": "1000",
 * "receiver_house_number": "21",
 * "driver_name": null,
 * "created_at": "2019-12-26 09:37:48",
 * "updated_at": "2019-12-26 09:37:49"
 * },
 * {
 * "id": 8,
 * "company_id": 1,
 * "order_no": "TMS00010000000000042",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2019-12-29",
 * "batch_no": "BATCH00010000000000019",
 * "tour_no": "TOUR0001000021",
 * "out_order_no": "44",
 * "source": "ERP",
 * "receiver_post_code": "1000",
 * "receiver_house_number": "21",
 * "driver_name": null,
 * "created_at": "2019-12-26 09:38:00",
 * "updated_at": "2019-12-26 09:38:00"
 * },
 * {
 * "id": 9,
 * "company_id": 1,
 * "order_no": "TMS00010000000000043",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2019-12-29",
 * "batch_no": "BATCH00010000000000020",
 * "tour_no": "TOUR0001000022",
 * "out_order_no": "45",
 * "source": "ERP",
 * "receiver_post_code": "1000",
 * "receiver_house_number": "21",
 * "driver_name": null,
 * "created_at": "2019-12-26 09:40:01",
 * "updated_at": "2019-12-26 09:40:02"
 * },
 * {
 * "id": 10,
 * "company_id": 1,
 * "order_no": "TMS00010000000000044",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2019-12-29",
 * "batch_no": "BATCH00010000000000021",
 * "tour_no": "TOUR0001000022",
 * "out_order_no": "46",
 * "source": "ERP",
 * "receiver_post_code": "1000",
 * "receiver_house_number": "20",
 * "driver_name": null,
 * "created_at": "2019-12-26 09:40:25",
 * "updated_at": "2019-12-26 09:40:26"
 * },
 * {
 * "id": 11,
 * "company_id": 1,
 * "order_no": "TMS00010000000000045",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2019-12-29",
 * "batch_no": "BATCH00010000000000022",
 * "tour_no": "TOUR0001000023",
 * "out_order_no": "123442",
 * "source": "ERP",
 * "receiver_post_code": "1000",
 * "receiver_house_number": "20",
 * "driver_name": null,
 * "created_at": "2019-12-26 10:53:30",
 * "updated_at": "2019-12-26 10:53:31"
 * },
 * {
 * "id": 14,
 * "company_id": 1,
 * "order_no": "TMS00010000000000046",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2019-12-29",
 * "batch_no": "BATCH00010000000000023",
 * "tour_no": "TOUR0001000024",
 * "out_order_no": "12343442",
 * "source": "ERP",
 * "receiver_post_code": "2500",
 * "receiver_house_number": "20",
 * "driver_name": null,
 * "created_at": "2019-12-26 10:55:10",
 * "updated_at": "2019-12-26 10:55:10"
 * },
 * {
 * "id": 15,
 * "company_id": 1,
 * "order_no": "TMS00010000000000047",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2019-12-29",
 * "batch_no": "BATCH00010000000000024",
 * "tour_no": "TOUR0001000024",
 * "out_order_no": "1234233442",
 * "source": "ERP",
 * "receiver_post_code": "2400",
 * "receiver_house_number": "20",
 * "driver_name": null,
 * "created_at": "2019-12-26 10:55:29",
 * "updated_at": "2019-12-26 10:55:29"
 * },
 * {
 * "id": 16,
 * "company_id": 1,
 * "order_no": "TMS00010000000000048",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2019-12-29",
 * "batch_no": "BATCH00010000000000025",
 * "tour_no": "TOUR0001000024",
 * "out_order_no": "1234233213442",
 * "source": "ERP",
 * "receiver_post_code": "2440",
 * "receiver_house_number": "20",
 * "driver_name": null,
 * "created_at": "2019-12-26 10:55:37",
 * "updated_at": "2019-12-26 10:55:38"
 * },
 * {
 * "id": 17,
 * "company_id": 1,
 * "order_no": "TMS00010000000000049",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2019-12-29",
 * "batch_no": "BATCH00010000000000025",
 * "tour_no": "TOUR0001000024",
 * "out_order_no": "12342332134442",
 * "source": "ERP",
 * "receiver_post_code": "2440",
 * "receiver_house_number": "20",
 * "driver_name": null,
 * "created_at": "2019-12-26 10:55:43",
 * "updated_at": "2019-12-26 10:55:43"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/order?page=1",
 * "last": "http://tms-api.test/api/admin/order?page=2",
 * "prev": null,
 * "next": "http://tms-api.test/api/admin/order?page=2"
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 2,
 * "path": "http://tms-api.test/api/admin/order",
 * "per_page": 15,
 * "to": 15,
 * "total": 20
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/order/{id} 订单详情
 * @apiName 订单详情
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 订单ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.merchant_id
 * @apiSuccess {string} data.merchant_id_name 货主名称
 * @apiSuccess {string} data.order_no 订单号
 * @apiSuccess {string} data.execution_date 取派日期
 * @apiSuccess {string} data.second_execution_date 取派订单中的派件日期
 * @apiSuccess {string} data.out_order_no 外部订单号
 * @apiSuccess {string} data.mask_code 掩码
 * @apiSuccess {string} data.source
 * @apiSuccess {string} data.source_name 来源名称
 * @apiSuccess {string} data.list_mode
 * @apiSuccess {string} data.type_name 类型名称
 * @apiSuccess {string} data.out_user_id 外部客户ID
 * @apiSuccess {string} data.nature
 * @apiSuccess {string} data.settlement_type 结算类型
 * @apiSuccess {string} data.settlement_amount 结算金额
 * @apiSuccess {string} data.replace_amount
 * @apiSuccess {string} data.delivery
 * @apiSuccess {string} data.status
 * @apiSuccess {string} data.status_name 状态名称
 * @apiSuccess {string} data.second_place_fullname 发件人姓名
 * @apiSuccess {string} data.second_place_phone
 * @apiSuccess {string} data.second_place_country
 * @apiSuccess {string} data.second_place_country_name
 * @apiSuccess {string} data.second_place_post_code
 * @apiSuccess {string} data.second_place_house_number
 * @apiSuccess {string} data.second_place_city
 * @apiSuccess {string} data.second_place_street
 * @apiSuccess {string} data.second_place_address
 * @apiSuccess {string} data.second_place_lon
 * @apiSuccess {string} data.second_place_lat
 * @apiSuccess {string} data.place_fullname 收件人姓名
 * @apiSuccess {string} data.place_phone
 * @apiSuccess {string} data.place_country
 * @apiSuccess {string} data.place_country_name
 * @apiSuccess {string} data.place_post_code
 * @apiSuccess {string} data.place_house_number
 * @apiSuccess {string} data.place_city
 * @apiSuccess {string} data.place_street
 * @apiSuccess {string} data.place_address
 * @apiSuccess {string} data.place_lon
 * @apiSuccess {string} data.place_lat
 * @apiSuccess {string} data.special_remark 特殊事项
 * @apiSuccess {string} data.remark 其余备注
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.item_list
 * @apiSuccess {string} data.package_list 包裹列表
 * @apiSuccess {string} data.package_list.id
 * @apiSuccess {string} data.package_list.company_id
 * @apiSuccess {string} data.package_list.merchant_id
 * @apiSuccess {string} data.package_list.order_no
 * @apiSuccess {string} data.package_list.execution_date
 * @apiSuccess {string} data.package_list.type
 * @apiSuccess {string} data.package_list.name
 * @apiSuccess {string} data.package_list.express_first_no
 * @apiSuccess {string} data.package_list.express_second_no
 * @apiSuccess {string} data.package_list.feature_logo
 * @apiSuccess {string} data.package_list.out_order_no
 * @apiSuccess {string} data.package_list.weight
 * @apiSuccess {string} data.package_list.expect_quantity
 * @apiSuccess {string} data.package_list.actual_quantity
 * @apiSuccess {string} data.package_list.status
 * @apiSuccess {string} data.package_list.sticker_no
 * @apiSuccess {string} data.package_list.sticker_amount
 * @apiSuccess {string} data.package_list.delivery_amount
 * @apiSuccess {string} data.package_list.remark
 * @apiSuccess {string} data.package_list.is_auth
 * @apiSuccess {string} data.package_list.auth_fullname
 * @apiSuccess {string} data.package_list.auth_birth_date
 * @apiSuccess {string} data.package_list.created_at
 * @apiSuccess {string} data.package_list.updated_at
 * @apiSuccess {string} data.package_list.tracking_order_no
 * @apiSuccess {string} data.package_list.status_name
 * @apiSuccess {string} data.package_list.type_name
 * @apiSuccess {string} data.material_list 材料列表
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.package_list. expiration_date 有效日期
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 752,
 * "company_id": 3,
 * "merchant_id": 17,
 * "merchant_id_name": "ERP",
 * "order_no": "TMS000300000518",
 * "execution_date": "2020-04-20",
 * "batch_no": "BATCH00030010O",
 * "tour_no": "TOUR00030004H",
 * "out_order_no": null,
 * "express_first_no": null,
 * "express_second_no": null,
 * "type": 2,
 * "out_user_id": null,
 * "nature": 1,
 * "settlement_type": 1,
 * "settlement_amount": null,
 * "replace_amount": null,
 * "delivery": 2,
 * "status": 1,
 * "sender": "827193289@qq.com",
 * "sender_phone": "23145654",
 * "sender_country": "NL",
 * "sender_country_name": "荷兰",
 * "sender_post_code": "2153PJ",
 * "sender_house_number": "20",
 * "sender_city": "Nieuw-Vennep",
 * "sender_street": "Pesetaweg",
 * "sender_address": null,
 * "receiver": "test",
 * "receiver_phone": "12314235436",
 * "receiver_country": "NL",
 * "receiver_country_name": "荷兰",
 * "receiver_post_code": "2151 MG",
 * "receiver_house_number": "1",
 * "receiver_city": null,
 * "receiver_street": "1185",
 * "receiver_address": "1185,Hoofdweg Oostzijde,Nieuw-Vennep,Haarlemmermeer,Noord-Holland,荷兰,2151 MG",
 * "special_remark": null,
 * "remark": null,
 * "unique_code": null,
 * "driver_id": null,
 * "driver_name": "",
 * "driver_phone": "",
 * "created_at": "2020-04-18 15:03:09",
 * "updated_at": "2020-04-18 15:03:09",
 * "item_list": null,
 * "lon": "4.632902500000001",
 * "lat": "52.2643678",
 * "package_list": [
 * {
 * "id": 861,
 * "company_id": 3,
 * "tour_no": "TOUR00030004H",
 * "batch_no": "BATCH00030010O",
 * "order_no": "TMS000300000518",
 * "type": 2,
 * "name": "测试",
 * "express_first_no": "PPNPWB1111",
 * "express_second_no": "",
 * "out_order_no": "",
 * "weight": "0.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "status": 1,
 * "sticker_no": "",
 * "sticker_amount": "0.00",
 * "remark": "",
 * "expiration_date":"2020-03-10",
 * "created_at": "2020-04-18 15:03:10",
 * "updated_at": "2020-04-18 15:03:10",
 * "status_name": "待分配",
 * "type_name": "派件"
 * },
 * {
 * "id": 862,
 * "company_id": 3,
 * "tour_no": "TOUR00030004H",
 * "batch_no": "BATCH00030010O",
 * "order_no": "TMS000300000518",
 * "type": 2,
 * "name": "测试",
 * "express_first_no": "PPNPWB2222",
 * "express_second_no": "",
 * "out_order_no": "",
 * "weight": "0.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "status": 1,
 * "sticker_no": "",
 * "sticker_amount": "0.00",
 * "remark": "",
 * "expiration_date":"2020-03-10",
 * "created_at": "2020-04-18 15:03:10",
 * "updated_at": "2020-04-18 15:03:10",
 * "status_name": "待分配",
 * "type_name": "派件"
 * }
 * ],
 * "material_list": [
 * {
 * "id": 720,
 * "company_id": 3,
 * "tour_no": "TOUR00030004H",
 * "batch_no": "BATCH00030010O",
 * "order_no": "TMS000300000518",
 * "name": "小箱子",
 * "code": "SB",
 * "out_order_no": "",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "remark": "这是一个小箱子",
 * "created_at": "2020-04-18 15:03:10",
 * "updated_at": "2020-04-18 15:03:10"
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/order/{id} 订单修改
 * @apiName 订单修改
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 订单ID
 * @apiParam {string} out_user_id 外部用户ID
 * @apiParam {string} execution_date 取件/派件 日期
 * @apiParam {string} out_order_no 外部订单号
 * @apiParam {string} express_first_no 快递单号1
 * @apiParam {string} express_second_no 快递单号2
 * @apiParam {string} source 订单来源
 * @apiParam {string} type 类型1-取，2-派
 * @apiParam {string} nature 性质1-包裹2-材料3-文件4-增值服务5-其他
 * @apiParam {string} settlement_type 结算方式1-寄付2-到付
 * @apiParam {string} settlement_amount 结算金额,到付必填
 * @apiParam {string} replace_amount 代收金额
 * @apiParam {string} delivery 自提1-是2-否
 * @apiParam {string} sender 发件人-姓名
 * @apiParam {string} sender_phone 发件人-手机号
 * @apiParam {string} sender_country 发件人-国家
 * @apiParam {string} sender_post_code 发件人-邮编
 * @apiParam {string} sender_house_number 发件人-门牌号
 * @apiParam {string} sender_city 发件人-城市
 * @apiParam {string} sender_street 发件人-街道
 * @apiParam {string} sender_address 发件人-地址
 * @apiParam {string} receiver 收件人-姓名
 * @apiParam {string} receiver_phone 收件人-手机号
 * @apiParam {string} receiver_country 收件人-国家
 * @apiParam {string} receiver_post_code 收件人-邮编
 * @apiParam {string} receiver_house_number 收件人-门牌号
 * @apiParam {string} receiver_city 收件人-城市
 * @apiParam {string} receiver_street 收件人-街道
 * @apiParam {string} receiver_address 收件人-地址
 * @apiParam {string} lon 经度
 * @apiParam {string} lat 纬度
 * @apiParam {string} special_remark 特殊事项
 * @apiParam {string} remark 备注
 * @apiParam {string} item_list 货物列表
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

/**
 * @api {get} /admin/order/count 订单统计
 * @apiName 订单统计
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} type 类型1-取2-派3-取派(不,统计全部)
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.0 全部
 * @apiSuccess {string} data.1 待取派
 * @apiSuccess {string} data.2 取派中
 * @apiSuccess {string} data.3 取派完成
 * @apiSuccess {string} data.data.4 取消取派
 * @apiSuccess {string} data.data.5 回收站
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "0": 9,
 * "1": 0,
 * "2": 8,
 * "3": 3,
 * "4": 3,
 * "5": 3
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/order/getLocation 获取坐标
 * @apiName 获取坐标
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} country 收件人国家
 * @apiParam {string} post_code 收件人邮编
 * @apiParam {string} house_number 收件人门牌号
 * @apiParam {string} city 收件人城市
 * @apiParam {string} street 收件人街道
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.lon 经度
 * @apiSuccess {string} data.lat 纬度
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "lon": 5.4740944,
 * "lat": 51.4384193
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {delete} /admin/order/{id} 订单删除
 * @apiName 订单删除
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 订单ID
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

/**
 * @api {delete} /admin/order/{id}/actual-destroy 订单彻底删除
 * @apiName 订单彻底删除
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 订单ID
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

/**
 * @api {put} /admin/order/{id}/recovery 订单恢复
 * @apiName 订单恢复
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} execution_date 重新指定的取派日期
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

/**
 * @api {put} /admin/order/assign-tour 批量订单分配至指定取件线路
 * @apiName 批量订单分配至指定取件线路
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list 订单ID列表，以逗号分隔
 * @apiParam {string} tour_no 取件线路编号
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

/**
 * @api {post} /admin/order/init 订单查询初始化
 * @apiName 订单查询初始化
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.source_list 来源列表
 * @apiSuccess {string} data.source_list.id
 * @apiSuccess {string} data.source_list.name
 * @apiSuccess {string} data.status_list 状态列表
 * @apiSuccess {string} data.status_list.id
 * @apiSuccess {string} data.status_list.name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "source_list": [
 * {
 * "id": 1,
 * "name": "手动添加"
 * },
 * {
 * "id": 2,
 * "name": "批量导入"
 * },
 * {
 * "id": 3,
 * "name": "第三方"
 * }
 * ],
 * "status_list": [
 * {
 * "id": 1,
 * "name": "待分配"
 * },
 * {
 * "id": 2,
 * "name": "已分配"
 * },
 * {
 * "id": 3,
 * "name": "待出库"
 * },
 * {
 * "id": 4,
 * "name": "取派中"
 * },
 * {
 * "id": 5,
 * "name": "已完成"
 * },
 * {
 * "id": 6,
 * "name": "取消取派"
 * },
 * {
 * "id": 7,
 * "name": "回收站"
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/order-trail 订单轨迹查看
 * @apiName 订单轨迹查看
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} order_no 订单编号
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 轨迹编号
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.order_no 订单编号
 * @apiSuccess {string} data.data.content 内容
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 20158,
 * "company_id": 2,
 * "order_no": "00025434",
 * "content": "Order allocation driver, driver name [398352614@qq.com], contact phone [123]",
 * "created_at": "2020-05-27 14:06:13",
 * "updated_at": "2020-05-27 14:06:13"
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/order/pdf 订单批量打印
 * @apiName 订单批量打印
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list 71,72,93,96
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 订单ID
 * @apiSuccess {string} data.url url地址
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 71,
 * "url": "https://dev-tms.nle-tech.com/storage/pdf/order/3/TMS000300000007.pdf"
 * },
 * {
 * "id": 72,
 * "url": "https://dev-tms.nle-tech.com/storage/pdf/order/3/TMS000300000008.pdf"
 * },
 * {
 * "id": 93,
 * "url": "https://dev-tms.nle-tech.com/storage/pdf/order/3/TMS000300000013.pdf"
 * },
 * {
 * "id": 96,
 * "url": "https://dev-tms.nle-tech.com/storage/pdf/order/3/TMS000300000014.pdf"
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/order/excel 订单导出
 * @apiName 订单导出
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} begin_date 起始时间
 * @apiParam {string} end_date 终止时间
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.name
 * @apiSuccess {string} data.path
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "name": "202007011910167.xlsx",
 * "path": "tms-api.test/storage/admin/excel/2/orderOut/202007011910167.xlsx"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {delete} /admin/order/list 订单批量删除
 * @apiName 订单批量删除
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list 订单ID列表
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

/**
 * @api {post} /admin/order/synchronize-status-list 同步订单状态
 * @apiName 同步订单状态
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list ID列表,以逗号分隔
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

/**
 * @api {get} /admin/order/{id}/third-party-log 订单对接日志
 * @apiName 订单对接日志
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 订单ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.merchant_id
 * @apiSuccess {string} data.order_no 订单号
 * @apiSuccess {string} data.content 内容
 * @apiSuccess {string} data.created_at 创建时间
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 1,
 * "company_id": 3,
 * "merchant_id": 3,
 * "order_no": "SMAAACNN0001",
 * "content": "出库推送成功",
 * "created_at": "2020-09-03 15:28:30",
 * "updated_at": "2020-09-03 15:28:32"
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/order/{id}/neutralize 订单无效化
 * @apiName 订单无效化
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 订单ID
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

/**
 * @api {get} /admin/order/{id}/tracking-order 订单的运单列表
 * @apiName 订单的运单列表
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.merchant_id
 * @apiSuccess {string} data.data.merchant_id_name 货主名称
 * @apiSuccess {string} data.data.order_no 订单号
 * @apiSuccess {string} data.data.tracking_order_no 运单号
 * @apiSuccess {string} data.data.mask_code 掩码
 * @apiSuccess {string} data.data.type
 * @apiSuccess {string} data.data.type_name 类型名称
 * @apiSuccess {string} data.data.out_user_id
 * @apiSuccess {string} data.data.status
 * @apiSuccess {string} data.data.status_name 状态名称
 * @apiSuccess {string} data.data.out_status
 * @apiSuccess {string} data.data.out_status_name 出库状态名称
 * @apiSuccess {string} data.data.execution_date 取派日期
 * @apiSuccess {string} data.data.out_order_no
 * @apiSuccess {string} data.data.exception_label
 * @apiSuccess {string} data.data.exception_label_name
 * @apiSuccess {string} data.data.receiver_post_code
 * @apiSuccess {string} data.data.receiver_house_number
 * @apiSuccess {string} data.data.driver_name 派送司机
 * @apiSuccess {string} data.data.batch_no 站点编号
 * @apiSuccess {string} data.data.tour_no 取件线路编号
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.data.car_no 车牌
 * @apiSuccess {string} data.data.driver_phone 司机电话
 * @apiSuccess {string} data.data.begin_time 发车时间
 * @apiSuccess {string} data.data.sign_time 完成时间
 * @apiSuccess {string} data.data.time_list
 * @apiSuccess {string} data.data.type 类型
 * @apiSuccess {string} data.data.time
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 2120,
 * "company_id": 3,
 * "merchant_id": 121,
 * "out_user_id": null,
 * "out_order_no": "",
 * "order_no": "SMAAAJDL0001",
 * "tracking_order_no": "YD00030003997",
 * "batch_no": "ZD4286",
 * "tour_no": "4CSV01",
 * "line_id": 127,
 * "line_name": "AMS（3）",
 * "type": 2,
 * "execution_date": "2021-03-24",
 * "warehouse_fullname": "827193289@qq.com",
 * "warehouse_phone": "23145654",
 * "warehouse_country": "NL",
 * "warehouse_province": "",
 * "warehouse_post_code": "2153PJ",
 * "warehouse_house_number": "20",
 * "warehouse_city": "Nieuw-Vennep",
 * "warehouse_district": "",
 * "warehouse_street": "Pesetaweg",
 * "warehouse_address": "NL Nieuw-Vennep Pesetaweg 20 2153PJ",
 * "warehouse_lon": "4.62897256",
 * "warehouse_lat": "52.25347699",
 * "place_fullname": "保利",
 * "place_phone": "18825558852",
 * "place_country": "NL",
 * "place_province": "河南",
 * "place_post_code": "2153PJ",
 * "place_house_number": "13",
 * "place_city": "长沙市",
 * "place_district": "驻马店",
 * "place_street": "桐梓坡西路",
 * "place_address": "13 长沙市 桐梓坡西路 2153PJ",
 * "place_lon": "4.62897256",
 * "place_lat": "52.25347699",
 * "driver_id": null,
 * "driver_name": "",
 * "driver_phone": "",
 * "car_id": null,
 * "car_no": "",
 * "status": 1,
 * "out_status": 1,
 * "exception_label": 1,
 * "cancel_type": null,
 * "cancel_remark": "",
 * "cancel_picture": "",
 * "mask_code": "",
 * "special_remark": "特别事项1",
 * "created_at": "2021-03-24 11:51:03",
 * "updated_at": "2021-03-24 11:51:03",
 * "package_list": [
 * {
 * "id": 2199,
 * "company_id": 3,
 * "merchant_id": 121,
 * "order_no": "SMAAAJDL0001",
 * "tour_no": "4CSV01",
 * "batch_no": "ZD4286",
 * "tracking_order_no": "YD00030003997",
 * "type": 2,
 * "execution_date": "2021-03-24",
 * "name": "",
 * "express_first_no": "22022223B",
 * "express_second_no": "",
 * "feature_logo": "",
 * "out_order_no": "",
 * "weight": "12.12",
 * "actual_weight": "",
 * "expect_quantity": 12,
 * "actual_quantity": 0,
 * "status": 1,
 * "sticker_no": "",
 * "settlement_amount": "0.00",
 * "count_settlement_amount": "0.00",
 * "sticker_amount": null,
 * "delivery_amount": null,
 * "remark": "12",
 * "is_auth": 2,
 * "auth_fullname": "",
 * "auth_birth_date": null,
 * "created_at": "2021-03-24 11:51:03",
 * "updated_at": "2021-03-24 11:51:03",
 * "expiration_date": null,
 * "expiration_status": 1,
 * "status_name": "待受理",
 * "type_name": "网点->配送",
 * "expiration_status_name": "未超期"
 * }
 * ],
 * "material_list": [],
 * "sign_time": null,
 * "begin_time": null,
 * "time_list": {
 * "type": "创建时间",
 * "time": "2021-03-24 11:51:03"
 * },
 * "status_name": "待受理",
 * "out_status_name": "是",
 * "type_name": "配送",
 * "merchant_id_name": "同城派送",
 * "country_name": null,
 * "merchant": {
 * "id": 121,
 * "company_id": 3,
 * "code": "00121",
 * "type": 1,
 * "name": "同城派送",
 * "short_name": "0",
 * "introduction": null,
 * "email": "tongcheng@nle-tech.com",
 * "country": "NL",
 * "settlement_type": 3,
 * "merchant_group_id": 52,
 * "contacter": "zeng",
 * "phone": "12125224",
 * "address": "hunan",
 * "avatar": "",
 * "invoice_title": "1",
 * "taxpayer_code": null,
 * "bank": null,
 * "bank_account": null,
 * "invoice_address": null,
 * "invoice_email": null,
 * "status": 1,
 * "created_at": "2020-12-04 04:58:06",
 * "updated_at": "2021-01-05 12:49:08",
 * "settlement_type_name": "月结",
 * "status_name": "启用",
 * "type_name": "个人",
 * "country_name": "荷兰",
 * "additional_status": 2,
 * "advance_days": 0,
 * "appointment_days": 22,
 * "delay_time": 10,
 * "pickup_count": 1,
 * "pie_count": 2,
 * "merchant_group": {
 * "id": 52,
 * "company_id": 3,
 * "name": "ERP同城派组",
 * "transport_price_id": 71,
 * "count": 8,
 * "is_default": 2,
 * "additional_status": 2,
 * "advance_days": 0,
 * "appointment_days": 22,
 * "delay_time": 10,
 * "pickup_count": 1,
 * "pie_count": 2,
 * "created_at": "2020-12-28 03:26:15",
 * "updated_at": "2021-02-03 14:54:02",
 * "additional_status_name": "禁用"
 * }
 * }
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/order 订单新增
 * @apiName 订单新增
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} merchant_id 货主ID
 * @apiParam {string} execution_date 取件/派件 日期
 * @apiParam {string} second_execution_date 取派订单中的派件日期
 * @apiParam {string} source 订单来源(1手动添加2-批量导入3-第三方)
 * @apiParam {string} type 类型1-取,2-派3-取派
 * @apiParam {string} out_user_id 外部用户ID
 * @apiParam {string} settlement_type 结算方式1-寄付2-到付
 * @apiParam {string} settlement_amount 结算金额,到付必填
 * @apiParam {string} replace_amount 代收金额
 * @apiParam {string} delivery 自提1-是2-否
 * @apiParam {string} second_place_fullname 发件人-姓名
 * @apiParam {string} second_place_phone 发件人-手机号
 * @apiParam {string} second_place_country 发件人-国家
 * @apiParam {string} second_place_post_code 发件人-邮编
 * @apiParam {string} second_place_house_number 发件人-门牌号
 * @apiParam {string} second_place_city 发件人-城市
 * @apiParam {string} second_place_street 发件人-街道
 * @apiParam {string} second_place_address 发件人-地址
 * @apiParam {string} second_place_lon 发件人-经度
 * @apiParam {string} second_place_lat 发件人-纬度
 * @apiParam {string} place_fullname 收件人-姓名
 * @apiParam {string} place_phone 收件人-手机号
 * @apiParam {string} place_country 收件人-国家
 * @apiParam {string} place_post_code 收件人-邮编
 * @apiParam {string} place_house_number 收件人-门牌号
 * @apiParam {string} place_city 收件人-城市
 * @apiParam {string} place_street 收件人-街道
 * @apiParam {string} place_address 收件人-地址
 * @apiParam {string} place_lon 收件人-经度
 * @apiParam {string} place_lat 收件人-纬度
 * @apiParam {string} special_remark 特殊事项
 * @apiParam {string} remark 备注
 * @apiParam {string} package_list 货物列表
 * @apiParam {string} material_list 材料列表
 * @apiParam {string} package_list.expiration_date 有效日期
 * @apiParam {string} control_mode 控货模式1-无2-等通知放货
 * @apiParam {string} transport_mode 运输方式1-整车2-零担
 * @apiParam {string} origin_type 始发地1-从网点出发，回到网点2-装货地
 * @apiParam {string} package_list.size 包裹体积
 * @apiParam {string} package_list.feature 包裹特性
 * @apiParam {string} material_list.type 材料类型
 * @apiParam {string} material_list.pack_type 材料包装
 * @apiParam {string} material_list.weight 材料重量
 * @apiParam {string} material_list.size 材料体积
 * @apiParam {string} material_list.unit_price 材料单价
 * @apiParam {string} amount_list 费用列表
 * @apiParam {string} amount_list.type 费用类型
 * @apiParam {string} amount_list.expect_amount 费用金额
 * @apiParam {string} create_date 开单日期
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

/**
 * @api {get} /admin/order/{id}/again-info 获取再次取派信息
 * @apiName 获取再次取派信息
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 订单ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.merchant_id
 * @apiSuccess {string} data.merchant_id_name
 * @apiSuccess {string} data.order_no
 * @apiSuccess {string} data.execution_date
 * @apiSuccess {string} data.second_execution_date
 * @apiSuccess {string} data.out_user_id
 * @apiSuccess {string} data.nature
 * @apiSuccess {string} data.settlement_type
 * @apiSuccess {string} data.settlement_amount
 * @apiSuccess {string} data.replace_amount
 * @apiSuccess {string} data.delivery
 * @apiSuccess {string} data.status
 * @apiSuccess {string} data.status_name
 * @apiSuccess {string} data.second_place_fullname
 * @apiSuccess {string} data.second_place_phone
 * @apiSuccess {string} data.second_place_country
 * @apiSuccess {string} data.second_place_country_name
 * @apiSuccess {string} data.second_place_post_code
 * @apiSuccess {string} data.second_place_house_number
 * @apiSuccess {string} data.second_place_city
 * @apiSuccess {string} data.second_place_street
 * @apiSuccess {string} data.second_place_address
 * @apiSuccess {string} data.second_place_lon
 * @apiSuccess {string} data.second_place_lat
 * @apiSuccess {string} data.second_place_fullname
 * @apiSuccess {string} data.second_place_phone
 * @apiSuccess {string} data.receiver_country
 * @apiSuccess {string} data.second_place_country_name
 * @apiSuccess {string} data.second_place_post_code
 * @apiSuccess {string} data>second_place_house_number
 * @apiSuccess {string} data.second_place_city
 * @apiSuccess {string} data.second_place_street
 * @apiSuccess {string} data.second_place_address
 * @apiSuccess {string} data.second_place_lon
 * @apiSuccess {string} data.second_place_lat
 * @apiSuccess {string} data.tracking_order_type 运单类型
 * @apiSuccess {string} data.tracking_order_type_name 运单类型名称
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1796,
 * "company_id": 3,
 * "merchant_id": 3,
 * "merchant_id_name": "货主asd",
 * "order_no": "SMAAADMF0001",
 * "execution_date": "2020-11-17",
 * "second_execution_date": "2020-11-18",
 * "out_user_id": "",
 * "nature": 1,
 * "settlement_type": 1,
 * "settlement_amount": "0.00",
 * "replace_amount": "0.00",
 * "delivery": 2,
 * "status": 2,
 * "status_name": "取派中",
 * "sender_fullname": "11221",
 * "sender_phone": "1144141",
 * "sender_country": "NL",
 * "sender_country_name": "荷兰",
 * "sender_post_code": "2153PJ",
 * "sender_house_number": "20",
 * "sender_city": "Nieuw Vennep",
 * "sender_street": "Pesetaweg",
 * "sender_address": "NL Nieuw Vennep Pesetaweg 20 2153PJ",
 * "sender_lon": "4.6299716",
 * "sender_lat": "52.2529179",
 * "receiver_fullname": "023",
 * "receiver_phone": "0031612357890",
 * "receiver_country": "NL",
 * "receiver_country_name": "荷兰",
 * "receiver_post_code": "2152PL",
 * "receiver_house_number": "88",
 * "receiver_city": "Nieuw-Vennep",
 * "receiver_street": "Rodelindalaan",
 * "receiver_address": "NL Nieuw-Vennep Rodelindalaan 88 2152PL",
 * "receiver_lon": "4.62510355",
 * "receiver_lat": "52.27770336",
 * "tracking_order_type": 1,
 * "tracking_order_type_name": "取件",
 * "created_at": "2020-11-10 14:25:36",
 * "updated_at": "2020-11-10 14:26:40"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/order/{id}/again 再次取派
 * @apiName 再次取派
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} tracking_order_type 运单类型
 * @apiParam {string} execution_date 取派日期
 * @apiParam {string} second_place_fullname
 * @apiParam {string} second_place_phone
 * @apiParam {string} second_place_country
 * @apiParam {string} second_place_post_code
 * @apiParam {string} second_place_house_number
 * @apiParam {string} second_place_city
 * @apiParam {string} second_place_street
 * @apiParam {string} second_place_address
 * @apiParam {string} second_place_address
 * @apiParam {string} second_place_lat
 * @apiParam {string} second_place_lon
 * @apiParam {string} place_fullname
 * @apiParam {string} place_phone
 * @apiParam {string} place_country
 * @apiParam {string} place_post_code
 * @apiParam {string} place_house_number
 * @apiParam {string} place_city
 * @apiParam {string} place_street
 * @apiParam {string} place_address
 * @apiParam {string} placee_address
 * @apiParam {string} place_lat
 * @apiParam {string} place_lon
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
 * @api {put} /admin/order/{id}/end 终止取派
 * @apiName 终止取派
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 订单ID
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
 * @api {post} /admin/order/price-count 估算运价
 * @apiName 估算运价
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} merchant_id 货主ID
 * @apiParam {string} execution_date 取件/派件 日期
 * @apiParam {string} second_execution_date 取派订单中的派件日期
 * @apiParam {string} source 订单来源(1手动添加2-批量导入3-第三方)
 * @apiParam {string} type 类型1-取,2-派3-取派
 * @apiParam {string} out_user_id 外部用户ID
 * @apiParam {string} settlement_type 结算方式1-寄付2-到付
 * @apiParam {string} settlement_amount 结算金额,到付必填
 * @apiParam {string} replace_amount 代收金额
 * @apiParam {string} delivery 自提1-是2-否
 * @apiParam {string} second_place_fullname 发件人-姓名
 * @apiParam {string} second_place_phone 发件人-手机号
 * @apiParam {string} second_place_country 发件人-国家
 * @apiParam {string} second_place_post_code 发件人-邮编
 * @apiParam {string} second_place_house_number 发件人-门牌号
 * @apiParam {string} second_place_city 发件人-城市
 * @apiParam {string} second_place_street 发件人-街道
 * @apiParam {string} second_place_address 发件人-地址
 * @apiParam {string} second_place_lon 发件人-经度
 * @apiParam {string} second_place_lat 发件人-纬度
 * @apiParam {string} place_fullname 收件人-姓名
 * @apiParam {string} place_phone 收件人-手机号
 * @apiParam {string} place_country 收件人-国家
 * @apiParam {string} place_post_code 收件人-邮编
 * @apiParam {string} place_house_number 收件人-门牌号
 * @apiParam {string} place_city 收件人-城市
 * @apiParam {string} place_street 收件人-街道
 * @apiParam {string} place_address 收件人-地址
 * @apiParam {string} place_lon 收件人-经度
 * @apiParam {string} place_lat 收件人-纬度
 * @apiParam {string} special_remark 特殊事项
 * @apiParam {string} remark 备注
 * @apiParam {string} package_list 货物列表
 * @apiParam {string} material_list 材料列表
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.execution_date
 * @apiSuccess {string} data.type
 * @apiSuccess {string} data.settlement_type
 * @apiSuccess {string} data.second_place_fullname
 * @apiSuccess {string} data.second_place_phone
 * @apiSuccess {string} data.second_place_country
 * @apiSuccess {string} data.second_place_post_code
 * @apiSuccess {string} data.second_place_house_number
 * @apiSuccess {string} data.second_place_city
 * @apiSuccess {string} data.second_place_street
 * @apiSuccess {string} data.second_place_address
 * @apiSuccess {string} data.second_place_lon
 * @apiSuccess {string} data.second_place_lat
 * @apiSuccess {string} data.place_fullname
 * @apiSuccess {string} data.place_phone
 * @apiSuccess {string} data.place_post_code
 * @apiSuccess {string} data.place_house_number
 * @apiSuccess {string} data.place_city
 * @apiSuccess {string} data.place_street
 * @apiSuccess {string} data.place_address
 * @apiSuccess {string} data.place_lon
 * @apiSuccess {string} data.place_lat
 * @apiSuccess {string} data.special_remark
 * @apiSuccess {string} data.remark
 * @apiSuccess {string} data.package_list
 * @apiSuccess {string} data.package_list.name
 * @apiSuccess {string} data.package_list.express_first_no
 * @apiSuccess {string} data.package_list.express_second_no
 * @apiSuccess {string} data.package_list.out_order_no
 * @apiSuccess {string} data.package_list.weight
 * @apiSuccess {string} data.package_list.expect_quantity
 * @apiSuccess {string} data.package_list.remark
 * @apiSuccess {string} data.package_list.count_settlement_amount 估算运价
 * @apiSuccess {string} data.material_list
 * @apiSuccess {string} data.material_list.name
 * @apiSuccess {string} data.material_list.out_order_no
 * @apiSuccess {string} data.material_list.code
 * @apiSuccess {string} data.material_list.expect_quantity
 * @apiSuccess {string} data.material_list.remark
 * @apiSuccess {string} data.merchant_id
 * @apiSuccess {string} data.distance 距离
 * @apiSuccess {string} data.count_settlement_amount 估算运价
 * @apiSuccess {string} data.settlement_amount 运价
 * @apiSuccess {string} data.starting_price 固定费用
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "execution_date": "2021-01-30",
 * "type": "2",
 * "settlement_type": "1",
 * "second_place_fullname": "胡洋铭",
 * "second_place_phone": "17570715315",
 * "second_place_country": "中国",
 * "second_place_post_code": "1333",
 * "second_place_house_number": "808",
 * "second_place_city": "长沙",
 * "second_place_street": "C8",
 * "second_place_address": "麓谷企业广场",
 * "second_place_lon": "4.486201",
 * "second_place_lat": "51.9251262",
 * "place_fullname": "龙放耀",
 * "place_phone": "18825558852",
 * "place_post_code": "3031AT",
 * "place_house_number": "199",
 * "place_city": "Rotterdam",
 * "place_street": "Jonker Fransstraat",
 * "place_address": "SAN SA454",
 * "place_lon": "4.486201",
 * "place_lat": "51.9251262",
 * "special_remark": "特别事项1",
 * "remark": "备注1",
 * "package_list": [
 * {
 * "name": "yu32mmy21",
 * "express_first_no": "yum32dgcmy22",
 * "express_second_no": "",
 * "out_order_no": "yum32my21",
 * "weight": "12.12",
 * "expect_quantity": "12",
 * "remark": "12",
 * "count_settlement_amount": 0
 * },
 * {
 * "name": "yu43mmy23",
 * "express_first_no": "yum24vcxmy24",
 * "express_second_no": "",
 * "out_order_no": "yum23my23",
 * "weight": "12.12",
 * "expect_quantity": "12",
 * "remark": "12",
 * "count_settlement_amount": 0
 * }
 * ],
 * "material_list": [
 * {
 * "name": "yum42m3y1",
 * "out_order_no": "yummy9",
 * "code": "yu4mmy",
 * "expect_quantity": "6",
 * "remark": "wqwq"
 * },
 * {
 * "name": "yum42my1",
 * "code": "yum324my1",
 * "out_order_no": "yumm32y10",
 * "expect_quantity": "5",
 * "remark": "wqwq"
 * }
 * ],
 * "merchant_id": "3",
 * "distance": 0,
 * "count_settlement_amount": 0,
 * "settlement_amount": 0,
 * "starting_price": "0.00"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/order/bill 面单批量打印
 * @apiName 面单批量打印
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list 订单ID列表，逗号连接
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.template_name 模板名称
 * @apiSuccess {string} data.template
 * @apiSuccess {string} data.template.id
 * @apiSuccess {string} data.template.logo 标志（base64）
 * @apiSuccess {string} data.template.sender 发件人
 * @apiSuccess {string} data.template.receiver 收件人
 * @apiSuccess {string} data.template.destination 目的地
 * @apiSuccess {string} data.template.carrier 承运人
 * @apiSuccess {string} data.template.carrier_address 承运人地址
 * @apiSuccess {string} data.template.contents 物品信息
 * @apiSuccess {string} data.template.package 包裹
 * @apiSuccess {string} data.template.material 材料
 * @apiSuccess {string} data.template.count 数量
 * @apiSuccess {string} data.template.replace_amount 代收货款
 * @apiSuccess {string} data.template.settlement_amount 运费金额
 * @apiSuccess {string} data.template.destination_mode_name
 * @apiSuccess {string} data.template.type_name
 * @apiSuccess {string} data.api
 * @apiSuccess {string} data.api.order_no 订单号
 * @apiSuccess {string} data.api.sender 发件人
 * @apiSuccess {string} data.api.sender.fullname 姓名
 * @apiSuccess {string} data.api.sender.phone 电话
 * @apiSuccess {string} data.api.sender.country 国家
 * @apiSuccess {string} data.api.sender.province 省份
 * @apiSuccess {string} data.api.sender.city 城市
 * @apiSuccess {string} data.api.sender.district 区县
 * @apiSuccess {string} data.api.sender.post_code 邮编
 * @apiSuccess {string} data.api.sender.street 街道
 * @apiSuccess {string} data.api.sender.house_number 门牌号
 * @apiSuccess {string} data.api.sender.address 地址
 * @apiSuccess {string} data.api.receiver 收件人
 * @apiSuccess {string} data.api.receiver.fullname
 * @apiSuccess {string} data.api.receiver.phone
 * @apiSuccess {string} data.api.receiver.country
 * @apiSuccess {string} data.api.receiver.province
 * @apiSuccess {string} data.api.receiver.city
 * @apiSuccess {string} data.api.receiver.district
 * @apiSuccess {string} data.api.receiver.post_code
 * @apiSuccess {string} data.api.receiver.street
 * @apiSuccess {string} data.api.receiver.house_number
 * @apiSuccess {string} data.api.receiver.address
 * @apiSuccess {string} data.api.destination 目的地
 * @apiSuccess {string} data.api.destination.country
 * @apiSuccess {string} data.api.destination.province
 * @apiSuccess {string} data.api.destination.city
 * @apiSuccess {string} data.api.destination.district
 * @apiSuccess {string} data.api.destination.post_code
 * @apiSuccess {string} data.api.destination.street
 * @apiSuccess {string} data.api.destination.house_number
 * @apiSuccess {string} data.api.destination.address
 * @apiSuccess {string} data.api.destination.all 整理好的目的地
 * @apiSuccess {string} data.api.warehouse 网点
 * @apiSuccess {string} data.api.warehouse.country
 * @apiSuccess {string} data.api.warehouse.province
 * @apiSuccess {string} data.api.warehouse.city
 * @apiSuccess {string} data.api.warehouse.district
 * @apiSuccess {string} data.api.warehouse.post_code
 * @apiSuccess {string} data.api.warehouse.street
 * @apiSuccess {string} data.api.warehouse.house_number
 * @apiSuccess {string} data.api.warehouse.address
 * @apiSuccess {string} data.api.replace_amount 代收货款
 * @apiSuccess {string} data.api.settlement_amount 运费金额
 * @apiSuccess {string} data.api.package_count 包裹数量
 * @apiSuccess {string} data.api.material_count 材料数量
 * @apiSuccess {string} data.api.order_barcode
 * @apiSuccess {string} data.api.first_package_barcode
 * @apiSuccess {string} data.api.first_package_no 包裹号
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.api.mask_code 掩码
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "template_name": "PrintStandard",
 * "template": {
 * "id": 3,
 * "logo": "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAfQAAABWCAMAAAAOq5TpAAAAM1BMVEUAAAD2uin2uin2uin2uin2\r\nuin2uin2uin2uin2uin2uin2uin2uin2uin2uin2uin2uimaB3VDAAAAEHRSTlMAQIC/EO/Pn2Df\r\nIDBwr49QtgnVAQAABzdJREFUeNrtnenWmyAQQGWTTXTe/2nbJORDFjMYbXuqc//01CZxuTIMA9qB\r\nIAiCIAiCIAiCIAiCIAiCIAiCIAiCIAiCIP4BzgzEcSR74Ib/AacB/o8j/XPAAzHshAmT/RUe8KEb\r\nw3kQYviGWQgAqG472a38N3q4N99INyOAPSR9hgdDH0v2w6L1VTmOuPV0pEoOt2a3dGksPFg2pbMB\r\nhcMD1neLARjkfpEjQKd1EQ/11uyULrmCF6ItXU4W3JnSDfzGVF8tnT+sU1P/E9KdVvCDb0hnuq8d\r\nLd3SNTxZSumF8/ZZ+BHeKBGBJ5qvmf6TJPSvS38ZTWhXSp9NvMa2s71N3b0/KF9Ir50rX+1FAc7P\r\nl29Et3QmIKFmVztMLLj07p5VF1ZMlI4799DH/QJ+p3RjIaG4LB3mBDQ1qKTj1mUWJHDng4E9jDey\r\nDti4NSZnCWuqf59hjZ1k327D0MWYrKQggTsfOOzibtKRJhdgTbOrTwR2dv4ok/UkHXGeRmdiDXxg\r\nuCiO5Sycvy4Mj2gRcdtxsg4CCctdf+tN0nHrSbqL0lHng10llYwbxuTG1VCvg7kkcvy2h3uJVbaU\r\n7rWCFQJJ4MpWqLqPXMVDSj0D7lymD6ZQPwoxc77kNwBcWLqGHeiyqVsueS7djJDBsWRQVsXUXryK\r\nzpMixHnRD2ioGUP88QsX6SbYhcma+jN7y6TPCgo4OkzTrl1MxfGzzNsl6jye75Juspp8PHBFoJv6\r\nIshVjKzjhhKI9PSF4Nfh1uHl2hIl4h/CwgsrXphmWcd/ku6zcs8VsbALv6WgLH2MRrIO6Q6S5/JC\r\nyyBQ6TiFdf4U/fHk2c/dcdU5dl0UnRf2m+m1MUtsVJlk1dLT8Fl7pMCWNzzdKr4zCzAdl14XAh2b\r\n0jKP5xy+akSzhQdx1RGbqcy0dfloB5NuVmUYXLpUWfxgsW2mn/XHpStsxLoaX164H6+HMAKVPkUf\r\nmPRBs6LA5tE0UjT261QcJB6Wbj+efjnYsNM1I3qrnIlKD7GLQ6XXSaLVRn7MKFjew/N17WduRSeB\r\nEjM6ZKhtAmQo7YcIu3TVfY7XHZGu6kaDS0+MnG32LbZd849Clj81b7RoBRlhyeYN5uG6LFFwLX2p\r\nuvR5n/Q5b0aBtaOMaVuSNs2jnT5v5Gf7YTjqx9f5X5bUqZfSWWV26ZbeHAf79iKlMtyL9M8HKqGp\r\nt0BSt0SQ8YyuP5meOvVSeqVP7pQutf2URodKisjCPT+YTbOmdFmmbvOi1tVDF//90gG+6NTb0l8d\r\n89AlfRaBc/OOlJPYWkThivZUF9/HY4uWWHvEYYvULXqOieqkfpxfuKWnTr2UXm6Y+6TbMigvrw7U\r\ntOtC9abhRRq3HZpXYI3NEb28B27RNEurBNQ1K7BZpz5uSUcL0Y49QHJmZ4JyzcJMtpUXvYjB46xn\r\nP0z8h9WojW2UfoOJ+2FapZotRMKFm3kKorKWXn7k+ECpFqw/r3wPsHG7QTeudcLjFDc7biHBXWzm\r\nl3+acY6ZeX3tJWOcCwH70ujOiWjV0GnSNmTcBt0MFWb277AuIIMzuPA8S92pz5V0Czl830AJwWzX\r\nf5dqC4TD0msWDSXaANzjWcbUqTtm+CxGZFr1nNXrNvrFvsvbTf1o6d0V1RkV78F48pcP7+8FohZb\r\nKvW9dMYY51xUn7J4lHhqGN3XLV30TLOE5Z2JSAVPLv9sC+9ejICTskD/TKeFEM1F5KL9o41k3alW\r\n2AhC8xVsRbGECg3r4yRXdSEWA8TFs/d4nlsoEfjEuu78p+gAm7DNwswnT4YdKr0jYX32RV1ogiuv\r\ng8Vi5dglO7boIISCBCZdb3T89sTr7et9uDqs/4YFti4GhssXYZ+YzJcQIirC8IXofumLaNfyxSHp\r\neHKhirAeX6eg06LMV3H+4rNsT5x4yuacsT0rQdmBtbRep8iLrHw/sfSui7Du4usU5PqcvbrJg8qs\r\nzuxcx5AcRwkROJ8Yc9W36y1anSjdNG5dn8J69sAdz250c79HVt/SD62aF/MzpR76SArOk96OVzaG\r\n9eJZHJvHhfldEroR+6QXD4SN33lbBF76w0Hj1TT79JqchC5W6Y93WRj7hXQthObx0U+0W8Zfb3Du\r\nEFlvH4jX+VO1ssz1pYILP+GCSMc5mIDXrU74Y8nJm8VunUU+zSJMa1TPrr9k6kzpaqd0p7M4y3q+\r\nwl8E/L0CFkk/tc8si9VSiws/tnq6dNgp3a4XL7njJUS89C7Wcf03bNJj/fFws079r0o32ZuKzpYe\r\nWjnjOq7rcSMwyPHiS6bOlL47Ktr0pqLTpWvZ3mOK6+PmtJIX9+nQfzMfkO52S+fpHSVnS9cbe7Rc\r\nFjW6uyyeSJxZDGW7pUsV2KFpQSsioZxq3drjxqs47tW0Cwx/8PWr/SfOd1mU3/33AfLEuTglZnP5\r\nUjvxgw18uf5CSIIgCIIgCIIgCIIgCIIgCIIgCIIgCIIgiP+FXyz9FIA0zJEnAAAAAElFTkSuQmCC\r\n",
 * "sender": "发货人",
 * "receiver": "收货人翻译",
 * "destination": "目的地",
 * "carrier": "承运方",
 * "carrier_address": "承运方详细地址",
 * "contents": "物品信息",
 * "package": "包裹",
 * "material": "材料",
 * "count": "数量",
 * "replace_amount": "代收货款",
 * "settlement_amount": "运费金额",
 * "destination_mode_name": "省市区",
 * "type_name": "模板一"
 * },
 * "api": {
 * "order_no": "SMAAAJEO0001",
 * "mask_code": "1",
 * "sender": {
 * "fullname": "achun",
 * "phone": "0031123123123",
 * "country": "NL",
 * "province": "",
 * "city": "Amsterdam",
 * "district": "",
 * "post_code": "1086ZK",
 * "street": "Cornelis Zillesenlaan",
 * "house_number": "46",
 * "address": "NL Amsterdam Cornelis Zillesenlaan 46 1086ZK"
 * },
 * "receiver": {
 * "fullname": "",
 * "phone": "",
 * "country": "",
 * "province": "",
 * "city": "",
 * "district": "",
 * "post_code": "",
 * "street": "",
 * "house_number": "",
 * "address": ""
 * },
 * "destination": {
 * "country": "NL",
 * "province": "",
 * "city": "Amsterdam",
 * "district": "",
 * "post_code": "1086ZK",
 * "street": "Cornelis Zillesenlaan",
 * "house_number": "46",
 * "address": "NL Amsterdam Cornelis Zillesenlaan 46 1086ZK",
 * "all": "Amsterdam"
 * },
 * "warehouse": {
 * "country": "NL",
 * "province": "NL",
 * "city": "Nieuw-Vennep",
 * "district": "",
 * "post_code": "2153PJ",
 * "street": "Pesetaweg",
 * "house_number": "20",
 * "address": "NL Nieuw-Vennep Pesetaweg 20 2153PJ"
 * },
 * "replace_amount": "0.00",
 * "settlement_amount": "18.00",
 * "package_count": 1,
 * "material_count": 0,
 * "order_barcode": "/var/www/html/api/storage/app/public/admin/barcode/smaaajeo0001.png",
 * "first_package_barcode": "/var/www/html/api/storage/app/public/admin/barcode/632500.png",
 * "first_package_no": "632500"
 * }
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/order/{id}/tracking-order-trail 订单的运单轨迹
 * @apiName 订单的运单轨迹
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 订单ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.tracking_order_no 运单号
 * @apiSuccess {string} data.place_lon 客户经度
 * @apiSuccess {string} data.place_lat 客户纬度
 * @apiSuccess {string} data.place_address 地址
 * @apiSuccess {string} data.place_fullname 客户姓名
 * @apiSuccess {string} data.batch_no
 * @apiSuccess {string} data.type 类型1-提货2-配送
 * @apiSuccess {string} data.driver_lon 司机经度
 * @apiSuccess {string} data.driver_lat 司机纬度
 * @apiSuccess {string} data.expect_arrive_time 预计到达时间
 * @apiSuccess {string} data.tracking_order_trail
 * @apiSuccess {string} data.tracking_order_trail.id
 * @apiSuccess {string} data.tracking_order_trail.company_id
 * @apiSuccess {string} data.tracking_order_trail.merchant_id
 * @apiSuccess {string} data.tracking_order_trail.type
 * @apiSuccess {string} data.tracking_order_trail.order_no
 * @apiSuccess {string} data.tracking_order_trail.tracking_order_no
 * @apiSuccess {string} data.tracking_order_trail.content 轨迹内容
 * @apiSuccess {string} data.tracking_order_trail.operator
 * @apiSuccess {string} data.tracking_order_trail.created_at 创建时间
 * @apiSuccess {string} data.tracking_order_trail.updated_at
 * @apiSuccess {string} data.tracking_order_trail.type_name
 * @apiSuccess {string} data.status_name
 * @apiSuccess {string} data.out_status_name
 * @apiSuccess {string} data.type_name
 * @apiSuccess {string} data.merchant_id_name
 * @apiSuccess {string} data.country_name
 * @apiSuccess {string} data.merchant
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "tracking_order_no": "YD00030002595",
 * "place_lon": "4.87510019",
 * "place_lat": "52.31153083",
 * "place_address": "NL Amstelveen Straat van Gibraltar 11 1183GT",
 * "place_fullname": "1-收货人",
 * "batch_no": "ZD3376",
 * "type": 2,
 * "driver_lon": "112.88099941",
 * "driver_lat": "28.21068374",
 * "expect_arrive_time": "2020-12-29 07:10:03",
 * "tracking_order_trail": [
 * {
 * "id": 3404,
 * "company_id": 3,
 * "merchant_id": 126,
 * "type": 1,
 * "order_no": "SMAAAHEY0001",
 * "tracking_order_no": "YD00030002595",
 * "content": "派件成功",
 * "operator": "",
 * "created_at": "2020-12-29 07:51:10",
 * "updated_at": "2020-12-29 07:51:10",
 * "type_name": "开单"
 * },
 * {
 * "id": 3345,
 * "company_id": 3,
 * "merchant_id": 126,
 * "type": 1,
 * "order_no": "SMAAAHEY0001",
 * "tracking_order_no": "YD00030002595",
 * "content": "运单派送中",
 * "operator": "",
 * "created_at": "2020-12-28 11:38:23",
 * "updated_at": "2020-12-28 11:38:23",
 * "type_name": "开单"
 * },
 * {
 * "id": 3342,
 * "company_id": 3,
 * "merchant_id": 126,
 * "type": 1,
 * "order_no": "SMAAAHEY0001",
 * "tracking_order_no": "YD00030002595",
 * "content": "运单装货中",
 * "operator": "",
 * "created_at": "2020-12-28 11:38:00",
 * "updated_at": "2020-12-28 11:38:00",
 * "type_name": "开单"
 * },
 * {
 * "id": 3339,
 * "company_id": 3,
 * "merchant_id": 126,
 * "type": 1,
 * "order_no": "SMAAAHEY0001",
 * "tracking_order_no": "YD00030002595",
 * "content": "运单分配司机，司机姓名[as]，联系方式[122134]",
 * "operator": "",
 * "created_at": "2020-12-28 11:37:50",
 * "updated_at": "2020-12-28 11:37:50",
 * "type_name": "开单"
 * },
 * {
 * "id": 3331,
 * "company_id": 3,
 * "merchant_id": 126,
 * "type": 1,
 * "order_no": "SMAAAHEY0001",
 * "tracking_order_no": "YD00030002595",
 * "content": "运单加入取件线路[4CAI01]",
 * "operator": "",
 * "created_at": "2020-12-28 11:28:57",
 * "updated_at": "2020-12-28 11:28:57",
 * "type_name": "开单"
 * },
 * {
 * "id": 3330,
 * "company_id": 3,
 * "merchant_id": 126,
 * "type": 1,
 * "order_no": "SMAAAHEY0001",
 * "tracking_order_no": "YD00030002595",
 * "content": "运单已加入站点[ZD3376]",
 * "operator": "",
 * "created_at": "2020-12-28 11:28:57",
 * "updated_at": "2020-12-28 11:28:57",
 * "type_name": "开单"
 * },
 * {
 * "id": 3329,
 * "company_id": 3,
 * "merchant_id": 126,
 * "type": 1,
 * "order_no": "SMAAAHEY0001",
 * "tracking_order_no": "YD00030002595",
 * "content": "运单创建成功,运单号[YD00030002595]",
 * "operator": "",
 * "created_at": "2020-12-28 11:28:57",
 * "updated_at": "2020-12-28 11:28:57",
 * "type_name": "开单"
 * }
 * ],
 * "status_name": null,
 * "out_status_name": null,
 * "type_name": "配送",
 * "merchant_id_name": "",
 * "country_name": null,
 * "merchant": null
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/order/warehouse 订单获取网点
 * @apiName 订单获取网点
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} place_post_code 邮编
 * @apiParam {string} execution_date 取派日期
 * @apiParam {string} type 取派类型1-取2-派
 * @apiParam {string} merchant_id 货主ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.name
 * @apiSuccess {string} data.fullname
 * @apiSuccess {string} data.phone
 * @apiSuccess {string} data.country
 * @apiSuccess {string} data.province
 * @apiSuccess {string} data.post_code
 * @apiSuccess {string} data.house_number
 * @apiSuccess {string} data.city
 * @apiSuccess {string} data.district
 * @apiSuccess {string} data.street
 * @apiSuccess {string} data.address
 * @apiSuccess {string} data.lon
 * @apiSuccess {string} data.lat
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.country_name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 4,
 * "company_id": 3,
 * "name": "tianyaox",
 * "fullname": "827193289@qq.com",
 * "phone": "23145654",
 * "country": "NL",
 * "province": "",
 * "post_code": "2153PJ",
 * "house_number": "20",
 * "city": "Nieuw-Vennep",
 * "district": "",
 * "street": "Pesetaweg",
 * "address": "NL Nieuw-Vennep Pesetaweg 20 2153PJ",
 * "lon": "4.62897256",
 * "lat": "52.25347699",
 * "created_at": "2020-03-13 12:00:10",
 * "updated_at": "2020-04-29 13:42:08",
 * "country_name": "荷兰"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/order/get-date 获取可选日期-地址
 * @apiName 获取可选日期-地址
 * @apiGroup 02
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} type 取派类型1取2派
 * @apiParam {string} place_post_code 收件人邮编（模板1）
 * @apiParam {string} place_country 收件人国家（模板1）
 * @apiParam {string} place_house_number 收件人门牌号（模板1）
 * @apiParam {string} place_city 收件人城市（模板1）
 * @apiParam {string} place_street 收件人街道（模板1）
 * @apiParam {string} place_address 地址（模板2）
 * @apiParam {string} place_lat 纬度（模板1，模板2）
 * @apiParam {string} place_lon 经度（模板1，模板2）
 * @apiSuccess {string} code
 * @apiSuccess {string} data 日期列表
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * "2020-04-23",
 * "2020-04-26",
 * "2020-04-30",
 * "2020-05-03",
 * "2020-05-07",
 * "2020-05-10",
 * "2020-05-14",
 * "2020-05-17",
 * "2020-05-21"
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/line 邮编线路查询
 * @apiName 邮编线路查询
 * @apiGroup 03
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 线路名称
 * @apiParam {string} country 国家
 * @apiParam {string} post_code 邮编
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 线路ID
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.name 线路名称
 * @apiSuccess {string} data.data.country 国家
 * @apiSuccess {string} data.data.line_range 邮编范围
 * @apiSuccess {string} data.data.work_day_list 取件日期列表(0-星期日1-星期一2-星期二3-星期三4-星期四5-星期五6-星期六)
 * @apiSuccess {string} data.data.pickup_max_count 最大取件量
 * @apiSuccess {string} data.data.creator_name 创建人姓名(员工姓名)
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.data.updated_at 修改时间
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.data.pie_max_count 最大派件量
 * @apiSuccess {string} data.data.is_increment 是否新增取件线路1-是2-否
 * @apiSuccess {string} data.data.order_deadline 当天下单截止时间
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 22,
 * "company_id": 2,
 * "name": "万能星期线",
 * "country": "NL",
 * "country_name": "荷兰",
 * "line_range": "1000-9999",
 * "work_day_list": "星期日,星期一,星期二,星期三,星期四,星期五,星期六",
 * "pickup_max_count": 10,
 * "pie_max_count": 10,
 * "is_increment": 1,
 * "order_deadline": "23:59:59",
 * "creator_name": "胡",
 * "created_at": "2020-03-26 18:49:07",
 * "updated_at": "2020-03-26 19:01:19"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/line?page=1",
 * "last": "http://tms-api.test/api/admin/line?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/line",
 * "per_page": 10,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/line/{id} 邮编线路详情
 * @apiName 邮编线路详情
 * @apiGroup 03
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 线路ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.name 线路名称
 * @apiSuccess {string} data.country 国家
 * @apiSuccess {string} data.remark 线路备注
 * @apiSuccess {string} data.warehouse_id 网点ID
 * @apiSuccess {string} data.pickup_max_count 取件最大订单量
 * @apiSuccess {string} data.pie_max_count 派件最大订单量
 * @apiSuccess {string} data.is_increment 是否新增取件线路1-是2-否
 * @apiSuccess {string} data.order_deadline 当天下单截止时间
 * @apiSuccess {string} data.appointment_days 可预约天数
 * @apiSuccess {string} data.creator_id 创建人ID(员工ID)
 * @apiSuccess {string} data.creator_name 创建人姓名(员工姓名)
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.country_name 国家名
 * @apiSuccess {string} data.line_range.post_code_start 起始邮编
 * @apiSuccess {string} data.line_range.post_code_end 结束邮编
 * @apiSuccess {string} data.work_day_list 取件日期(0-星期日1-星期一2-星期二3-星期三4-星期四5-星期五6-星期六)
 * @apiSuccess {string} data.can_skip_batch 站点能否跳过1-不能2-可以
 * @apiSuccess {string} data.status 状态1-启用2-禁用
 * @apiSuccess {string} data.country_name
 * @apiSuccess {string} data.can_skip_batch_name
 * @apiSuccess {string} data.line_range
 * @apiSuccess {string} data.line_range.post_code_start 起始邮编
 * @apiSuccess {string} data.line_range.post_code_end 终止邮编
 * @apiSuccess {string} data.work_day_list 星期列表
 * @apiSuccess {string} data.merchant_group_count_list
 * @apiSuccess {string} data.merchant_group_count_list.merchant_group_id 货主组ID
 * @apiSuccess {string} data.merchant_group_count_list.pickup_min_count 取件最小订单量
 * @apiSuccess {string} data.merchant_group_count_list.pie_min_count 派件最小订单量
 * @apiSuccess {string} data.merchant_group_count_list.merchant_group_name 货主组名称
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1128,
 * "company_id": 3,
 * "rule": 1,
 * "name": "万能星期线7",
 * "country": "NL",
 * "remark": "线路2线路2",
 * "warehouse_id": 27,
 * "pickup_max_count": 10,
 * "pie_max_count": 10,
 * "is_increment": 2,
 * "can_skip_batch": 1,
 * "order_deadline": "23:59:59",
 * "appointment_days": 30,
 * "status": 1,
 * "creator_id": 3,
 * "creator_name": "TMS测试",
 * "created_at": "2021-03-10 16:44:46",
 * "updated_at": "2021-03-10 16:44:46",
 * "country_name": "荷兰",
 * "can_skip_batch_name": "不能跳过",
 * "line_range": [
 * {
 * "post_code_start": 9999,
 * "post_code_end": 9999
 * }
 * ],
 * "work_day_list": "2",
 * "merchant_group_count_list": [
 * {
 * "merchant_group_id": 52,
 * "pickup_min_count": 10,
 * "pie_min_count": 10,
 * "merchant_group_name": "ERP同城派组"
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/line 邮编线路新增
 * @apiName 邮编线路新增
 * @apiGroup 03
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 线路名称
 * @apiParam {string} country 国家
 * @apiParam {string} warehouse_id 网点ID
 * @apiParam {string} remark 线路备注
 * @apiParam {string} work_day_list 取件日期列表(0-星期日1-星期一2-星期二3-星期三4-星期四5-星期五6-星期六)
 * @apiParam {string} item_list 邮编范围列表
 * @apiParam {string} pickup_max_count 取件最大订单数量
 * @apiParam {string} pie_max_count 派件最大订单数量
 * @apiParam {string} is_increment 是否新增取件线路1-是2-否
 * @apiParam {string} order_deadline 下单截止时间
 * @apiParam {string} appointment_days 可预约天数范围
 * @apiParam {string} merchant_group_count_list 货主组最小订单量列表
 * @apiParam {string} status 状态1-启用2-禁用
 * @apiParam {string} appointment_days 可预约天数
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

/**
 * @api {delete} /admin/line/{id} 邮编线路删除
 * @apiName 邮编线路删除
 * @apiGroup 03
 * @apiVersion 1.0.0
 * @apiUse auth
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

/**
 * @api {get} /admin/line/area 区域线路查询
 * @apiName 区域线路查询
 * @apiGroup 03
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 线路名称
 * @apiParam {string} country 国家
 * @apiParam {string} is_get_area 是否获取区域坐标列表1-是2-否
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id id
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.name 名称
 * @apiSuccess {string} data.data.country 国家
 * @apiSuccess {string} data.data.country_name 国家名称
 * @apiSuccess {string} data.data.pickup_max_count 最大取件订单量
 * @apiSuccess {string} data.data.pie_max_count 最大派件订单量
 * @apiSuccess {string} data.data.is_increment 是否新增1-是2-否
 * @apiSuccess {string} data.data.order_deadline 下单截止时间
 * @apiSuccess {string} data.data.creator_name 创建人
 * @apiSuccess {string} data.data.remark 备注
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.data.line_range
 * @apiSuccess {string} data.data.work_day_list
 * @apiSuccess {string} data.data.coordinate_list 坐标列表
 * @apiSuccess {string} data.data.coordinate_list.lat
 * @apiSuccess {string} data.data.coordinate_list.lon
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 165,
 * "company_id": 3,
 * "name": "中国线路xxss",
 * "country": "CN",
 * "country_name": "中国",
 * "pickup_max_count": 10,
 * "pie_max_count": 10,
 * "is_increment": 1,
 * "order_deadline": "23:59:59",
 * "creator_name": "827193289@qq.com",
 * "remark": "线路2线路2",
 * "created_at": "2020-05-08 16:21:17",
 * "updated_at": "2020-05-08 16:24:14",
 * "line_range": "",
 * "work_day_list": "",
 * "coordinate_list": [
 * {
 * "lat": "39.990206",
 * "lon": "116.223595"
 * },
 * {
 * "lat": "40.009391",
 * "lon": "116.3122821"
 * },
 * {
 * "lat": "40.02619",
 * "lon": "116.399095"
 * },
 * {
 * "lat": "39.945698",
 * "lon": "116.374948"
 * },
 * {
 * "lat": "39.926224",
 * "lon": "116.239843"
 * }
 * ]
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/line/area?page=1",
 * "last": "http://tms-api.test/api/admin/line/area?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/line/area",
 * "per_page": 10,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/line/area/{id} 区域线路详情
 * @apiName 区域线路详情
 * @apiGroup 03
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 线路ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.name 线路名称
 * @apiSuccess {string} data.country 国家
 * @apiSuccess {string} data.remark 线路备注
 * @apiSuccess {string} data.warehouse_id 网点ID
 * @apiSuccess {string} data.pickup_max_count 取件最大订单量
 * @apiSuccess {string} data.pie_max_count 派件最大订单量
 * @apiSuccess {string} data.is_increment 是否新增取件线路1-是2-否
 * @apiSuccess {string} data.order_deadline 当天下单截止时间
 * @apiSuccess {string} data.appointment_days 可预约天数
 * @apiSuccess {string} data.creator_id 创建人ID(员工ID)
 * @apiSuccess {string} data.creator_name 创建人姓名(员工姓名)
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.country_name 国家名
 * @apiSuccess {string} data.coordinate_list 坐标列表
 * @apiSuccess {string} data.coordinate_list.lat 纬度
 * @apiSuccess {string} data.coordinate_list.lon 经度
 * @apiSuccess {string} data.work_day_list
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 22,
 * "company_id": 2,
 * "name": "万能星期线",
 * "country": "NL",
 * "remark": "",
 * "warehouse_id": 2,
 * "pickup_max_count": 10,
 * "pie_max_count": 10,
 * "is_increment": 1,
 * "order_deadline": "23:59:59",
 * "appointment_days": 10,
 * "creator_id": 7,
 * "creator_name": "胡",
 * "created_at": "2020-03-26 18:49:07",
 * "updated_at": "2020-03-26 19:01:19",
 * "country_name": "荷兰",
 * "coordinate_list": [
 * {
 * "lat": "39.990206",
 * "lon": "116.223595"
 * },
 * {
 * "lat": "40.009391",
 * "lon": "116.3122821"
 * },
 * {
 * "lat": "40.02619",
 * "lon": "116.399095"
 * },
 * {
 * "lat": "39.945698",
 * "lon": "116.374948"
 * },
 * {
 * "lat": "39.926224",
 * "lon": "116.239843"
 * }
 * ],
 * "work_day_list": "0,1,2,3,4,5,6"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/line/area 区域线路新增
 * @apiName 区域线路新增
 * @apiGroup 03
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 线路名称
 * @apiParam {string} country 国家
 * @apiParam {string} warehouse_id 网点ID
 * @apiParam {string} remark 线路备注
 * @apiParam {string} pickup_max_count 1
 * @apiParam {string} pie_max_count 1
 * @apiParam {string} is_increment 1
 * @apiParam {string} order_deadline 23:59:59
 * @apiParam {string} appointment_days 30
 * @apiParam {string} coordinate_list 坐标列表
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

/**
 * @api {put} /admin/line/area/{id} 区域线路修改
 * @apiName 区域线路修改
 * @apiGroup 03
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 线路ID
 * @apiParam {string} name 线路名称
 * @apiParam {string} country 国家
 * @apiParam {string} warehouse_id 网点ID
 * @apiParam {string} remark 线路备注
 * @apiParam {string} pickup_max_count 1
 * @apiParam {string} pie_max_count 1
 * @apiParam {string} is_increment 1
 * @apiParam {string} order_deadline 23:59:59
 * @apiParam {string} appointment_days 30
 * @apiParam {string} coordinate_list
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

/**
 * @api {delete} /admin/line/area/{id} 区域线路删除
 * @apiName 区域线路删除
 * @apiGroup 03
 * @apiVersion 1.0.0
 * @apiUse auth
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

/**
 * @api {put} /admin/line/status 批量启用禁用
 * @apiName 批量启用禁用
 * @apiGroup 03
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list 线路ID列表,以逗号分隔
 * @apiParam {string} status 1-启用，2-禁用
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

/**
 * @api {get} /admin/line/by-date 获取线路列表
 * @apiName 获取线路列表
 * @apiGroup 03
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} date 日期
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 线路ID
 * @apiSuccess {string} data.name 线路名称
 * @apiSuccess {string} data.country_name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 35,
 * "name": "当日派",
 * "country_name": null
 * },
 * {
 * "id": 148,
 * "name": "ledon(1)",
 * "country_name": null
 * },
 * {
 * "id": 175,
 * "name": "NL",
 * "country_name": null
 * },
 * {
 * "id": 176,
 * "name": "Line-NL",
 * "country_name": null
 * },
 * {
 * "id": 178,
 * "name": "10",
 * "country_name": null
 * },
 * {
 * "id": 179,
 * "name": "50",
 * "country_name": null
 * },
 * {
 * "id": 247,
 * "name": "鹿特丹",
 * "country_name": null
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/line/{id}/merchant-group-line-range 获取货主组线路范围详情
 * @apiName 获取货主组线路范围详情
 * @apiGroup 03
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 线路ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 线路ID
 * @apiSuccess {string} data.name 线路名称
 * @apiSuccess {string} data.country_name
 * @apiSuccess {string} data.merchant_line_range_list 邮编列表
 * @apiSuccess {string} data.merchant_group_line_range_list.post_code_range 邮编范围
 * @apiSuccess {string} data.merchant_group_line_range_list.workday_list 邮编取派日列表
 * @apiSuccess {string} data.merchant_group_line_range_list.merchant_group_list 货主组列表
 * @apiSuccess {string} data.merchant_group_line_range_list.merchant_group_list.id
 * @apiSuccess {string} data.merchant_group_line_range_list.merchant_group_list.company_id
 * @apiSuccess {string} data.merchant_group_line_range_list.merchant_group_list.merchant_id 货主组ID
 * @apiSuccess {string} data.merchant_group_line_range_list.merchant_group_list.line_id 线路ID
 * @apiSuccess {string} data.merchant_group_line_range_list.merchant_group_list.is_alone 是否独立取派1-是;2-否
 * @apiSuccess {string} data.merchant_group_line_range_list.merchant_group_list.merchant_id_name 货主组名称
 * @apiSuccess {string} data.merchant_group_line_range_list.merchant_group_list.workday_list 货主组取派日列表
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1001,
 * "name": "Same day delivery (MES)",
 * "country_name": null,
 * "can_skip_batch_name": null,
 * "merchant_group_line_range_list": [
 * {
 * "post_code_range": "1081-1083",
 * "workday_list": [
 * 1
 * ],
 * "merchant_group_list": [
 * {
 * "id": 21445,
 * "company_id": 3,
 * "merchant_group_id": 3,
 * "line_id": 1001,
 * "is_alone": 1,
 * "merchant_group_id_name": "tms",
 * "workday_list": [
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1
 * ]
 * },
 * {
 * "id": 22092,
 * "company_id": 3,
 * "merchant_group_id": 48,
 * "line_id": 1001,
 * "is_alone": 2,
 * "merchant_group_id_name": "VIP_2",
 * "workday_list": [
 * 1
 * ]
 * }
 * ]
 * },
 * {
 * "post_code_range": "1181-1189",
 * "workday_list": [
 * 1
 * ],
 * "merchant_group_list": [
 * {
 * "id": 21453,
 * "company_id": 3,
 * "merchant_group_id": 3,
 * "line_id": 1001,
 * "is_alone": 1,
 * "merchant_group_id_name": "tms",
 * "workday_list": [
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1
 * ]
 * },
 * {
 * "id": 22093,
 * "company_id": 3,
 * "merchant_group_id": 48,
 * "line_id": 1001,
 * "is_alone": 2,
 * "merchant_group_id_name": "VIP_2",
 * "workday_list": [
 * 1
 * ]
 * }
 * ]
 * },
 * {
 * "post_code_range": "1420-1438",
 * "workday_list": [
 * 1
 * ],
 * "merchant_group_list": [
 * {
 * "id": 21461,
 * "company_id": 3,
 * "merchant_group_id": 3,
 * "line_id": 1001,
 * "is_alone": 1,
 * "merchant_group_id_name": "tms",
 * "workday_list": [
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1
 * ]
 * },
 * {
 * "id": 22094,
 * "company_id": 3,
 * "merchant_group_id": 48,
 * "line_id": 1001,
 * "is_alone": 2,
 * "merchant_group_id_name": "VIP_2",
 * "workday_list": [
 * 1
 * ]
 * }
 * ]
 * },
 * {
 * "post_code_range": "1117-1119",
 * "workday_list": [
 * 1
 * ],
 * "merchant_group_list": [
 * {
 * "id": 21469,
 * "company_id": 3,
 * "merchant_group_id": 3,
 * "line_id": 1001,
 * "is_alone": 1,
 * "merchant_group_id_name": "tms",
 * "workday_list": [
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1,
 * 1
 * ]
 * },
 * {
 * "id": 22095,
 * "company_id": 3,
 * "merchant_group_id": 48,
 * "line_id": 1001,
 * "is_alone": 2,
 * "merchant_group_id_name": "VIP_2",
 * "workday_list": [
 * 1
 * ]
 * }
 * ]
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/line/{id}/merchant-group-line-range 配置货主组线路范围
 * @apiName 配置货主组线路范围
 * @apiGroup 03
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 线路ID
 * @apiParam {string} merchant_group_line_range_list 货主组线路范围列表
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

/**
 * @api {put} /admin/line/{id} 邮编线路修改
 * @apiName 邮编线路修改
 * @apiGroup 03
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 线路ID
 * @apiParam {string} name 线路名称
 * @apiParam {string} country 国家
 * @apiParam {string} warehouse_id 网点ID
 * @apiParam {string} remark 线路备注
 * @apiParam {string} work_day_list 取件日期列表(0-星期日1-星期一2-星期二3-星期三4-星期四5-星期五6-星期六)
 * @apiParam {string} item_list 邮编范围列表
 * @apiParam {string} pickup_max_count 取件最大订单数量
 * @apiParam {string} pie_max_count 派件最大订单数量
 * @apiParam {string} is_increment 是否新增取件线路1-是2-否
 * @apiParam {string} order_deadline 下单截止时间
 * @apiParam {string} appointment_days 可预约天数范围
 * @apiParam {string} merchant_group_count_list 货主组最小订单量列表
 * @apiParam {string} status 状态1-启用2-禁用
 * @apiParam {string} appointment_days 可预约天数
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

/**
 * @api {get} /admin/line/test 线路测试
 * @apiName 线路测试
 * @apiGroup 03
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} place_fullname
 * @apiParam {string} place_phone
 * @apiParam {string} place_country
 * @apiParam {string} place_province
 * @apiParam {string} place_post_code 邮编
 * @apiParam {string} place_house_number
 * @apiParam {string} place_city
 * @apiParam {string} place_district
 * @apiParam {string} place_street
 * @apiParam {string} place_address
 * @apiParam {string} place_lat 纬度
 * @apiParam {string} place_lon 经度
 * @apiParam {string} execution_date 取派日期
 * @apiParam {string} second_place_fullname
 * @apiParam {string} second_place_phone
 * @apiParam {string} second_place_country
 * @apiParam {string} second_place_province
 * @apiParam {string} second_place_post_code
 * @apiParam {string} second_place_house_number
 * @apiParam {string} second_place_city
 * @apiParam {string} second_place_district
 * @apiParam {string} second_place_street
 * @apiParam {string} second_place_address
 * @apiParam {string} second_place_lat
 * @apiParam {string} second_place_lon
 * @apiParam {string} second_execution_date
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.type 类型1-取件2-派件3-取派
 * @apiSuccess {string} data.name 名称
 * @apiSuccess {string} data.is_center 图标类型1-分拨中心2-网点3-收发件人
 * @apiSuccess {string} data.status 状态1-寄件人2-网点取件3-分拨中心4-网点派件5-收件人6-网点取件/派件
 * @apiSuccess {string} data.status_name
 * @apiSuccess {string} data.is_center_name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "type": 1,
 * "name": "保利",
 * "is_center": 3,
 * "status": 1,
 * "status_name": "寄件人",
 * "is_center_name": "地址"
 * },
 * {
 * "name": "tianyaox",
 * "is_center": 2,
 * "type": 3,
 * "status": 6,
 * "status_name": "网点取件/派件",
 * "is_center_name": "网点"
 * },
 * {
 * "type": 2,
 * "name": "保利",
 * "is_center": 3,
 * "status": 5,
 * "status_name": "收件人",
 * "is_center_name": "地址"
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/company-info 公司详情
 * @apiName 公司详情
 * @apiGroup 04
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 公司ID
 * @apiSuccess {string} data.data.name 公司名称
 * @apiSuccess {string} data.data.contacts 公司联系人
 * @apiSuccess {string} data.data.phone 公司电话
 * @apiSuccess {string} data.data.country 所在国家
 * @apiSuccess {string} data.data.address 公司地址
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.data.web_site 企业网址
 * @apiSuccess {string} data.data.system_name 系统名称
 * @apiSuccess {string} data.data.logo_url 企业Logo
 * @apiSuccess {string} data.data.login_logo_url 登录页Logo
 * @apiSuccess {string} data.data.lon 经度
 * @apiSuccess {string} data.data.lat 纬度
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": {
 * "id": 1,
 * "name": "恩尔伊科技",
 * "contacts": "",
 * "phone": "15655695569",
 * "country": "中国",
 * "address": "湖北省武汉市"
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/company-info 公司修改
 * @apiName 公司修改
 * @apiGroup 04
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 公司名称
 * @apiParam {string} contacts 公司联系人
 * @apiParam {string} phone 公司电话
 * @apiParam {string} country 所在国家
 * @apiParam {string} address 公司地址
 * @apiParam {string} web_site 企业网址
 * @apiParam {string} system_name 系统名称
 * @apiParam {string} logo_url 企业Logo
 * @apiParam {string} login_logo_url 登录页Logo
 * @apiParam {string} lon 经度
 * @apiParam {string} lat 纬度
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [],
 * "msg": "success"
 * }
 */

/**
 * @api {get} /admin/company-config/show 查询公司配置
 * @apiName 查询公司配置
 * @apiGroup 04
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.line_rule 线路分配规则
 * @apiSuccess {string} data.weight_unit 重量单位
 * @apiSuccess {string} data.currency_unit 货币单位
 * @apiSuccess {string} data.volume_unit 体积单位
 * @apiSuccess {string} data.map 地图引擎
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.show_type 展示方式（1-全部展示2-按线路规则展示）
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 3,
 * "company_id": 1,
 * "line_rule": "POST_CODE",
 * "show_type": 1,
 * "weight_unit": "G",
 * "currency_unit": "$",
 * "volume_unit": "L",
 * "map": "google",
 * "created_at": "2020-02-13 16:36:36",
 * "updated_at": "2020-02-13 16:36:36"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/company-config/update 更新公司配置
 * @apiName 更新公司配置
 * @apiGroup 04
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} line_rule 线路分配规则
 * @apiParam {string} weight_unit 重量单位
 * @apiParam {string} currency_unit 货币单位
 * @apiParam {string} volume_unit 体积单位
 * @apiParam {string} map 地图引擎
 * @apiParam {string} show_type 展示方式
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

/**
 * @api {get} /admin/company-config/address-template 获取地址模板
 * @apiName 获取地址模板
 * @apiGroup 04
 * @apiVersion 1.0.0
 * @apiUse auth
 */

/**
 * @api {get} /admin/company-config/unit 计量单位详情
 * @apiName 计量单位详情
 * @apiGroup 04
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.weight_unit 重量单位
 * @apiSuccess {string} data.currency_unit 货币单位
 * @apiSuccess {string} data.volume_unit 体积单位
 * @apiSuccess {string} msg
 */

/**
 * @api {put} /admin/company-config/unit 计量单位更新
 * @apiName 计量单位更新
 * @apiGroup 04
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} weight_unit 重量单位
 * @apiParam {string} currency_unit 货币单位
 * @apiParam {string} volume_unit 体积单位
 */

/**
 * @api {get} /admin/company-config/rule 调度规则详情
 * @apiName 调度规则详情
 * @apiGroup 04
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.line_rule 线路分配规则
 * @apiSuccess {string} data.scheduling_rule 调度规则
 * @apiSuccess {string} msg
 */

/**
 * @api {put} /admin/company-config/rule 调度规则更新
 * @apiName 调度规则更新
 * @apiGroup 04
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} line_rule 联络规则
 * @apiParam {string} scheduling_rule 调度规则
 */

/**
 * @api {get} /admin/common/location 获取具体地址经纬度
 * @apiName 获取具体地址经纬度
 * @apiGroup 05
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} country 国家
 * @apiParam {string} post_code 邮编
 * @apiParam {string} house_number 门牌号
 * @apiParam {string} city 城市(如果国际是荷兰，不填)
 * @apiParam {string} street 街道(如果国际是荷兰，不填)
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.lon 经度
 * @apiSuccess {string} data.lat 纬度
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "lon": 4.6289699,
 * "lat": 52.2534749
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/common/country 获取所有国家列表
 * @apiName 获取所有国家列表
 * @apiGroup 05
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.short
 * @apiSuccess {string} data.name
 * @apiSuccess {string} data.tel
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 44,
 * "short": "DE",
 * "tel": "",
 * "name": "德国"
 * },
 * {
 * "id": 28,
 * "short": "Netherlands",
 * "tel": "",
 * "name": "荷兰"
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} {{url}}/admin/source 获取来源记录
 * @apiName 获取来源记录
 * @apiGroup 05
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.source_name 来源名
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 3,
 * "company_id": 9,
 * "source_name": "ERP"
 * },
 * {
 * "id": 4,
 * "company_id": 9,
 * "source_name": "ERP1"
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/common/address/{country} 获取国家地址
 * @apiName 获取国家地址
 * @apiGroup 05
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} country 国家简称
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.Name
 * @apiSuccess {string} data.Code
 * @apiSuccess {string} data.State
 * @apiSuccess {string} data.State.Name
 * @apiSuccess {string} data.State.Code
 * @apiSuccess {string} data.State.City
 * @apiSuccess {string} data.State.City.Name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "Name": "中国台湾",
 * "Code": "TW",
 * "State": [
 * {
 * "Name": "台北市",
 * "Code": "",
 * "City": [
 * {
 * "Name": "中正区"
 * },
 * {
 * "Name": "大同区"
 * },
 * {
 * "Name": "中山区"
 * },
 * {
 * "Name": "松山区"
 * },
 * {
 * "Name": "大安区"
 * },
 * {
 * "Name": "万华区"
 * },
 * {
 * "Name": "信义区"
 * },
 * {
 * "Name": "士林区"
 * },
 * {
 * "Name": "北投区"
 * },
 * {
 * "Name": "内湖区"
 * },
 * {
 * "Name": "南港区"
 * },
 * {
 * "Name": "文山区"
 * },
 * {
 * "Name": "其它区"
 * }
 * ]
 * },
 * {
 * "Name": "高雄市",
 * "Code": "",
 * "City": [
 * {
 * "Name": "新兴区"
 * },
 * {
 * "Name": "前金区"
 * },
 * {
 * "Name": "芩雅区"
 * },
 * {
 * "Name": "盐埕区"
 * },
 * {
 * "Name": "鼓山区"
 * },
 * {
 * "Name": "旗津区"
 * },
 * {
 * "Name": "前镇区"
 * },
 * {
 * "Name": "三民区"
 * },
 * {
 * "Name": "左营区"
 * },
 * {
 * "Name": "楠梓区"
 * },
 * {
 * "Name": "小港区"
 * },
 * {
 * "Name": "其它区"
 * },
 * {
 * "Name": "苓雅区"
 * },
 * {
 * "Name": "仁武区"
 * },
 * {
 * "Name": "大社区"
 * },
 * {
 * "Name": "冈山区"
 * },
 * {
 * "Name": "路竹区"
 * },
 * {
 * "Name": "阿莲区"
 * },
 * {
 * "Name": "田寮区"
 * },
 * {
 * "Name": "燕巢区"
 * },
 * {
 * "Name": "桥头区"
 * },
 * {
 * "Name": "梓官区"
 * },
 * {
 * "Name": "弥陀区"
 * },
 * {
 * "Name": "永安区"
 * },
 * {
 * "Name": "湖内区"
 * },
 * {
 * "Name": "凤山区"
 * },
 * {
 * "Name": "大寮区"
 * },
 * {
 * "Name": "林园区"
 * },
 * {
 * "Name": "鸟松区"
 * },
 * {
 * "Name": "大树区"
 * },
 * {
 * "Name": "旗山区"
 * },
 * {
 * "Name": "美浓区"
 * },
 * {
 * "Name": "六龟区"
 * },
 * {
 * "Name": "内门区"
 * },
 * {
 * "Name": "杉林区"
 * },
 * {
 * "Name": "甲仙区"
 * },
 * {
 * "Name": "桃源区"
 * },
 * {
 * "Name": "那玛夏区"
 * },
 * {
 * "Name": "茂林区"
 * },
 * {
 * "Name": "茄萣区"
 * }
 * ]
 * },
 * {
 * "Name": "台南市",
 * "Code": "",
 * "City": [
 * {
 * "Name": "中西区"
 * },
 * {
 * "Name": "东区"
 * },
 * {
 * "Name": "南区"
 * },
 * {
 * "Name": "北区"
 * },
 * {
 * "Name": "安平区"
 * },
 * {
 * "Name": "安南区"
 * },
 * {
 * "Name": "其它区"
 * },
 * {
 * "Name": "永康区"
 * },
 * {
 * "Name": "归仁区"
 * },
 * {
 * "Name": "新化区"
 * },
 * {
 * "Name": "左镇区"
 * },
 * {
 * "Name": "玉井区"
 * },
 * {
 * "Name": "楠西区"
 * },
 * {
 * "Name": "南化区"
 * },
 * {
 * "Name": "仁德区"
 * },
 * {
 * "Name": "关庙区"
 * },
 * {
 * "Name": "龙崎区"
 * },
 * {
 * "Name": "官田区"
 * },
 * {
 * "Name": "麻豆区"
 * },
 * {
 * "Name": "佳里区"
 * },
 * {
 * "Name": "西港区"
 * },
 * {
 * "Name": "七股区"
 * },
 * {
 * "Name": "将军区"
 * },
 * {
 * "Name": "学甲区"
 * },
 * {
 * "Name": "北门区"
 * },
 * {
 * "Name": "新营区"
 * },
 * {
 * "Name": "后壁区"
 * },
 * {
 * "Name": "白河区"
 * },
 * {
 * "Name": "东山区"
 * },
 * {
 * "Name": "六甲区"
 * },
 * {
 * "Name": "下营区"
 * },
 * {
 * "Name": "柳营区"
 * },
 * {
 * "Name": "盐水区"
 * },
 * {
 * "Name": "善化区"
 * },
 * {
 * "Name": "大内区"
 * },
 * {
 * "Name": "山上区"
 * },
 * {
 * "Name": "新市区"
 * },
 * {
 * "Name": "安定区"
 * }
 * ]
 * },
 * {
 * "Name": "台中市",
 * "Code": "",
 * "City": [
 * {
 * "Name": "中区"
 * },
 * {
 * "Name": "东区"
 * },
 * {
 * "Name": "南区"
 * },
 * {
 * "Name": "西区"
 * },
 * {
 * "Name": "北区"
 * },
 * {
 * "Name": "北屯区"
 * },
 * {
 * "Name": "西屯区"
 * },
 * {
 * "Name": "南屯区"
 * },
 * {
 * "Name": "其它区"
 * },
 * {
 * "Name": "太平区"
 * },
 * {
 * "Name": "大里区"
 * },
 * {
 * "Name": "雾峰区"
 * },
 * {
 * "Name": "乌日区"
 * },
 * {
 * "Name": "丰原区"
 * },
 * {
 * "Name": "后里区"
 * },
 * {
 * "Name": "石冈区"
 * },
 * {
 * "Name": "东势区"
 * },
 * {
 * "Name": "和平区"
 * },
 * {
 * "Name": "新社区"
 * },
 * {
 * "Name": "潭子区"
 * },
 * {
 * "Name": "大雅区"
 * },
 * {
 * "Name": "神冈区"
 * },
 * {
 * "Name": "大肚区"
 * },
 * {
 * "Name": "沙鹿区"
 * },
 * {
 * "Name": "龙井区"
 * },
 * {
 * "Name": "梧栖区"
 * },
 * {
 * "Name": "清水区"
 * },
 * {
 * "Name": "大甲区"
 * },
 * {
 * "Name": "外埔区"
 * },
 * {
 * "Name": "大安区"
 * }
 * ]
 * },
 * {
 * "Name": "金门县",
 * "Code": "",
 * "City": [
 * {
 * "Name": "金沙镇"
 * },
 * {
 * "Name": "金湖镇"
 * },
 * {
 * "Name": "金宁乡"
 * },
 * {
 * "Name": "金城镇"
 * },
 * {
 * "Name": "烈屿乡"
 * },
 * {
 * "Name": "乌坵乡"
 * }
 * ]
 * },
 * {
 * "Name": "南投县",
 * "Code": "",
 * "City": [
 * {
 * "Name": "南投市"
 * },
 * {
 * "Name": "中寮乡"
 * },
 * {
 * "Name": "草屯镇"
 * },
 * {
 * "Name": "国姓乡"
 * },
 * {
 * "Name": "埔里镇"
 * },
 * {
 * "Name": "仁爱乡"
 * },
 * {
 * "Name": "名间乡"
 * },
 * {
 * "Name": "集集镇"
 * },
 * {
 * "Name": "水里乡"
 * },
 * {
 * "Name": "鱼池乡"
 * },
 * {
 * "Name": "信义乡"
 * },
 * {
 * "Name": "竹山镇"
 * },
 * {
 * "Name": "鹿谷乡"
 * }
 * ]
 * },
 * {
 * "Name": "基隆市",
 * "Code": "",
 * "City": [
 * {
 * "Name": "仁爱区"
 * },
 * {
 * "Name": "信义区"
 * },
 * {
 * "Name": "中正区"
 * },
 * {
 * "Name": "中山区"
 * },
 * {
 * "Name": "安乐区"
 * },
 * {
 * "Name": "暖暖区"
 * },
 * {
 * "Name": "七堵区"
 * },
 * {
 * "Name": "其它区"
 * }
 * ]
 * },
 * {
 * "Name": "新竹市",
 * "Code": "",
 * "City": [
 * {
 * "Name": "东区"
 * },
 * {
 * "Name": "北区"
 * },
 * {
 * "Name": "香山区"
 * },
 * {
 * "Name": "其它区"
 * }
 * ]
 * },
 * {
 * "Name": "嘉义市",
 * "Code": "",
 * "City": [
 * {
 * "Name": "东区"
 * },
 * {
 * "Name": "西区"
 * },
 * {
 * "Name": "其它区"
 * }
 * ]
 * },
 * {
 * "Name": "新北市",
 * "Code": "",
 * "City": [
 * {
 * "Name": "万里区"
 * },
 * {
 * "Name": "金山区"
 * },
 * {
 * "Name": "板桥区"
 * },
 * {
 * "Name": "汐止区"
 * },
 * {
 * "Name": "深坑区"
 * },
 * {
 * "Name": "石碇区"
 * },
 * {
 * "Name": "瑞芳区"
 * },
 * {
 * "Name": "平溪区"
 * },
 * {
 * "Name": "双溪区"
 * },
 * {
 * "Name": "贡寮区"
 * },
 * {
 * "Name": "新店区"
 * },
 * {
 * "Name": "坪林区"
 * },
 * {
 * "Name": "乌来区"
 * },
 * {
 * "Name": "永和区"
 * },
 * {
 * "Name": "中和区"
 * },
 * {
 * "Name": "土城区"
 * },
 * {
 * "Name": "三峡区"
 * },
 * {
 * "Name": "树林区"
 * },
 * {
 * "Name": "莺歌区"
 * },
 * {
 * "Name": "三重区"
 * },
 * {
 * "Name": "新庄区"
 * },
 * {
 * "Name": "泰山区"
 * },
 * {
 * "Name": "林口区"
 * },
 * {
 * "Name": "芦洲区"
 * },
 * {
 * "Name": "五股区"
 * },
 * {
 * "Name": "八里区"
 * },
 * {
 * "Name": "淡水区"
 * },
 * {
 * "Name": "三芝区"
 * },
 * {
 * "Name": "石门区"
 * }
 * ]
 * },
 * {
 * "Name": "宜兰县",
 * "Code": "",
 * "City": [
 * {
 * "Name": "宜兰市"
 * },
 * {
 * "Name": "头城镇"
 * },
 * {
 * "Name": "礁溪乡"
 * },
 * {
 * "Name": "壮围乡"
 * },
 * {
 * "Name": "员山乡"
 * },
 * {
 * "Name": "罗东镇"
 * },
 * {
 * "Name": "三星乡"
 * },
 * {
 * "Name": "大同乡"
 * },
 * {
 * "Name": "五结乡"
 * },
 * {
 * "Name": "冬山乡"
 * },
 * {
 * "Name": "苏澳镇"
 * },
 * {
 * "Name": "南澳乡"
 * },
 * {
 * "Name": "钓鱼台"
 * }
 * ]
 * },
 * {
 * "Name": "新竹县",
 * "Code": "",
 * "City": [
 * {
 * "Name": "竹北市"
 * },
 * {
 * "Name": "湖口乡"
 * },
 * {
 * "Name": "新丰乡"
 * },
 * {
 * "Name": "新埔镇"
 * },
 * {
 * "Name": "关西镇"
 * },
 * {
 * "Name": "芎林乡"
 * },
 * {
 * "Name": "宝山乡"
 * },
 * {
 * "Name": "竹东镇"
 * },
 * {
 * "Name": "五峰乡"
 * },
 * {
 * "Name": "横山乡"
 * },
 * {
 * "Name": "尖石乡"
 * },
 * {
 * "Name": "北埔乡"
 * },
 * {
 * "Name": "峨眉乡"
 * }
 * ]
 * },
 * {
 * "Name": "桃园县",
 * "Code": "",
 * "City": [
 * {
 * "Name": "中坜市"
 * },
 * {
 * "Name": "平镇市"
 * },
 * {
 * "Name": "龙潭乡"
 * },
 * {
 * "Name": "杨梅市"
 * },
 * {
 * "Name": "新屋乡"
 * },
 * {
 * "Name": "观音乡"
 * },
 * {
 * "Name": "桃园市"
 * },
 * {
 * "Name": "龟山乡"
 * },
 * {
 * "Name": "八德市"
 * },
 * {
 * "Name": "大溪镇"
 * },
 * {
 * "Name": "复兴乡"
 * },
 * {
 * "Name": "大园乡"
 * },
 * {
 * "Name": "芦竹乡"
 * }
 * ]
 * },
 * {
 * "Name": "苗栗县",
 * "Code": "",
 * "City": [
 * {
 * "Name": "竹南镇"
 * },
 * {
 * "Name": "头份镇"
 * },
 * {
 * "Name": "三湾乡"
 * },
 * {
 * "Name": "南庄乡"
 * },
 * {
 * "Name": "狮潭乡"
 * },
 * {
 * "Name": "后龙镇"
 * },
 * {
 * "Name": "通霄镇"
 * },
 * {
 * "Name": "苑里镇"
 * },
 * {
 * "Name": "苗栗市"
 * },
 * {
 * "Name": "造桥乡"
 * },
 * {
 * "Name": "头屋乡"
 * },
 * {
 * "Name": "公馆乡"
 * },
 * {
 * "Name": "大湖乡"
 * },
 * {
 * "Name": "泰安乡"
 * },
 * {
 * "Name": "铜锣乡"
 * },
 * {
 * "Name": "三义乡"
 * },
 * {
 * "Name": "西湖乡"
 * },
 * {
 * "Name": "卓兰镇"
 * }
 * ]
 * },
 * {
 * "Name": "彰化县",
 * "Code": "",
 * "City": [
 * {
 * "Name": "彰化市"
 * },
 * {
 * "Name": "芬园乡"
 * },
 * {
 * "Name": "花坛乡"
 * },
 * {
 * "Name": "秀水乡"
 * },
 * {
 * "Name": "鹿港镇"
 * },
 * {
 * "Name": "福兴乡"
 * },
 * {
 * "Name": "线西乡"
 * },
 * {
 * "Name": "和美镇"
 * },
 * {
 * "Name": "伸港乡"
 * },
 * {
 * "Name": "员林镇"
 * },
 * {
 * "Name": "社头乡"
 * },
 * {
 * "Name": "永靖乡"
 * },
 * {
 * "Name": "埔心乡"
 * },
 * {
 * "Name": "溪湖镇"
 * },
 * {
 * "Name": "大村乡"
 * },
 * {
 * "Name": "埔盐乡"
 * },
 * {
 * "Name": "田中镇"
 * },
 * {
 * "Name": "北斗镇"
 * },
 * {
 * "Name": "田尾乡"
 * },
 * {
 * "Name": "埤头乡"
 * },
 * {
 * "Name": "溪州乡"
 * },
 * {
 * "Name": "竹塘乡"
 * },
 * {
 * "Name": "二林镇"
 * },
 * {
 * "Name": "大城乡"
 * },
 * {
 * "Name": "芳苑乡"
 * },
 * {
 * "Name": "二水乡"
 * }
 * ]
 * },
 * {
 * "Name": "嘉义县",
 * "Code": "",
 * "City": [
 * {
 * "Name": "番路乡"
 * },
 * {
 * "Name": "梅山乡"
 * },
 * {
 * "Name": "竹崎乡"
 * },
 * {
 * "Name": "阿里山乡"
 * },
 * {
 * "Name": "中埔乡"
 * },
 * {
 * "Name": "大埔乡"
 * },
 * {
 * "Name": "水上乡"
 * },
 * {
 * "Name": "鹿草乡"
 * },
 * {
 * "Name": "太保市"
 * },
 * {
 * "Name": "朴子市"
 * },
 * {
 * "Name": "东石乡"
 * },
 * {
 * "Name": "六脚乡"
 * },
 * {
 * "Name": "新港乡"
 * },
 * {
 * "Name": "民雄乡"
 * },
 * {
 * "Name": "大林镇"
 * },
 * {
 * "Name": "溪口乡"
 * },
 * {
 * "Name": "义竹乡"
 * },
 * {
 * "Name": "布袋镇"
 * }
 * ]
 * },
 * {
 * "Name": "云林县",
 * "Code": "",
 * "City": [
 * {
 * "Name": "斗南镇"
 * },
 * {
 * "Name": "大埤乡"
 * },
 * {
 * "Name": "虎尾镇"
 * },
 * {
 * "Name": "土库镇"
 * },
 * {
 * "Name": "褒忠乡"
 * },
 * {
 * "Name": "东势乡"
 * },
 * {
 * "Name": "台西乡"
 * },
 * {
 * "Name": "仑背乡"
 * },
 * {
 * "Name": "麦寮乡"
 * },
 * {
 * "Name": "斗六市"
 * },
 * {
 * "Name": "林内乡"
 * },
 * {
 * "Name": "古坑乡"
 * },
 * {
 * "Name": "莿桐乡"
 * },
 * {
 * "Name": "西螺镇"
 * },
 * {
 * "Name": "二仑乡"
 * },
 * {
 * "Name": "北港镇"
 * },
 * {
 * "Name": "水林乡"
 * },
 * {
 * "Name": "口湖乡"
 * },
 * {
 * "Name": "四湖乡"
 * },
 * {
 * "Name": "元长乡"
 * }
 * ]
 * },
 * {
 * "Name": "屏东县",
 * "Code": "",
 * "City": [
 * {
 * "Name": "屏东市"
 * },
 * {
 * "Name": "三地门乡"
 * },
 * {
 * "Name": "雾台乡"
 * },
 * {
 * "Name": "玛家乡"
 * },
 * {
 * "Name": "九如乡"
 * },
 * {
 * "Name": "里港乡"
 * },
 * {
 * "Name": "高树乡"
 * },
 * {
 * "Name": "盐埔乡"
 * },
 * {
 * "Name": "长治乡"
 * },
 * {
 * "Name": "麟洛乡"
 * },
 * {
 * "Name": "竹田乡"
 * },
 * {
 * "Name": "内埔乡"
 * },
 * {
 * "Name": "万丹乡"
 * },
 * {
 * "Name": "潮州镇"
 * },
 * {
 * "Name": "泰武乡"
 * },
 * {
 * "Name": "来义乡"
 * },
 * {
 * "Name": "万峦乡"
 * },
 * {
 * "Name": "崁顶乡"
 * },
 * {
 * "Name": "新埤乡"
 * },
 * {
 * "Name": "南州乡"
 * },
 * {
 * "Name": "林边乡"
 * },
 * {
 * "Name": "东港镇"
 * },
 * {
 * "Name": "琉球乡"
 * },
 * {
 * "Name": "佳冬乡"
 * },
 * {
 * "Name": "新园乡"
 * },
 * {
 * "Name": "枋寮乡"
 * },
 * {
 * "Name": "枋山乡"
 * },
 * {
 * "Name": "春日乡"
 * },
 * {
 * "Name": "狮子乡"
 * },
 * {
 * "Name": "车城乡"
 * },
 * {
 * "Name": "牡丹乡"
 * },
 * {
 * "Name": "恒春镇"
 * },
 * {
 * "Name": "满州乡"
 * }
 * ]
 * },
 * {
 * "Name": "台东县",
 * "Code": "",
 * "City": [
 * {
 * "Name": "台东市"
 * },
 * {
 * "Name": "绿岛乡"
 * },
 * {
 * "Name": "兰屿乡"
 * },
 * {
 * "Name": "延平乡"
 * },
 * {
 * "Name": "卑南乡"
 * },
 * {
 * "Name": "鹿野乡"
 * },
 * {
 * "Name": "关山镇"
 * },
 * {
 * "Name": "海端乡"
 * },
 * {
 * "Name": "池上乡"
 * },
 * {
 * "Name": "东河乡"
 * },
 * {
 * "Name": "成功镇"
 * },
 * {
 * "Name": "长滨乡"
 * },
 * {
 * "Name": "金峰乡"
 * },
 * {
 * "Name": "大武乡"
 * },
 * {
 * "Name": "达仁乡"
 * },
 * {
 * "Name": "太麻里乡"
 * }
 * ]
 * },
 * {
 * "Name": "花莲县",
 * "Code": "",
 * "City": [
 * {
 * "Name": "花莲市"
 * },
 * {
 * "Name": "新城乡"
 * },
 * {
 * "Name": "太鲁阁"
 * },
 * {
 * "Name": "秀林乡"
 * },
 * {
 * "Name": "吉安乡"
 * },
 * {
 * "Name": "寿丰乡"
 * },
 * {
 * "Name": "凤林镇"
 * },
 * {
 * "Name": "光复乡"
 * },
 * {
 * "Name": "丰滨乡"
 * },
 * {
 * "Name": "瑞穗乡"
 * },
 * {
 * "Name": "万荣乡"
 * },
 * {
 * "Name": "玉里镇"
 * },
 * {
 * "Name": "卓溪乡"
 * },
 * {
 * "Name": "富里乡"
 * }
 * ]
 * },
 * {
 * "Name": "澎湖县",
 * "Code": "",
 * "City": [
 * {
 * "Name": "马公市"
 * },
 * {
 * "Name": "西屿乡"
 * },
 * {
 * "Name": "望安乡"
 * },
 * {
 * "Name": "七美乡"
 * },
 * {
 * "Name": "白沙乡"
 * },
 * {
 * "Name": "湖西乡"
 * }
 * ]
 * },
 * {
 * "Name": "连江县",
 * "Code": "",
 * "City": [
 * {
 * "Name": "南竿乡"
 * },
 * {
 * "Name": "北竿乡"
 * },
 * {
 * "Name": "莒光乡"
 * },
 * {
 * "Name": "东引乡"
 * }
 * ]
 * }
 * ]
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /common/postcode 获取邮编
 * @apiName 获取邮编
 * @apiGroup 05
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} province 省份
 * @apiParam {string} city 城市
 * @apiParam {string} district 区县
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.postcode 邮编
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "postcode": 430702
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/common/dictionary 对照表
 * @apiName 对照表
 * @apiGroup 05
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.noTypeList 对照表
 * @apiSuccess {string} data.noTypeList.id id
 * @apiSuccess {string} data.noTypeList.name 名
 * @apiSuccess {string} data.lineRuleList
 * @apiSuccess {string} data.lineRuleList.id
 * @apiSuccess {string} data.lineRuleList.name
 * @apiSuccess {string} data.printTemplateList
 * @apiSuccess {string} data.printTemplateList.id
 * @apiSuccess {string} data.printTemplateList.name
 * @apiSuccess {string} data.weekList
 * @apiSuccess {string} data.weekList.id
 * @apiSuccess {string} data.weekList.name
 * @apiSuccess {string} data.orderTypeList
 * @apiSuccess {string} data.orderTypeList.id
 * @apiSuccess {string} data.orderTypeList.name
 * @apiSuccess {string} data.orderSourceList
 * @apiSuccess {string} data.orderSourceList.id
 * @apiSuccess {string} data.orderSourceList.name
 * @apiSuccess {string} data.orderSettlementTypeList
 * @apiSuccess {string} data.orderSettlementTypeList.id
 * @apiSuccess {string} data.orderSettlementTypeList.name
 * @apiSuccess {string} data.orderStatusList
 * @apiSuccess {string} data.orderStatusList.id
 * @apiSuccess {string} data.orderStatusList.name
 * @apiSuccess {string} data.orderOutStatusList
 * @apiSuccess {string} data.orderOutStatusList.id
 * @apiSuccess {string} data.orderOutStatusList.name
 * @apiSuccess {string} data.packageStatusList
 * @apiSuccess {string} data.packageStatusList.id
 * @apiSuccess {string} data.packageStatusList.name
 * @apiSuccess {string} data.merchantPackageStatusList
 * @apiSuccess {string} data.merchantPackageStatusList.id
 * @apiSuccess {string} data.merchantPackageStatusList.name
 * @apiSuccess {string} data.orderExceptionLabelList
 * @apiSuccess {string} data.orderExceptionLabelList.id
 * @apiSuccess {string} data.orderExceptionLabelList.name
 * @apiSuccess {string} data.orderNatureList
 * @apiSuccess {string} data.orderNatureList.id
 * @apiSuccess {string} data.orderNatureList.name
 * @apiSuccess {string} data.batchPayTypeList
 * @apiSuccess {string} data.batchPayTypeList.id
 * @apiSuccess {string} data.batchPayTypeList.name
 * @apiSuccess {string} data.batchExceptionLabelList
 * @apiSuccess {string} data.batchExceptionLabelList.id
 * @apiSuccess {string} data.batchExceptionLabelList.name
 * @apiSuccess {string} data.batchStatusList
 * @apiSuccess {string} data.batchStatusList.id
 * @apiSuccess {string} data.batchStatusList.name
 * @apiSuccess {string} data.merchantBatchStatusList
 * @apiSuccess {string} data.merchantBatchStatusList.id
 * @apiSuccess {string} data.merchantBatchStatusList.name
 * @apiSuccess {string} data.batchExceptionStatusList
 * @apiSuccess {string} data.batchExceptionStatusList.id
 * @apiSuccess {string} data.batchExceptionStatusList.name
 * @apiSuccess {string} data.batchExceptionStageList
 * @apiSuccess {string} data.batchExceptionStageList.id
 * @apiSuccess {string} data.batchExceptionStageList.name
 * @apiSuccess {string} data.batchExceptionFirstStageTypeList
 * @apiSuccess {string} data.batchExceptionFirstStageTypeList.id
 * @apiSuccess {string} data.batchExceptionFirstStageTypeList.name
 * @apiSuccess {string} data.batchExceptionSecondStageTypeList
 * @apiSuccess {string} data.batchExceptionSecondStageTypeList.id
 * @apiSuccess {string} data.batchExceptionSecondStageTypeList.name
 * @apiSuccess {string} data.tourStatusList
 * @apiSuccess {string} data.tourStatusList.id
 * @apiSuccess {string} data.tourStatusList.name
 * @apiSuccess {string} data.merchantTourStatusList
 * @apiSuccess {string} data.merchantTourStatusList.id
 * @apiSuccess {string} data.merchantTourStatusList.name
 * @apiSuccess {string} data.carTransmissionList
 * @apiSuccess {string} data.carTransmissionList.id
 * @apiSuccess {string} data.carTransmissionList.name
 * @apiSuccess {string} data.carFuelTypeList
 * @apiSuccess {string} data.carFuelTypeList.id
 * @apiSuccess {string} data.carFuelTypeList.name
 * @apiSuccess {string} data.carOwnerShipTypeList
 * @apiSuccess {string} data.carOwnerShipTypeList.id
 * @apiSuccess {string} data.carOwnerShipTypeList.name
 * @apiSuccess {string} data.carRepairList
 * @apiSuccess {string} data.carRepairList.id
 * @apiSuccess {string} data.carRepairList.name
 * @apiSuccess {string} data.driverTypeList
 * @apiSuccess {string} data.driverTypeList.id
 * @apiSuccess {string} data.driverTypeList.name
 * @apiSuccess {string} data.driverStatusList
 * @apiSuccess {string} data.driverStatusList.id
 * @apiSuccess {string} data.driverStatusList.name
 * @apiSuccess {string} data.deviceStatusList
 * @apiSuccess {string} data.deviceStatusList.id
 * @apiSuccess {string} data.deviceStatusList.name
 * @apiSuccess {string} data.adminImageDirList
 * @apiSuccess {string} data.adminImageDirList.id
 * @apiSuccess {string} data.adminImageDirList.name
 * @apiSuccess {string} data.driverImageDirList
 * @apiSuccess {string} data.driverImageDirList.id
 * @apiSuccess {string} data.driverImageDirList.name
 * @apiSuccess {string} data.adminFileDirList
 * @apiSuccess {string} data.adminFileDirList.id
 * @apiSuccess {string} data.adminFileDirList.name
 * @apiSuccess {string} data.adminExcelDirList
 * @apiSuccess {string} data.adminExcelDirList.id
 * @apiSuccess {string} data.adminExcelDirList.name
 * @apiSuccess {string} data.adminTxtDirList
 * @apiSuccess {string} data.adminTxtDirList.id
 * @apiSuccess {string} data.adminTxtDirList.name
 * @apiSuccess {string} data.driverFileDirList
 * @apiSuccess {string} data.driverFileDirList.id
 * @apiSuccess {string} data.driverFileDirList.name
 * @apiSuccess {string} data.merchantTypeList
 * @apiSuccess {string} data.merchantTypeList.id
 * @apiSuccess {string} data.merchantTypeList.name
 * @apiSuccess {string} data.merchantSettlementTypeList
 * @apiSuccess {string} data.merchantSettlementTypeList.id
 * @apiSuccess {string} data.merchantSettlementTypeList.name
 * @apiSuccess {string} data.merchantStatusList
 * @apiSuccess {string} data.merchantStatusList.id
 * @apiSuccess {string} data.merchantStatusList.name
 * @apiSuccess {string} data.driverEventList
 * @apiSuccess {string} data.driverEventList.id
 * @apiSuccess {string} data.driverEventList.name
 * @apiSuccess {string} data.feeLevelList
 * @apiSuccess {string} data.feeLevelList.id
 * @apiSuccess {string} data.feeLevelList.name
 * @apiSuccess {string} data.showTypeList
 * @apiSuccess {string} data.showTypeList.id
 * @apiSuccess {string} data.showTypeList.name
 * @apiSuccess {string} data.rechargeStatusList
 * @apiSuccess {string} data.rechargeStatusList.id
 * @apiSuccess {string} data.rechargeStatusList.name
 * @apiSuccess {string} data.verifyStatusList
 * @apiSuccess {string} data.verifyStatusList.id
 * @apiSuccess {string} data.verifyStatusList.name
 * @apiSuccess {string} data.isSkippedList
 * @apiSuccess {string} data.isSkippedList.id
 * @apiSuccess {string} data.isSkippedList.name
 * @apiSuccess {string} data.canSkipBatchList
 * @apiSuccess {string} data.canSkipBatchList.id
 * @apiSuccess {string} data.canSkipBatchList.name
 * @apiSuccess {string} data.languageList
 * @apiSuccess {string} data.languageList.id
 * @apiSuccess {string} data.languageList.name
 * @apiSuccess {string} data.merchantAdditionalStatusList
 * @apiSuccess {string} data.merchantAdditionalStatusList.id
 * @apiSuccess {string} data.merchantAdditionalStatusList.name
 * @apiSuccess {string} data.tourDelayTypeList
 * @apiSuccess {string} data.tourDelayTypeList.id
 * @apiSuccess {string} data.tourDelayTypeList.name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "noTypeList": [
 * {
 * "id": "order",
 * "name": "订单编号规则"
 * },
 * {
 * "id": "batch",
 * "name": "站点编号规则"
 * },
 * {
 * "id": "batch_exception",
 * "name": "站点异常编号规则"
 * },
 * {
 * "id": "tour",
 * "name": "取件线路编号规则"
 * },
 * {
 * "id": "recharge",
 * "name": "充值单号规则"
 * }
 * ],
 * "lineRuleList": [
 * {
 * "id": 1,
 * "name": "按邮编自动分配"
 * },
 * {
 * "id": 2,
 * "name": "按区域自动分配"
 * }
 * ],
 * "printTemplateList": [
 * {
 * "id": 1,
 * "name": "标准模板"
 * },
 * {
 * "id": 2,
 * "name": "通用模板"
 * }
 * ],
 * "weekList": [
 * {
 * "id": 1,
 * "name": "星期一"
 * },
 * {
 * "id": 2,
 * "name": "星期二"
 * },
 * {
 * "id": 3,
 * "name": "星期三"
 * },
 * {
 * "id": 4,
 * "name": "星期四"
 * },
 * {
 * "id": 5,
 * "name": "星期五"
 * },
 * {
 * "id": 6,
 * "name": "星期六"
 * },
 * {
 * "id": 0,
 * "name": "星期日"
 * }
 * ],
 * "orderTypeList": [
 * {
 * "id": 1,
 * "name": "取件"
 * },
 * {
 * "id": 2,
 * "name": "派件"
 * }
 * ],
 * "orderSourceList": [
 * {
 * "id": 1,
 * "name": "手动添加"
 * },
 * {
 * "id": 2,
 * "name": "批量导入"
 * },
 * {
 * "id": 3,
 * "name": "第三方"
 * }
 * ],
 * "orderSettlementTypeList": [
 * {
 * "id": 1,
 * "name": "寄付"
 * },
 * {
 * "id": 2,
 * "name": "到付"
 * }
 * ],
 * "orderStatusList": [
 * {
 * "id": 1,
 * "name": "待分配"
 * },
 * {
 * "id": 2,
 * "name": "已分配"
 * },
 * {
 * "id": 3,
 * "name": "待出库"
 * },
 * {
 * "id": 4,
 * "name": "取派中"
 * },
 * {
 * "id": 5,
 * "name": "已完成"
 * },
 * {
 * "id": 6,
 * "name": "取消取派"
 * },
 * {
 * "id": 7,
 * "name": "回收站"
 * }
 * ],
 * "orderOutStatusList": [
 * {
 * "id": 1,
 * "name": "是"
 * },
 * {
 * "id": 2,
 * "name": "否"
 * }
 * ],
 * "packageStatusList": [
 * {
 * "id": 1,
 * "name": "待分配"
 * },
 * {
 * "id": 2,
 * "name": "已分配"
 * },
 * {
 * "id": 3,
 * "name": "待出库"
 * },
 * {
 * "id": 4,
 * "name": "取派中"
 * },
 * {
 * "id": 5,
 * "name": "已完成"
 * },
 * {
 * "id": 6,
 * "name": "取消取派"
 * },
 * {
 * "id": 7,
 * "name": "回收站"
 * }
 * ],
 * "merchantPackageStatusList": [
 * {
 * "id": 1,
 * "name": "未取派"
 * },
 * {
 * "id": 2,
 * "name": "取派中"
 * },
 * {
 * "id": 3,
 * "name": "已完成"
 * },
 * {
 * "id": 4,
 * "name": "取消取派"
 * },
 * {
 * "id": 5,
 * "name": "回收站"
 * }
 * ],
 * "orderExceptionLabelList": [
 * {
 * "id": 1,
 * "name": "正常"
 * },
 * {
 * "id": 2,
 * "name": "异常"
 * }
 * ],
 * "orderNatureList": [
 * {
 * "id": 1,
 * "name": "包裹"
 * },
 * {
 * "id": 2,
 * "name": "材料"
 * }
 * ],
 * "batchPayTypeList": [
 * {
 * "id": 1,
 * "name": "现金支付"
 * },
 * {
 * "id": 2,
 * "name": "银行卡支付"
 * },
 * {
 * "id": 3,
 * "name": "第三方支付"
 * },
 * {
 * "id": 4,
 * "name": "无需支付"
 * }
 * ],
 * "batchExceptionLabelList": [
 * {
 * "id": 1,
 * "name": "正常"
 * },
 * {
 * "id": 2,
 * "name": "异常"
 * }
 * ],
 * "batchStatusList": [
 * {
 * "id": 1,
 * "name": "待分配"
 * },
 * {
 * "id": 2,
 * "name": "已分配"
 * },
 * {
 * "id": 3,
 * "name": "待出库"
 * },
 * {
 * "id": 4,
 * "name": "取派中"
 * },
 * {
 * "id": 5,
 * "name": "已签收"
 * },
 * {
 * "id": 6,
 * "name": "取消取派"
 * }
 * ],
 * "merchantBatchStatusList": [
 * {
 * "id": 1,
 * "name": "未取派"
 * },
 * {
 * "id": 2,
 * "name": "取派中"
 * },
 * {
 * "id": 3,
 * "name": "已签收"
 * },
 * {
 * "id": 4,
 * "name": "取派失败"
 * }
 * ],
 * "batchExceptionStatusList": [
 * {
 * "id": 1,
 * "name": "未处理"
 * },
 * {
 * "id": 2,
 * "name": "已处理"
 * }
 * ],
 * "batchExceptionStageList": [
 * {
 * "id": 1,
 * "name": "在途异常"
 * },
 * {
 * "id": 2,
 * "name": "装货异常"
 * }
 * ],
 * "batchExceptionFirstStageTypeList": [
 * {
 * "id": 1,
 * "name": "道路"
 * },
 * {
 * "id": 2,
 * "name": "车辆异常"
 * },
 * {
 * "id": 3,
 * "name": "其他"
 * }
 * ],
 * "batchExceptionSecondStageTypeList": [
 * {
 * "id": 1,
 * "name": "少货"
 * },
 * {
 * "id": 2,
 * "name": "货损"
 * },
 * {
 * "id": 3,
 * "name": "其他"
 * }
 * ],
 * "tourStatusList": [
 * {
 * "id": 1,
 * "name": "待分配"
 * },
 * {
 * "id": 2,
 * "name": "已分配"
 * },
 * {
 * "id": 3,
 * "name": "待出库"
 * },
 * {
 * "id": 4,
 * "name": "取派中"
 * },
 * {
 * "id": 5,
 * "name": "取派完成"
 * }
 * ],
 * "merchantTourStatusList": [
 * {
 * "id": 1,
 * "name": "未取派"
 * },
 * {
 * "id": 2,
 * "name": "取派中"
 * },
 * {
 * "id": 3,
 * "name": "取派完成"
 * }
 * ],
 * "carTransmissionList": [
 * {
 * "id": 1,
 * "name": "自动挡"
 * },
 * {
 * "id": 2,
 * "name": "手动挡"
 * }
 * ],
 * "carFuelTypeList": [
 * {
 * "id": 1,
 * "name": "柴油"
 * },
 * {
 * "id": 2,
 * "name": "汽油"
 * },
 * {
 * "id": 3,
 * "name": "混合动力"
 * },
 * {
 * "id": 4,
 * "name": "电动"
 * }
 * ],
 * "carOwnerShipTypeList": [
 * {
 * "id": 1,
 * "name": "租赁（到期转私）"
 * },
 * {
 * "id": 2,
 * "name": "私有"
 * },
 * {
 * "id": 3,
 * "name": "租赁（到期转待定）"
 * }
 * ],
 * "carRepairList": [
 * {
 * "id": 1,
 * "name": "是"
 * },
 * {
 * "id": 2,
 * "name": "否"
 * }
 * ],
 * "driverTypeList": [
 * {
 * "id": 1,
 * "name": "雇佣"
 * },
 * {
 * "id": 2,
 * "name": "包线"
 * }
 * ],
 * "driverStatusList": [
 * {
 * "id": 1,
 * "name": "正常"
 * },
 * {
 * "id": 2,
 * "name": "锁定"
 * }
 * ],
 * "deviceStatusList": [
 * {
 * "id": 1,
 * "name": "在线"
 * },
 * {
 * "id": 2,
 * "name": "离线"
 * }
 * ],
 * "adminImageDirList": [
 * {
 * "id": "driver",
 * "name": "司机图片目录"
 * },
 * {
 * "id": "tour",
 * "name": "取件线路图片目录"
 * },
 * {
 * "id": "cancel",
 * "name": "取消取派图片目录"
 * },
 * {
 * "id": "merchant",
 * "name": "货主图片目录"
 * }
 * ],
 * "driverImageDirList": [
 * {
 * "id": "tour",
 * "name": "取件线路图片目录"
 * }
 * ],
 * "adminFileDirList": [
 * {
 * "id": "driver",
 * "name": "司机文件目录"
 * },
 * {
 * "id": "car",
 * "name": "车辆文件目录"
 * },
 * {
 * "id": "order",
 * "name": "订单文件目录"
 * },
 * {
 * "id": "package",
 * "name": "安装包目录"
 * },
 * {
 * "id": "template",
 * "name": "表格模板目录"
 * },
 * {
 * "id": "line",
 * "name": "线路目录"
 * }
 * ],
 * "adminExcelDirList": [
 * {
 * "id": "tour",
 * "name": "取件线路表格目录"
 * }
 * ],
 * "adminTxtDirList": [
 * {
 * "id": "tour",
 * "name": "取件线路文件目录"
 * }
 * ],
 * "driverFileDirList": [
 * {
 * "id": "tour",
 * "name": "取件线路文件目录"
 * }
 * ],
 * "merchantTypeList": [
 * {
 * "id": 1,
 * "name": "个人"
 * },
 * {
 * "id": 2,
 * "name": "货主"
 * }
 * ],
 * "merchantSettlementTypeList": [
 * {
 * "id": 1,
 * "name": "票结"
 * },
 * {
 * "id": 2,
 * "name": "日结"
 * },
 * {
 * "id": 3,
 * "name": "月结"
 * }
 * ],
 * "merchantStatusList": [
 * {
 * "id": 1,
 * "name": "启用"
 * },
 * {
 * "id": 2,
 * "name": "禁用"
 * }
 * ],
 * "driverEventList": [
 * {
 * "id": 1,
 * "name": "司机从网点出发"
 * },
 * {
 * "id": 2,
 * "name": "司机到达客户家"
 * },
 * {
 * "id": 3,
 * "name": "司机从客户家离开"
 * },
 * {
 * "id": 4,
 * "name": "司机返回网点"
 * }
 * ],
 * "feeLevelList": [
 * {
 * "id": 1,
 * "name": "系统级"
 * },
 * {
 * "id": 2,
 * "name": "自定义"
 * }
 * ],
 * "showTypeList": [
 * {
 * "id": 1,
 * "name": "全部展示"
 * },
 * {
 * "id": 2,
 * "name": "全部展示"
 * }
 * ],
 * "rechargeStatusList": [
 * {
 * "id": 1,
 * "name": "充值中"
 * },
 * {
 * "id": 2,
 * "name": "充值失败"
 * },
 * {
 * "id": 3,
 * "name": "充值完成"
 * }
 * ],
 * "verifyStatusList": [
 * {
 * "id": 1,
 * "name": "未审核"
 * },
 * {
 * "id": 2,
 * "name": "已审核"
 * }
 * ],
 * "isSkippedList": [
 * {
 * "id": 1,
 * "name": "已跳过"
 * },
 * {
 * "id": 2,
 * "name": "未跳过"
 * }
 * ],
 * "canSkipBatchList": [
 * {
 * "id": 1,
 * "name": "不能跳过"
 * },
 * {
 * "id": 2,
 * "name": "可以跳过"
 * }
 * ],
 * "languageList": [
 * {
 * "id": "cn",
 * "name": "汉语"
 * },
 * {
 * "id": "en",
 * "name": "英语"
 * },
 * {
 * "id": "nl",
 * "name": "荷兰语"
 * }
 * ],
 * "merchantAdditionalStatusList": [
 * {
 * "id": 1,
 * "name": "开启"
 * },
 * {
 * "id": 2,
 * "name": "禁用"
 * }
 * ],
 * "tourDelayTypeList": [
 * {
 * "id": 1,
 * "name": "用餐休息"
 * },
 * {
 * "id": 2,
 * "name": "交通堵塞"
 * },
 * {
 * "id": 3,
 * "name": "更换行车路线"
 * },
 * {
 * "id": 4,
 * "name": "其他"
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/car/brand 车辆品牌新增
 * @apiName 车辆品牌新增
 * @apiGroup 06
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} cn_name 中文名称
 * @apiParam {string} en_name 英文名称
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.cn_name 中文名称
 * @apiSuccess {string} data.en_name 英文名称
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.updated_at 创建时间
 * @apiSuccess {string} data.created_at 修改时间
 * @apiSuccess {string} data.id 品牌ID
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "cn_name": "宝马",
 * "company_id": 1,
 * "updated_at": "2019-12-28 02:18:57",
 * "created_at": "2019-12-28 02:18:57",
 * "id": 7
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/car/brands 车辆品牌查询
 * @apiName 车辆品牌查询
 * @apiGroup 06
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 品牌ID
 * @apiSuccess {string} data.cn_name 中文名称
 * @apiSuccess {string} data.en_name 英文名称
 * @apiSuccess {string} data.created_at 创建时间
 * @apiSuccess {string} data.updated_at 修改时间
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 3,
 * "cn_name": "test",
 * "en_name": "",
 * "created_at": "2019-12-27 10:30:24",
 * "updated_at": "2019-12-27 10:30:24",
 * "company_id": 1
 * },
 * {
 * "id": 4,
 * "cn_name": "test",
 * "en_name": "",
 * "created_at": "2019-12-27 10:33:03",
 * "updated_at": "2019-12-27 10:33:03",
 * "company_id": 1
 * },
 * {
 * "id": 5,
 * "cn_name": "test",
 * "en_name": "",
 * "created_at": "2019-12-27 10:52:34",
 * "updated_at": "2019-12-27 10:52:34",
 * "company_id": 1
 * },
 * {
 * "id": 6,
 * "cn_name": "test",
 * "en_name": "",
 * "created_at": "2019-12-27 11:03:03",
 * "updated_at": "2019-12-27 11:03:03",
 * "company_id": 1
 * },
 * {
 * "id": 7,
 * "cn_name": "宝马",
 * "en_name": "",
 * "created_at": "2019-12-28 02:18:57",
 * "updated_at": "2019-12-28 02:18:57",
 * "company_id": 1
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/car/brands?page=1",
 * "last": "http://tms-api.test/api/admin/car/brands?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/car/brands",
 * "per_page": 15,
 * "to": 5,
 * "total": 5
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/car/model 车辆型号新增
 * @apiName 车辆型号新增
 * @apiGroup 06
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} cn_name 中文名称
 * @apiParam {string} brand_id 品牌ID
 * @apiParam {string} en_name 英文名称
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.cn_name 中文名称
 * @apiSuccess {string} data.en_name 英文名称
 * @apiSuccess {string} data.brand_id 品牌ID
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.updated_at 更新时间
 * @apiSuccess {string} data.created_at 创建时间
 * @apiSuccess {string} data.id 型号ID
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "cn_name": "X7",
 * "brand_id": "1",
 * "company_id": 1,
 * "updated_at": "2019-12-28 02:24:28",
 * "created_at": "2019-12-28 02:24:28",
 * "id": 6
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/car/models 车辆型号查询
 * @apiName 车辆型号查询
 * @apiGroup 06
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} brand_id 品牌ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 型号ID
 * @apiSuccess {string} data.brand_id 品牌ID
 * @apiSuccess {string} data.cn_name 型号名称
 * @apiSuccess {string} data.en_name 英文名称
 * @apiSuccess {string} data.created_at 创建时间
 * @apiSuccess {string} data.updated_at 更新时间
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data":  [
 * {
 * "id": 2,
 * "brand_id": 1,
 * "cn_name": "test",
 * "en_name": "",
 * "created_at": "2019-12-27 10:31:09",
 * "updated_at": "2019-12-27 10:31:09",
 * "company_id": 1
 * },
 * {
 * "id": 3,
 * "brand_id": 1,
 * "cn_name": "test",
 * "en_name": "",
 * "created_at": "2019-12-27 11:03:38",
 * "updated_at": "2019-12-27 11:03:38",
 * "company_id": 1
 * },
 * {
 * "id": 4,
 * "brand_id": 1,
 * "cn_name": "test",
 * "en_name": "",
 * "created_at": "2019-12-27 11:04:09",
 * "updated_at": "2019-12-27 11:04:09",
 * "company_id": 1
 * },
 * {
 * "id": 5,
 * "brand_id": 1,
 * "cn_name": "test",
 * "en_name": "",
 * "created_at": "2019-12-28 02:23:39",
 * "updated_at": "2019-12-28 02:23:39",
 * "company_id": 1
 * },
 * {
 * "id": 6,
 * "brand_id": 1,
 * "cn_name": "X7",
 * "en_name": "",
 * "created_at": "2019-12-28 02:24:28",
 * "updated_at": "2019-12-28 02:24:28",
 * "company_id": 1
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/car/models?page=1",
 * "last": "http://tms-api.test/api/admin/car/models?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/car/models",
 * "per_page": 15,
 * "to": 5,
 * "total": 5
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/car 车辆查询
 * @apiName 车辆查询
 * @apiGroup 06
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} tour_no 取件线路编号（分配司机是调用接口时传）
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 车辆ID
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.car_no 车牌号
 * @apiSuccess {string} data.data.outgoing_time 出厂日期
 * @apiSuccess {string} data.data.car_brand_id 车辆品牌ID
 * @apiSuccess {string} data.data.car_model_id 车辆型号ID
 * @apiSuccess {string} data.data.frame_number 车架号
 * @apiSuccess {string} data.data.engine_number 发动机编号
 * @apiSuccess {string} data.data.transmission 车型（1自动档，2手动挡）
 * @apiSuccess {string} data.data.fuel_type 燃料类型（1 柴油/ 2 汽油/ 3 混合动力/ 4电动）
 * @apiSuccess {string} data.data.current_miles 当前里程数
 * @apiSuccess {string} data.data.annual_inspection_date 下次年检日期
 * @apiSuccess {string} data.data.ownership_type 类型( 1 租赁到期转私/ 2 私有/ 3 租赁到期转待定）
 * @apiSuccess {string} data.data.received_date 接收车辆日期
 * @apiSuccess {string} data.data.month_road_tax 每月路税
 * @apiSuccess {string} data.data.insurance_company 保险公司
 * @apiSuccess {string} data.data.insurance_type 保险类型
 * @apiSuccess {string} data.data.month_insurance 每月保险
 * @apiSuccess {string} data.data.rent_start_date 起租时间
 * @apiSuccess {string} data.data.rent_end_date 到期时间
 * @apiSuccess {string} data.data.rent_month_fee 月租金
 * @apiSuccess {string} data.data.repair 维修自理（1 是/ 2 否）
 * @apiSuccess {string} data.data.remark 备注
 * @apiSuccess {string} data.data.relate_material 文件(相关材料)
 * @apiSuccess {string} data.data.is_locked 是否锁定1-正常2-锁定
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.data.updated_at 修改时间
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 3,
 * "company_id": 1,
 * "car_no": "k7805",
 * "outgoing_time": "1993-09-03",
 * "car_brand_id": 1,
 * "car_model_id": 1,
 * "frame_number": "523142412",
 * "engine_number": "244231",
 * "transmission": 1,
 * "fuel_type": 3,
 * "current_miles": "20000.00",
 * "annual_inspection_date": null,
 * "ownership_type": 1,
 * "received_date": "2019-12-30",
 * "month_road_tax": 2,
 * "insurance_company": "中国人寿",
 * "insurance_type": "交强险",
 * "month_insurance": "2.00",
 * "rent_start_date": "2019-12-12",
 * "rent_end_date": "2020-12-12",
 * "rent_month_fee": "20.00",
 * "repair": 1,
 * "remark": "是个老司机",
 * "relate_material": "http//www.1.png",
 * "is_locked": 1,
 * "created_at": "2019-12-27 10:52:18",
 * "updated_at": "2019-12-27 10:52:18"
 * },
 * {
 * "id": 4,
 * "company_id": 1,
 * "car_no": "k7807",
 * "outgoing_time": "1993-09-03",
 * "car_brand_id": 1,
 * "car_model_id": 1,
 * "frame_number": "523142412",
 * "engine_number": "244231",
 * "transmission": 1,
 * "fuel_type": 3,
 * "current_miles": "20000.00",
 * "annual_inspection_date": null,
 * "ownership_type": 1,
 * "received_date": "2019-12-30",
 * "month_road_tax": 2,
 * "insurance_company": "中国人寿",
 * "insurance_type": "交强险",
 * "month_insurance": "2.00",
 * "rent_start_date": "2019-12-12",
 * "rent_end_date": "2020-12-12",
 * "rent_month_fee": "20.00",
 * "repair": 1,
 * "remark": "是个老司机",
 * "relate_material": "http//www.1.png",
 * "is_locked": 1,
 * "created_at": "2019-12-27 11:19:08",
 * "updated_at": "2019-12-28 02:13:48"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/car?page=1",
 * "last": "http://tms-api.test/api/admin/car?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/car",
 * "per_page": 15,
 * "to": 2,
 * "total": 2
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/car/{id} 车辆详情
 * @apiName 车辆详情
 * @apiGroup 06
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 车辆ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 车辆ID
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.car_no 车牌号
 * @apiSuccess {string} data.outgoing_time 出厂日期
 * @apiSuccess {string} data.car_brand_id 车辆品牌ID
 * @apiSuccess {string} data.car_model_id 车辆型号ID
 * @apiSuccess {string} data.frame_number 车架号
 * @apiSuccess {string} data.engine_number 发动机编号
 * @apiSuccess {string} data.transmission 车型（1自动档，2手动挡）
 * @apiSuccess {string} data.fuel_type 燃料类型（1 柴油/ 2 汽油/ 3 混合动力/ 4电动）
 * @apiSuccess {string} data.current_miles 当前里程数
 * @apiSuccess {string} data.annual_inspection_date 下次年检日期
 * @apiSuccess {string} data.ownership_type 类型( 1 租赁到期转私/ 2 私有/ 3 租赁到期转待定）
 * @apiSuccess {string} data.received_date 接收车辆日期
 * @apiSuccess {string} data.month_road_tax 每月路税
 * @apiSuccess {string} data.insurance_company 保险公司
 * @apiSuccess {string} data.insurance_type 保险类型
 * @apiSuccess {string} data.month_insurance 每月保险
 * @apiSuccess {string} data.rent_start_date 起租时间
 * @apiSuccess {string} data.rent_end_date 到期时间
 * @apiSuccess {string} data.rent_month_fee 月租金
 * @apiSuccess {string} data.repair 维修自理（1 是/ 2 否）
 * @apiSuccess {string} data.remark 备注
 * @apiSuccess {string} data.relate_material_list 文件(相关材料)
 * @apiSuccess {string} data.is_locked 是否锁定1-正常2-锁定
 * @apiSuccess {string} data.created_at 创建时间
 * @apiSuccess {string} data.updated_at 修改时间
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 4,
 * "company_id": 1,
 * "car_no": "k7807",
 * "outgoing_time": "1993-09-03",
 * "car_brand_id": 1,
 * "car_model_id": 1,
 * "frame_number": "523142412",
 * "engine_number": "244231",
 * "transmission": 1,
 * "fuel_type": 3,
 * "current_miles": "20000.00",
 * "annual_inspection_date": null,
 * "ownership_type": 1,
 * "received_date": "2019-12-30",
 * "month_road_tax": 2,
 * "insurance_company": "中国人寿",
 * "insurance_type": "交强险",
 * "month_insurance": "2.00",
 * "rent_start_date": "2019-12-12",
 * "rent_end_date": "2020-12-12",
 * "rent_month_fee": "20.00",
 * "repair": 1,
 * "remark": "是个老司机",
 * "relate_material_list": [
 * {
 * "material_name":"1.png",
 * "material_url":"http//www.1.png"
 * },
 * {
 * "material_name":"1.png",
 * "material_url":"http//www.1.png"
 * }
 * ],
 * "is_locked": 1,
 * "created_at": "2019-12-27 11:19:08",
 * "updated_at": "2019-12-28 02:13:48"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/car 车辆新增
 * @apiName 车辆新增
 * @apiGroup 06
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} code
 * @apiParam {string} data
 * @apiParam {string} data>>car_no 车牌号
 * @apiParam {string} data>>outgoing_time 出厂日期
 * @apiParam {string} data>>car_brand_id 车辆品牌ID
 * @apiParam {string} data>>car_model_id 车辆型号ID
 * @apiParam {string} data>>frame_number 车架号
 * @apiParam {string} data>>engine_number 发动机编号
 * @apiParam {string} data>>transmission 车型（1自动档，2手动挡）
 * @apiParam {string} data>>fuel_type 燃料类型（1 柴油/ 2 汽油/ 3 混合动力/ 4电动）
 * @apiParam {string} data>>current_miles 当前里程数
 * @apiParam {string} data>>ownership_type 类型( 1 租赁到期转私/ 2 私有/ 3 租赁到期转待定）
 * @apiParam {string} data>>received_date 接收车辆日期
 * @apiParam {string} data>>month_road_tax 每月路税
 * @apiParam {string} data>>insurance_company 保险公司
 * @apiParam {string} data>>insurance_type 保险类型
 * @apiParam {string} data>>month_insurance 每月保险
 * @apiParam {string} data>>rent_start_date 起租时间
 * @apiParam {string} data>>rent_end_date 到期时间
 * @apiParam {string} data>>rent_month_fee 月租金
 * @apiParam {string} data>>repair 维修自理（1 是/ 2 否）
 * @apiParam {string} data>>remark 备注
 * @apiParam {string} data>>relate_material_list 文件(相关材料)
 * @apiParam {string} data>>company_id 公司ID
 * @apiParam {string} data>>updated_at 创建时间
 * @apiParam {string} data>>created_at 修改时间
 * @apiParam {string} data>>id 车辆ID
 * @apiParam {string} data>>annual_inspection_date 下次年检日期
 * @apiParam {string} msg
 * @apiParam {string} data>>car_length 车长
 * @apiParam {string} data>>gps_device_number GPS设备号
 * @apiParam {string} data>>car_model_type 车辆类型
 */

/**
 * @api {put} /admin/car/{id} 车辆修改
 * @apiName 车辆修改
 * @apiGroup 06
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} code
 * @apiParam {string} data
 * @apiParam {string} data>>car_no 车牌号
 * @apiParam {string} data>>outgoing_time 出厂日期
 * @apiParam {string} data>>car_brand_id 车辆品牌ID
 * @apiParam {string} data>>car_model_id 车辆型号ID
 * @apiParam {string} data>>frame_number 车架号
 * @apiParam {string} data>>engine_number 发动机编号
 * @apiParam {string} data>>transmission 车型（1自动档，2手动挡）
 * @apiParam {string} data>>fuel_type 燃料类型（1 柴油/ 2 汽油/ 3 混合动力/ 4电动）
 * @apiParam {string} data>>current_miles 当前里程数
 * @apiParam {string} data>>ownership_type 类型( 1 租赁到期转私/ 2 私有/ 3 租赁到期转待定）
 * @apiParam {string} data>>received_date 接收车辆日期
 * @apiParam {string} data>>month_road_tax 每月路税
 * @apiParam {string} data>>insurance_company 保险公司
 * @apiParam {string} data>>insurance_type 保险类型
 * @apiParam {string} data>>month_insurance 每月保险
 * @apiParam {string} data>>rent_start_date 起租时间
 * @apiParam {string} data>>rent_end_date 到期时间
 * @apiParam {string} data>>rent_month_fee 月租金
 * @apiParam {string} data>>repair 维修自理（1 是/ 2 否）
 * @apiParam {string} data>>remark 备注
 * @apiParam {string} data>>relate_material_list 文件(相关材料)
 * @apiParam {string} data>>company_id 公司ID
 * @apiParam {string} data>>updated_at 创建时间
 * @apiParam {string} data>>created_at 修改时间
 * @apiParam {string} data>>id 车辆ID
 * @apiParam {string} data>>annual_inspection_date 下次年检日期
 * @apiParam {string} msg
 * @apiParam {string} data>>car_length 车长
 * @apiParam {string} data>>gps_device_number GPS设备编号
 * @apiParam {string} data>>car_model_type 车辆类型
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

/**
 * @api {put} /admin/car/{id}/lock 车辆锁定解锁
 * @apiName 车辆锁定解锁
 * @apiGroup 06
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 车辆ID
 * @apiParam {string} is_locked 是否锁定（1-非锁定，2-锁定）
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
 * @api {get} /admin/car/init 初始化
 * @apiName 初始化
 * @apiGroup 06
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.car_owner_ship_type_list
 * @apiSuccess {string} data.car_owner_ship_type_list.id
 * @apiSuccess {string} data.car_owner_ship_type_list.name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "car_owner_ship_type_list": [
 * {
 * "id": 1,
 * "name": "Lease（Conversion to maturity）"
 * },
 * {
 * "id": 2,
 * "name": "private"
 * },
 * {
 * "id": 3,
 * "name": "Lease（Maturity to be confirmed）"
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/car/all-track 车辆追踪
 * @apiName 车辆追踪
 * @apiGroup 06
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.tour_no 取件线路编号
 * @apiSuccess {string} data.line_name 线路名称
 * @apiSuccess {string} data.driver_id 司机ID
 * @apiSuccess {string} data.driver_name 司机姓名
 * @apiSuccess {string} data.driver_phone 司机电话
 * @apiSuccess {string} data.car_no 车牌号
 * @apiSuccess {string} data.lon 经度
 * @apiSuccess {string} data.lat 纬度
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.time 最后更新时间
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 540,
 * "tour_no": "TOUR00020009J",
 * "line_name": "Rotterdam (7)",
 * "driver_id": 2,
 * "driver_name": "司机老",
 * "driver_phone": "123",
 * "car_no": "333",
 * "lon": "3",
 * "lat": "6.6"
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {delete} /admin/car/{id} 车辆删除
 * @apiName 车辆删除
 * @apiGroup 06
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 车辆ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {},
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/car/{id}/distance 里程导出
 * @apiName 里程导出
 * @apiGroup 06
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 车辆ID
 * @apiParam {string} begin_date 起始日期
 * @apiParam {string} end_date 终止时间
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.name
 * @apiSuccess {string} data.path
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "name": "202009171722403.xlsx",
 * "path": "tms-api.test/storage/admin/excel/3/carDistance/202009171722403.xlsx"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/car/{id}/info 信息导出
 * @apiName 信息导出
 * @apiGroup 06
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.name
 * @apiSuccess {string} data.path
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "name": "202009171722403.pdf",
 * "path": "tms-api.test/storage/admin/excel/3/carDistance/202009171722403.pdf"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/batch 站点查询
 * @apiName 站点查询
 * @apiGroup 07
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} status 状态：1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派
 * @apiParam {string} begin_date 开始日期
 * @apiParam {string} end_date 结束日期
 * @apiParam {string} driver_name 司机姓名
 * @apiParam {string} line_id 线路ID
 * @apiParam {string} line_name 线路名称
 * @apiParam {string} receiver 收件人
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 站点ID
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.batch_no 站点编号
 * @apiSuccess {string} data.data.tour_no 取件线路编号
 * @apiSuccess {string} data.data.line_id 线路ID
 * @apiSuccess {string} data.data.line_name 线路名称
 * @apiSuccess {string} data.data.execution_date 取派日期
 * @apiSuccess {string} data.data.status 状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派
 * @apiSuccess {string} data.data.driver_id 司机ID
 * @apiSuccess {string} data.data.driver_name 司机姓名
 * @apiSuccess {string} data.data.driver_phone 司机电话
 * @apiSuccess {string} data.data.driver_rest_time 司机休息时长-秒
 * @apiSuccess {string} data.data.car_id 车辆ID
 * @apiSuccess {string} data.data.car_no 车牌号
 * @apiSuccess {string} data.data.sort_id 排序ID
 * @apiSuccess {string} data.data.expect_pickup_quantity 预计取件数量
 * @apiSuccess {string} data.data.actual_pickup_quantity 实际取件数量
 * @apiSuccess {string} data.data.expect_pie_quantity 预计派件数量
 * @apiSuccess {string} data.data.actual_pie_quantity 实际派件数量
 * @apiSuccess {string} data.data.receiver 收件人姓名
 * @apiSuccess {string} data.data.receiver_phone 收件人电话
 * @apiSuccess {string} data.data.receiver_country 收件人国家
 * @apiSuccess {string} data.data.receiver_post_code 收件人邮编
 * @apiSuccess {string} data.data.receiver_house_number 收件人门牌号
 * @apiSuccess {string} data.data.receiver_city 收件人城市
 * @apiSuccess {string} data.data.receiver_street 收件人街道
 * @apiSuccess {string} data.data.receiver_address 收件人详细地址
 * @apiSuccess {string} data.data.receiver_lon 收件人经度
 * @apiSuccess {string} data.data.receiver_lat 收件人纬度
 * @apiSuccess {string} data.data.expect_arrive_time 预计到达时间
 * @apiSuccess {string} data.data.actual_arrive_time 实际到达时间
 * @apiSuccess {string} data.data.expect_distance 预计里程
 * @apiSuccess {string} data.data.actual_time 实际里程
 * @apiSuccess {string} data.data.order_amount 贴单费用
 * @apiSuccess {string} data.data.replace_amount 代收货款
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.data.orders
 * @apiSuccess {string} data.data.orders.id 订单ID
 * @apiSuccess {string} data.data.orders.company_id company_id
 * @apiSuccess {string} data.data.orders.order_no order_no
 * @apiSuccess {string} data.data.orders.status_name 状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站
 * @apiSuccess {string} data.data.orders.execution_date 取件/派件 日期
 * @apiSuccess {string} data.data.orders.batch_no 站点编号
 * @apiSuccess {string} data.data.orders.tour_no 取件线路编号
 * @apiSuccess {string} data.data.orders.out_order_no 外部订单号
 * @apiSuccess {string} data.data.orders.source 来源
 * @apiSuccess {string} data.data.orders.exception_label 异常标签1-正常2-异常
 * @apiSuccess {string} data.data.orders.exception_label_name 异常标签名称
 * @apiSuccess {string} data.data.orders.receiver_post_code 收件人邮编
 * @apiSuccess {string} data.data.orders.exception_stage_name 异常阶段1-在途异常2-装货异常
 * @apiSuccess {string} data.data.orders.receiver_house_number 收件人门牌号
 * @apiSuccess {string} data.data.orders.driver_name 司机姓名
 * @apiSuccess {string} data.data.orders.created_at
 * @apiSuccess {string} data.data.orders.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 4,
 * "company_id": 1,
 * "batch_no": "BATCH00010000000000035",
 * "tour_no": "TOUR0001000033",
 * "line_id": 28,
 * "line_name": "万能星期天线",
 * "execution_date": "2020-01-05",
 * "status": 1,
 * "exception_type": 1,
 * "exception_remark": "",
 * "exception_picture": "",
 * "driver_id": 9,
 * "driver_name": "",
 * "driver_phone": "",
 * "driver_rest_time": 0,
 * "car_id": null,
 * "car_no": "",
 * "sort_id": 0,
 * "expect_pickup_quantity": 0,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 1,
 * "actual_pie_quantity": 0,
 * "receiver": "龙放耀",
 * "receiver_phone": "18825558852",
 * "receiver_country": "NL",
 * "receiver_post_code": "1183PG",
 * "receiver_house_number": "17",
 * "receiver_city": "Amstelveen",
 * "receiver_street": "Fideliolaan",
 * "receiver_address": "SAN SA454",
 * "receiver_lon": "5.4740944",
 * "receiver_lat": "51.4384193",
 * "expect_arrive_time": null,
 * "actual_arrive_time": null,
 * "expect_distance": null,
 * "actual_time": null,
 * "order_amount": "0.00",
 * "replace_amount": "0.00",
 * "created_at": "2019-12-28 06:16:41",
 * "updated_at": "2019-12-28 06:16:41",
 * "orders": [
 * {
 * "id": 8,
 * "company_id": 1,
 * "order_no": "TMS00010000000000067",
 * "status_name": "未取派",
 * "exception_type_name": "正常",
 * "execution_date": "2020-01-05",
 * "batch_no": "BATCH00010000000000035",
 * "tour_no": "TOUR0001000033",
 * "out_order_no": "0004",
 * "source": "ERP",
 * "receiver_post_code": "1183PG",
 * "receiver_house_number": "17",
 * "driver_name": null,
 * "created_at": "2019-12-28 06:16:40",
 * "updated_at": "2019-12-28 06:16:41"
 * }
 * ]
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/batch?page=1",
 * "last": "http://tms-api.test/api/admin/batch?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/batch",
 * "per_page": 15,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/batch/{id} 站点详情
 * @apiName 站点详情
 * @apiGroup 07
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 站点ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 站点ID
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.batch_no 站点编号
 * @apiSuccess {string} data.tour_no 取件线路编号
 * @apiSuccess {string} data.line_id 线路ID
 * @apiSuccess {string} data.line_name 线路名称
 * @apiSuccess {string} data.execution_date 取派日期
 * @apiSuccess {string} data.status 状态：1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派
 * @apiSuccess {string} data.exception_type 异常标签1-正常2-异常
 * @apiSuccess {string} data.exception_remark 异常备注
 * @apiSuccess {string} data.exception_picture 异常图片
 * @apiSuccess {string} data.driver_id 司机ID
 * @apiSuccess {string} data.driver_name 司机姓名
 * @apiSuccess {string} data.driver_phone 司机电话
 * @apiSuccess {string} data.driver_rest_time 司机休息时长-秒
 * @apiSuccess {string} data.car_id 车辆ID
 * @apiSuccess {string} data.car_no 车牌号
 * @apiSuccess {string} data.sort_id 排序ID
 * @apiSuccess {string} data.expect_pickup_quantity 预计取件数量
 * @apiSuccess {string} data.actual_pickup_quantity 实际取件数量
 * @apiSuccess {string} data.expect_pie_quantity 预计派件数量
 * @apiSuccess {string} data.actual_pie_quantity 实际派件数量
 * @apiSuccess {string} data.receiver 收件人姓名
 * @apiSuccess {string} data.receiver_phone 收件人电话
 * @apiSuccess {string} data.receiver_country 收件人国家
 * @apiSuccess {string} data.receiver_post_code 收件人邮编
 * @apiSuccess {string} data.receiver_house_number 收件人门牌号
 * @apiSuccess {string} data.receiver_city 收件人城市
 * @apiSuccess {string} data.receiver_street 收件人街道
 * @apiSuccess {string} data.receiver_address 收件人详细地址
 * @apiSuccess {string} data.receiver_lon 收件人经度
 * @apiSuccess {string} data.receiver_lat 收件人纬度
 * @apiSuccess {string} data.expect_arrive_time 预计到达时间
 * @apiSuccess {string} data.actual_arrive_time 实际到达时间
 * @apiSuccess {string} data.expect_distance 预计里程
 * @apiSuccess {string} data.expect_distance 预计耗时-秒
 * @apiSuccess {string} data.actual_time 实际耗时-秒
 * @apiSuccess {string} data.order_amount 贴单费用
 * @apiSuccess {string} data.replace_amount 代收货款
 * @apiSuccess {string} data.created_at 创建时间
 * @apiSuccess {string} data.updated_at 修改时间
 * @apiSuccess {string} data.order_count 订单数量
 * @apiSuccess {string} data.orders 订单列表
 * @apiSuccess {string} data.orders.id 订单ID
 * @apiSuccess {string} data.orders.company_id 公司ID
 * @apiSuccess {string} data.orders.order_no 订单号
 * @apiSuccess {string} data.orders.type 取派类型1取2派
 * @apiSuccess {string} data.orders.type_name 取派类型名称
 * @apiSuccess {string} data.orders.status_name 状态名称:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站
 * @apiSuccess {string} data.orders.exception_type_name 异常类型名称
 * @apiSuccess {string} data.orders.execution_date 取件/派件 日期
 * @apiSuccess {string} data.orders.batch_no 站点编号
 * @apiSuccess {string} data.orders.tour_no 取件线路编号
 * @apiSuccess {string} data.orders.out_order_no 外部订单号
 * @apiSuccess {string} data.orders.source 来源
 * @apiSuccess {string} data.orders.receiver_post_code 收件人邮编
 * @apiSuccess {string} data.orders.receiver_house_number 收件人门牌号
 * @apiSuccess {string} data.orders.driver_name 司机姓名
 * @apiSuccess {string} data.orders.created_at 创建时间
 * @apiSuccess {string} data.orders.updated_at 修改时间
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.orders.package_list 包裹列表
 * @apiSuccess {string} data.orders.package_list.id 包裹ID
 * @apiSuccess {string} data.orders.package_list.company_id
 * @apiSuccess {string} data.orders.package_list.tour_no
 * @apiSuccess {string} data.orders.package_list.batch_no
 * @apiSuccess {string} data.orders.package_list.order_no 订单编号
 * @apiSuccess {string} data.orders.package_list.type 包裹类型
 * @apiSuccess {string} data.orders.package_list.name 包裹名称
 * @apiSuccess {string} data.orders.package_list.express_first_no 快递单号1
 * @apiSuccess {string} data.orders.package_list.express_second_no 快递单号2
 * @apiSuccess {string} data.orders.package_list.feature_logo 特性标志
 * @apiSuccess {string} data.orders.package_list.out_order_no 外部标识
 * @apiSuccess {string} data.orders.package_list.weight 重量
 * @apiSuccess {string} data.orders.package_list.expect_quantity 预计数量
 * @apiSuccess {string} data.orders.package_list.actual_quantity 实际数量
 * @apiSuccess {string} data.orders.package_list.status
 * @apiSuccess {string} data.orders.package_list.sticker_no 贴单号
 * @apiSuccess {string} data.orders.package_list.sticker_amount 贴单费
 * @apiSuccess {string} data.orders.package_list.remark 备注
 * @apiSuccess {string} data.orders.package_list.created_at
 * @apiSuccess {string} data.orders.package_list.updated_at
 * @apiSuccess {string} data.orders.package_list.status_name
 * @apiSuccess {string} data.orders.package_list.type_name
 * @apiSuccess {string} data.orders.package_list.merchant_status 状态
 * @apiSuccess {string} data.orders.package_list.merchant_status_name 状态名
 * @apiSuccess {string} data.orders.material_list 材料列表
 * @apiSuccess {string} data.orders.material_list.id 材料ID
 * @apiSuccess {string} data.orders.material_list.company_id
 * @apiSuccess {string} data.orders.material_list.tour_no
 * @apiSuccess {string} data.orders.material_list.batch_no
 * @apiSuccess {string} data.orders.material_list.order_no
 * @apiSuccess {string} data.orders.material_list.name 材料名称
 * @apiSuccess {string} data.orders.material_list.code 材料代号
 * @apiSuccess {string} data.orders.material_list.out_order_no 外部标识
 * @apiSuccess {string} data.orders.material_list.expect_quantity 预计数量
 * @apiSuccess {string} data.orders.material_list.actual_quantity 实际数量
 * @apiSuccess {string} data.orders.material_list.remark 备注
 * @apiSuccess {string} data.orders.material_list.created_at
 * @apiSuccess {string} data.orders.material_list.updated_at
 * @apiSuccess {string} data.orders.status_name
 * @apiSuccess {string} data.orders.exception_label_name
 * @apiSuccess {string} data.orders.type_name
 * @apiSuccess {string} data.orders.merchant_id_name
 * @apiSuccess {string} data.orders.receiver_country_name
 * @apiSuccess {string} data.orders.sender_country_name
 * @apiSuccess {string} data.orders.country_name
 * @apiSuccess {string} data.orders.settlement_type_name
 * @apiSuccess {string} data.orders.source_name
 * @apiSuccess {string} data.orders.merchant
 * @apiSuccess {string} data.orders.merchant.id
 * @apiSuccess {string} data.orders.merchant.company_id
 * @apiSuccess {string} data.orders.merchant.type
 * @apiSuccess {string} data.orders.merchant.name
 * @apiSuccess {string} data.orders.merchant.email
 * @apiSuccess {string} data.orders.merchant.country
 * @apiSuccess {string} data.orders.merchant.settlement_type
 * @apiSuccess {string} data.orders.merchant.merchant_group_id
 * @apiSuccess {string} data.orders.merchant.contacter
 * @apiSuccess {string} data.orders.merchant.phone
 * @apiSuccess {string} data.orders.merchant.address
 * @apiSuccess {string} data.orders.merchant.avatar
 * @apiSuccess {string} data.orders.merchant.status
 * @apiSuccess {string} data.orders.merchant.advance_days
 * @apiSuccess {string} data.orders.merchant.appointment_days
 * @apiSuccess {string} data.orders.merchant.delay_time
 * @apiSuccess {string} data.orders.merchant.created_at
 * @apiSuccess {string} data.orders.merchant.updated_at
 * @apiSuccess {string} data.orders.merchant.settlement_type_name
 * @apiSuccess {string} data.orders.merchant.status_name
 * @apiSuccess {string} data.orders.merchant.type_name
 * @apiSuccess {string} data.orders.merchant.country_name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 206,
 * "company_id": 3,
 * "batch_no": "ZD0985",
 * "tour_no": "4YK01",
 * "line_id": 35,
 * "line_name": "当日派",
 * "execution_date": "2020-07-30",
 * "status": 6,
 * "exception_label": 1,
 * "cancel_type": 1,
 * "cancel_remark": "一会回家看看你",
 * "cancel_picture": "https://dev-tms.nle-tech.com/storage/driver/images/3/2020-07-27/17/tour/202007271539595f1e84cf825ac.jpg",
 * "driver_id": 17,
 * "driver_name": "小玉米",
 * "driver_phone": "15763526251",
 * "driver_rest_time": 0,
 * "car_id": 11,
 * "car_no": "as",
 * "sort_id": 1000,
 * "expect_pickup_quantity": 1,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 0,
 * "actual_pie_quantity": 0,
 * "receiver_fullname": "AA",
 * "receiver_phone": "0031612354789",
 * "receiver_country": "NL",
 * "receiver_post_code": "1082HT",
 * "receiver_house_number": "561",
 * "receiver_city": "Amsterdam",
 * "receiver_street": "Van Nijenrodeweg",
 * "receiver_address": "NL Amsterdam Van Nijenrodeweg 561 1082HT",
 * "receiver_lon": "4.87408763",
 * "receiver_lat": "52.32751747",
 * "expect_arrive_time": "2020-07-27 11:51:55",
 * "actual_arrive_time": "2020-07-27 15:39:46",
 * "expect_distance": 26986,
 * "actual_distance": 26986,
 * "expect_time": 1326,
 * "actual_time": 15001,
 * "sticker_amount": "0.00",
 * "replace_amount": "0.00",
 * "actual_replace_amount": "0.00",
 * "settlement_amount": "12.00",
 * "actual_settlement_amount": "0.00",
 * "signature": "",
 * "pay_type": 1,
 * "pay_picture": "",
 * "created_at": "2020-07-27 11:29:05",
 * "updated_at": "2020-07-27 15:40:01",
 * "order_count": 1,
 * "status_name": "取消取派",
 * "exception_label_name": "正常",
 * "pay_type_name": "现金支付",
 * "receiver_country_name": "荷兰",
 * "expect_time_human": "22分钟6秒",
 * "actual_time_human": "4小时10分钟1秒",
 * "orders": [
 * {
 * "id": 159,
 * "company_id": 3,
 * "merchant_id": 3,
 * "order_no": "SMAAABLG0001",
 * "execution_date": "2020-07-30",
 * "batch_no": "ZD0985",
 * "tour_no": "4YK01",
 * "out_order_no": "",
 * "express_first_no": "",
 * "express_second_no": "",
 * "mask_code": "",
 * "source": "1",
 * "list_mode": 1,
 * "type": 1,
 * "out_user_id": "",
 * "nature": 1,
 * "settlement_type": 2,
 * "settlement_amount": "12.00",
 * "replace_amount": "0.00",
 * "delivery": 1,
 * "status": 6,
 * "exception_label": 1,
 * "cancel_type": 1,
 * "cancel_remark": "一会回家看看你",
 * "cancel_picture": "https://dev-tms.nle-tech.com/storage/driver/images/3/2020-07-27/17/tour/202007271539595f1e84cf825ac.jpg",
 * "sender_fullname": "827193289@qq.com",
 * "sender_phone": "23145654",
 * "sender_country": "NL",
 * "sender_post_code": "2153PJ",
 * "sender_house_number": "20",
 * "sender_city": "Nieuw-Vennep",
 * "sender_street": "Pesetaweg",
 * "sender_address": "NL Nieuw-Vennep Pesetaweg 20 2153PJ",
 * "receiver_fullname": "AA",
 * "receiver_phone": "0031612354789",
 * "receiver_country": "NL",
 * "receiver_post_code": "1082HT",
 * "receiver_house_number": "561",
 * "receiver_city": "Amsterdam",
 * "receiver_street": "Van Nijenrodeweg",
 * "receiver_address": "NL Amsterdam Van Nijenrodeweg 561 1082HT",
 * "lon": "4.87408763",
 * "lat": "52.32751747",
 * "special_remark": "",
 * "remark": "",
 * "unique_code": "",
 * "driver_id": 17,
 * "driver_name": "小玉米",
 * "driver_phone": "15763526251",
 * "car_id": 11,
 * "car_no": "as",
 * "sticker_no": "",
 * "sticker_amount": "0.00",
 * "out_status": 1,
 * "created_at": "2020-07-27 11:29:05",
 * "updated_at": "2020-07-27 15:40:01",
 * "package_list": [
 * {
 * "id": 232,
 * "company_id": 3,
 * "tour_no": "4YK01",
 * "batch_no": "ZD0985",
 * "order_no": "SMAAABLG0001",
 * "type": 1,
 * "name": "EE2",
 * "express_first_no": "EE2",
 * "express_second_no": "",
 * "feature_logo": "",
 * "out_order_no": "",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "status": 6,
 * "sticker_no": "",
 * "sticker_amount": "0.00",
 * "remark": "",
 * "created_at": "2020-07-27 11:29:05",
 * "updated_at": "2020-07-27 15:40:02",
 * "status_name": "取消取派",
 * "type_name": "取件",
 * "merchant_status": 4,
 * "merchant_status_name": "取消取派"
 * }
 * ],
 * "material_list": [
 * {
 * "id": 99,
 * "company_id": 3,
 * "tour_no": "4YK01",
 * "batch_no": "ZD0985",
 * "order_no": "SMAAABLG0001",
 * "name": "SS",
 * "code": "SS",
 * "out_order_no": "",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "remark": "",
 * "created_at": "2020-07-27 11:29:05",
 * "updated_at": "2020-07-27 11:29:05"
 * }
 * ],
 * "status_name": "取消取派",
 * "exception_label_name": "正常",
 * "type_name": "取件",
 * "merchant_id_name": "tianyaox",
 * "receiver_country_name": "荷兰",
 * "sender_country_name": "荷兰",
 * "country_name": null,
 * "settlement_type_name": "到付",
 * "source_name": "手动添加",
 * "merchant": {
 * "id": 3,
 * "company_id": 3,
 * "type": 2,
 * "name": "tianyaox",
 * "email": "827193289@qq.com",
 * "country": "NL",
 * "settlement_type": 1,
 * "merchant_group_id": 3,
 * "contacter": "827193289",
 * "phone": "1234567890",
 * "address": "隋东风",
 * "avatar": "",
 * "status": 1,
 * "advance_days": null,
 * "appointment_days": null,
 * "delay_time": null,
 * "created_at": "2020-03-13 12:00:10",
 * "updated_at": "2020-06-27 14:28:17",
 * "settlement_type_name": "票结",
 * "status_name": "启用",
 * "type_name": "货主",
 * "country_name": "荷兰"
 * }
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/batch/{id}/cancel 取消取派
 * @apiName 取消取派
 * @apiGroup 07
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 站点ID
 * @apiParam {string} cancel_type 取消取派-类型1-派送失败(客户不在家)2-另约时间3-其他
 * @apiParam {string} cancel_remark 取消备注
 * @apiParam {string} cancel_picture 取消图片
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

/**
 * @api {get} /admin/batch/{id}/get-tour 获得可分配取件线路列表
 * @apiName 获得可分配取件线路列表
 * @apiGroup 07
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 站点ID
 * @apiParam {string} execution_date 重新制定的取派日期
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.tour_no 取件线路编号
 * @apiSuccess {string} data.line_id 线路ID
 * @apiSuccess {string} data.line_name 线路名称
 * @apiSuccess {string} data.execution_date 取派日期
 * @apiSuccess {string} data.driver_id 司机ID
 * @apiSuccess {string} data.driver_name 司机姓名
 * @apiSuccess {string} data.driver_phone 司机电话
 * @apiSuccess {string} data.driver_rest_time 司机休息时长
 * @apiSuccess {string} data.driver_avt_id 取派件AVT设备ID
 * @apiSuccess {string} data.car_id 车辆ID
 * @apiSuccess {string} data.car_no 车牌
 * @apiSuccess {string} data.warehouse_id 网点ID
 * @apiSuccess {string} data.warehouse_name 网点名称
 * @apiSuccess {string} data.warehouse_phone 网点电话
 * @apiSuccess {string} data.warehouse_post_code 网点邮编
 * @apiSuccess {string} data.warehouse_city 网点城市
 * @apiSuccess {string} data.warehouse_street 网点街道
 * @apiSuccess {string} data.warehouse_house_number 网点门牌号
 * @apiSuccess {string} data.warehouse_address 网点详细地址
 * @apiSuccess {string} data.warehouse_lon 网点经度
 * @apiSuccess {string} data.warehouse_lat 网点纬度
 * @apiSuccess {string} data.status 状态
 * @apiSuccess {string} data.begin_time 出库时间
 * @apiSuccess {string} data.begin_signature 出库签名
 * @apiSuccess {string} data.begin_signature_remark 出库备注
 * @apiSuccess {string} data.begin_signature_first_pic 出库图片1
 * @apiSuccess {string} data.begin_signature_second_pic 出库图片2
 * @apiSuccess {string} data.begin_signature_third_pic 出库图片3
 * @apiSuccess {string} data.end_time 入库时间
 * @apiSuccess {string} data.end_signature 入库签名
 * @apiSuccess {string} data.end_signature_remark end_signature_remark
 * @apiSuccess {string} data.expect_distance 预计里程
 * @apiSuccess {string} data.actual_distance 实际里程
 * @apiSuccess {string} data.expect_time 预计耗时-秒
 * @apiSuccess {string} data.actual_time 实际耗时-秒
 * @apiSuccess {string} data.expect_pickup_quantity 预计取件数量(预计包裹入库数量)
 * @apiSuccess {string} data.actual_pickup_quantity 实际取件数量(实际包裹入库数量)
 * @apiSuccess {string} data.expect_pie_quantity 预计派件数量(预计包裹出库数量)
 * @apiSuccess {string} data.actual_pie_quantity 实际派件数量(实际包裹出库数量)
 * @apiSuccess {string} data.sticker_amount 贴单费用
 * @apiSuccess {string} data.replace_amount 代收货款
 * @apiSuccess {string} data.settlement_amount 结算金额-运费
 * @apiSuccess {string} data.remark 备注
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.lave_distance
 * @apiSuccess {string} data.status_name
 * @apiSuccess {string} data.expect_time_human
 * @apiSuccess {string} data.actual_time_human
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 130,
 * "company_id": 1,
 * "tour_no": "TOUR0001000096G",
 * "line_id": 70,
 * "line_name": "万能星期天线",
 * "execution_date": "2020-04-05",
 * "driver_id": 9,
 * "driver_name": "洋铭胡",
 * "driver_phone": "17570715318",
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": 8,
 * "car_no": "88888",
 * "warehouse_id": 17,
 * "warehouse_name": "原始网点",
 * "warehouse_phone": "17570715315",
 * "warehouse_post_code": "2241WD",
 * "warehouse_city": "Wassenaar",
 * "warehouse_street": "Burgemeester Geertsemalaan",
 * "warehouse_house_number": "18",
 * "warehouse_address": "一楼",
 * "warehouse_lon": "4.4195133",
 * "warehouse_lat": "52.144296",
 * "status": 2,
 * "begin_time": null,
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_time": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": 0,
 * "actual_distance": null,
 * "expect_time": null,
 * "actual_time": null,
 * "expect_pickup_quantity": 1,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 1,
 * "actual_pie_quantity": 0,
 * "sticker_amount": "0.00",
 * "replace_amount": "0.00",
 * "settlement_amount": "0.00",
 * "remark": null,
 * "created_at": "2020-02-11 15:24:49",
 * "updated_at": "2020-02-13 14:41:55",
 * "lave_distance": 0,
 * "status_name": "已分配",
 * "expect_time_human": null,
 * "actual_time_human": null
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {delete} /admin/batch/{id}/remove 站点从取件线路移除
 * @apiName 站点从取件线路移除
 * @apiGroup 07
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 站点ID
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

/**
 * @api {put} /admin/batch/{id}/assign-tour 站点分配至取件线路
 * @apiName 站点分配至取件线路
 * @apiGroup 07
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 站点ID
 * @apiParam {string} execution_date 重新制定的取派日期
 * @apiParam {string} line_id 线路ID
 * @apiParam {string} tour_no 取件线路编号
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

/**
 * @api {get} /admin/batch/{id}/get-date 获取可选日期-id
 * @apiName 获取可选日期-id
 * @apiGroup 07
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 站点ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data 日期列表
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * "2020-04-23",
 * "2020-04-26",
 * "2020-04-30",
 * "2020-05-03",
 * "2020-05-07",
 * "2020-05-10",
 * "2020-05-14",
 * "2020-05-17",
 * "2020-05-21"
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/batch/{id}/get-date 获取可选日期-线路
 * @apiName 获取可选日期-线路
 * @apiGroup 07
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 站点ID
 * @apiParam {string} line_id 线路ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data 日期列表
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * "2020-04-23",
 * "2020-04-26",
 * "2020-04-30",
 * "2020-05-03",
 * "2020-05-07",
 * "2020-05-10",
 * "2020-05-14",
 * "2020-05-17",
 * "2020-05-21"
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/batch/get-line 获取所有线路
 * @apiName 获取所有线路
 * @apiGroup 07
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 线路ID
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.name 线路名称
 * @apiSuccess {string} data.data.country
 * @apiSuccess {string} data.data.country_name
 * @apiSuccess {string} data.data.pickup_max_count
 * @apiSuccess {string} data.data.pie_max_count
 * @apiSuccess {string} data.data.is_increment
 * @apiSuccess {string} data.data.order_deadline
 * @apiSuccess {string} data.data.creator_name
 * @apiSuccess {string} data.data.remark
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.data.line_range
 * @apiSuccess {string} data.data.work_day_list
 * @apiSuccess {string} data.data.coordinate_list
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "company_id": 2,
 * "name": "万能星期线",
 * "country": "NL",
 * "country_name": "荷兰",
 * "pickup_max_count": 10,
 * "pie_max_count": 10,
 * "is_increment": 1,
 * "order_deadline": "23:59:59",
 * "creator_name": "胡",
 * "remark": "",
 * "created_at": "2020-04-08 13:24:33",
 * "updated_at": "2020-04-18 15:55:57",
 * "line_range": "1000-9999",
 * "work_day_list": "星期一,星期二,星期三,星期四,星期五",
 * "coordinate_list": null
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/batch/get-line?page=1",
 * "last": "http://tms-api.test/api/admin/batch/get-line?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/batch/get-line",
 * "per_page": 10,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {delete} /admin/batch/remove 批量站点从取件线路移除
 * @apiName 批量站点从取件线路移除
 * @apiGroup 07
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list 批量站点ID
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

/**
 * @api {put} /admin/batch/assign-tour 批量站点分配至取件线路
 * @apiName 批量站点分配至取件线路
 * @apiGroup 07
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list 批量站点ID
 * @apiParam {string} execution_date 重新制定的取派日期
 * @apiParam {string} line_id 线路ID
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

/**
 * @api {get} /admin/tour/report/{id} 任务报告详情
 * @apiName 任务报告详情
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 取件线路ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id ID
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.tour_no 取件线路编号
 * @apiSuccess {string} data.line_id 线路号
 * @apiSuccess {string} data.line_name 线路名称
 * @apiSuccess {string} data.execution_date 取派日期
 * @apiSuccess {string} data.driver_id 司机ID
 * @apiSuccess {string} data.driver_name 司机名称
 * @apiSuccess {string} data.driver_phone 司机电话
 * @apiSuccess {string} data.driver_rest_time 司机剩余休息时间
 * @apiSuccess {string} data.driver_avt_id 设备ID
 * @apiSuccess {string} data.car_id 车辆ID
 * @apiSuccess {string} data.car_no 车牌号
 * @apiSuccess {string} data.warehouse_id 网点ID
 * @apiSuccess {string} data.warehouse_name 网点名
 * @apiSuccess {string} data.warehouse_phone 网点电话
 * @apiSuccess {string} data.warehouse_post_code 网点邮编
 * @apiSuccess {string} data.warehouse_city 网点城市
 * @apiSuccess {string} data.warehouse_street 网点街道
 * @apiSuccess {string} data.warehouse_house_number 网点门牌号
 * @apiSuccess {string} data.warehouse_address 网点地址
 * @apiSuccess {string} data.warehouse_lon 网点经度
 * @apiSuccess {string} data.warehouse_lat 网点纬度
 * @apiSuccess {string} data.status 状态
 * @apiSuccess {string} data.begin_time 出库时间
 * @apiSuccess {string} data.begin_signature 出库签名
 * @apiSuccess {string} data.begin_signature_remark 出库签名备注
 * @apiSuccess {string} data.begin_signature_first_pic 出库签名图片1
 * @apiSuccess {string} data.begin_signature_second_pic 出库签名图片2
 * @apiSuccess {string} data.begin_signature_third_pic 出库签名图片3
 * @apiSuccess {string} data.end_time 入库时间
 * @apiSuccess {string} data.end_signature 入库签名
 * @apiSuccess {string} data.end_signature_remark 入库签名备注
 * @apiSuccess {string} data.expect_distance 预计里程
 * @apiSuccess {string} data.actual_distance 实际里程
 * @apiSuccess {string} data.expect_time 预计时间
 * @apiSuccess {string} data.actual_time 实际时间
 * @apiSuccess {string} data.expect_pickup_quantity 预计取件数量
 * @apiSuccess {string} data.actual_pickup_quantity 实际取件数量
 * @apiSuccess {string} data.expect_pie_quantity 预计派件数量
 * @apiSuccess {string} data.actual_pie_quantity 实际派件数量
 * @apiSuccess {string} data.sticker_amount 贴单费用
 * @apiSuccess {string} data.replace_amount 代收货款
 * @apiSuccess {string} data.settlement_amount 结算金额-运费
 * @apiSuccess {string} data.remark 备注
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.lave_distance 剩余里程数
 * @apiSuccess {string} data.status_name 状态名
 * @apiSuccess {string} data.expect_time_human 预计时间（时分秒格式）
 * @apiSuccess {string} data.actual_time_human 实际时间（时分秒格式）
 * @apiSuccess {string} data.batch_count 站点数量
 * @apiSuccess {string} data.out_warehouse 出库
 * @apiSuccess {string} data.out_warehouse.id 网点ID
 * @apiSuccess {string} data.out_warehouse.name 网点名
 * @apiSuccess {string} data.out_warehouse.phone 网点电话
 * @apiSuccess {string} data.out_warehouse.post_code 网点邮编
 * @apiSuccess {string} data.out_warehouse.street 网点街道
 * @apiSuccess {string} data.out_warehouse.house_number 网点门牌号
 * @apiSuccess {string} data.out_warehouse.city 网点城市
 * @apiSuccess {string} data.out_warehouse.address 网点地址
 * @apiSuccess {string} data.out_warehouse.package_list 包裹列表
 * @apiSuccess {string} data.out_warehouse.package_list.id 包裹ID
 * @apiSuccess {string} data.out_warehouse.package_list.company_id 公司ID
 * @apiSuccess {string} data.out_warehouse.package_list.tour_no 取件线路编号
 * @apiSuccess {string} data.out_warehouse.package_list.batch_no 站点编号
 * @apiSuccess {string} data.out_warehouse.package_list.order_no 订单号
 * @apiSuccess {string} data.out_warehouse.package_list.name 包裹名称
 * @apiSuccess {string} data.out_warehouse.package_list.express_first_no 快递单号1
 * @apiSuccess {string} data.out_warehouse.package_list.express_second_no 快递单号2
 * @apiSuccess {string} data.out_warehouse.package_list.out_order_no 外部订单号
 * @apiSuccess {string} data.out_warehouse.package_list.weight 重量
 * @apiSuccess {string} data.out_warehouse.package_list.quantity 数量
 * @apiSuccess {string} data.out_warehouse.package_list.remark 备注
 * @apiSuccess {string} data.out_warehouse.package_list.created_at
 * @apiSuccess {string} data.out_warehouse.package_list.updated_at
 * @apiSuccess {string} data.out_warehouse.material_list 材料列表
 * @apiSuccess {string} data.out_warehouse.material_list.id 材料ID
 * @apiSuccess {string} data.out_warehouse.material_list.company_id 公司ID
 * @apiSuccess {string} data.out_warehouse.material_list.tour_no 取件线路编号
 * @apiSuccess {string} data.out_warehouse.material_list.batch_no 站点编号
 * @apiSuccess {string} data.out_warehouse.material_list.name 材料名
 * @apiSuccess {string} data.out_warehouse.material_list.order_no 订单号
 * @apiSuccess {string} data.out_warehouse.material_list.code 材料编号
 * @apiSuccess {string} data.out_warehouse.material_list.expect_quantity 预计数量
 * @apiSuccess {string} data.out_warehouse.material_list.actual_quantity 实际数量
 * @apiSuccess {string} data.out_warehouse.material_list.out_order_no 外部订单号
 * @apiSuccess {string} data.out_warehouse.material_list.remark 备注
 * @apiSuccess {string} data.out_warehouse.material_list.updated_at
 * @apiSuccess {string} data.out_warehouse.material_list.created_at
 * @apiSuccess {string} data.detail_list 站点列表
 * @apiSuccess {string} data.detail_list.id 站点ID
 * @apiSuccess {string} data.detail_list.name 姓名
 * @apiSuccess {string} data.detail_list.phone 电话
 * @apiSuccess {string} data.detail_list.post_code 邮编
 * @apiSuccess {string} data.detail_list.city 城市
 * @apiSuccess {string} data.detail_list.street 街道
 * @apiSuccess {string} data.detail_list.house_number 门牌号
 * @apiSuccess {string} data.detail_list.address 地址
 * @apiSuccess {string} data.detail_list.expect_quantity 预计数量
 * @apiSuccess {string} data.detail_list.signature 签名
 * @apiSuccess {string} data.detail_list.expect_arrive_time 预计到达时间
 * @apiSuccess {string} data.detail_list.actual_arrive_time 实际到达时间
 * @apiSuccess {string} data.detail_list.expect_distance 预计距离
 * @apiSuccess {string} data.detail_list.actual_distance 实际距离
 * @apiSuccess {string} data.detail_list.expect_time 预计时间
 * @apiSuccess {string} data.detail_list.actual_time 实际时间
 * @apiSuccess {string} data.detail_list.order_list 订单列表（和出入库不同，这里给的不是包裹列表）
 * @apiSuccess {string} data.detail_list.order_list.>id 订单ID
 * @apiSuccess {string} data.detail_list.order_list.company_id 公司ID
 * @apiSuccess {string} data.detail_list.order_list.tour_no 取件线路ID
 * @apiSuccess {string} data.detail_list.order_list.batch_no 站点ID
 * @apiSuccess {string} data.detail_list.order_list.order_no 订单号
 * @apiSuccess {string} data.detail_list.order_list.out_order_no 外部订单号
 * @apiSuccess {string} data.detail_list.order_list.name 材料名
 * @apiSuccess {string} data.detail_list.order_list.type 类型
 * @apiSuccess {string} data.detail_list.order_list.status 状态
 * @apiSuccess {string} data.detail_list.order_list.special_remark 特殊事项
 * @apiSuccess {string} data.detail_list.order_list.remark 备注
 * @apiSuccess {string} data.detail_list.order_list.status_name 状态名
 * @apiSuccess {string} data.detail_list.order_list.exception_label_name 异常名
 * @apiSuccess {string} data.detail_list.order_list.type_name 类型名
 * @apiSuccess {string} data.detail_list.material_list 材料列表
 * @apiSuccess {string} data.detail_list.material_list.id 材料ID
 * @apiSuccess {string} data.detail_list.material_list.company_id 公司ID
 * @apiSuccess {string} data.detail_list.material_list.tour_no 取件线路编号
 * @apiSuccess {string} data.detail_list.material_list.batch_no 站点编号
 * @apiSuccess {string} data.detail_list.material_list.order_no 订单号
 * @apiSuccess {string} data.detail_list.material_list.name 材料名
 * @apiSuccess {string} data.detail_list.material_list.code 材料编号
 * @apiSuccess {string} data.detail_list.material_list.out_order_no 外部订单号
 * @apiSuccess {string} data.detail_list.material_list.expect_quantity 预计数量
 * @apiSuccess {string} data.detail_list.material_list.actual_quantity 实际数量
 * @apiSuccess {string} data.detail_list.material_list.remark 备注
 * @apiSuccess {string} data.detail_list.material_list.updated_at
 * @apiSuccess {string} data.detail_list.material_list.created_at
 * @apiSuccess {string} data.in_warehouse 入库
 * @apiSuccess {string} data.in_warehouse.id 网点ID
 * @apiSuccess {string} data.in_warehouse.name 网点名
 * @apiSuccess {string} data.in_warehouse.phone 网点电话
 * @apiSuccess {string} data.in_warehouse.post_code 网点邮编
 * @apiSuccess {string} data.in_warehouse.street 网点街道
 * @apiSuccess {string} data.in_warehouse.house_number 网点门牌号
 * @apiSuccess {string} data.in_warehouse.city 网点城市
 * @apiSuccess {string} data.in_warehouse.address 网点地址
 * @apiSuccess {string} data.in_warehouse.package_list 包裹列表
 * @apiSuccess {string} data.in_warehouse.package_list.id 包裹ID
 * @apiSuccess {string} data.in_warehouse.package_list.company_id 公司ID
 * @apiSuccess {string} data.in_warehouse.package_list.tour_no 取件线路编号
 * @apiSuccess {string} data.in_warehouse.package_list.batch_no 站点编号
 * @apiSuccess {string} data.in_warehouse.package_list.order_no 订单号
 * @apiSuccess {string} data.in_warehouse.package_list.name 包裹名
 * @apiSuccess {string} data.in_warehouse.package_list.express_first_no 快递单号1
 * @apiSuccess {string} data.in_warehouse.package_list.express_second_no 快递单号2
 * @apiSuccess {string} data.in_warehouse.package_list.out_order_no 外部订单号
 * @apiSuccess {string} data.in_warehouse.package_list.weight 重量
 * @apiSuccess {string} data.in_warehouse.package_list.quantity 数量
 * @apiSuccess {string} data.in_warehouse.package_list.remark 备注
 * @apiSuccess {string} data.in_warehouse.package_list.created_at
 * @apiSuccess {string} data.in_warehouse.package_list.updated_at
 * @apiSuccess {string} data.in_warehouse.material_list 材料列表
 * @apiSuccess {string} data.in_warehouse.material_list.id 材料ID
 * @apiSuccess {string} data.in_warehouse.material_list.company_id 公司ID
 * @apiSuccess {string} data.in_warehouse.material_list.tour_no 取件线路编号
 * @apiSuccess {string} data.in_warehouse.material_list.batch_no 站点编号
 * @apiSuccess {string} data.in_warehouse.material_list.order_no 订单号
 * @apiSuccess {string} data.in_warehouse.material_list.name 材料名
 * @apiSuccess {string} data.in_warehouse.material_list.code 材料编号
 * @apiSuccess {string} data.in_warehouse.material_list.out_order_no 外部订单号
 * @apiSuccess {string} data.in_warehouse.material_list.expect_quantity 预计数量
 * @apiSuccess {string} data.in_warehouse.material_list.actual_quantity 实际数量
 * @apiSuccess {string} data.in_warehouse.material_list.remark 备注
 * @apiSuccess {string} data.in_warehouse.material_list.created_at
 * @apiSuccess {string} data.in_warehouse.material_list.updated_at
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.out_warehouse.package_list.type 取派类型
 * @apiSuccess {string} data.out_warehouse.package_list.type_name 类型名称
 * @apiSuccess {string} data.in_warehouse.package_list.type_name 取派类型
 * @apiSuccess {string} data.in_warehouse.package_list.type_name 类型名称
 * @apiSuccess {string} data.expect_material_quantity 预计材料数量
 * @apiSuccess {string} data.actual_material_quantity 实际材料数量
 * @apiSuccess {string} data.card_settlement_amount 银行卡运费
 * @apiSuccess {string} data.card_replace_amount 银行卡代收货款
 * @apiSuccess {string} data.card_sticker_amount 银行卡贴单费
 * @apiSuccess {string} data.card_total_amount 银行卡总计
 * @apiSuccess {string} data.card_sticker_count 银行卡贴单数
 * @apiSuccess {string} data.cash_settlement_amount 现金运费
 * @apiSuccess {string} data.cash_replace_amount 现金代收货款
 * @apiSuccess {string} data.cash_sticker_amount 现金贴单费
 * @apiSuccess {string} data.cash_total_amount 现金总计
 * @apiSuccess {string} data.cash_sticker_count 现金贴单数
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 32,
 * "company_id": 2,
 * "tour_no": "TOUR00020002F",
 * "line_id": 4,
 * "line_name": "123",
 * "execution_date": "2020-03-18",
 * "driver_id": 1,
 * "driver_name": "洋铭胡",
 * "driver_phone": "12345678",
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": 2,
 * "car_no": "543261",
 * "warehouse_id": 2,
 * "warehouse_name": "撤硕儿",
 * "warehouse_phone": "12345678",
 * "warehouse_post_code": "2153PJ",
 * "warehouse_city": "Nieuw-Vennep",
 * "warehouse_street": "Pesetaweg",
 * "warehouse_house_number": "20",
 * "warehouse_address": null,
 * "warehouse_lon": "4.62897256",
 * "warehouse_lat": "52.25347699",
 * "status": 5,
 * "begin_time": "2020-03-13 18:44:28",
 * "begin_signature": "https://dev-tms.nle-tech.com/storage/driver/images/2/2020-03-13/1/tour/202003131844285e6b640c1987f.png",
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": "https://dev-tms.nle-tech.com/storage/driver/images/2/2020-03-13/1/tour/202003131844195e6b640361095.jpg",
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_time": "2020-03-13 19:15:18",
 * "end_signature": "https://dev-tms.nle-tech.com/storage/driver/images/2/2020-03-13/1/tour/202003131915175e6b6b4592541.png",
 * "end_signature_remark": null,
 * "expect_distance": 0,
 * "actual_distance": null,
 * "expect_time": null,
 * "actual_time": null,
 * "expect_pickup_quantity": 2,
 * "actual_pickup_quantity": 2,
 * "expect_pie_quantity": 0,
 * "actual_pie_quantity": 0,
 * "sticker_amount": "14.00",
 * "replace_amount": "0.00",
 * "settlement_amount": "0.00",
 * "remark": null,
 * "created_at": "2020-03-13 17:52:24",
 * "updated_at": "2020-03-13 19:15:18",
 * "lave_distance": 0,
 * "status_name": "取派完成",
 * "expect_time_human": null,
 * "actual_time_human": null,
 * "batch_count": 1,
 * "actual_batch_count": 1,
 * "expect_material_quantity": 4,
 * "actual_material_quantity": 4,
 * "card_settlement_amount": 0,
 * "card_replace_amount": 0,
 * "card_sticker_amount": 0,
 * "card_total_amount": 0,
 * "card_sticker_count": 0,
 * "cash_settlement_amount": 0,
 * "cash_replace_amount": 0,
 * "cash_sticker_amount": 0,
 * "cash_total_amount": 0,
 * "cash_sticker_count": 0,
 * "out_warehouse": {
 * "id": 2,
 * "name": "撤硕儿",
 * "phone": "12345678",
 * "post_code": "2153PJ",
 * "street": "Pesetaweg",
 * "house_number": "20",
 * "city": "Nieuw-Vennep",
 * "address": null,
 * "package_list": [
 * {
 * "id": 7,
 * "company_id": 2,
 * "tour_no": "TOUR00020002F",
 * "batch_no": "BATCH00020001H",
 * "order_no": "TMS000200000008",
 * "type": 1,
 * "name": "11111",
 * "express_first_no": "11111",
 * "express_second_no": "",
 * "out_order_no": "111111",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 1,
 * "status": 5,
 * "sticker_no": "",
 * "sticker_amount": "7.00",
 * "remark": "",
 * "created_at": "2020-03-13 17:52:25",
 * "updated_at": "2020-03-13 19:08:51",
 * "status_name": "已完成",
 * "type_name": "取件"
 * },
 * {
 * "id": 8,
 * "company_id": 2,
 * "tour_no": "TOUR00020002F",
 * "batch_no": "BATCH00020001H",
 * "order_no": "TMS000200000009",
 * "type": 1,
 * "name": "123",
 * "express_first_no": "12344",
 * "express_second_no": "",
 * "out_order_no": "12344",
 * "weight": "123.00",
 * "expect_quantity": 1,
 * "actual_quantity": 1,
 * "status": 5,
 * "sticker_no": "1",
 * "sticker_amount": "7.00",
 * "remark": "123",
 * "created_at": "2020-03-13 17:58:20",
 * "updated_at": "2020-03-13 19:08:51",
 * "status_name": "已完成",
 * "type_name": "取件"
 * }
 * ],
 * "material_list": []
 * },
 * "detail_list": [
 * {
 * "id": 8,
 * "name": "肖哥",
 * "phone": "12345",
 * "post_code": "2153PJ",
 * "city": "Nieuw-Vennep",
 * "street": "Pesetaweg",
 * "house_number": "20",
 * "address": "老八家撤硕儿",
 * "expect_quantity": 2,
 * "signature": "https://dev-tms.nle-tech.com/storage/driver/images/2/2020-03-13/1/tour/202003131908515e6b69c36aaf6.png",
 * "expect_arrive_time": null,
 * "actual_arrive_time": "2020-03-13 18:49:56",
 * "expect_distance": 0,
 * "actual_distance": null,
 * "expect_time": 0,
 * "actual_time": 328,
 * "order_list": [
 * {
 * "id": 13,
 * "type": 1,
 * "tour_no": "TOUR00020002F",
 * "batch_no": "BATCH00020001H",
 * "order_no": "TMS000200000008",
 * "out_order_no": null,
 * "status": 5,
 * "special_remark": null,
 * "remark": null,
 * "status_name": "已完成",
 * "exception_label_name": null,
 * "type_name": "取件",
 * "merchant_id_name": "",
 * "receiver_country_name": null,
 * "sender_country_name": null,
 * "country_name": null,
 * "merchant": null
 * },
 * {
 * "id": 14,
 * "type": 1,
 * "tour_no": "TOUR00020002F",
 * "batch_no": "BATCH00020001H",
 * "order_no": "TMS000200000009",
 * "out_order_no": null,
 * "status": 5,
 * "special_remark": null,
 * "remark": null,
 * "status_name": "已完成",
 * "exception_label_name": null,
 * "type_name": "取件",
 * "merchant_id_name": "",
 * "receiver_country_name": null,
 * "sender_country_name": null,
 * "country_name": null,
 * "merchant": null
 * }
 * ],
 * "material_list": []
 * }
 * ],
 * "in_warehouse": {
 * "id": 2,
 * "name": "撤硕儿",
 * "phone": "12345678",
 * "post_code": "2153PJ",
 * "street": "Pesetaweg",
 * "house_number": "20",
 * "city": "Nieuw-Vennep",
 * "address": null,
 * "package_list": [
 * {
 * "id": 7,
 * "company_id": 2,
 * "tour_no": "TOUR00020002F",
 * "batch_no": "BATCH00020001H",
 * "order_no": "TMS000200000008",
 * "type": 1,
 * "name": "11111",
 * "express_first_no": "11111",
 * "express_second_no": "",
 * "out_order_no": "111111",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 1,
 * "status": 5,
 * "sticker_no": "",
 * "sticker_amount": "7.00",
 * "remark": "",
 * "created_at": "2020-03-13 17:52:25",
 * "updated_at": "2020-03-13 19:08:51",
 * "status_name": "已完成",
 * "type_name": "取件"
 * },
 * {
 * "id": 8,
 * "company_id": 2,
 * "tour_no": "TOUR00020002F",
 * "batch_no": "BATCH00020001H",
 * "order_no": "TMS000200000009",
 * "type": 1,
 * "name": "123",
 * "express_first_no": "12344",
 * "express_second_no": "",
 * "out_order_no": "12344",
 * "weight": "123.00",
 * "expect_quantity": 1,
 * "actual_quantity": 1,
 * "status": 5,
 * "sticker_no": "1",
 * "sticker_amount": "7.00",
 * "remark": "123",
 * "created_at": "2020-03-13 17:58:20",
 * "updated_at": "2020-03-13 19:08:51",
 * "status_name": "已完成",
 * "type_name": "取件"
 * }
 * ],
 * "material_list": []
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/tour/report 任务报告查询
 * @apiName 任务报告查询
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} driver_name 司机姓名
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 取件线路ID
 * @apiSuccess {string} data.data.tour_no 取件线路编号
 * @apiSuccess {string} data.data.line_name 线路名称
 * @apiSuccess {string} data.data.execution_date 取派日期
 * @apiSuccess {string} data.data.driver_name 司机名称
 * @apiSuccess {string} data.data.expect_pickup_quantity 预计取件数量(预计包裹入库数量)
 * @apiSuccess {string} data.data.actual_pickup_quantity 实际取件数量(实际包裹入库数量)
 * @apiSuccess {string} data.data.expect_pie_quantity 预计派件数量(预计包裹出库数量)
 * @apiSuccess {string} data.data.actual_pie_quantity 实际派件数量(实际包裹出库数量)
 * @apiSuccess {string} data.data.batch_count 站点数量
 * @apiSuccess {string} data.data.actual_batch_count 实际站点数量
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccess {string} actual_batch_count
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 7,
 * "tour_no": "TOUR0001000036",
 * "line_name": "万能星期天线",
 * "execution_date": "2020-01-26",
 * "driver_name": "ZhangqiHuo",
 * "expect_pickup_quantity": 3,
 * "actual_pickup_quantity": 3,
 * "expect_pie_quantity": 1,
 * "actual_pie_quantity": 1,
 * "batch_count": 4,
 * "created_at": "2020-01-02 03:03:53",
 * "updated_at": "2020-01-02 16:44:44"
 * },
 * {
 * "id": 11,
 * "tour_no": "TOUR0001000040",
 * "line_name": "万能星期天线",
 * "execution_date": "2020-01-26",
 * "driver_name": "ZhangqiHuo",
 * "expect_pickup_quantity": 0,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 10,
 * "actual_pie_quantity": 7,
 * "batch_count": 4,
 * "created_at": "2020-01-03 02:12:39",
 * "updated_at": "2020-01-03 11:05:40"
 * },
 * {
 * "id": 12,
 * "tour_no": "TOUR0001000041",
 * "line_name": "万能星期天线",
 * "execution_date": "2020-01-26",
 * "driver_name": "胡洋铭",
 * "expect_pickup_quantity": 0,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 3,
 * "actual_pie_quantity": 2,
 * "batch_count": 2,
 * "created_at": "2020-01-03 02:27:51",
 * "updated_at": "2020-01-03 11:15:06"
 * },
 * {
 * "id": 16,
 * "tour_no": "TOUR0001000042",
 * "line_name": "万能星期天线",
 * "execution_date": "2020-01-26",
 * "driver_name": "胡洋铭",
 * "expect_pickup_quantity": 0,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 1,
 * "actual_pie_quantity": 0,
 * "batch_count": 1,
 * "created_at": "2020-01-03 12:04:46",
 * "updated_at": "2020-01-03 12:07:20"
 * },
 * {
 * "id": 17,
 * "tour_no": "TOUR0001000043",
 * "line_name": "万能星期天线",
 * "execution_date": "2020-01-26",
 * "driver_name": "ZhangqiHuo",
 * "expect_pickup_quantity": 4,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 3,
 * "actual_pie_quantity": 2,
 * "batch_count": 6,
 * "created_at": "2020-01-04 05:54:22",
 * "updated_at": "2020-01-06 08:16:22"
 * },
 * {
 * "id": 18,
 * "tour_no": "TOUR0001000044",
 * "line_name": "万能星期天线",
 * "execution_date": "2020-01-19",
 * "driver_name": "ZhangqiHuo",
 * "expect_pickup_quantity": 4,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 6,
 * "actual_pie_quantity": 0,
 * "batch_count": 6,
 * "created_at": "2020-01-04 06:09:46",
 * "updated_at": "2020-01-09 17:17:39"
 * },
 * {
 * "id": 19,
 * "tour_no": "TOUR0001000045",
 * "line_name": "万能星期天线",
 * "execution_date": "2020-02-02",
 * "driver_name": "ZhangqiHuo",
 * "expect_pickup_quantity": 3,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 7,
 * "actual_pie_quantity": 0,
 * "batch_count": 4,
 * "created_at": "2020-01-04 07:00:17",
 * "updated_at": "2020-01-08 17:54:47"
 * },
 * {
 * "id": 20,
 * "tour_no": "TOUR0001000046",
 * "line_name": "万能星期天线",
 * "execution_date": "2020-01-12",
 * "driver_name": "ZhangqiHuo",
 * "expect_pickup_quantity": 4,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 6,
 * "actual_pie_quantity": 0,
 * "batch_count": 3,
 * "created_at": "2020-01-04 07:01:03",
 * "updated_at": "2020-01-09 18:22:53"
 * },
 * {
 * "id": 21,
 * "tour_no": "TOUR0001000047",
 * "line_name": "万能星期天线",
 * "execution_date": "2020-02-09",
 * "driver_name": "ZhangqiHuo",
 * "expect_pickup_quantity": 4,
 * "actual_pickup_quantity": 2,
 * "expect_pie_quantity": 6,
 * "actual_pie_quantity": 3,
 * "batch_count": 3,
 * "created_at": "2020-01-04 07:01:51",
 * "updated_at": "2020-01-06 07:44:34"
 * },
 * {
 * "id": 22,
 * "tour_no": "TOUR0001000048",
 * "line_name": "万能星期天线",
 * "execution_date": "2020-01-12",
 * "driver_name": "tmztmz",
 * "expect_pickup_quantity": 3,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 4,
 * "actual_pie_quantity": 0,
 * "batch_count": 5,
 * "created_at": "2020-01-04 07:07:22",
 * "updated_at": "2020-01-09 19:47:54"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/report?page=1",
 * "last": "http://tms-api.test/api/admin/report?page=2",
 * "prev": null,
 * "next": "http://tms-api.test/api/admin/report?page=2"
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 2,
 * "path": "http://tms-api.test/api/admin/report",
 * "per_page": 10,
 * "to": 10,
 * "total": 14
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/tour 取件线路查询
 * @apiName 取件线路查询
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} status 状态1-待分配2-已分配-3-待出库4-取派中5-取派完成
 * @apiParam {string} begin_date 开始时间
 * @apiParam {string} end_date 结束时间
 * @apiParam {string} driver_name 司机
 * @apiParam {string} line_name 线路名称
 * @apiParam {string} tour_no 取件线路编号
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 取件线路ID
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.tour_no 取件线路编号
 * @apiSuccess {string} data.data.line_id 线路ID
 * @apiSuccess {string} data.data.line_name 线路名
 * @apiSuccess {string} data.data.execution_date 取派日期
 * @apiSuccess {string} data.data.driver_id 司机ID
 * @apiSuccess {string} data.data.driver_name 司机姓名
 * @apiSuccess {string} data.data.driver_rest_time 司机休息时长
 * @apiSuccess {string} data.data.driver_avt_id 取派件AVT设备ID
 * @apiSuccess {string} data.data.driver_assign_status 司机分配状态1-未分配2-已分配
 * @apiSuccess {string} data.data.car_id 车辆ID
 * @apiSuccess {string} data.data.car_no 车牌
 * @apiSuccess {string} car_assign_status 车辆分配状态1-未分配2-已分配
 * @apiSuccess {string} data.data.warehouse_id 网点ID
 * @apiSuccess {string} data.data.warehouse_name 网点名称
 * @apiSuccess {string} data.data.warehouse_phone 网点电话
 * @apiSuccess {string} data.data.warehouse_post_code 网点邮编
 * @apiSuccess {string} data.data.warehouse_city 网点城市
 * @apiSuccess {string} data.data.warehouse_address 网点详细地址
 * @apiSuccess {string} data.data.warehouse_lon 网点经度
 * @apiSuccess {string} data.data.warehouse_lat 网点纬度
 * @apiSuccess {string} data.data.status 状态1-待分配2-已分配-3-待出库4-取派中5-取派完成
 * @apiSuccess {string} data.data.begin_signature 出库签名
 * @apiSuccess {string} data.data.begin_signature_remark 出库备注
 * @apiSuccess {string} data.data.begin_signature_first_pic 出库图片1
 * @apiSuccess {string} data.data.begin_signature_second_pic 出库图片2
 * @apiSuccess {string} data.data.begin_signature_third_pic 出库图片3
 * @apiSuccess {string} data.data.end_signature 入库签名
 * @apiSuccess {string} data.data.end_signature_remark 入库备注
 * @apiSuccess {string} data.data.expect_distance 预计里程
 * @apiSuccess {string} data.data.actual_distance 实际里程
 * @apiSuccess {string} data.data.expect_pickup_quantity 预计取件数量(预计包裹入库数量)
 * @apiSuccess {string} data.data.actual_pickup_quantity 实际取件数量(实际包裹入库数量)
 * @apiSuccess {string} data.data.expect_pie_quantity 预计派件数量(预计包裹出库数量)
 * @apiSuccess {string} data.data.actual_pie_quantity 实际派件数量(实际包裹出库数量)
 * @apiSuccess {string} data.data.order_amount 贴单费用
 * @apiSuccess {string} data.data.replace_amount 代收货款
 * @apiSuccess {string} data.data.remark 备注
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.data.updated_at 修改时间
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.data.expect_time 预计时间
 * @apiSuccess {string} data.data.actual_time 实际时间
 * @apiSuccess {string} data.data.batch_count 站点数量
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "company_id": 1,
 * "tour_no": "TOUR0001000030",
 * "line_id": 23,
 * "line_name": "线路ab",
 * "execution_date": "2019-12-31",
 * "driver_id": null,
 * "driver_name": null,
 * "driver_assign_status": 1,
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": 3,
 * "car_no": "k7805",
 * "car_assign_status": 2,
 * "warehouse_id": 9,
 * "warehouse_name": "麓谷企业广场6",
 * "warehouse_phone": "17570715315",
 * "warehouse_post_code": "1",
 * "warehouse_city": "长沙",
 * "warehouse_address": "C3",
 * "warehouse_lon": "",
 * "warehouse_lat": "",
 * "status": 1,
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": null,
 * "actual_distance": null,
 * "expect_pickup_quantity": 0,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 1,
 * "actual_pie_quantity": 0,
 * "order_amount": null,
 * "replace_amount": null,
 * "remark": null,
 * "created_at": "2019-12-28 05:58:34",
 * "updated_at": "2019-12-29 10:55:58"
 * },
 * {
 * "id": 2,
 * "company_id": 1,
 * "tour_no": "TOUR0001000031",
 * "line_id": 23,
 * "line_name": "线路ab",
 * "execution_date": "2020-01-07",
 * "driver_id": null,
 * "driver_name": "",
 * "driver_assign_status": 1,
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": null,
 * "car_no": null,
 * "car_assign_status": 1,
 * "warehouse_id": 9,
 * "warehouse_name": "麓谷企业广场6",
 * "warehouse_phone": "17570715315",
 * "warehouse_post_code": "1",
 * "warehouse_city": "长沙",
 * "warehouse_address": "C3",
 * "warehouse_lon": "",
 * "warehouse_lat": "",
 * "status": 1,
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": null,
 * "actual_distance": null,
 * "expect_pickup_quantity": 0,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 1,
 * "actual_pie_quantity": 0,
 * "order_amount": null,
 * "replace_amount": null,
 * "remark": null,
 * "created_at": "2019-12-28 05:59:42",
 * "updated_at": "2019-12-28 05:59:42"
 * },
 * {
 * "id": 3,
 * "company_id": 1,
 * "tour_no": "TOUR0001000032",
 * "line_id": 28,
 * "line_name": "万能星期天线",
 * "execution_date": "2019-12-29",
 * "driver_id": null,
 * "driver_name": "",
 * "driver_assign_status": 1,
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": null,
 * "car_no": null,
 * "car_assign_status": 1,
 * "warehouse_id": 9,
 * "warehouse_name": "麓谷企业广场6",
 * "warehouse_phone": "17570715315",
 * "warehouse_post_code": "1",
 * "warehouse_city": "长沙",
 * "warehouse_address": "C3",
 * "warehouse_lon": "",
 * "warehouse_lat": "",
 * "status": 1,
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": null,
 * "actual_distance": null,
 * "expect_pickup_quantity": 0,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 2,
 * "actual_pie_quantity": 0,
 * "order_amount": null,
 * "replace_amount": null,
 * "remark": null,
 * "created_at": "2019-12-28 06:15:20",
 * "updated_at": "2019-12-28 06:16:00"
 * },
 * {
 * "id": 4,
 * "company_id": 1,
 * "tour_no": "TOUR0001000033",
 * "line_id": 28,
 * "line_name": "万能星期天线",
 * "execution_date": "2020-01-05",
 * "driver_id": 9,
 * "driver_name": "",
 * "driver_assign_status": 1,
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": null,
 * "car_no": null,
 * "car_assign_status": 1,
 * "warehouse_id": 9,
 * "warehouse_name": "麓谷企业广场6",
 * "warehouse_phone": "17570715315",
 * "warehouse_post_code": "1",
 * "warehouse_city": "长沙",
 * "warehouse_address": "C3",
 * "warehouse_lon": "",
 * "warehouse_lat": "",
 * "status": 1,
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": null,
 * "actual_distance": null,
 * "expect_pickup_quantity": 0,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 1,
 * "actual_pie_quantity": 0,
 * "order_amount": null,
 * "replace_amount": null,
 * "remark": null,
 * "created_at": "2019-12-28 06:16:41",
 * "updated_at": "2019-12-28 06:16:41"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/tour?page=1",
 * "last": "http://tms-api.test/api/admin/tour?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/tour",
 * "per_page": 15,
 * "to": 4,
 * "total": 4
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/tour/{id} 取件线路详情
 * @apiName 取件线路详情
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 取件线路ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 区间线路ID
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.tour_no 取件线路编号
 * @apiSuccess {string} data.line_id 线路ID
 * @apiSuccess {string} data.line_name 线路名
 * @apiSuccess {string} data.execution_date 取派日期
 * @apiSuccess {string} data.driver_id 司机ID
 * @apiSuccess {string} data.driver_name 司机姓名
 * @apiSuccess {string} data.driver_rest_time 司机休息时长
 * @apiSuccess {string} data.driver_avt_id 取派件AVT设备ID
 * @apiSuccess {string} data.car_id 车辆ID
 * @apiSuccess {string} data.car_no 车牌
 * @apiSuccess {string} data.warehouse_id 网点ID
 * @apiSuccess {string} data.warehouse_name 网点名称
 * @apiSuccess {string} data.warehouse_phone 网点电话
 * @apiSuccess {string} data.warehouse_post_code 网点邮编
 * @apiSuccess {string} data.warehouse_city 网点城市
 * @apiSuccess {string} data.warehouse_address 网点详细地址
 * @apiSuccess {string} data.warehouse_lon 网点经度
 * @apiSuccess {string} data.warehouse_lat 网点纬度
 * @apiSuccess {string} data.status 状态1-待分配2-已分配-3-待出库4-取派中5-取派完成
 * @apiSuccess {string} data.begin_signature 出库签名
 * @apiSuccess {string} data.begin_signature_remark 出库备注
 * @apiSuccess {string} data.begin_signature_first_pic 出库图片1
 * @apiSuccess {string} data.begin_signature_second_pic 出库图片2
 * @apiSuccess {string} data.begin_signature_third_pic 出库图片3
 * @apiSuccess {string} data.end_signature 入库签名
 * @apiSuccess {string} data.end_signature_remark 入库备注
 * @apiSuccess {string} data.expect_distance 预计里程
 * @apiSuccess {string} data.actual_distance 实际里程
 * @apiSuccess {string} data.expect_pickup_quantity 预计取件数量(预计包裹入库数量)
 * @apiSuccess {string} data.actual_pickup_quantity 实际取件数量(实际包裹入库数量)
 * @apiSuccess {string} data.expect_pie_quantity 预计派件数量(预计包裹出库数量)
 * @apiSuccess {string} data.actual_pie_quantity 实际派件数量(实际包裹出库数量)
 * @apiSuccess {string} data.order_amount 贴单费用
 * @apiSuccess {string} data.replace_amount 代收货款
 * @apiSuccess {string} data.remark 备注
 * @apiSuccess {string} data.created_at 创建时间
 * @apiSuccess {string} data.updated_at 修改时间
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1,
 * "company_id": 1,
 * "tour_no": "TOUR0001000030",
 * "line_id": 23,
 * "line_name": "线路ab",
 * "execution_date": "2019-12-31",
 * "driver_id": null,
 * "driver_name": "",
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": null,
 * "car_no": null,
 * "warehouse_id": 9,
 * "warehouse_name": "麓谷企业广场6",
 * "warehouse_phone": "17570715315",
 * "warehouse_post_code": "1",
 * "warehouse_city": "长沙",
 * "warehouse_address": "C3",
 * "warehouse_lon": "",
 * "warehouse_lat": "",
 * "status": 1,
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": null,
 * "actual_distance": null,
 * "expect_pickup_quantity": 0,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 1,
 * "actual_pie_quantity": 0,
 * "order_amount": null,
 * "replace_amount": null,
 * "remark": null,
 * "created_at": "2019-12-28 05:58:34",
 * "updated_at": "2019-12-28 05:58:34"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/tour/{id}/assign-driver 分配司机
 * @apiName 分配司机
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 取件线路ID
 * @apiParam {string} driver_id 司机ID
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

/**
 * @api {put} /admin/tour/1/cancel-driver 取消分配司机
 * @apiName 取消分配司机
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 取件线路ID
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

/**
 * @api {put} /admin/tour/1/assign-car 分配车辆
 * @apiName 分配车辆
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 取件线路ID
 * @apiParam {string} car_id 车辆ID
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

/**
 * @api {put} /admin/tour/{id}/cancel-car 取消车辆分配
 * @apiName 取消车辆分配
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 取件线路ID
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

/**
 * @api {post} /admin/tour/route-init 自动线路优化
 * @apiName 自动线路优化
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} tour_no 取件线路编号
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": "修改线路成功",
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/tour/auto-op-tour 自动线路优化
 * @apiName 自动线路优化
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} tour_no TOUR0001000043
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": "修改线路成功",
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/tour/route-update 手动线路调整
 * @apiName 手动线路调整
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} batch_ids 站点ID数组
 * @apiParam {string} tour_no 取件线路编号
 * @apiSuccess {string} batch_ids
 * @apiSuccess {string} tour_no
 * @apiSuccessExample {json} Success-Response:
 * {
 * "batch_ids": [53,54,55,56,57,58],
 * "tour_no": "TOUR0001000043"
 * }
 */

/**
 * @api {get} /admin/car/track 线路追踪
 * @apiName 线路追踪
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} driver_id 司机ID（查询条件二选一）
 * @apiParam {string} tour_no 取件线路编号（查询条件二选一）
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.driver 司机信息
 * @apiSuccess {string} data.driver.id 司机ID
 * @apiSuccess {string} data.driver.email 邮司机邮箱
 * @apiSuccess {string} data.driver.fullname 司机全名
 * @apiSuccess {string} data.driver.phone 司机电话
 * @apiSuccess {string} data.route_tracking 线路追踪
 * @apiSuccess {string} data.route_tracking.id 线路追踪ID
 * @apiSuccess {string} data.route_tracking.company_id 公司ID
 * @apiSuccess {string} data.route_tracking.lon 经度
 * @apiSuccess {string} data.route_tracking.lat 纬度
 * @apiSuccess {string} data.route_tracking.tour_no 取件线路编号
 * @apiSuccess {string} data.route_tracking.driver_id 司机ID
 * @apiSuccess {string} data.route_tracking.tour_driver_event_id
 * @apiSuccess {string} data.route_tracking.time
 * @apiSuccess {string} data.route_tracking.stop_time 停留时间
 * @apiSuccess {string} data.route_tracking.created_at
 * @apiSuccess {string} data.route_tracking.updated_at
 * @apiSuccess {string} data.route_tracking.time_human 时间
 * @apiSuccess {string} data.route_tracking.event 事件
 * @apiSuccess {string} data.route_tracking.event.content 事件内容
 * @apiSuccess {string} data.route_tracking.event.time 事件时间
 * @apiSuccess {string} data.route_tracking.event.type 事件类型
 * @apiSuccess {string} data.tour_event 站点事件
 * @apiSuccess {string} data.tour_event.receiver_lon 经度
 * @apiSuccess {string} data.tour_event.receiver_lat 纬度
 * @apiSuccess {string} data.tour_event.receiver_fullname 全名
 * @apiSuccess {string} data.tour_event.event 事件
 * @apiSuccess {string} data.tour_event.event.id 事件ID
 * @apiSuccess {string} data.tour_event.event.company_id 公司ID
 * @apiSuccess {string} data.tour_event.event.lon 经度
 * @apiSuccess {string} data.tour_event.event.lat 纬度
 * @apiSuccess {string} data.tour_event.event.type 类型
 * @apiSuccess {string} data.tour_event.event.content 内容
 * @apiSuccess {string} data.tour_event.event.address 地址
 * @apiSuccess {string} data.tour_event.event.icon_id
 * @apiSuccess {string} data.tour_event.event.icon_path
 * @apiSuccess {string} data.tour_event.event.batch_no 站点编号
 * @apiSuccess {string} data.tour_event.event.tour_no 取件线路编号
 * @apiSuccess {string} data.tour_event.event.route_tracking_id
 * @apiSuccess {string} data.tour_event.event.created_at
 * @apiSuccess {string} data.tour_event.event.updated_at
 * @apiSuccess {string} msg
 * @apiSuccess {string} sort_id
 * @apiSuccess {string} data.tour_event.event.sort_id 站点序号
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "driver": {
 * "id": 56,
 * "email": "m@qq.com",
 * "fullname": "mm",
 * "phone": "888"
 * },
 * "route_tracking": [
 * {
 * "id": 1035,
 * "company_id": 3,
 * "lon": "4.62897256",
 * "lat": "52.25347699",
 * "tour_no": "4OH01",
 * "driver_id": 56,
 * "tour_driver_event_id": 2400,
 * "time": 1594977118,
 * "stop_time": 100,
 * "created_at": "2020-07-17 17:11:58",
 * "updated_at": "2020-07-17 17:11:58",
 * "time_human": "2020-07-17 17:11:58",
 * "event": {
 * "content": "司机已在此停留[100]分钟",
 * "time": "2020-07-17 17:11:58",
 * "type": "stop"
 * }
 * },
 * {
 * "id": 1036,
 * "company_id": 3,
 * "lon": "4.87510019",
 * "lat": "52.31153083",
 * "tour_no": "4OH01",
 * "driver_id": 56,
 * "tour_driver_event_id": 2401,
 * "time": 1594977127,
 * "stop_time": 0,
 * "created_at": "2020-07-17 17:12:07",
 * "updated_at": "2020-07-17 17:12:07",
 * "time_human": "2020-07-17 17:12:07"
 * },
 * {
 * "id": 1037,
 * "company_id": 3,
 * "lon": "4.87510019",
 * "lat": "52.31153083",
 * "tour_no": "4OH01",
 * "driver_id": 56,
 * "tour_driver_event_id": 2402,
 * "time": 1594977520,
 * "stop_time": 0,
 * "created_at": "2020-07-17 17:18:40",
 * "updated_at": "2020-07-17 17:18:40",
 * "time_human": "2020-07-17 17:18:40"
 * },
 * {
 * "id": 1038,
 * "company_id": 3,
 * "lon": "4.62897256",
 * "lat": "52.25347699",
 * "tour_no": "4OH01",
 * "driver_id": 56,
 * "tour_driver_event_id": 2403,
 * "time": 1594977540,
 * "stop_time": 0,
 * "created_at": "2020-07-17 17:19:00",
 * "updated_at": "2020-07-17 17:19:00",
 * "time_human": "2020-07-17 17:19:00"
 * }
 * ],
 * "tour_event": [
 * {
 * "receiver_lon": "4.62897256",
 * "receiver_lat": "52.25347699",
 * "receiver_fullname": "827193289@qq.com",
 * "content": [
 * {
 * "id": 2400,
 * "company_id": 3,
 * "lon": "4.62897256",
 * "lat": "52.25347699",
 * "type": 0,
 * "content": "司机从网点出发",
 * "address": "Pesetaweg 20 Nieuw-Vennep 2153PJ NL",
 * "icon_id": 0,
 * "icon_path": "",
 * "batch_no": "",
 * "tour_no": "4OH01",
 * "route_tracking_id": 0,
 * "created_at": "2020-07-17 17:11:57",
 * "updated_at": "2020-07-17 17:11:57"
 * }
 * ]
 * },
 * {
 * "batch_no": "ZD0515",
 * "receiver_fullname": "wanglihui",
 * "receiver_address": "NL Amstelveen - 1183GT 11",
 * "receiver_lon": "4.87510019",
 * "receiver_lat": "52.31153083",
 * "expect_arrive_time": "2020-07-17 17:36:17",
 * "sort_id": 1000,
 * "content": [
 * {
 * "id": 2401,
 * "company_id": 3,
 * "lon": "4.87510019",
 * "lat": "52.31153083",
 * "type": 0,
 * "content": "到达[wanglihui]客户家",
 * "address": "- 11 Amstelveen 1183GT NL",
 * "icon_id": 0,
 * "icon_path": "",
 * "batch_no": "ZD0515",
 * "tour_no": "4OH01",
 * "route_tracking_id": 0,
 * "created_at": "2020-07-17 17:12:06",
 * "updated_at": "2020-07-17 17:12:06"
 * },
 * {
 * "id": 2402,
 * "company_id": 3,
 * "lon": "4.87510019",
 * "lat": "52.31153083",
 * "type": 0,
 * "content": "从[wanglihui]客户家离开",
 * "address": "- 11 Amstelveen 1183GT NL",
 * "icon_id": 0,
 * "icon_path": "",
 * "batch_no": "ZD0515",
 * "tour_no": "4OH01",
 * "route_tracking_id": 0,
 * "created_at": "2020-07-17 17:18:40",
 * "updated_at": "2020-07-17 17:18:40"
 * }
 * ],
 * "status_name": null,
 * "exception_label_name": null,
 * "pay_type_name": null,
 * "receiver_country_name": null,
 * "expect_time_human": null,
 * "actual_time_human": null
 * },
 * {
 * "receiver_lon": "4.62897256",
 * "receiver_lat": "52.25347699",
 * "receiver_fullname": "827193289@qq.com",
 * "event": [
 * {
 * "id": 2403,
 * "company_id": 3,
 * "lon": "4.62897256",
 * "lat": "52.25347699",
 * "type": 0,
 * "content": "司机返回网点",
 * "address": "Pesetaweg 20 Nieuw-Vennep 2153PJ NL",
 * "icon_id": 0,
 * "icon_path": "",
 * "batch_no": "",
 * "tour_no": "4OH01",
 * "route_tracking_id": 0,
 * "created_at": "2020-07-17 17:19:00",
 * "updated_at": "2020-07-17 17:19:00"
 * }
 * ]
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/tour/batch-excel 导出站点统计
 * @apiName 导出站点统计
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} year 年份
 * @apiParam {string} month 月份
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.name 名称
 * @apiSuccess {string} data.path 路径
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "name": "20200212TOUR0001000096H.xlsx",
 * "path": "https://dev-tms.nle-tech.com/storage/admin/excel/1/tour/20200212TOUR0001000096H.xlsx"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/tour/{id}/txt 导出城市文档
 * @apiName 导出城市文档
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.name 文件名
 * @apiSuccess {string} data.path 路径
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "name": "20200212TOUR0001000096H.txt",
 * "path": "https://dev-tms.nle-tech.com/storage/admin/txt/1/tour/20200212TOUR0001000096H.txt"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/tour/{id}/unlock 取消待出库
 * @apiName 取消待出库
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 取件线路ID
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

/**
 * @api {get} /admin/tour/{id}/map-png 派送地图打印
 * @apiName 派送地图打印
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 取件线路ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.name 地图文件名
 * @apiSuccess {string} data.path 路径
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "name": "20200214TOUR00020001G.png",
 * "path": "tms-api.test/storage/admin/images/2\\tour\\20200214TOUR00020001G.png"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/tour/{id}/tour-excel 取件线路导出
 * @apiName 取件线路导出
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 取件线路ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.name
 * @apiSuccess {string} data.path
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "name": "20200701TOUR00020002L.xlsx",
 * "path": "tms-api.test/storage/admin/excel/2/tour/20200701TOUR00020002L.xlsx"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/tour/{id}/assign 分配线路
 * @apiName 分配线路
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 取件线路ID
 * @apiParam {string} line_id 线路ID
 * @apiParam {string} execution_date 取派日期
 * @apiParam {string} tour_no 取件线路编号
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

/**
 * @api {get} /admin/tour/by-line 获取可加入的取件线路列表
 * @apiName 获取可加入的取件线路列表
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} line_id 线路ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 取件线路ID
 * @apiSuccess {string} data.tour_no 取件线路编号
 * @apiSuccess {string} data.status_name
 * @apiSuccess {string} data.expect_time_human
 * @apiSuccess {string} data.actual_time_human
 * @apiSuccess {string} data.merchant_status
 * @apiSuccess {string} data.merchant_status_name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 243,
 * "tour_no": "4ABJ01",
 * "status_name": null,
 * "expect_time_human": null,
 * "actual_time_human": null,
 * "merchant_status": "",
 * "merchant_status_name": null
 * },
 * {
 * "id": 269,
 * "tour_no": "4ACI01",
 * "status_name": null,
 * "expect_time_human": null,
 * "actual_time_human": null,
 * "merchant_status": "",
 * "merchant_status_name": null
 * },
 * {
 * "id": 278,
 * "tour_no": "4ACR01",
 * "status_name": null,
 * "expect_time_human": null,
 * "actual_time_human": null,
 * "merchant_status": "",
 * "merchant_status_name": null
 * },
 * {
 * "id": 281,
 * "tour_no": "4ACU01",
 * "status_name": null,
 * "expect_time_human": null,
 * "actual_time_human": null,
 * "merchant_status": "",
 * "merchant_status_name": null
 * },
 * {
 * "id": 296,
 * "tour_no": "4ADH01",
 * "status_name": null,
 * "expect_time_human": null,
 * "actual_time_human": null,
 * "merchant_status": "",
 * "merchant_status_name": null
 * },
 * {
 * "id": 315,
 * "tour_no": "4ADZ01",
 * "status_name": null,
 * "expect_time_human": null,
 * "actual_time_human": null,
 * "merchant_status": "",
 * "merchant_status_name": null
 * },
 * {
 * "id": 348,
 * "tour_no": "4AEP01",
 * "status_name": null,
 * "expect_time_human": null,
 * "actual_time_human": null,
 * "merchant_status": "",
 * "merchant_status_name": null
 * },
 * {
 * "id": 362,
 * "tour_no": "4AEZ01",
 * "status_name": null,
 * "expect_time_human": null,
 * "actual_time_human": null,
 * "merchant_status": "",
 * "merchant_status_name": null
 * },
 * {
 * "id": 364,
 * "tour_no": "4AFB01",
 * "status_name": null,
 * "expect_time_human": null,
 * "actual_time_human": null,
 * "merchant_status": "",
 * "merchant_status_name": null
 * },
 * {
 * "id": 391,
 * "tour_no": "4AGC01",
 * "status_name": null,
 * "expect_time_human": null,
 * "actual_time_human": null,
 * "merchant_status": "",
 * "merchant_status_name": null
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/delay 延迟查询
 * @apiName 延迟查询
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} tour_no 取件线路编号
 * @apiParam {string} begin_date 起始日期
 * @apiParam {string} end_date 终止日期
 * @apiParam {string} driver_name 司机姓名
 * @apiParam {string} line_name 线路名称
 * @apiParam {string} delay_type 延迟类型1-用餐休息2-交通堵塞3-更换行车路线4-其他
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.tour_no 取件线路编号
 * @apiSuccess {string} data.data.line_id 线路ID
 * @apiSuccess {string} data.data.line_name 线路名称
 * @apiSuccess {string} data.data.execution_date 取派日期
 * @apiSuccess {string} data.data.driver_id 司机ID
 * @apiSuccess {string} data.data.driver_name 司机名称
 * @apiSuccess {string} data.data.delay_time 延时时长（秒）
 * @apiSuccess {string} data.data.delay_time_human 延时时长（可读格式）
 * @apiSuccess {string} data.data.delay_type 延迟类型1-用餐休息2-交通堵塞3-更换行车路线4-其他
 * @apiSuccess {string} data.data.delay_type_name 延迟类型名称
 * @apiSuccess {string} data.data.delay_remark
 * @apiSuccess {string} data.data.created_at 延时提交时间
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 22,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "AMS（2）",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 780,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-12 13:48:41",
 * "updated_at": "2020-10-12 13:48:41"
 * },
 * {
 * "id": 21,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "AMS（2）",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 780,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-10 19:07:00",
 * "updated_at": "2020-10-10 19:07:00"
 * },
 * {
 * "id": 20,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "AMS（2）",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 13,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-10 17:33:38",
 * "updated_at": "2020-10-10 17:33:38"
 * },
 * {
 * "id": 19,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "AMS（2）",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 13,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-10 17:23:46",
 * "updated_at": "2020-10-10 17:23:46"
 * },
 * {
 * "id": 18,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "AMS（2）",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 13,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-10 15:00:17",
 * "updated_at": "2020-10-10 15:00:17"
 * },
 * {
 * "id": 17,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "AMS（2）",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 13,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-10 15:00:08",
 * "updated_at": "2020-10-10 15:00:08"
 * },
 * {
 * "id": 16,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "AMS（2）",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 13,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-10 14:59:35",
 * "updated_at": "2020-10-10 14:59:35"
 * },
 * {
 * "id": 15,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "AMS（2）",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 12,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-10 14:58:23",
 * "updated_at": "2020-10-10 14:58:23"
 * },
 * {
 * "id": 14,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "AMS（2）",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 12,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-10 14:57:09",
 * "updated_at": "2020-10-10 14:57:09"
 * },
 * {
 * "id": 13,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "AMS（2）",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 12,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-10 14:55:33",
 * "updated_at": "2020-10-10 14:55:33"
 * },
 * {
 * "id": 12,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "AMS（2）",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 12,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-10 14:55:21",
 * "updated_at": "2020-10-10 14:55:21"
 * },
 * {
 * "id": 11,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "AMS（2）",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 12,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-10 14:53:31",
 * "updated_at": "2020-10-10 14:53:31"
 * },
 * {
 * "id": 10,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 12,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-09 18:45:10",
 * "updated_at": "2020-10-09 18:45:10"
 * },
 * {
 * "id": 9,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 1,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-09 18:44:38",
 * "updated_at": "2020-10-09 18:44:38"
 * },
 * {
 * "id": 8,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 20,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-09 18:44:24",
 * "updated_at": "2020-10-09 18:44:24"
 * },
 * {
 * "id": 7,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 20,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-09 18:44:07",
 * "updated_at": "2020-10-09 18:44:07"
 * },
 * {
 * "id": 6,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 20,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-09 18:43:44",
 * "updated_at": "2020-10-09 18:43:44"
 * },
 * {
 * "id": 5,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 20,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-09 18:43:36",
 * "updated_at": "2020-10-09 18:43:36"
 * },
 * {
 * "id": 4,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 20,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-09 18:43:29",
 * "updated_at": "2020-10-09 18:43:29"
 * },
 * {
 * "id": 3,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 20,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-09 18:43:16",
 * "updated_at": "2020-10-09 18:43:16"
 * },
 * {
 * "id": 2,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 20,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-09 18:36:40",
 * "updated_at": "2020-10-09 18:36:40"
 * },
 * {
 * "id": 1,
 * "company_id": 3,
 * "tour_no": "4ATT01",
 * "line_id": 1072,
 * "line_name": "",
 * "execution_date": "2020-09-22",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "delay_time": 20,
 * "delay_type": 4,
 * "delay_type_name": "其他",
 * "delay_remark": "备注",
 * "created_at": "2020-10-09 18:35:57",
 * "updated_at": "2020-10-09 18:35:57"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/delay?page=1",
 * "last": "http://tms-api.test/api/admin/delay?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/delay",
 * "per_page": 200,
 * "to": 22,
 * "total": 22
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/delay/init 延迟初始化
 * @apiName 延迟初始化
 * @apiGroup 08
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.tour_delay_list
 * @apiSuccess {string} data.tour_delay_list.id
 * @apiSuccess {string} data.tour_delay_list.name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "tour_delay_list": [
 * {
 * "id": 1,
 * "name": "用餐休息"
 * },
 * {
 * "id": 2,
 * "name": "交通堵塞"
 * },
 * {
 * "id": 3,
 * "name": "更换行车路线"
 * },
 * {
 * "id": 4,
 * "name": "其他"
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/driver 司机查询
 * @apiName 司机查询
 * @apiGroup 09
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} email 司机邮箱
 * @apiParam {string} phone 司机电话
 * @apiParam {string} tour_no 取件线路编号（分配司机调用该接口时传）
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id ID
 * @apiSuccess {string} data.data.email 用户邮箱
 * @apiSuccess {string} data.data.fullname 姓名
 * @apiSuccess {string} data.data.gender 性别
 * @apiSuccess {string} data.data.birthday 生日
 * @apiSuccess {string} data.data.phone 手机
 * @apiSuccess {string} data.data.duty_paragraph 税号
 * @apiSuccess {string} data.data.post_code 邮编
 * @apiSuccess {string} data.data.door_no 门牌号
 * @apiSuccess {string} data.data.street 街道
 * @apiSuccess {string} data.data.city 城市
 * @apiSuccess {string} data.data.country 国家
 * @apiSuccess {string} data.data.lisence_number 驾照编号
 * @apiSuccess {string} data.data.lisence_valid_date 有效期
 * @apiSuccess {string} data.data.lisence_type 驾照类型
 * @apiSuccess {string} data.data.lisence_material 驾照材料
 * @apiSuccess {string} data.data.government_material 政府信件
 * @apiSuccess {string} data.data.avatar 头像
 * @apiSuccess {string} data.data.bank_name 银行名称
 * @apiSuccess {string} data.data.iban IBAN
 * @apiSuccess {string} data.data.bic BIC
 * @apiSuccess {string} data.data.crop_type 合作类型（1-雇佣，2-包线）
 * @apiSuccess {string} data.data.is_locked 是否锁定1-正常2-锁定
 * @apiSuccess {string} data.data.is_locked_name 是否锁定名称
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "email": "398352614@qq.com",
 * "last_name": "胡",
 * "first_name": "洋铭",
 * "gender": "男",
 * "birthday": "1993-09-03",
 * "phone": "17570715315",
 * "duty_paragraph": "42302134",
 * "post_code": "4938AC",
 * "door_no": "233",
 * "street": "麓谷大道",
 * "city": "长沙",
 * "country": "中国",
 * "lisence_number": "21303203349",
 * "lisence_valid_date": "2019-12-31",
 * "lisence_type": "C1",
 * "lisence_material": "\"https:\\/\\/www.header_picture.png\"",
 * "government_material": "\"https:\\/\\/www.header1_picture.png\"",
 * "avatar": "https://www.header2_picture.png",
 * "bank_name": "中国银行",
 * "iban": "324912938912481203",
 * "bic": "328491023",
 * "crop_type": 1,
 * "is_locked": 2
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/driver?page=1",
 * "last": "http://tms-api.test/api/admin/driver?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/driver",
 * "per_page": 15,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/driver/{id} 司机详情
 * @apiName 司机详情
 * @apiGroup 09
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} email 司机邮箱
 * @apiParam {string} phone 司机电话
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.email 用户邮箱
 * @apiSuccess {string} data.last_name 姓
 * @apiSuccess {string} data.first_name 名
 * @apiSuccess {string} data.gender 性别
 * @apiSuccess {string} data.birthday 生日
 * @apiSuccess {string} data.phone 手机
 * @apiSuccess {string} data.duty_paragraph 税号
 * @apiSuccess {string} data.post_code 邮编
 * @apiSuccess {string} data.door_no 门牌号
 * @apiSuccess {string} data.street 街道
 * @apiSuccess {string} data.city 城市
 * @apiSuccess {string} data.country 国家
 * @apiSuccess {string} data.lisence_number 驾照编号
 * @apiSuccess {string} data.lisence_valid_date 有效期
 * @apiSuccess {string} data.lisence_type 驾照类型
 * @apiSuccess {string} data.lisence_material 驾照材料
 * @apiSuccess {string} data.government_material 政府信件
 * @apiSuccess {string} data.avatar 头像
 * @apiSuccess {string} data.bank_name 银行名称
 * @apiSuccess {string} data.iban IBAN
 * @apiSuccess {string} data.bic BIC
 * @apiSuccess {string} data.crop_type 合作类型（1-雇佣，2-包线）
 * @apiSuccess {string} data.is_locked 是否锁定1-正常2-锁定
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "email": "398352614@qq.com",
 * "last_name": "胡",
 * "first_name": "洋铭",
 * "gender": "男",
 * "birthday": "1993-09-03",
 * "phone": "17570715315",
 * "duty_paragraph": "42302134",
 * "post_code": "4938AC",
 * "door_no": "233",
 * "street": "麓谷大道",
 * "city": "长沙",
 * "country": "中国",
 * "lisence_number": "21303203349",
 * "lisence_valid_date": "2019-12-31",
 * "lisence_type": "C1",
 * "lisence_material": "\"https:\\/\\/www.header_picture.png\"",
 * "government_material": "\"https:\\/\\/www.header1_picture.png\"",
 * "avatar": "https://www.header2_picture.png",
 * "bank_name": "中国银行",
 * "iban": "324912938912481203",
 * "bic": "328491023",
 * "crop_type": 1,
 * "is_locked": 2
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/driver/register 司机新增
 * @apiName 司机新增
 * @apiGroup 09
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} email 用户邮箱
 * @apiParam {string} password 密码
 * @apiParam {string} confirm_password 重复密码
 * @apiParam {string} gender 性别
 * @apiParam {string} birthday 生日
 * @apiParam {string} phone 手机
 * @apiParam {string} duty_paragraph 税号
 * @apiParam {string} post_code 邮编
 * @apiParam {string} door_no 门牌号
 * @apiParam {string} street 街道
 * @apiParam {string} city 城市
 * @apiParam {string} country 国家
 * @apiParam {string} lisence_number 驾照编号
 * @apiParam {string} lisence_valid_date 有效期
 * @apiParam {string} lisence_type 驾照类型
 * @apiParam {string} lisence_material 驾照材料
 * @apiParam {string} government_material 政府信件
 * @apiParam {string} avatar 头像
 * @apiParam {string} bank_name 银行名称
 * @apiParam {string} iban IBAN
 * @apiParam {string} bic BIC
 * @apiParam {string} crop_type 合作类型（1-雇佣，2-包线）
 * @apiParam {string} fullname 姓名
 * @apiParam {string} type 司机类型
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

/**
 * @api {get} /admin/driver/driver-status 司机新增状态初始化
 * @apiName 司机新增状态初始化
 * @apiGroup 09
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.1 正常
 * @apiSuccess {string} data.2 锁定
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "1": "正常",
 * "2": "锁定"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/driver/crop-type 司机新增合作类型初始化
 * @apiName 司机新增合作类型初始化
 * @apiGroup 09
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.1 雇佣
 * @apiSuccess {string} data.2 包线
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "1": "雇佣",
 * "2": "包线"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/driver/{id}/lock 司机锁定解锁
 * @apiName 司机锁定解锁
 * @apiGroup 09
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 司机ID
 * @apiParam {string} is_locked 锁定状态（1-正常，2锁定）
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
 * @api {put} /admin/driver/{id}/update-password 修改司机密码
 * @apiName 修改司机密码
 * @apiGroup 09
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 司机ID
 * @apiParam {string} new_password 新密码
 * @apiParam {string} confirm_new_password 重复新密码
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": "",
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/driver/{id} 司机信息修改
 * @apiName 司机信息修改
 * @apiGroup 09
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} email 用户邮箱
 * @apiParam {string} last_name 姓
 * @apiParam {string} first_name 名
 * @apiParam {string} gender 性别
 * @apiParam {string} birthday 生日
 * @apiParam {string} phone 手机
 * @apiParam {string} duty_paragraph 税号
 * @apiParam {string} post_code 邮编
 * @apiParam {string} door_no 门牌号
 * @apiParam {string} street 街道
 * @apiParam {string} city 城市
 * @apiParam {string} country 国家
 * @apiParam {string} lisence_number 驾照编号
 * @apiParam {string} lisence_valid_date 有效期
 * @apiParam {string} lisence_type 驾照类型
 * @apiParam {string} lisence_material 驾照材料
 * @apiParam {string} government_material 政府信件
 * @apiParam {string} avatar 头像
 * @apiParam {string} bank_name 银行名称
 * @apiParam {string} iban IBAN
 * @apiParam {string} bic BIC
 * @apiParam {string} crop_type 合作类型1-雇佣2-包线
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": 1,
 * "msg": "successful"
 * }
 */

/**
 * @api {delete} /admin/driver/{id} 司机删除删除
 * @apiName 司机删除删除
 * @apiGroup 09
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 车辆ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {},
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/country 列表查询
 * @apiName 列表查询
 * @apiGroup 10
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.short 简称
 * @apiSuccess {string} data.tel
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.name 国家名称
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 44,
 * "company_id": 27,
 * "short": "DE",
 * "tel": "",
 * "created_at": "2020-01-17 13:31:17",
 * "updated_at": "2020-01-17 13:31:17",
 * "name": "德国"
 * },
 * {
 * "id": 28,
 * "company_id": 27,
 * "short": "Netherlands",
 * "tel": "",
 * "created_at": "2020-01-14 14:36:20",
 * "updated_at": "2020-01-14 14:36:20",
 * "name": "荷兰"
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/country 新增
 * @apiName 新增
 * @apiGroup 10
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} short 简称
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

/**
 * @api {delete} /admin/country/{id} 删除
 * @apiName 删除
 * @apiGroup 10
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code":200,
 * "data":"",
 * "msg":"successful"
 * }
 */

/**
 * @api {post} /admin/country/init 新增初始化
 * @apiName 新增初始化
 * @apiGroup 10
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.country_list
 * @apiSuccess {string} data.country_list.short 简称
 * @apiSuccess {string} data.country_list.tel 区号
 * @apiSuccess {string} data.country_list.pinyin
 * @apiSuccess {string} data.country_list.name 名称
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "country_list": [
 * {
 * "short": "AD",
 * "tel": "376",
 * "pinyin": "adeghg",
 * "name": "安道尔共和国"
 * },
 * {
 * "short": "AE",
 * "tel": "971",
 * "pinyin": "alblhqzg",
 * "name": "阿拉伯联合酋长国"
 * },
 * {
 * "short": "AF",
 * "tel": "93",
 * "pinyin": "afh",
 * "name": "阿富汗"
 * },
 * {
 * "short": "AG",
 * "tel": "1268",
 * "pinyin": "atghbbd",
 * "name": "安提瓜和巴布达"
 * },
 * {
 * "short": "AI",
 * "tel": "1264",
 * "pinyin": "agld",
 * "name": "安圭拉岛"
 * },
 * {
 * "short": "AL",
 * "tel": "355",
 * "pinyin": "aebny",
 * "name": "阿尔巴尼亚"
 * },
 * {
 * "short": "AM",
 * "tel": "374",
 * "pinyin": "amny",
 * "name": "阿美尼亚"
 * },
 * {
 * "short": "",
 * "tel": "685",
 * "pinyin": "xsmy",
 * "name": "西萨摩亚"
 * },
 * {
 * "short": "AO",
 * "tel": "244",
 * "pinyin": "agl",
 * "name": "安哥拉"
 * },
 * {
 * "short": "AR",
 * "tel": "54",
 * "pinyin": "agt",
 * "name": "阿根廷"
 * },
 * {
 * "short": "AT",
 * "tel": "43",
 * "pinyin": "adl",
 * "name": "奥地利"
 * },
 * {
 * "short": "AU",
 * "tel": "61",
 * "pinyin": "adly",
 * "name": "澳大利亚"
 * },
 * {
 * "short": "AZ",
 * "tel": "994",
 * "pinyin": "asbj",
 * "name": "阿塞拜疆"
 * },
 * {
 * "short": "BB",
 * "tel": "1246",
 * "pinyin": "bbds",
 * "name": "巴巴多斯"
 * },
 * {
 * "short": "BD",
 * "tel": "880",
 * "pinyin": "mjlg",
 * "name": "孟加拉国"
 * },
 * {
 * "short": "BE",
 * "tel": "32",
 * "pinyin": "bls",
 * "name": "比利时"
 * },
 * {
 * "short": "BF",
 * "tel": "226",
 * "pinyin": "bjnfs",
 * "name": "布基纳法索"
 * },
 * {
 * "short": "BG",
 * "tel": "359",
 * "pinyin": "bjly",
 * "name": "保加利亚"
 * },
 * {
 * "short": "BH",
 * "tel": "973",
 * "pinyin": "bl",
 * "name": "巴林"
 * },
 * {
 * "short": "BI",
 * "tel": "257",
 * "pinyin": "bld",
 * "name": "布隆迪"
 * },
 * {
 * "short": "BJ",
 * "tel": "229",
 * "pinyin": "bl",
 * "name": "贝宁"
 * },
 * {
 * "short": "BL",
 * "tel": "970",
 * "pinyin": "blst",
 * "name": "巴勒斯坦"
 * },
 * {
 * "short": "BM",
 * "tel": "1441",
 * "pinyin": "bmdqd",
 * "name": "百慕大群岛"
 * },
 * {
 * "short": "BN",
 * "tel": "673",
 * "pinyin": "wl",
 * "name": "文莱"
 * },
 * {
 * "short": "BO",
 * "tel": "591",
 * "pinyin": "blwy",
 * "name": "玻利维亚"
 * },
 * {
 * "short": "BR",
 * "tel": "55",
 * "pinyin": "bx",
 * "name": "巴西"
 * },
 * {
 * "short": "BS",
 * "tel": "1242",
 * "pinyin": "bhm",
 * "name": "巴哈马"
 * },
 * {
 * "short": "BW",
 * "tel": "267",
 * "pinyin": "bcwn",
 * "name": "博茨瓦纳"
 * },
 * {
 * "short": "BY",
 * "tel": "375",
 * "pinyin": "bels",
 * "name": "白俄罗斯"
 * },
 * {
 * "short": "BZ",
 * "tel": "501",
 * "pinyin": "blz",
 * "name": "伯利兹"
 * },
 * {
 * "short": "CA",
 * "tel": "1",
 * "pinyin": "jnd",
 * "name": "加拿大"
 * },
 * {
 * "short": "CF",
 * "tel": "236",
 * "pinyin": "zfghg",
 * "name": "中非共和国"
 * },
 * {
 * "short": "CG",
 * "tel": "242",
 * "pinyin": "gg",
 * "name": "刚果"
 * },
 * {
 * "short": "CH",
 * "tel": "41",
 * "pinyin": "rs",
 * "name": "瑞士"
 * },
 * {
 * "short": "CK",
 * "tel": "682",
 * "pinyin": "kkqd",
 * "name": "库克群岛"
 * },
 * {
 * "short": "CL",
 * "tel": "56",
 * "pinyin": "zl",
 * "name": "智利"
 * },
 * {
 * "short": "CM",
 * "tel": "237",
 * "pinyin": "kml",
 * "name": "喀麦隆"
 * },
 * {
 * "short": "CN",
 * "tel": "86",
 * "pinyin": "zg",
 * "name": "中国"
 * },
 * {
 * "short": "CO",
 * "tel": "57",
 * "pinyin": "glby",
 * "name": "哥伦比亚"
 * },
 * {
 * "short": "CR",
 * "tel": "506",
 * "pinyin": "gsdlj",
 * "name": "哥斯达黎加"
 * },
 * {
 * "short": "CS",
 * "tel": "420",
 * "pinyin": "jk",
 * "name": "捷克"
 * },
 * {
 * "short": "CU",
 * "tel": "53",
 * "pinyin": "gb",
 * "name": "古巴"
 * },
 * {
 * "short": "CY",
 * "tel": "357",
 * "pinyin": "spls",
 * "name": "塞浦路斯"
 * },
 * {
 * "short": "CZ",
 * "tel": "420",
 * "pinyin": "jk",
 * "name": "捷克"
 * },
 * {
 * "short": "DE",
 * "tel": "49",
 * "pinyin": "dg",
 * "name": "德国"
 * },
 * {
 * "short": "DJ",
 * "tel": "253",
 * "pinyin": "jbt",
 * "name": "吉布提"
 * },
 * {
 * "short": "DK",
 * "tel": "45",
 * "pinyin": "dm",
 * "name": "丹麦"
 * },
 * {
 * "short": "DO",
 * "tel": "1890",
 * "pinyin": "dmnjghg",
 * "name": "多米尼加共和国"
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/batch-exception/{id}/deal 异常处理
 * @apiName 异常处理
 * @apiGroup 11
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 1
 * @apiParam {string} deal_remark 处理内容
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

/**
 * @api {get} /admin/batch-exception 列表查询
 * @apiName 列表查询
 * @apiGroup 11
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} status 状态1-未处理2-已处理
 * @apiParam {string} keyword 关键字查询
 * @apiParam {string} begin_date 开始时间
 * @apiParam {string} end_date 结束时间
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id ID
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.batch_no 站点编号
 * @apiSuccess {string} data.data.batch_exception_no 异常编号
 * @apiSuccess {string} data.data.stage 异常阶段1-在途异常2-装货异常
 * @apiSuccess {string} data.data.stage_name 异常阶段名称
 * @apiSuccess {string} data.data.status 状态1-未处理2-已处理
 * @apiSuccess {string} data.data.status_name 状态名称
 * @apiSuccess {string} data.data.receiver 收货方
 * @apiSuccess {string} data.data.source 来源
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.data.driver_name 司机名称
 * @apiSuccess {string} data.data.type 异常类型（在途异常：1道路2车辆3其他，装货异常1少货2货损3其他）
 * @apiSuccess {string} data.data.type_name 异常类型名称
 * @apiSuccess {string} data.data.remark 异常内容
 * @apiSuccess {string} data.data.deal_name 处理名称
 * @apiSuccess {string} data.data.deal_time 处理时间
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "company_id": 1,
 * "batch_no": "BATCH00010000000000074",
 * "batch_exception_no": "BE00010000000000002",
 * "stage": 1,
 * "stage_name": "未处理",
 * "status": 1,
 * "status_name": "未取派",
 * "receiver": "龙放耀",
 * "source": "司机来源",
 * "created_at": "2020-01-03 10:58:52",
 * "driver_name": "ZhangqiHuo",
 * "type": 1,
 * "type_name": "道路",
 * "remark": "1212121",
 * "deal_name": "",
 * "deal_time": null
 * },
 * {
 * "id": 2,
 * "company_id": 1,
 * "batch_no": "BATCH00010000000000079",
 * "batch_exception_no": "BE00010000000000003",
 * "stage": 1,
 * "stage_name": "未处理",
 * "status": 1,
 * "status_name": "未取派",
 * "receiver": "龙放耀",
 * "source": "司机来源",
 * "created_at": "2020-01-03 11:13:24",
 * "driver_name": "胡洋铭",
 * "type": 1,
 * "type_name": "道路",
 * "remark": "1212121",
 * "deal_name": "",
 * "deal_time": null
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/batch-exception?page=1",
 * "last": "http://tms-api.test/api/admin/batch-exception?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/batch-exception",
 * "per_page": 10,
 * "to": 2,
 * "total": 2
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/batch-exception/{id} 获取详情
 * @apiName 获取详情
 * @apiGroup 11
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 1
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id ID
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.batch_exception_no 异常编号
 * @apiSuccess {string} data.batch_no 站点编号
 * @apiSuccess {string} data.receiver 收获方
 * @apiSuccess {string} data.status 状态1-未处理2-已处理
 * @apiSuccess {string} data.source 来源
 * @apiSuccess {string} data.stage 异常阶段1-在途异常2-装货异常
 * @apiSuccess {string} data.type 异常类型（在途异常：1道路2车辆3其他，装货异常1少货2货损3其他）
 * @apiSuccess {string} data.remark 异常内容
 * @apiSuccess {string} data.picture 异常图片
 * @apiSuccess {string} data.deal_remark 处理内容
 * @apiSuccess {string} data.deal_id 处理人ID
 * @apiSuccess {string} data.deal_name 处理人姓名
 * @apiSuccess {string} data.deal_time 处理事件
 * @apiSuccess {string} data.driver_id 司机ID
 * @apiSuccess {string} data.driver_name 司机姓名(创建人姓名)
 * @apiSuccess {string} data.created_at 创建事件
 * @apiSuccess {string} data.updated_at 修改事件
 * @apiSuccess {string} data.status_name 状态名称
 * @apiSuccess {string} data.stage_name 异常阶段名称
 * @apiSuccess {string} data.type_name 异常类型名称
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1,
 * "company_id": 1,
 * "batch_exception_no": "BE00010000000000002",
 * "batch_no": "BATCH00010000000000074",
 * "receiver": "龙放耀",
 * "status": 1,
 * "source": "司机来源",
 * "stage": 1,
 * "type": 1,
 * "remark": "1212121",
 * "picture": "http://www.test.com/1.png",
 * "deal_remark": "",
 * "deal_id": null,
 * "deal_name": "",
 * "deal_time": null,
 * "driver_id": 1,
 * "driver_name": "ZhangqiHuo",
 * "created_at": "2020-01-03 10:58:52",
 * "updated_at": "2020-01-03 10:58:52",
 * "status_name": "未取派",
 * "stage_name": "在途异常",
 * "type_name": "道路"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/upload/image-dir 图片目录查询
 * @apiName 图片目录查询
 * @apiGroup 12
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 目录ID
 * @apiSuccess {string} data.name 目录名称
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": "driver",
 * "name": "司机图片目录"
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/upload/file-dir 文件目录查询
 * @apiName 文件目录查询
 * @apiGroup 12
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 目录ID
 * @apiSuccess {string} data.name 目录名称
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": "driver",
 * "name": "司机文件目录"
 * },
 * {
 * "id": "car",
 * "name": "车辆文件目录"
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/upload/image 图片上传
 * @apiName 图片上传
 * @apiGroup 12
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} image 图片（必须是jpeg,bmp,png类型）
 * @apiParam {string} dir 目录（driver)
 */

/**
 * @api {post} /admin/upload/file 文件上传
 * @apiName 文件上传
 * @apiGroup 12
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} file 文件（必须是txt,excel,word,jpeg,bmp,png类型）
 * @apiParam {string} dir 目录（driver，car）
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.name 文件名称
 * @apiSuccess {string} data.path 文件路径
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "name": "202001040353415e100c45382f8.xlsx",
 * "path": "tms-api.test/storage/admin/file/1\\driver\\202001040353415e100c45382f8.xlsx"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/address/{id} 地址详情
 * @apiName 地址详情
 * @apiGroup 13
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 地址ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.place_fullname 收件人姓名
 * @apiSuccess {string} data.place_phone 收件人电话
 * @apiSuccess {string} data.place_country 收件人国家
 * @apiSuccess {string} data.place_post_code 收件人邮编
 * @apiSuccess {string} data.place_house_number 收件人门牌号
 * @apiSuccess {string} data.place_city 收件人城市
 * @apiSuccess {string} data.place_street 收件人街道
 * @apiSuccess {string} data.place_address 收件人详细地址
 * @apiSuccess {string} data.place_lon 经度
 * @apiSuccess {string} data.place_lat 纬度
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.merchant 货主名称
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1,
 * "company_id": 1,
 * "receiver": "胡洋铭",
 * "receiver_phone": "17570715315",
 * "receiver_country": "中国",
 * "receiver_post_code": "43141",
 * "receiver_house_number": "807",
 * "receiver_city": "长沙",
 * "receiver_street": "麓谷",
 * "receiver_address": null,
 * "receiver_lon": "5.4740944",
 * "receiver_lat": "51.4384193",
 * "created_at": "2020-01-10 15:57:34",
 * "updated_at": "2020-01-10 15:59:51"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/address 地址查询
 * @apiName 地址查询
 * @apiGroup 13
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} receiver 收件人姓名
 * @apiParam {string} receiver_post_code 收件人邮编
 * @apiParam {string} merchant_id 货主ID，拉接口
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 地址ID
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.place_fullname 收件人姓名
 * @apiSuccess {string} data.data.place_phone 收件人电话
 * @apiSuccess {string} data.data.place_country 收件人国家
 * @apiSuccess {string} data.data.place_post_code 收件人邮编
 * @apiSuccess {string} data.data.place_house_number 收件人门牌号
 * @apiSuccess {string} data.data.place_city 收件人城市
 * @apiSuccess {string} data.data.place_street 收件人街道
 * @apiSuccess {string} data.data.place_address 收件人详细地址
 * @apiSuccess {string} data.data.place_lon 经度
 * @apiSuccess {string} data.data.place_lat 纬度
 * @apiSuccess {string} data.meta.merchant 货主名
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "company_id": 1,
 * "receiver": "胡洋铭",
 * "receiver_phone": "17570715315",
 * "receiver_country": "中国",
 * "receiver_post_code": "43141",
 * "receiver_house_number": "807",
 * "receiver_city": "长沙",
 * "receiver_street": "麓谷",
 * "receiver_address": null,
 * "lon": "5.4740944",
 * "lat": "51.4384193",
 * "created_at": "2020-01-10 15:57:34",
 * "updated_at": "2020-01-10 15:59:51"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/receiver-address?page=1",
 * "last": "http://tms-api.test/api/admin/receiver-address?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/receiver-address",
 * "per_page": 10,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/address 地址新增
 * @apiName 地址新增
 * @apiGroup 13
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} place_fullname 收件人姓名
 * @apiParam {string} place_phone 收件人电话
 * @apiParam {string} place_country 收件人国家
 * @apiParam {string} place_post_code 收件人邮编
 * @apiParam {string} place_house_number 收件人门牌号
 * @apiParam {string} place_city 收件人城市
 * @apiParam {string} place_street 收件人街道
 * @apiParam {string} place_address 收件人详细地址
 * @apiParam {string} place_lon 经度
 * @apiParam {string} place_lat 纬度
 * @apiParam {string} merchant_id 货主ID
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

/**
 * @api {delete} /admin/address/{id} 地址删除
 * @apiName 地址删除
 * @apiGroup 13
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} Id 地址ID
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

/**
 * @api {put} /admin/address/{id} 地址修改
 * @apiName 地址修改
 * @apiGroup 13
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 收件人ID
 * @apiParam {string} place_fullname
 * @apiParam {string} place_phone
 * @apiParam {string} place_country
 * @apiParam {string} place_post_code
 * @apiParam {string} place_house_number
 * @apiParam {string} place_city
 * @apiParam {string} place_street
 * @apiParam {string} place_address
 * @apiParam {string} place_lon
 * @apiParam {string} place_lat
 * @apiParam {string} merchant_id
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

/**
 * @api {get} /admin/address/excel 地址导出
 * @apiName 地址导出
 * @apiGroup 13
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list id列表，多个用逗号分隔
 */

/**
 * @api {get} /admin/address/excel-template 获取地址模板
 * @apiName 获取地址模板
 * @apiGroup 13
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.name
 * @apiSuccess {string} data.path
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "name": "9c5e650f3b187fd4ff5e16ec665a8d76.xlsx",
 * "path": "tms-api.test/storage/admin/excel/3/addressExcelExport/9c5e650f3b187fd4ff5e16ec665a8d76.xlsx"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/address/excel 地址导入
 * @apiName 地址导入
 * @apiGroup 13
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} file 表格文件
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.place_fullname
 * @apiSuccess {string} data.place_phone
 * @apiSuccess {string} data.place_country
 * @apiSuccess {string} data.place_province
 * @apiSuccess {string} data.place_post_code
 * @apiSuccess {string} data.place_house_number
 * @apiSuccess {string} data.place_city
 * @apiSuccess {string} data.place_district
 * @apiSuccess {string} data.place_street
 * @apiSuccess {string} data.place_address
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "place_fullname": "1",
 * "place_phone": "1XXXXXXXXXX",
 * "place_country": "CN",
 * "place_province": "湖南省",
 * "place_post_code": "41X000",
 * "place_house_number": "27",
 * "place_city": "长沙市",
 * "place_district": "岳麓区",
 * "place_street": "文轩路",
 * "place_address": "麓谷企业广场C8栋808"
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/address/excel-check 地址检查
 * @apiName 地址检查
 * @apiGroup 13
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} list 数据串
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.status 状态1通过2不通过
 * @apiSuccess {string} data.error 错误
 * @apiSuccess {string} data.error.log 总体错误
 * @apiSuccess {string} data.error.place_fullname 字段
 * @apiSuccess {string} data.data 补值
 * @apiSuccess {string} data.data.place_fullname
 * @apiSuccess {string} data.data.place_phone
 * @apiSuccess {string} data.data.place_country
 * @apiSuccess {string} data.data.place_province
 * @apiSuccess {string} data.data.place_post_code
 * @apiSuccess {string} data.data.place_house_number
 * @apiSuccess {string} data.data.place_city
 * @apiSuccess {string} data.data.place_district
 * @apiSuccess {string} data.data.place_street
 * @apiSuccess {string} data.data.place_address
 * @apiSuccess {string} data.data.place_lon 经度
 * @apiSuccess {string} data.data.place_lat 纬度
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "status": 2,
 * "error": {
 * "log": "国家，城市，街道，门牌号或邮编不正确，请仔细检查输入或联系客服",
 * "place_fullname": "收件人姓名 必须是个整数"
 * },
 * "data": {
 * "place_fullname": "asd",
 * "place_phone": "123",
 * "place_country": "CN",
 * "place_province": "湖南省",
 * "place_post_code": "41X000",
 * "place_house_number": "27",
 * "place_city": "长沙市",
 * "place_district": "岳麓区",
 * "place_street": "文轩路",
 * "place_address": "麓谷企业广场C8栋808",
 * "place_lon": "4",
 * "place_lat": "2"
 * }
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/address/list 地址批量新增
 * @apiName 地址批量新增
 * @apiGroup 13
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} list 地址列表
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {},
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/statistics 总体统计
 * @apiName 总体统计
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.preparing_order 待取派订单
 * @apiSuccess {string} data.taking_order 取派中订单
 * @apiSuccess {string} data.signed_order 已完成订单
 * @apiSuccess {string} data.cancel_order 取消取派订单
 * @apiSuccess {string} data.exception_order 异常订单
 * @apiSuccess {string} data.total_order 订单总数
 * @apiSuccess {string} data.no_out_order 不可出库订单
 * @apiSuccess {string} data.tour 取件线路
 * @apiSuccess {string} data.total_driver 车辆总数
 * @apiSuccess {string} data.working_driver 占用中车辆：
 * @apiSuccess {string} data.free_driver 空闲中车辆
 * @apiSuccess {string} data.total_car 司机总数
 * @apiSuccess {string} data.working_car 出勤中司机
 * @apiSuccess {string} data.free_car 休息中司机
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "preparing_order": 4,
 * "taking_order": 0,
 * "signed_order": 4,
 * "cancel_order": 6,
 * "exception_order": 0,
 * "total_order": 14,
 * "no_out_order": 4,
 * "tour": 5,
 * "total_driver": 28,
 * "working_driver": 1,
 * "free_driver": 27,
 * "total_car": 11,
 * "working_car": 1,
 * "free_car": 10
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/statistics/this-week 本周统计
 * @apiName 本周统计
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.merchant_name 货主名
 * @apiSuccess {string} data.graph
 * @apiSuccess {string} data.graph.date 日期
 * @apiSuccess {string} data.graph.order 订单
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "merchant_name": "test",
 * "graph": [
 * {
 * "date": "2020-09-14",
 * "order": 0
 * },
 * {
 * "date": "2020-09-15",
 * "order": 0
 * },
 * {
 * "date": "2020-09-16",
 * "order": 0
 * }
 * ]
 * },
 * {
 * "merchant_name": "test-test",
 * "graph": [
 * {
 * "date": "2020-09-11",
 * "order": 0
 * },
 * {
 * "date": "2020-09-12",
 * "order": 1
 * },
 * {
 * "date": "2020-09-13",
 * "order": 0
 * }
 * ]
 * },
 * {
 * "merchant_name": "货主122222333",
 * "graph": [
 * {
 * "date": "2020-09-08",
 * "order": 4
 * },
 * {
 * "date": "2020-09-09",
 * "order": 16
 * },
 * {
 * "date": "2020-09-10",
 * "order": 17
 * }
 * ]
 * },
 * {
 * "merchant_name": "货主asd",
 * "graph": [
 * {
 * "date": "2020-09-05",
 * "order": 29
 * },
 * {
 * "date": "2020-09-06",
 * "order": 0
 * },
 * {
 * "date": "2020-09-07",
 * "order": 0
 * }
 * ]
 * },
 * {
 * "merchant_name": "测试",
 * "graph": [
 * {
 * "date": "2020-09-02",
 * "order": 0
 * },
 * {
 * "date": "2020-09-03",
 * "order": 0
 * },
 * {
 * "date": "2020-09-04",
 * "order": 0
 * }
 * ]
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/home/last-week 上周统计
 * @apiName 上周统计
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.date 日期
 * @apiSuccess {string} data.ordercount 已完成订单量
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "date": "2020-01-05",
 * "ordercount": 0
 * },
 * {
 * "date": "2020-01-04",
 * "ordercount": 0
 * },
 * {
 * "date": "2020-01-03",
 * "ordercount": 0
 * },
 * {
 * "date": "2020-01-02",
 * "ordercount": 0
 * },
 * {
 * "date": "2020-01-01",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-31",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-30",
 * "ordercount": 0
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/home/this-month 本月统计
 * @apiName 本月统计
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.date 日期
 * @apiSuccess {string} data.ordercount 已完成订单量
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "date": "2020-01-11",
 * "ordercount": 0
 * },
 * {
 * "date": "2020-01-10",
 * "ordercount": 0
 * },
 * {
 * "date": "2020-01-09",
 * "ordercount": 0
 * },
 * {
 * "date": "2020-01-08",
 * "ordercount": 0
 * },
 * {
 * "date": "2020-01-07",
 * "ordercount": 0
 * },
 * {
 * "date": "2020-01-06",
 * "ordercount": 0
 * },
 * {
 * "date": "2020-01-05",
 * "ordercount": 0
 * },
 * {
 * "date": "2020-01-04",
 * "ordercount": 0
 * },
 * {
 * "date": "2020-01-03",
 * "ordercount": 0
 * },
 * {
 * "date": "2020-01-02",
 * "ordercount": 0
 * },
 * {
 * "date": "2020-01-01",
 * "ordercount": 0
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/home/last-month 上月统计
 * @apiName 上月统计
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.date 日期
 * @apiSuccess {string} data.ordercount 已完成订单量
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "date": "2019-12-31",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-30",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-29",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-28",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-27",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-26",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-25",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-24",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-23",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-22",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-21",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-20",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-19",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-18",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-17",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-16",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-15",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-14",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-13",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-12",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-11",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-10",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-09",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-08",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-07",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-06",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-05",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-04",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-03",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-02",
 * "ordercount": 0
 * },
 * {
 * "date": "2019-12-01",
 * "ordercount": 0
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/home/period 订单量查询
 * @apiName 订单量查询
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} begin_date 开始日期
 * @apiParam {string} end_date 结束日期
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.date 日期
 * @apiSuccess {string} data.ordercount 已完成订单量
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "date": "2020-01-01",
 * "ordercount": 0
 * },
 * {
 * "date": "2020-01-02",
 * "ordercount": 0
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/statistics/last-week 上周统计
 * @apiName 上周统计
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.merchant_name 货主名
 * @apiSuccess {string} data.graph
 * @apiSuccess {string} data.graph.date 日期
 * @apiSuccess {string} data.graph.order 订单
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "merchant_name": "test",
 * "graph": [
 * {
 * "date": "2020-09-14",
 * "order": 0
 * },
 * {
 * "date": "2020-09-15",
 * "order": 0
 * },
 * {
 * "date": "2020-09-16",
 * "order": 0
 * }
 * ]
 * },
 * {
 * "merchant_name": "test-test",
 * "graph": [
 * {
 * "date": "2020-09-11",
 * "order": 0
 * },
 * {
 * "date": "2020-09-12",
 * "order": 1
 * },
 * {
 * "date": "2020-09-13",
 * "order": 0
 * }
 * ]
 * },
 * {
 * "merchant_name": "货主122222333",
 * "graph": [
 * {
 * "date": "2020-09-08",
 * "order": 4
 * },
 * {
 * "date": "2020-09-09",
 * "order": 16
 * },
 * {
 * "date": "2020-09-10",
 * "order": 17
 * }
 * ]
 * },
 * {
 * "merchant_name": "货主asd",
 * "graph": [
 * {
 * "date": "2020-09-05",
 * "order": 29
 * },
 * {
 * "date": "2020-09-06",
 * "order": 0
 * },
 * {
 * "date": "2020-09-07",
 * "order": 0
 * }
 * ]
 * },
 * {
 * "merchant_name": "测试",
 * "graph": [
 * {
 * "date": "2020-09-02",
 * "order": 0
 * },
 * {
 * "date": "2020-09-03",
 * "order": 0
 * },
 * {
 * "date": "2020-09-04",
 * "order": 0
 * }
 * ]
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/statistics/last-mouth 上月统计
 * @apiName 上月统计
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.merchant_name 货主名
 * @apiSuccess {string} data.graph
 * @apiSuccess {string} data.graph.date 日期
 * @apiSuccess {string} data.graph.order 订单
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "merchant_name": "test",
 * "graph": [
 * {
 * "date": "2020-09-14",
 * "order": 0
 * },
 * {
 * "date": "2020-09-15",
 * "order": 0
 * },
 * {
 * "date": "2020-09-16",
 * "order": 0
 * }
 * ]
 * },
 * {
 * "merchant_name": "test-test",
 * "graph": [
 * {
 * "date": "2020-09-11",
 * "order": 0
 * },
 * {
 * "date": "2020-09-12",
 * "order": 1
 * },
 * {
 * "date": "2020-09-13",
 * "order": 0
 * }
 * ]
 * },
 * {
 * "merchant_name": "货主122222333",
 * "graph": [
 * {
 * "date": "2020-09-08",
 * "order": 4
 * },
 * {
 * "date": "2020-09-09",
 * "order": 16
 * },
 * {
 * "date": "2020-09-10",
 * "order": 17
 * }
 * ]
 * },
 * {
 * "merchant_name": "货主asd",
 * "graph": [
 * {
 * "date": "2020-09-05",
 * "order": 29
 * },
 * {
 * "date": "2020-09-06",
 * "order": 0
 * },
 * {
 * "date": "2020-09-07",
 * "order": 0
 * }
 * ]
 * },
 * {
 * "merchant_name": "测试",
 * "graph": [
 * {
 * "date": "2020-09-02",
 * "order": 0
 * },
 * {
 * "date": "2020-09-03",
 * "order": 0
 * },
 * {
 * "date": "2020-09-04",
 * "order": 0
 * }
 * ]
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/statistics/this-mouth 本月统计
 * @apiName 本月统计
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.merchant_name 货主名
 * @apiSuccess {string} data.graph
 * @apiSuccess {string} data.graph.date 日期
 * @apiSuccess {string} data.graph.order 订单
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "merchant_name": "test",
 * "graph": [
 * {
 * "date": "2020-09-14",
 * "order": 0
 * },
 * {
 * "date": "2020-09-15",
 * "order": 0
 * },
 * {
 * "date": "2020-09-16",
 * "order": 0
 * }
 * ]
 * },
 * {
 * "merchant_name": "test-test",
 * "graph": [
 * {
 * "date": "2020-09-11",
 * "order": 0
 * },
 * {
 * "date": "2020-09-12",
 * "order": 1
 * },
 * {
 * "date": "2020-09-13",
 * "order": 0
 * }
 * ]
 * },
 * {
 * "merchant_name": "货主122222333",
 * "graph": [
 * {
 * "date": "2020-09-08",
 * "order": 4
 * },
 * {
 * "date": "2020-09-09",
 * "order": 16
 * },
 * {
 * "date": "2020-09-10",
 * "order": 17
 * }
 * ]
 * },
 * {
 * "merchant_name": "货主asd",
 * "graph": [
 * {
 * "date": "2020-09-05",
 * "order": 29
 * },
 * {
 * "date": "2020-09-06",
 * "order": 0
 * },
 * {
 * "date": "2020-09-07",
 * "order": 0
 * }
 * ]
 * },
 * {
 * "merchant_name": "测试",
 * "graph": [
 * {
 * "date": "2020-09-02",
 * "order": 0
 * },
 * {
 * "date": "2020-09-03",
 * "order": 0
 * },
 * {
 * "date": "2020-09-04",
 * "order": 0
 * }
 * ]
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/statistics/merchant 货主分统计
 * @apiName 货主分统计
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.merchant_name 货主名
 * @apiSuccess {string} data.total_order 订单总数
 * @apiSuccess {string} data.pickup_order 取件订单数
 * @apiSuccess {string} data.pie_order 派件订单数
 * @apiSuccess {string} data.cancel_order 取消取派数
 * @apiSuccess {string} data.additional_package 顺带包裹数
 * @apiSuccess {string} data.total_recharge 现金充值合计金额
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.pickup_pie_order 取派订单数
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "merchant_name": "test",
 * "total_order": 0,
 * "pickup_order": 0,
 * "pie_order": 0,
 * "pickup_pie_order": 0,
 * "cancel_order": 0,
 * "additional_package": 197,
 * "total_recharge": 0
 * },
 * {
 * "merchant_name": "test-test",
 * "total_order": 0,
 * "pickup_order": 0,
 * "pie_order": 0,
 * "cancel_order": 0,
 * "additional_package": 197,
 * "total_recharge": 0
 * },
 * {
 * "merchant_name": "货主122222333",
 * "total_order": 0,
 * "pickup_order": 0,
 * "pie_order": 0,
 * "cancel_order": 0,
 * "additional_package": 197,
 * "total_recharge": "5595.01"
 * },
 * {
 * "merchant_name": "货主asd",
 * "total_order": 0,
 * "pickup_order": 0,
 * "pie_order": 0,
 * "cancel_order": 0,
 * "additional_package": 197,
 * "total_recharge": 0
 * },
 * {
 * "merchant_name": "测试",
 * "total_order": 0,
 * "pickup_order": 0,
 * "pie_order": 0,
 * "cancel_order": 0,
 * "additional_package": 197,
 * "total_recharge": 0
 * },
 * {
 * "merchant_name": "合计",
 * "total_order": 0,
 * "pickup_order": 0,
 * "pie_order": 0,
 * "cancel_order": 0,
 * "additional_package": 985,
 * "total_recharge": 5595.01
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/statistics/merchant-total 货主总统计
 * @apiName 货主总统计
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.merchant_name 货主名
 * @apiSuccess {string} data.order 订单
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "merchant_name": "test-test",
 * "order": 1
 * },
 * {
 * "merchant_name": "货主122222333",
 * "order": 210
 * },
 * {
 * "merchant_name": "货主asd",
 * "order": 233
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/statistics/period 本月统计
 * @apiName 本月统计
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} begin_date 起始时间
 * @apiParam {string} end_date 终止时间
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.merchant_name 货主名
 * @apiSuccess {string} data.graph
 * @apiSuccess {string} data.graph.date 日期
 * @apiSuccess {string} data.graph.order 订单
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "merchant_name": "test",
 * "graph": [
 * {
 * "date": "2020-09-14",
 * "order": 0
 * },
 * {
 * "date": "2020-09-15",
 * "order": 0
 * },
 * {
 * "date": "2020-09-16",
 * "order": 0
 * }
 * ]
 * },
 * {
 * "merchant_name": "test-test",
 * "graph": [
 * {
 * "date": "2020-09-11",
 * "order": 0
 * },
 * {
 * "date": "2020-09-12",
 * "order": 1
 * },
 * {
 * "date": "2020-09-13",
 * "order": 0
 * }
 * ]
 * },
 * {
 * "merchant_name": "货主122222333",
 * "graph": [
 * {
 * "date": "2020-09-08",
 * "order": 4
 * },
 * {
 * "date": "2020-09-09",
 * "order": 16
 * },
 * {
 * "date": "2020-09-10",
 * "order": 17
 * }
 * ]
 * },
 * {
 * "merchant_name": "货主asd",
 * "graph": [
 * {
 * "date": "2020-09-05",
 * "order": 29
 * },
 * {
 * "date": "2020-09-06",
 * "order": 0
 * },
 * {
 * "date": "2020-09-07",
 * "order": 0
 * }
 * ]
 * },
 * {
 * "merchant_name": "测试",
 * "graph": [
 * {
 * "date": "2020-09-02",
 * "order": 0
 * },
 * {
 * "date": "2020-09-03",
 * "order": 0
 * },
 * {
 * "date": "2020-09-04",
 * "order": 0
 * }
 * ]
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/statistics/today-overview 今日概览
 * @apiName 今日概览
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.tracking_order_count 待取派-运单数
 * @apiSuccess {string} data.no_out_tracking_order_count 待取派-不可出库运单数
 * @apiSuccess {string} data.batch_count 待取派-站点数
 * @apiSuccess {string} data.tour_count 待取派-线路数
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "tracking_order_count": 0,
 * "no_out_tracking_order_count": 0,
 * "batch_count": 0,
 * "tour_count": 0
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/statistics/result-overview 任务结果概览
 * @apiName 任务结果概览
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} execution_date 日期(为空,则表示所有。今日，昨日传日期)
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.tracking_order_success_count 运单-成功数量
 * @apiSuccess {string} data.tracking_order_cancel_count 运单-失败数量
 * @apiSuccess {string} data.batch_success_count 站点-成功数量
 * @apiSuccess {string} data.batch_cancel_count 站点-失败数量
 * @apiSuccess {string} data.package_success_count 包裹-成功数量
 * @apiSuccess {string} data.package_cancel_count 包裹-失败数量
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "tracking_order_success_count": 24,
 * "tracking_order_cancel_count": 3,
 * "batch_success_count": 5,
 * "batch_cancel_count": 2,
 * "package_success_count": 23,
 * "package_cancel_count": 3
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/statistics/order-analysis 订单分析
 * @apiName 订单分析
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.today_order_count 当日订单数
 * @apiSuccess {string} data.order_count 订单数量
 * @apiSuccess {string} data.order_success_count 订单成功数量
 * @apiSuccess {string} data.order_cancel_count 订单失败数量
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "today_order_count": 0,
 * "order_count": 1033,
 * "order_success_count": 152,
 * "order_cancel_count": 118
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/statistics/short-cut 获取快捷方式列表
 * @apiName 获取快捷方式列表
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 标识,和功能标识一致
 * @apiSuccess {string} data.name 名称
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": "order.store",
 * "name": "下单"
 * },
 * {
 * "id": "order.index",
 * "name": "订单"
 * },
 * {
 * "id": "line.post-code-index",
 * "name": "线路规划"
 * },
 * {
 * "id": "tracking-order.index",
 * "name": "运单"
 * },
 * {
 * "id": "batch.index",
 * "name": "站点"
 * },
 * {
 * "id": "tour.index",
 * "name": "线路任务"
 * },
 * {
 * "id": "tour.intelligent-scheduling",
 * "name": "智能调度"
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/statistics/reservation 预约任务
 * @apiName 预约任务
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.date 预约日期
 * @apiSuccess {string} data.tour 任务数量
 * @apiSuccess {string} data.batch 站点数量
 * @apiSuccess {string} data.tracking_order 运单数量
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "date": "2021-03-27",
 * "tour": 1,
 * "batch": 1,
 * "tracking_order": 1
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/statistics 总体统计
 * @apiName 总体统计
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.preparing_order 待取派订单
 * @apiSuccess {string} data.preparing_batch 待取派站点
 * @apiSuccess {string} data.preparing_tour 待取派任务
 * @apiSuccess {string} data.tour 任务
 * @apiSuccess {string} data.taking_tour 进行中任务
 * @apiSuccess {string} data.done_tour 已完成任务
 * @apiSuccess {string} data.exception_batch 异常站点
 * @apiSuccess {string} data.exception_tracking_order 异常运单
 * @apiSuccess {string} data.tracking_order 运单
 * @apiSuccess {string} data.exception_package 异常包裹
 * @apiSuccess {string} data.package 包裹
 * @apiSuccess {string} data.order 订单
 * @apiSuccess {string} data.pickup_order 取件订单
 * @apiSuccess {string} data.pie_order 派件订单
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.pickup_pie_order 取派订单
 * @apiSuccess {string} data.batch 站点
 * @apiSuccess {string} data.month_order 本月累计完成订单
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "preparing_order": 0,
 * "preparing_batch": 0,
 * "preparing_tour": 0,
 * "tour": 0,
 * "taking_tour": 0,
 * "done_tour": 0,
 * "exception_batch": 0,
 * "batch": 0,
 * "exception_tracking_order": 0,
 * "tracking_order": 0,
 * "exception_package": 0,
 * "package": 0,
 * "rder": 0,
 * "pickup_order": 0,
 * "pie_order": 0,
 * "pickup_pie_order": 0
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/statistics/flow 获取流程图
 * @apiName 获取流程图
 * @apiGroup 14
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 后端路由别名
 * @apiSuccess {string} data.name 名称
 * @apiSuccess {string} data.permission 是否拥有权限1-有2-没有
 * @apiSuccess {string} data.route 前端路由
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": "order.store",
 * "name": "手动录单",
 * "permission": 1,
 * "route": "orderAdd"
 * },
 * {
 * "id": "merchant-api.index",
 * "name": "API对接",
 * "permission": 1,
 * "route": ""
 * },
 * {
 * "id": "order.index",
 * "name": "订单管理",
 * "permission": 1,
 * "route": "OrderList"
 * },
 * {
 * "id": "package.index",
 * "name": "包裹管理",
 * "permission": 1,
 * "route": ""
 * },
 * {
 * "id": "material.index",
 * "name": "材料管理",
 * "permission": 1,
 * "route": ""
 * },
 * {
 * "id": "tracking-order.index",
 * "name": "运单管理",
 * "permission": 1,
 * "route": "WaybillManagement"
 * },
 * {
 * "id": "batch.index",
 * "name": "站点管理",
 * "permission": 1,
 * "route": "stationList"
 * },
 * {
 * "id": "tour.index",
 * "name": "任务管理",
 * "permission": 1,
 * "route": "lineTask"
 * },
 * {
 * "id": "tour.intelligent-scheduling",
 * "name": "智能调度",
 * "permission": 1,
 * "route": "intelligentDispatch"
 * },
 * {
 * "id": "driver.index",
 * "name": "司机管理",
 * "permission": 1,
 * "route": ""
 * },
 * {
 * "id": "car.index",
 * "name": "车辆管理",
 * "permission": 1,
 * "route": ""
 * },
 * {
 * "id": "car-management.index",
 * "name": "智能管车",
 * "permission": 1,
 * "route": ""
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/employees 员工查询
 * @apiName 员工查询
 * @apiGroup 16
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} email 账号
 * @apiParam {string} fullname 姓名
 * @apiParam {string} warehouse_id 网点ID
 * @apiParam {string} role_id 权限组Id
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 员工ID
 * @apiSuccess {string} data.data.email 邮箱
 * @apiSuccess {string} data.data.fullname 姓名
 * @apiSuccess {string} data.data.username 用户名
 * @apiSuccess {string} data.data.phone 电话
 * @apiSuccess {string} data.data.remark 备注
 * @apiSuccess {string} data.data.institution 机构ID
 * @apiSuccess {string} data.data.forbid_login 禁止登录标志
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccess {string} role_id 权限组ID
 * @apiSuccess {string} role_id_name 权限组名
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 3,
 * "email": "tms@nle-tech.com",
 * "fullname": "TMS测试",
 * "username": "tms@nle-tech.com",
 * "phone": "13025935188",
 * "remark": "222121",
 * "forbid_login": false,
 * "role_id": 1,
 * "role_id_name": "管理员组",
 * "is_admin": 1,
 * "created_at": "2020-03-13T04:00:10.000000Z",
 * "updated_at": "2021-04-20T04:18:38.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 5,
 * "email": "827193288@qq.com",
 * "fullname": "员工3",
 * "username": "827193288@qq.com",
 * "phone": "13025935188",
 * "remark": "12121",
 * "forbid_login": false,
 * "role_id": 10,
 * "role_id_name": "客服",
 * "is_admin": 2,
 * "created_at": "2020-03-13T06:28:50.000000Z",
 * "updated_at": "2021-01-21T02:26:48.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 28,
 * "email": "chrystal@holland-at-home.nl",
 * "fullname": "123",
 * "username": "chrystal@holland-at-home.nl",
 * "phone": "123",
 * "remark": "123",
 * "forbid_login": false,
 * "role_id": 1,
 * "role_id_name": "管理员组",
 * "is_admin": 2,
 * "created_at": "2020-04-22T03:01:15.000000Z",
 * "updated_at": "2020-04-22T03:01:15.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 30,
 * "email": "799880548@qq.com",
 * "fullname": "12",
 * "username": "799880548@qq.com",
 * "phone": "123",
 * "remark": "12",
 * "forbid_login": false,
 * "role_id": null,
 * "role_id_name": "",
 * "is_admin": 2,
 * "created_at": "2020-04-22T03:54:26.000000Z",
 * "updated_at": "2020-11-24T08:44:12.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 31,
 * "email": "cctv@163.com",
 * "fullname": "阿西吧",
 * "username": "cctv@163.com",
 * "phone": "123456789",
 * "remark": "no proble",
 * "forbid_login": false,
 * "role_id": null,
 * "role_id_name": "",
 * "is_admin": 2,
 * "created_at": "2020-04-26T10:43:40.000000Z",
 * "updated_at": "2020-04-26T10:43:40.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 32,
 * "email": "ccav@163.com",
 * "fullname": "sas",
 * "username": "ccav@163.com",
 * "phone": "1234567895",
 * "remark": "ad",
 * "forbid_login": false,
 * "role_id": null,
 * "role_id_name": "",
 * "is_admin": 2,
 * "created_at": "2020-04-26T11:04:41.000000Z",
 * "updated_at": "2020-04-26T11:04:41.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 37,
 * "email": "827193562@qq.com",
 * "fullname": "奶茶",
 * "username": "827193562@qq.com",
 * "phone": "15922228888",
 * "remark": "司机",
 * "forbid_login": false,
 * "role_id": 8,
 * "role_id_name": "扫描员",
 * "is_admin": 2,
 * "created_at": "2020-04-29T03:04:26.000000Z",
 * "updated_at": "2020-04-29T03:04:26.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 39,
 * "email": "827193289@qq.com4",
 * "fullname": "1",
 * "username": "827193289@qq.com4",
 * "phone": "12",
 * "remark": "1",
 * "forbid_login": false,
 * "role_id": null,
 * "role_id_name": "",
 * "is_admin": 2,
 * "created_at": "2020-04-30T03:29:09.000000Z",
 * "updated_at": "2020-04-30T03:29:09.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 41,
 * "email": "17774657855@qq.com",
 * "fullname": "nnt",
 * "username": "17774657855@qq.com",
 * "phone": "12345678904",
 * "remark": "u",
 * "forbid_login": false,
 * "role_id": 1,
 * "role_id_name": "管理员组",
 * "is_admin": 2,
 * "created_at": "2020-05-06T07:21:50.000000Z",
 * "updated_at": "2020-05-06T07:21:50.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 44,
 * "email": "827193289@qq.com3",
 * "fullname": "2",
 * "username": "827193289@qq.com3",
 * "phone": "31",
 * "remark": "1",
 * "forbid_login": false,
 * "role_id": null,
 * "role_id_name": "",
 * "is_admin": 2,
 * "created_at": "2020-05-14T05:39:13.000000Z",
 * "updated_at": "2020-05-14T05:39:13.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 46,
 * "email": "827193546@qq.com",
 * "fullname": "13",
 * "username": "827193546@qq.com",
 * "phone": "546",
 * "remark": "845",
 * "forbid_login": false,
 * "role_id": null,
 * "role_id_name": "",
 * "is_admin": 2,
 * "created_at": "2020-05-19T07:09:40.000000Z",
 * "updated_at": "2020-05-19T07:09:40.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 47,
 * "email": "8271998645@qq.com",
 * "fullname": "453",
 * "username": "8271998645@qq.com",
 * "phone": "654",
 * "remark": "2145",
 * "forbid_login": false,
 * "role_id": null,
 * "role_id_name": "",
 * "is_admin": 2,
 * "created_at": "2020-05-19T07:20:39.000000Z",
 * "updated_at": "2020-05-19T07:20:39.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 48,
 * "email": "277848787@qq.com",
 * "fullname": "5435",
 * "username": "277848787@qq.com",
 * "phone": "54",
 * "remark": "548",
 * "forbid_login": false,
 * "role_id": null,
 * "role_id_name": "",
 * "is_admin": 2,
 * "created_at": "2020-05-19T07:21:05.000000Z",
 * "updated_at": "2020-05-19T07:21:05.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 49,
 * "email": "785454345@qq.com",
 * "fullname": "8754",
 * "username": "785454345@qq.com",
 * "phone": "452",
 * "remark": "5454",
 * "forbid_login": false,
 * "role_id": null,
 * "role_id_name": "",
 * "is_admin": 2,
 * "created_at": "2020-05-19T07:21:52.000000Z",
 * "updated_at": "2020-05-19T07:21:52.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 50,
 * "email": "78974656546@qq.com",
 * "fullname": "7869",
 * "username": "78974656546@qq.com",
 * "phone": "8754",
 * "remark": "6321",
 * "forbid_login": false,
 * "role_id": null,
 * "role_id_name": "",
 * "is_admin": 2,
 * "created_at": "2020-05-19T07:23:01.000000Z",
 * "updated_at": "2020-05-19T07:23:01.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 51,
 * "email": "45635321@qq.com",
 * "fullname": "456",
 * "username": "45635321@qq.com",
 * "phone": "54654",
 * "remark": "578",
 * "forbid_login": false,
 * "role_id": null,
 * "role_id_name": "",
 * "is_admin": 2,
 * "created_at": "2020-05-19T07:24:45.000000Z",
 * "updated_at": "2020-05-19T07:24:45.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 52,
 * "email": "46132@qq.com",
 * "fullname": "45",
 * "username": "46132@qq.com",
 * "phone": "245",
 * "remark": "54",
 * "forbid_login": false,
 * "role_id": null,
 * "role_id_name": "",
 * "is_admin": 2,
 * "created_at": "2020-05-19T07:26:04.000000Z",
 * "updated_at": "2020-05-19T07:27:34.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 83,
 * "email": "1125867341@qq.com",
 * "fullname": "bx",
 * "username": "1125867341@qq.com",
 * "phone": "112586734",
 * "remark": "..",
 * "forbid_login": false,
 * "role_id": null,
 * "role_id_name": "",
 * "is_admin": 2,
 * "created_at": "2020-08-17T08:32:45.000000Z",
 * "updated_at": "2020-08-17T08:32:45.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 89,
 * "email": "zengchun@qq.com",
 * "fullname": "zeng",
 * "username": "zengchun@qq.com",
 * "phone": "123124214",
 * "remark": "2",
 * "forbid_login": false,
 * "role_id": 22,
 * "role_id_name": "测试组",
 * "is_admin": 2,
 * "created_at": "2020-12-01T02:59:31.000000Z",
 * "updated_at": "2020-12-01T02:59:31.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 95,
 * "email": "ceshi@qq.com",
 * "fullname": "ceshi",
 * "username": "ceshi@qq.com",
 * "phone": "123654789",
 * "remark": "wu",
 * "forbid_login": false,
 * "role_id": 24,
 * "role_id_name": "A组",
 * "is_admin": 2,
 * "created_at": "2021-01-20T04:40:15.000000Z",
 * "updated_at": "2021-01-20T04:40:15.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 100,
 * "email": "zc@123.com",
 * "fullname": "test123",
 * "username": "zc@123.com",
 * "phone": "1231243",
 * "remark": "12",
 * "forbid_login": false,
 * "role_id": 1,
 * "role_id_name": "管理员组",
 * "is_admin": 2,
 * "created_at": "2021-01-21T20:28:26.000000Z",
 * "updated_at": "2021-01-21T20:28:26.000000Z",
 * "warehouse_id": null
 * },
 * {
 * "id": 120,
 * "email": "90099@nle-tech.com",
 * "fullname": "zhouhui",
 * "username": "90099@nle-tech.com",
 * "phone": "1787772221",
 * "remark": "11",
 * "forbid_login": false,
 * "role_id": 1,
 * "role_id_name": "管理员组",
 * "is_admin": 2,
 * "created_at": "2021-04-17T21:51:12.000000Z",
 * "updated_at": "2021-04-17T21:51:12.000000Z",
 * "warehouse_id": null
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test:10002/api/admin/employees?page=1",
 * "last": "http://tms-api.test:10002/api/admin/employees?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test:10002/api/admin/employees",
 * "per_page": 200,
 * "to": 22,
 * "total": 22
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/employees/{id} 员工详情
 * @apiName 员工详情
 * @apiGroup 16
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 员工ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.email 邮箱
 * @apiSuccess {string} data.fullname 姓名
 * @apiSuccess {string} data.username 用户名
 * @apiSuccess {string} data.phone 手机号
 * @apiSuccess {string} data.remark 备注
 * @apiSuccess {string} data.group 权限组/员工组
 * @apiSuccess {string} data.institution 机构ID
 * @apiSuccess {string} data.institution.id 机构组织ID
 * @apiSuccess {string} data.institution.name 机构组织名
 * @apiSuccess {string} data.institution.contacts 机构组织负责人
 * @apiSuccess {string} data.institution.country 机构组织国家
 * @apiSuccess {string} data.institution.address 机构组织负责人详细地址
 * @apiSuccess {string} data.institution.phone 机构组织电话
 * @apiSuccess {string} data.institution.created_at
 * @apiSuccess {string} data.institution.updated_at
 * @apiSuccess {string} data.forbid_login 禁止登录标志
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1,
 * "email": "h947136@qq.com",
 * "fullname": "h947136@qq.com",
 * "username": "HuoZhangqi",
 * "phone": "",
 * "remark": "",
 * "group": "",
 * "institution": {
 * "id": 2,
 * "name": "NLE麓谷分部",
 * "contacts": "张三",
 * "country": "马来西亚",
 * "address": "马来西亚",
 * "phone": "15806336936",
 * "created_at": "2020-01-04T01:41:51.000000Z",
 * "updated_at": "2020-01-05T22:34:43.000000Z"
 * },
 * "forbid_login": false,
 * "created_at": "2019-12-20T23:57:13.000000Z",
 * "updated_at": "2020-01-03T03:53:45.000000Z"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/employees 员工新增
 * @apiName 员工新增
 * @apiGroup 16
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} email 邮箱（登录用）
 * @apiParam {string} password 密码
 * @apiParam {string} username 用户名
 * @apiParam {string} fullname 姓名
 * @apiParam {string} group_id 权限组/员工组
 * @apiParam {string} phone 电话
 * @apiParam {string} remark 备注
 * @apiParam {string} warehouse_id 网点ID
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

/**
 * @api {put} /admin/employees/{id} 员工修改
 * @apiName 员工修改
 * @apiGroup 16
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} email 邮箱（登录用）
 * @apiParam {string} password 密码
 * @apiParam {string} username 用户名
 * @apiParam {string} fullname 姓名
 * @apiParam {string} group_id 权限组/员工组
 * @apiParam {string} institution_id 组织ID
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

/**
 * @api {delete} /admin/employees/{id} 员工删除
 * @apiName 员工删除
 * @apiGroup 16
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 员工ID
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

/**
 * @api {put} /admin/employees/{id}/password 重置员工密码
 * @apiName 重置员工密码
 * @apiGroup 16
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} password 新密码
 * @apiParam {string} confirm_password 重复新密码
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [],
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/employees/{id}/move-to/{institutionId} 修改员工所属组织
 * @apiName 修改员工所属组织
 * @apiGroup 16
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 员工ID
 * @apiParam {string} institutionId 组织ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [],
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/employees/forbid-login 批量启用禁用
 * @apiName 批量启用禁用
 * @apiGroup 16
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list ID列表
 * @apiParam {string} status 状态(1-禁用2-启用）
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

/**
 * @api {get} /admin/institutions 组织树
 * @apiName 组织树
 * @apiGroup 17
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.name 机构组织名
 * @apiSuccess {string} data.phone 机构组织电话
 * @apiSuccess {string} data.contacts 机构组织负责人
 * @apiSuccess {string} data.country 机构组织国家代号
 * @apiSuccess {string} data.address 机构组织负责人详细地址
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.parent 父ID
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.ancestor
 * @apiSuccess {string} data.descendant
 * @apiSuccess {string} data.distance
 * @apiSuccess {string} data.children 下属机构组织
 * @apiSuccess {string} data.children.id
 * @apiSuccess {string} data.children.name 下属机构组织名
 * @apiSuccess {string} data.children.phone 下属机构组织电话
 * @apiSuccess {string} data.children.contacts 下属机构组织负责人
 * @apiSuccess {string} data.children.country 下属机构组织国家代号
 * @apiSuccess {string} data.children.address 下属机构组织负责人详细地址
 * @apiSuccess {string} data.children.company_id 下属公司ID
 * @apiSuccess {string} data.children.parent 下属父ID
 * @apiSuccess {string} data.children.created_at
 * @apiSuccess {string} data.children.updated_at
 * @apiSuccess {string} data.children.ancestor
 * @apiSuccess {string} data.children.descendant
 * @apiSuccess {string} data.children.distance
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.country_name 国家名
 * @apiSuccess {string} data.children.country_name 下属机构国家名
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 2,
 * "name": "NLE麓谷分部",
 * "phone": "15806336936",
 * "contacts": "张三",
 * "country": "马来西亚",
 * "address": "马来西亚",
 * "company_id": 1,
 * "parent": 1,
 * "created_at": "2020-01-04 09:41:51",
 * "updated_at": "2020-01-06 06:34:43",
 * "ancestor": 1,
 * "descendant": 2,
 * "distance": 1,
 * "children": [
 * {
 * "id": 3,
 * "name": "财务部",
 * "phone": "",
 * "contacts": "",
 * "country": "",
 * "address": "",
 * "company_id": 1,
 * "parent": 2,
 * "created_at": "2020-01-04 09:46:15",
 * "updated_at": "2020-01-04 09:46:15",
 * "ancestor": 1,
 * "descendant": 3,
 * "distance": 2
 * },
 * {
 * "id": 4,
 * "name": "NLE马来西亚分部",
 * "phone": "15806336936",
 * "contacts": "张三",
 * "country": "马来西亚",
 * "address": "马来西亚",
 * "company_id": 1,
 * "parent": 2,
 * "created_at": "2020-01-04 09:46:48",
 * "updated_at": "2020-01-06 06:40:35",
 * "ancestor": 1,
 * "descendant": 4,
 * "distance": 2,
 * "children": [
 * {
 * "id": 6,
 * "name": "助理办公室",
 * "phone": "",
 * "contacts": "",
 * "country": "",
 * "address": "",
 * "company_id": 1,
 * "parent": 4,
 * "created_at": "2020-01-04 10:02:03",
 * "updated_at": "2020-01-04 10:02:03",
 * "ancestor": 1,
 * "descendant": 6,
 * "distance": 3
 * }
 * ]
 * },
 * {
 * "id": 7,
 * "name": "NLE非洲分部",
 * "phone": "15806336936",
 * "contacts": "张三",
 * "country": "中国长沙",
 * "address": "岳麓区枫林三路",
 * "company_id": 1,
 * "parent": 2,
 * "created_at": "2020-01-06 03:53:06",
 * "updated_at": "2020-01-06 03:53:06",
 * "ancestor": 1,
 * "descendant": 7,
 * "distance": 2
 * },
 * {
 * "id": 8,
 * "name": "NLE马来西亚分部",
 * "phone": "15806336936",
 * "contacts": "张三",
 * "country": "马来西亚",
 * "address": "马来西亚",
 * "company_id": 1,
 * "parent": 2,
 * "created_at": "2020-01-06 03:55:19",
 * "updated_at": "2020-01-06 03:55:19",
 * "ancestor": 1,
 * "descendant": 8,
 * "distance": 2
 * },
 * {
 * "id": 9,
 * "name": "NLE马来西亚分部",
 * "phone": "15806336936",
 * "contacts": "张三",
 * "country": "马来西亚",
 * "address": "马来西亚",
 * "company_id": 1,
 * "parent": 2,
 * "created_at": "2020-01-06 06:22:19",
 * "updated_at": "2020-01-06 06:22:19",
 * "ancestor": 1,
 * "descendant": 9,
 * "distance": 2
 * },
 * {
 * "id": 10,
 * "name": "NLE马来西亚分部",
 * "phone": "15806336936",
 * "contacts": "张三",
 * "country": "马来西亚",
 * "address": "马来西亚",
 * "company_id": 1,
 * "parent": 2,
 * "created_at": "2020-02-03 16:34:12",
 * "updated_at": "2020-02-03 16:34:12",
 * "ancestor": 1,
 * "descendant": 10,
 * "distance": 2
 * }
 * ]
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/institutions/{id} 组织详情
 * @apiName 组织详情
 * @apiGroup 17
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 组织机构ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.name 机构组织名
 * @apiSuccess {string} data.phone 机构组织电话
 * @apiSuccess {string} data.contacts 机构组织负责人
 * @apiSuccess {string} data.country 机构组织国家代号
 * @apiSuccess {string} data.address 机构组织负责人详细地址
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.parent 父ID
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.country_name 国家名
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 4,
 * "name": "撤硕儿",
 * "phone": "1",
 * "contacts": "whoyummy@sina.com",
 * "country": "NL",
 * "address": "1",
 * "company_id": 2,
 * "parent": 3,
 * "created_at": "2020-03-13 11:50:55",
 * "updated_at": "2020-03-26 14:00:20",
 * "country_name": "荷兰"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/institutions 组织新增
 * @apiName 组织新增
 * @apiGroup 17
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 机构组织名
 * @apiParam {string} phone 机构组织电话
 * @apiParam {string} contacts 机构组织负责人
 * @apiParam {string} country 机构组织国家城市
 * @apiParam {string} address 机构组织负责人详细地址
 * @apiParam {string} parent_id 父ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [],
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/institutions/{id} 组织修改
 * @apiName 组织修改
 * @apiGroup 17
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 机构组织名
 * @apiParam {string} phone 机构组织电话
 * @apiParam {string} contacts 机构组织负责人
 * @apiParam {string} country 机构组织国家城市
 * @apiParam {string} address 机构组织负责人详细地址
 * @apiParam {string} parent_id 父ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [],
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/institutions/{id}/move-to/{parent_id} 组织移动
 * @apiName 组织移动
 * @apiGroup 17
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 要移动的结构组织ID
 * @apiParam {string} parent_id 移动到该结构组织ID下
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [],
 * "msg": "successful"
 * }
 */

/**
 * @api {delete} /admin/institutions/{id} 组织删除
 * @apiName 组织删除
 * @apiGroup 17
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/institutions/{id}/member 组织成员
 * @apiName 组织成员
 * @apiGroup 17
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 结构组织ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 员工ID
 * @apiSuccess {string} data.data.email 邮箱
 * @apiSuccess {string} data.data.fullname 姓名
 * @apiSuccess {string} data.data.username 用户名
 * @apiSuccess {string} data.data.phone 电话
 * @apiSuccess {string} data.data.remark 备注
 * @apiSuccess {string} data.data.group 权限组/员工组
 * @apiSuccess {string} data.data.institution 组织
 * @apiSuccess {string} data.data.institution.id 机构组织ID
 * @apiSuccess {string} data.data.institution.name 机构组织名
 * @apiSuccess {string} data.data.institution.contacts 机构组织负责人
 * @apiSuccess {string} data.data.institution.country 机构组织国家
 * @apiSuccess {string} data.data.institution.address 机构组织负责人详细地址
 * @apiSuccess {string} data.data.institution.phone 机构组织电话
 * @apiSuccess {string} data.data.institution.created_at
 * @apiSuccess {string} data.data.institution.updated_at
 * @apiSuccess {string} data.data.forbid_login
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "email": "h947136@qq.com",
 * "fullname": "h947136@qq.com",
 * "username": "HuoZhangqi",
 * "phone": "",
 * "remark": "",
 * "group": "",
 * "institution": {
 * "id": 2,
 * "name": "NLE麓谷分部",
 * "contacts": "张三",
 * "country": "马来西亚",
 * "address": "马来西亚",
 * "phone": "15806336936",
 * "created_at": "2020-01-04T01:41:51.000000Z",
 * "updated_at": "2020-01-05T22:34:43.000000Z"
 * },
 * "forbid_login": false,
 * "created_at": "2019-12-20T23:57:13.000000Z",
 * "updated_at": "2020-01-03T03:53:45.000000Z"
 * }
 * ],
 * "links": {
 * "first": "http://dev-tms.nle-tech.com:443/api/admin/institutions/2/employees?page=1",
 * "last": "http://dev-tms.nle-tech.com:443/api/admin/institutions/2/employees?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://dev-tms.nle-tech.com:443/api/admin/institutions/2/employees",
 * "per_page": 15,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/merchant 货主查询
 * @apiName 货主查询
 * @apiGroup 18
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 货主名
 * @apiParam {string} merchant_group_id 货主组ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 货主ID
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.type 类型1-个人2-货主
 * @apiSuccess {string} data.data.name 名称
 * @apiSuccess {string} data.data.email 邮箱
 * @apiSuccess {string} data.data.settlement_type 结算方式1-票结2-日结3-月结
 * @apiSuccess {string} data.data.merchant_group_id 货主组ID
 * @apiSuccess {string} data.data.contacter 联系人
 * @apiSuccess {string} data.data.phone 电话
 * @apiSuccess {string} data.data.address 联系地址
 * @apiSuccess {string} data.data.avatar 头像
 * @apiSuccess {string} data.data.status 状态1-启用2-禁用
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.data.country 国家
 * @apiSuccess {string} data.data.country_name 国家名称
 * @apiSuccess {string} data.data.code 用户编码
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "company_id": 2,
 * "type": 1,
 * "name": "货主1",
 * "email": "827193289@qq.com",
 * "country": "国家",
 * "country_name": "国家名称",
 * "settlement_type": 1,
 * "merchant_group_id": 1,
 * "contacter": "联系人1",
 * "phone": "1312121211",
 * "address": "详细地址1",
 * "avatar": "头像",
 * "status": 2,
 * "created_at": "2020-02-20 16:49:41",
 * "updated_at": "2020-02-20 16:49:41"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/merchant?page=1",
 * "last": "http://tms-api.test/api/admin/merchant?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/merchant",
 * "per_page": 15,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/merchant/{id} 货主详情
 * @apiName 货主详情
 * @apiGroup 18
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 货主ID
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.type 类型1-个人2-货主
 * @apiSuccess {string} data.name 名称
 * @apiSuccess {string} data.email 邮箱
 * @apiSuccess {string} data.settlement_type 结算方式1-票结2-日结3-月结
 * @apiSuccess {string} data.merchant_group_id 货主组ID
 * @apiSuccess {string} data.contacter 联系人
 * @apiSuccess {string} data.phone 电话
 * @apiSuccess {string} data.address 联系地址
 * @apiSuccess {string} data.avatar 头像
 * @apiSuccess {string} data.status 状态1-启用2-禁用
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.country 国家
 * @apiSuccess {string} data.country_name 国家名称
 * @apiSuccess {string} data.fee_list 费用列表
 * @apiSuccess {string} data.fee_list.id 费用ID
 * @apiSuccess {string} data.fee_list.code 费用编码
 * @apiSuccess {string} data.fee_list.name 费用名称
 * @apiSuccess {string} data.fee_list.status_name
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1,
 * "company_id": 2,
 * "type": 1,
 * "name": "货主1",
 * "email": "827193289@qq.com",
 * "country": "NL",
 * "country_name": "荷兰",
 * "settlement_type": 1,
 * "merchant_group_id": 1,
 * "contacter": "联系人1",
 * "phone": "1312121211",
 * "address": "详细地址1",
 * "avatar": "头像",
 * "status": 2,
 * "created_at": "2020-02-20 16:49:41",
 * "updated_at": "2020-02-20 16:49:41",
 * "fee_list": [
 * {
 * "id": 41,
 * "code": "QT",
 * "name": "其他费用",
 * "status_name": null
 * },
 * {
 * "id": 54,
 * "code": "TH",
 * "name": "提货费",
 * "status_name": null
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/merchant 货主新增
 * @apiName 货主新增
 * @apiGroup 18
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} type 类型1-个人2-货主
 * @apiParam {string} name 名称
 * @apiParam {string} email 邮箱
 * @apiParam {string} country 国家
 * @apiParam {string} settlement_type 结算方式1-票结2-日结3-月结
 * @apiParam {string} merchant_group_id 货主组ID
 * @apiParam {string} contacter 联系人
 * @apiParam {string} phone 电话
 * @apiParam {string} address 联系地址
 * @apiParam {string} avatar 企业Logo
 * @apiParam {string} status 状态1-启用2-禁用
 * @apiParam {string} advance_days 提前下单天数
 * @apiParam {string} appointment_days 可预约天数
 * @apiParam {string} delay_time 延后时间(分钟)
 * @apiParam {string} fee_code_list 费用编码列表,以逗号分隔
 * @apiParam {string} invoice_title 发票抬头
 * @apiParam {string} taxpayer_code 纳税人识别码
 * @apiParam {string} bank 开户行
 * @apiParam {string} bank_account 开户账号
 * @apiParam {string} invoice_address 寄票地址
 * @apiParam {string} invoice_email 收票邮箱
 * @apiParam {string} introduction 企业介绍
 * @apiParam {string} warehouse_id 网点ID
 * @apiParam {string} below_warehouse 仓配一体1-是2-否
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

/**
 * @api {put} /admin/merchant/{id} 货主修改
 * @apiName 货主修改
 * @apiGroup 18
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} type 类型1-个人2-货主
 * @apiParam {string} name 名称
 * @apiParam {string} email 邮箱
 * @apiParam {string} settlement_type 结算方式1-票结2-日结3-月结
 * @apiParam {string} merchant_group_id 货主组ID
 * @apiParam {string} contacter 联系人
 * @apiParam {string} phone 电话
 * @apiParam {string} address 联系地址
 * @apiParam {string} avatar 头像
 * @apiParam {string} status 状态1-启用2-禁用
 * @apiParam {string} country 国家
 * @apiParam {string} advance_days 提前下单天数
 * @apiParam {string} appointment_days 可预约天数
 * @apiParam {string} delay_time 延后时间(分钟)
 * @apiParam {string} fee_code_list 费用编码列表,以逗号分隔
 * @apiParam {string} stock_exception_verify 是否开启入库异常审核1-开启2-关闭
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

/**
 * @api {put} /admin/merchant/{id}/password 修改货主密码
 * @apiName 修改货主密码
 * @apiGroup 18
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} password 密码
 * @apiParam {string} confirm_password 重复密码
 * @apiParam {string} id 货主ID
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

/**
 * @api {post} /admin/merchant/{id}/status 货主禁用启用
 * @apiName 货主禁用启用
 * @apiGroup 18
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 货主ID
 * @apiParam {string} status 状态1-启用2-禁用
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

/**
 * @api {put} /admin/merchant/status 货主批量启用禁用
 * @apiName 货主批量启用禁用
 * @apiGroup 18
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} ids 货主ID数组
 * @apiParam {string} status 1-启用，2-禁用
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

/**
 * @api {get} /admin/merchant/merchant-excel 货主信息导出
 * @apiName 货主信息导出
 * @apiGroup 18
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.name 名称
 * @apiSuccess {string} data.path 路径
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "name": "20200302merchant.xlsx",
 * "path": "tms-api.test/storage/admin/excel/2\\merchant\\20200302merchant.xlsx"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/merchant/init 初始化
 * @apiName 初始化
 * @apiGroup 18
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "settlement_type_list": [
 * {
 * "id": 1,
 * "name": "票结"
 * },
 * {
 * "id": 2,
 * "name": "日结"
 * },
 * {
 * "id": 3,
 * "name": "月结"
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/merchant-group 货主组查询
 * @apiName 货主组查询
 * @apiGroup 19
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 货主组名
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 货主组ID
 * @apiSuccess {string} data.data.name 名称
 * @apiSuccess {string} data.data.count 成员数
 * @apiSuccess {string} data.data.transport_price_id 运价ID
 * @apiSuccess {string} data.data.transport_price_name 运价方案名
 * @apiSuccess {string} data.data.is_default 是否是默认组1-是2-否
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "name": "VIP_21",
 * "transport_price_id": 1,
 * "is_default": 2,
 * "created_at": "2020-02-20 15:10:13"
 * }
 * ],
 * "links": {
 * "first": "http://dev-tms.nle-tech.com:443/api/admin/merchant-group?page=1",
 * "last": "http://dev-tms.nle-tech.com:443/api/admin/merchant-group?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://dev-tms.nle-tech.com:443/api/admin/merchant-group",
 * "per_page": 10,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/merchant-group/{id} 货主组详情
 * @apiName 货主组详情
 * @apiGroup 19
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 用户组ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 用户组ID
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.name 名称
 * @apiSuccess {string} data.transport_price_id 运价ID
 * @apiSuccess {string} data.is_default 是否是默认组1-是2-否
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1,
 * "company_id": 1,
 * "name": "VIP_21",
 * "transport_price_id": 1,
 * "is_default": 2,
 * "created_at": "2020-02-20 15:10:13",
 * "updated_at": "2020-02-20 15:24:41"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/merchant-group 货主组新增
 * @apiName 货主组新增
 * @apiGroup 19
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 名称
 * @apiParam {string} transport_price_id 运价ID
 * @apiParam {string} is_default 是否是默认组1-是2-否
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

/**
 * @api {put} /admin/merchant-group/{id} 货主组修改
 * @apiName 货主组修改
 * @apiGroup 19
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 名称
 * @apiParam {string} transport_price_id 运价ID
 * @apiParam {string} is_default 是否是默认组1-是2-否
 * @apiParam {string} id 货主组ID
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

/**
 * @api {delete} /admin/merchant-group/{id} 货主组删除
 * @apiName 货主组删除
 * @apiGroup 19
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 货主组ID
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

/**
 * @api {get} /admin/merchant-group/{id}/member 货主组成员查询
 * @apiName 货主组成员查询
 * @apiGroup 19
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 货主组id
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.current_page
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.type 类型1-个人2-货主
 * @apiSuccess {string} data.data.name 名称
 * @apiSuccess {string} data.data.email 邮箱
 * @apiSuccess {string} data.data.settlement_type 结算方式1-票结2-日结3-月结
 * @apiSuccess {string} data.data.merchant_group_id 货主组ID
 * @apiSuccess {string} data.data.contacter 联系人
 * @apiSuccess {string} data.data.phone 电话
 * @apiSuccess {string} data.data.address 联系地址
 * @apiSuccess {string} data.data.avatar 头像
 * @apiSuccess {string} data.data.status 状态1-启用2-禁用
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.first_page_url
 * @apiSuccess {string} data.from
 * @apiSuccess {string} data.last_page
 * @apiSuccess {string} data.last_page_url
 * @apiSuccess {string} data.next_page_url
 * @apiSuccess {string} data.path
 * @apiSuccess {string} data.per_page
 * @apiSuccess {string} data.prev_page_url
 * @apiSuccess {string} data.to
 * @apiSuccess {string} data.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "current_page": 1,
 * "data": [
 * {
 * "id": 1,
 * "company_id": 2,
 * "type": 1,
 * "name": "货主1",
 * "email": "827193289@qq.com",
 * "password": "$2y$10$DrmYuqwRW7kiYLaKofNltO.3zMCqYmmoyLhKGQfnSPdUsk52aIFVO",
 * "settlement_type": 1,
 * "merchant_group_id": 1,
 * "contacter": "联系人1",
 * "phone": "1312121211",
 * "address": "详细地址1",
 * "avatar": "头像",
 * "status": 2,
 * "created_at": "2020-02-20 16:49:41",
 * "updated_at": "2020-02-20 16:49:41"
 * },
 * {
 * "id": 2,
 * "company_id": 2,
 * "type": 1,
 * "name": "货主5",
 * "email": "8271932893@qq.com",
 * "password": "$2y$10$abXzzLZdX1CZA./CVmxm0..tOVKFvbwwgGIbK7u0S87YuhRVWhRt.",
 * "settlement_type": 1,
 * "merchant_group_id": 1,
 * "contacter": "联系人1",
 * "phone": "1312121211",
 * "address": "详细地址1",
 * "avatar": "头像",
 * "status": 1,
 * "created_at": "2020-02-20 16:56:57",
 * "updated_at": "2020-02-20 17:10:29"
 * },
 * {
 * "id": 3,
 * "company_id": 2,
 * "type": 1,
 * "name": "货主3",
 * "email": "8271932895@qq.com",
 * "password": "$2y$10$qV4hPDV2sFcXOukZYY/I2u7Sov2l.KHw8X18eeZRKnjyJ8WG3acmK",
 * "settlement_type": 1,
 * "merchant_group_id": 1,
 * "contacter": "联系人1",
 * "phone": "1312121211",
 * "address": "详细地址1",
 * "avatar": "头像",
 * "status": 2,
 * "created_at": "2020-02-20 17:01:13",
 * "updated_at": "2020-02-20 17:01:13"
 * },
 * {
 * "id": 4,
 * "company_id": 2,
 * "type": 1,
 * "name": "货主4",
 * "email": "827193289h@qq.com",
 * "password": "$2y$10$wYzKBNmDEOiyOteCTu6n/O0EIJnNVeU2dvDxGWl4XwVYsaMPfUcU6",
 * "settlement_type": 1,
 * "merchant_group_id": 1,
 * "contacter": "联系人1",
 * "phone": "1312121211",
 * "address": "详细地址1",
 * "avatar": "头像",
 * "status": 2,
 * "created_at": "2020-02-20 17:07:49",
 * "updated_at": "2020-02-20 17:07:49"
 * },
 * {
 * "id": 5,
 * "company_id": 2,
 * "type": 1,
 * "name": "货主5d",
 * "email": "827193289g@qq.com",
 * "password": "$2y$10$FoMPmPX5a9GhABbceT5g6O0s4pypfW7xfjv1Rp7DIWmpgghs4jOYi",
 * "settlement_type": 1,
 * "merchant_group_id": 1,
 * "contacter": "联系人1",
 * "phone": "1312121211",
 * "address": "详细地址1",
 * "avatar": "头像",
 * "status": 2,
 * "created_at": "2020-02-20 17:36:10",
 * "updated_at": "2020-02-20 17:36:10"
 * }
 * ],
 * "first_page_url": "http://tms-api.test/api/admin/merchant-group/1/indexOfMerchant?page=1",
 * "from": 1,
 * "last_page": 1,
 * "last_page_url": "http://tms-api.test/api/admin/merchant-group/1/indexOfMerchant?page=1",
 * "next_page_url": null,
 * "path": "http://tms-api.test/api/admin/merchant-group/1/indexOfMerchant",
 * "per_page": 15,
 * "prev_page_url": null,
 * "to": 5,
 * "total": 5
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/merchant-group/transport-price 批量设置运价
 * @apiName 批量设置运价
 * @apiGroup 19
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} ids 用户组ID数组
 * @apiParam {string} transport_price_id 运价方案ID
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

/**
 * @api {post} /admin/merchant/merchant-group 获得货主组
 * @apiName 获得货主组
 * @apiGroup 19
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.merchant_group_id 货主组ID
 * @apiSuccess {string} data.merchant_group_name 货主组名
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "merchant_group_id": 1,
 * "merchant_group_name": "VIP_21"
 * },
 * {
 * "merchant_group_id": 5,
 * "merchant_group_name": "VIP_3"
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/merchant-group/fee 费用列表查询
 * @apiName 费用列表查询
 * @apiGroup 19
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} merchant_group_id 货主ID;货主组新增时传空,货主组编辑时传货主组ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id ID
 * @apiSuccess {string} data.code 编码
 * @apiSuccess {string} data.name 名称
 * @apiSuccess {string} data.status_name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 57,
 * "code": "DELIVERY",
 * "name": "提货费用",
 * "status_name": null
 * },
 * {
 * "id": 3,
 * "code": "STICKER",
 * "name": "贴单费用",
 * "status_name": null
 * },
 * {
 * "id": 43,
 * "code": "SH",
 * "name": "送货上门",
 * "status_name": null
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/merchant-group/{id}/config 权限配置
 * @apiName 权限配置
 * @apiGroup 19
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} additional_status 顺带包裹状态1-启用2-禁用
 * @apiParam {string} advance_days 提前下单天数
 * @apiParam {string} appointment_days 可预约天数
 * @apiParam {string} delay_time 延迟时间
 * @apiParam {string} pickup_count 取件失败次数
 * @apiParam {string} pie_count 派件失败次数
 * @apiParam {string} fee_code_list 费用编码列表,逗号隔开
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

/**
 * @api {put} /admin/merchant-group/id/status 修改状态
 * @apiName 修改状态
 * @apiGroup 19
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiParam {string} status 状态1-启用2-禁用
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

/**
 * @api {get} /admin/transport-price 运价查询
 * @apiName 运价查询
 * @apiGroup 20
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 运价名
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.name 运价方案名
 * @apiSuccess {string} data.data.starting_price 固定费用
 * @apiSuccess {string} data.data.remark 备注
 * @apiSuccess {string} data.data.status 状态1启用2禁用
 * @apiSuccess {string} data.data.part 组成
 * @apiSuccess {string} data.data.type 类型
 * @apiSuccess {string} data.data.type_name 类型说明
 * @apiSuccess {string} data.data.km_list 里程表
 * @apiSuccess {string} data.data.km_list.id
 * @apiSuccess {string} data.data.km_list.company_id
 * @apiSuccess {string} data.data.km_list.transport_price_id
 * @apiSuccess {string} data.data.km_list.start 起始里程
 * @apiSuccess {string} data.data.km_list.end 终止里程
 * @apiSuccess {string} data.data.km_list.price 价格
 * @apiSuccess {string} data.data.km_list.created_at
 * @apiSuccess {string} data.data.km_list.updated_at
 * @apiSuccess {string} data.data.weight_list 重量表
 * @apiSuccess {string} data.data.weight_list.id
 * @apiSuccess {string} data.data.weight_list.company_id
 * @apiSuccess {string} data.data.weight_list.transport_price_id
 * @apiSuccess {string} data.data.weight_list.start 起始重量
 * @apiSuccess {string} data.data.weight_list.end 终止重量
 * @apiSuccess {string} data.data.weight_list.price 价格
 * @apiSuccess {string} data.data.weight_list.created_at
 * @apiSuccess {string} data.data.weight_list.updated_at
 * @apiSuccess {string} data.data.special_time_list
 * @apiSuccess {string} data.data.special_time_list.id
 * @apiSuccess {string} data.data.special_time_list.company_id
 * @apiSuccess {string} data.data.special_time_list.transport_price_id
 * @apiSuccess {string} data.data.special_time_list.start
 * @apiSuccess {string} data.data.special_time_list.end
 * @apiSuccess {string} data.data.special_time_list.price
 * @apiSuccess {string} data.data.special_time_list.created_at
 * @apiSuccess {string} data.data.special_time_list.updated_at
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 3,
 * "name": "tianyaox",
 * "starting_price": "0.00",
 * "remark": "122112",
 * "status": 1,
 * "part": "固定费用,重量,里程",
 * "type": 1,
 * "type_name": "阶梯乘积值计算（固定费用+（每单位重量价格*重量价格）*（每单位里程价格*里程价格））",
 * "km_list": [
 * {
 * "id": 38,
 * "company_id": 3,
 * "transport_price_id": 3,
 * "start": 0,
 * "end": 2,
 * "price": "4.00",
 * "created_at": "2020-04-21 15:13:50",
 * "updated_at": "2020-04-21 15:13:50"
 * }
 * ],
 * "weight_list": [
 * {
 * "id": 34,
 * "company_id": 3,
 * "transport_price_id": 3,
 * "start": 1,
 * "end": 2,
 * "price": "2.00",
 * "created_at": "2020-04-21 15:13:50",
 * "updated_at": "2020-04-21 15:13:50"
 * }
 * ],
 * "special_time_list": [
 * {
 * "id": 34,
 * "company_id": 3,
 * "transport_price_id": 3,
 * "start": "10:00:00",
 * "end": "11:00:00",
 * "price": "1.50",
 * "created_at": "2020-04-21 15:13:50",
 * "updated_at": "2020-04-21 15:13:50"
 * }
 * ],
 * "created_at": "2020-03-13 12:00:10"
 * },
 * {
 * "id": 20,
 * "name": "运价方案1",
 * "starting_price": "12.00",
 * "remark": "运价方案2",
 * "status": 1,
 * "part": "固定费用,重量,里程",
 * "type": 2,
 * "type_name": "阶梯固定值计算（固定费用+（重量价格档）*（里程价格档））",
 * "km_list": [
 * {
 * "id": 109,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": 0,
 * "end": 2,
 * "price": "0.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40"
 * },
 * {
 * "id": 110,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": 2,
 * "end": 4,
 * "price": "2.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40"
 * },
 * {
 * "id": 111,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": 4,
 * "end": 999999999,
 * "price": "5.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40"
 * }
 * ],
 * "weight_list": [
 * {
 * "id": 112,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": 0,
 * "end": 2,
 * "price": "0.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40"
 * },
 * {
 * "id": 113,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": 2,
 * "end": 4,
 * "price": "2.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40"
 * },
 * {
 * "id": 114,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": 4,
 * "end": 999999999,
 * "price": "5.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40"
 * }
 * ],
 * "special_time_list": [
 * {
 * "id": 92,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": "08:00:00",
 * "end": "11:00:00",
 * "price": "2.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40"
 * },
 * {
 * "id": 93,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": "11:00:00",
 * "end": "12:00:00",
 * "price": "4.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40"
 * }
 * ],
 * "created_at": "2020-04-22 13:52:19"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test:10002/api/admin/transport-price?page=1",
 * "last": "http://tms-api.test:10002/api/admin/transport-price?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test:10002/api/admin/transport-price",
 * "per_page": 200,
 * "to": 2,
 * "total": 2
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/transport-price/{id} 运价详情
 * @apiName 运价详情
 * @apiGroup 20
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 运价ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 运价ID
 * @apiSuccess {string} data.data.name 名称
 * @apiSuccess {string} data.data.starting_price 起步价
 * @apiSuccess {string} data.data.remark 特别说明
 * @apiSuccess {string} data.data.status 状态1-启用2-禁用
 * @apiSuccess {string} data.data.km_list
 * @apiSuccess {string} data.data.km_list.id
 * @apiSuccess {string} data.data.km_list.company_id 公司ID
 * @apiSuccess {string} data.data.km_list.transport_price_id 运价ID
 * @apiSuccess {string} data.data.km_list.start 起始公里
 * @apiSuccess {string} data.data.km_list.end 截止公里
 * @apiSuccess {string} data.data.km_list.price 加价
 * @apiSuccess {string} data.data.km_list.created_at
 * @apiSuccess {string} data.data.km_list.updated_at
 * @apiSuccess {string} data.data.weight_list
 * @apiSuccess {string} data.data.weight_list.id
 * @apiSuccess {string} data.data.weight_list.company_id 公司ID
 * @apiSuccess {string} data.data.weight_list.transport_price_id 运价ID
 * @apiSuccess {string} data.data.weight_list.start 起始重量
 * @apiSuccess {string} data.data.weight_list.end 截止重量
 * @apiSuccess {string} data.data.weight_list.price 加价
 * @apiSuccess {string} data.data.weight_list.created_at
 * @apiSuccess {string} data.data.weight_list.updated_at
 * @apiSuccess {string} data.data.special_time_list
 * @apiSuccess {string} data.data.special_time_list.id
 * @apiSuccess {string} data.data.special_time_list.company_id 公司ID
 * @apiSuccess {string} data.data.special_time_list.transport_price_id 运价ID
 * @apiSuccess {string} data.data.special_time_list.start 起始时间
 * @apiSuccess {string} data.data.special_time_list.end created_at
 * @apiSuccess {string} data.data.special_time_list.price 加价
 * @apiSuccess {string} data.data.special_time_list.created_at
 * @apiSuccess {string} data.data.special_time_list.updated_at
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.data.type 类型
 * @apiSuccess {string} data.data.type_name 类型说明
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 20,
 * "company_id": 3,
 * "name": "运价方案1",
 * "starting_price": "12.00",
 * "type": 2,
 * "remark": "运价方案2",
 * "status": 1,
 * "created_at": "2020-04-22 13:52:19",
 * "updated_at": "2021-01-27 17:50:40",
 * "type_name": "阶梯固定值计算（固定费用+（重量价格档）*（里程价格档））",
 * "km_list": [
 * {
 * "id": 109,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": 0,
 * "end": 2,
 * "price": "0.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40"
 * },
 * {
 * "id": 110,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": 2,
 * "end": 4,
 * "price": "2.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40"
 * },
 * {
 * "id": 111,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": 4,
 * "end": 999999999,
 * "price": "5.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40"
 * }
 * ],
 * "weight_list": [
 * {
 * "id": 112,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": 0,
 * "end": 2,
 * "price": "0.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40"
 * },
 * {
 * "id": 113,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": 2,
 * "end": 4,
 * "price": "2.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40"
 * },
 * {
 * "id": 114,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": 4,
 * "end": 999999999,
 * "price": "5.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40"
 * }
 * ],
 * "special_time_list": [
 * {
 * "id": 92,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": "08:00:00",
 * "end": "11:00:00",
 * "price": "2.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40",
 * "period": [
 * "08:00:00",
 * "11:00:00"
 * ]
 * },
 * {
 * "id": 93,
 * "company_id": 3,
 * "transport_price_id": 20,
 * "start": "11:00:00",
 * "end": "12:00:00",
 * "price": "4.00",
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40",
 * "period": [
 * "11:00:00",
 * "12:00:00"
 * ]
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/transport-price/{id} 运价修改
 * @apiName 运价修改
 * @apiGroup 20
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 名称
 * @apiParam {string} starting_price 起步价
 * @apiParam {string} remark 特别说明
 * @apiParam {string} status 状态1-启用2-禁用
 * @apiParam {string} km_list 距离运价列表
 * @apiParam {string} weight_list 重量运价列表
 * @apiParam {string} special_time_list 特殊时段运价列表
 * @apiParam {string} id 运价ID
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

/**
 * @api {put} /admin/transport-price/{id}/status 运价启用禁用
 * @apiName 运价启用禁用
 * @apiGroup 20
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 运价ID
 * @apiParam {string} status 状态1-启用2-禁用
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

/**
 * @api {get} /admin/transport-price/{id}/test 价格测试
 * @apiName 价格测试
 * @apiGroup 20
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 运价ID
 * @apiParam {string} km 距离
 * @apiParam {string} weight 重量
 * @apiParam {string} special_time 08:00:00
 * @apiSuccess {string} code
 * @apiSuccess {string} data 计算结果
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": 20,
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/transport-price 运价新增
 * @apiName 运价新增
 * @apiGroup 20
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 名称
 * @apiParam {string} starting_price 起步价
 * @apiParam {string} remark 特别说明
 * @apiParam {string} status 状态1-启用2-禁用
 * @apiParam {string} km_list 距离运价列表
 * @apiParam {string} weight_list 重量运价列表
 * @apiParam {string} special_time_list 特殊时段运价列表
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

/**
 * @api {post} /admin/transport-price/{id}/test 价格测试
 * @apiName 价格测试
 * @apiGroup 20
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} package_list 包裹列表（重量）
 * @apiParam {string} distance 距离
 * @apiParam {string} id 运价方案ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.package_list
 * @apiSuccess {string} data.package_list.weight
 * @apiSuccess {string} data.package_list.count_settlement_amount 估算运价
 * @apiSuccess {string} data.distance 距离
 * @apiSuccess {string} data.count_settlement_amount 估算运价
 * @apiSuccess {string} data.settlement_amount
 * @apiSuccess {string} data.starting_price 固定费用
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "package_list": [
 * {
 * "weight": "12.12",
 * "count_settlement_amount": 0
 * },
 * {
 * "weight": "13.12",
 * "count_settlement_amount": 0
 * }
 * ],
 * "distance": "1",
 * "count_settlement_amount": 5,
 * "settlement_amount": 5,
 * "starting_price": "5.00"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/transport-price/{id}/log 运价方案操作日志
 * @apiName 运价方案操作日志
 * @apiGroup 20
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 运价方案ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.transport_price_id 运价方案列表
 * @apiSuccess {string} data.operation 操作类型
 * @apiSuccess {string} data.content 内容1
 * @apiSuccess {string} data.content.id
 * @apiSuccess {string} data.content.name
 * @apiSuccess {string} data.content.type
 * @apiSuccess {string} data.content.remark
 * @apiSuccess {string} data.content.status
 * @apiSuccess {string} data.content.km_list
 * @apiSuccess {string} data.content.km_list.id
 * @apiSuccess {string} data.content.km_list.end
 * @apiSuccess {string} data.content.km_list.price
 * @apiSuccess {string} data.content.km_list.start
 * @apiSuccess {string} data.content.km_list.company_id
 * @apiSuccess {string} data.content.km_list.created_at
 * @apiSuccess {string} data.content.km_list.updated_at
 * @apiSuccess {string} data.content.km_list.transport_price_id
 * @apiSuccess {string} data.content.type_name
 * @apiSuccess {string} data.content.company_id
 * @apiSuccess {string} data.content.created_at
 * @apiSuccess {string} data.content.updated_at
 * @apiSuccess {string} data.content.weight_list
 * @apiSuccess {string} data.content.weight_list.id
 * @apiSuccess {string} data.content.weight_list.end
 * @apiSuccess {string} data.content.weight_list.price
 * @apiSuccess {string} data.content.weight_list.start
 * @apiSuccess {string} data.content.weight_list.company_id
 * @apiSuccess {string} data.content.weight_list.created_at
 * @apiSuccess {string} data.content.weight_list.updated_at
 * @apiSuccess {string} data.content.weight_list.transport_price_id
 * @apiSuccess {string} data.content.starting_price
 * @apiSuccess {string} data.content.special_time_list
 * @apiSuccess {string} data.content.special_time_list.id
 * @apiSuccess {string} data.content.special_time_list.end
 * @apiSuccess {string} data.content.special_time_list.price
 * @apiSuccess {string} data.content.special_time_list.start
 * @apiSuccess {string} data.content.special_time_list.period
 * @apiSuccess {string} data.content.special_time_list.company_id
 * @apiSuccess {string} data.content.special_time_list.created_at
 * @apiSuccess {string} data.content.special_time_list.updated_at
 * @apiSuccess {string} data.content.special_time_list.transport_price_id
 * @apiSuccess {string} data.second_content 内容2
 * @apiSuccess {string} data.second_content.name
 * @apiSuccess {string} data.second_content.remark
 * @apiSuccess {string} data.second_content.status
 * @apiSuccess {string} data.second_content.km_list
 * @apiSuccess {string} data.second_content.km_list.end
 * @apiSuccess {string} data.second_content.km_list.price
 * @apiSuccess {string} data.second_content.km_list.start
 * @apiSuccess {string} data.second_content.weight_list
 * @apiSuccess {string} data.second_content.weight_list.end
 * @apiSuccess {string} data.second_content.weight_list.price
 * @apiSuccess {string} data.second_content.weight_list.start
 * @apiSuccess {string} data.second_content.starting_price
 * @apiSuccess {string} data.second_content.special_time_list
 * @apiSuccess {string} data.second_content.special_time_list.end
 * @apiSuccess {string} data.second_content.special_time_list.price
 * @apiSuccess {string} data.second_content.special_time_list.start
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 1,
 * "company_id": "3",
 * "transport_price_id": 20,
 * "operation": "2",
 * "content": {
 * "id": 20,
 * "name": "gongli",
 * "type": 1,
 * "remark": "wut",
 * "status": 1,
 * "km_list": [
 * {
 * "id": 88,
 * "end": 10,
 * "price": "5.00",
 * "start": 0,
 * "company_id": 3,
 * "created_at": "2020-11-24 17:31:22",
 * "updated_at": "2020-11-24 17:31:22",
 * "transport_price_id": 20
 * },
 * {
 * "id": 89,
 * "end": 20,
 * "price": "10.00",
 * "start": 10,
 * "company_id": 3,
 * "created_at": "2020-11-24 17:31:22",
 * "updated_at": "2020-11-24 17:31:22",
 * "transport_price_id": 20
 * }
 * ],
 * "type_name": "阶梯乘积值计算（固定费用+（每单位重量价格*重量价格）*（每单位里程价格*里程价格））",
 * "company_id": 3,
 * "created_at": "2020-04-22 13:52:19",
 * "updated_at": "2021-01-22 08:22:30",
 * "weight_list": [
 * {
 * "id": 90,
 * "end": 2,
 * "price": "1.00",
 * "start": 1,
 * "company_id": 3,
 * "created_at": "2020-11-24 17:31:22",
 * "updated_at": "2020-11-24 17:31:22",
 * "transport_price_id": 20
 * },
 * {
 * "id": 91,
 * "end": 3,
 * "price": "2.00",
 * "start": 2,
 * "company_id": 3,
 * "created_at": "2020-11-24 17:31:22",
 * "updated_at": "2020-11-24 17:31:22",
 * "transport_price_id": 20
 * },
 * {
 * "id": 92,
 * "end": 4,
 * "price": "3.00",
 * "start": 3,
 * "company_id": 3,
 * "created_at": "2020-11-24 17:31:22",
 * "updated_at": "2020-11-24 17:31:22",
 * "transport_price_id": 20
 * }
 * ],
 * "starting_price": "5.00",
 * "special_time_list": [
 * {
 * "id": 76,
 * "end": "13:00:00",
 * "price": "1.00",
 * "start": "11:00:00",
 * "period": [
 * "11:00:00",
 * "13:00:00"
 * ],
 * "company_id": 3,
 * "created_at": "2020-11-24 17:31:22",
 * "updated_at": "2020-11-24 17:31:22",
 * "transport_price_id": 20
 * }
 * ]
 * },
 * "second_content": {
 * "name": "运价方案1",
 * "remark": "运价方案2",
 * "status": "1",
 * "km_list": [
 * {
 * "end": 2,
 * "price": 0,
 * "start": 0
 * },
 * {
 * "end": 4,
 * "price": 2,
 * "start": 2
 * },
 * {
 * "end": 999999999,
 * "price": 5,
 * "start": 4
 * }
 * ],
 * "weight_list": [
 * {
 * "end": 2,
 * "price": 0,
 * "start": 0
 * },
 * {
 * "end": 4,
 * "price": 2,
 * "start": 2
 * },
 * {
 * "end": 999999999,
 * "price": 5,
 * "start": 4
 * }
 * ],
 * "starting_price": "12",
 * "special_time_list": [
 * {
 * "end": "11:00:00",
 * "price": 2,
 * "start": "08:00:00"
 * },
 * {
 * "end": "12:00:00",
 * "price": 4,
 * "start": "11:00:00"
 * }
 * ]
 * },
 * "created_at": "2021-01-27 17:22:46",
 * "updated_at": "2021-01-27 17:22:46"
 * },
 * {
 * "id": 2,
 * "company_id": "3",
 * "transport_price_id": 20,
 * "operation": "2",
 * "content": {
 * "id": 20,
 * "name": "运价方案1",
 * "type": 1,
 * "remark": "运价方案2",
 * "status": 1,
 * "km_list": [
 * {
 * "id": 106,
 * "end": 2,
 * "price": "0.00",
 * "start": 0,
 * "company_id": 3,
 * "created_at": "2021-01-27 17:22:46",
 * "updated_at": "2021-01-27 17:22:46",
 * "transport_price_id": 20
 * },
 * {
 * "id": 107,
 * "end": 4,
 * "price": "2.00",
 * "start": 2,
 * "company_id": 3,
 * "created_at": "2021-01-27 17:22:46",
 * "updated_at": "2021-01-27 17:22:46",
 * "transport_price_id": 20
 * },
 * {
 * "id": 108,
 * "end": 999999999,
 * "price": "5.00",
 * "start": 4,
 * "company_id": 3,
 * "created_at": "2021-01-27 17:22:46",
 * "updated_at": "2021-01-27 17:22:46",
 * "transport_price_id": 20
 * }
 * ],
 * "type_name": "阶梯乘积值计算（固定费用+（每单位重量价格*重量价格）*（每单位里程价格*里程价格））",
 * "company_id": 3,
 * "created_at": "2020-04-22 13:52:19",
 * "updated_at": "2021-01-27 17:22:46",
 * "weight_list": [
 * {
 * "id": 109,
 * "end": 2,
 * "price": "0.00",
 * "start": 0,
 * "company_id": 3,
 * "created_at": "2021-01-27 17:22:46",
 * "updated_at": "2021-01-27 17:22:46",
 * "transport_price_id": 20
 * },
 * {
 * "id": 110,
 * "end": 4,
 * "price": "2.00",
 * "start": 2,
 * "company_id": 3,
 * "created_at": "2021-01-27 17:22:46",
 * "updated_at": "2021-01-27 17:22:46",
 * "transport_price_id": 20
 * },
 * {
 * "id": 111,
 * "end": 999999999,
 * "price": "5.00",
 * "start": 4,
 * "company_id": 3,
 * "created_at": "2021-01-27 17:22:46",
 * "updated_at": "2021-01-27 17:22:46",
 * "transport_price_id": 20
 * }
 * ],
 * "starting_price": "12.00",
 * "special_time_list": [
 * {
 * "id": 90,
 * "end": "11:00:00",
 * "price": "2.00",
 * "start": "08:00:00",
 * "period": [
 * "08:00:00",
 * "11:00:00"
 * ],
 * "company_id": 3,
 * "created_at": "2021-01-27 17:22:46",
 * "updated_at": "2021-01-27 17:22:46",
 * "transport_price_id": 20
 * },
 * {
 * "id": 91,
 * "end": "12:00:00",
 * "price": "4.00",
 * "start": "11:00:00",
 * "period": [
 * "11:00:00",
 * "12:00:00"
 * ],
 * "company_id": 3,
 * "created_at": "2021-01-27 17:22:46",
 * "updated_at": "2021-01-27 17:22:46",
 * "transport_price_id": 20
 * }
 * ]
 * },
 * "second_content": {
 * "name": "运价方案1",
 * "type": "2",
 * "remark": "运价方案2",
 * "status": "1",
 * "km_list": [
 * {
 * "end": 2,
 * "price": 0,
 * "start": 0
 * },
 * {
 * "end": 4,
 * "price": 2,
 * "start": 2
 * },
 * {
 * "end": 999999999,
 * "price": 5,
 * "start": 4
 * }
 * ],
 * "weight_list": [
 * {
 * "end": 2,
 * "price": 0,
 * "start": 0
 * },
 * {
 * "end": 4,
 * "price": 2,
 * "start": 2
 * },
 * {
 * "end": 999999999,
 * "price": 5,
 * "start": 4
 * }
 * ],
 * "starting_price": "12",
 * "special_time_list": [
 * {
 * "end": "11:00:00",
 * "price": 2,
 * "start": "08:00:00"
 * },
 * {
 * "end": "12:00:00",
 * "price": 4,
 * "start": "11:00:00"
 * }
 * ]
 * },
 * "created_at": "2021-01-27 17:50:40",
 * "updated_at": "2021-01-27 17:50:40"
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/tour-driver/{tour_no} 获取实际运动线路
 * @apiName 获取实际运动线路
 * @apiGroup 21
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} tour_no TOUR00030002M
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.lon 经度
 * @apiSuccess {string} data.lat 纬度
 * @apiSuccess {string} data.type
 * @apiSuccess {string} data.content 内容
 * @apiSuccess {string} data.address 地址
 * @apiSuccess {string} data.icon_id
 * @apiSuccess {string} data.icon_path
 * @apiSuccess {string} data.tour_no
 * @apiSuccess {string} data.route_tracking_id
 * @apiSuccess {string} data.created_at 时间
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 48,
 * "company_id": 3,
 * "lon": "52.25347699",
 * "lat": "4.62897256",
 * "type": 0,
 * "content": "司机从网点出发",
 * "address": "",
 * "icon_id": 0,
 * "icon_path": "",
 * "tour_no": "TOUR00030002M",
 * "route_tracking_id": 0,
 * "created_at": "2020-04-01 14:17:00",
 * "updated_at": "2020-04-01 14:17:00"
 * },
 * {
 * "id": 49,
 * "company_id": 3,
 * "lon": "4.87465697",
 * "lat": "52.31153637",
 * "type": 0,
 * "content": "到达test客户家",
 * "address": "",
 * "icon_id": 0,
 * "icon_path": "",
 * "tour_no": "TOUR00030002M",
 * "route_tracking_id": 0,
 * "created_at": "2020-04-01 14:20:27",
 * "updated_at": "2020-04-01 14:20:27"
 * },
 * {
 * "id": 53,
 * "company_id": 3,
 * "lon": "4.87465697",
 * "lat": "52.31153637",
 * "type": 0,
 * "content": "从test客户家离开",
 * "address": "",
 * "icon_id": 0,
 * "icon_path": "",
 * "tour_no": "TOUR00030002M",
 * "route_tracking_id": 0,
 * "created_at": "2020-04-01 14:42:25",
 * "updated_at": "2020-04-01 14:42:25"
 * },
 * {
 * "id": 54,
 * "company_id": 3,
 * "lon": "4.87510019",
 * "lat": "52.31153083",
 * "type": 0,
 * "content": "到达test客户家",
 * "address": "",
 * "icon_id": 0,
 * "icon_path": "",
 * "tour_no": "TOUR00030002M",
 * "route_tracking_id": 0,
 * "created_at": "2020-04-01 15:14:13",
 * "updated_at": "2020-04-01 15:14:13"
 * },
 * {
 * "id": 56,
 * "company_id": 3,
 * "lon": "4.87510019",
 * "lat": "52.31153083",
 * "type": 0,
 * "content": "到达testxu客户家",
 * "address": "",
 * "icon_id": 0,
 * "icon_path": "",
 * "tour_no": "TOUR00030002M",
 * "route_tracking_id": 0,
 * "created_at": "2020-04-01 17:58:45",
 * "updated_at": "2020-04-01 17:58:45"
 * },
 * {
 * "id": 57,
 * "company_id": 3,
 * "lon": "4.87510019",
 * "lat": "52.31153083",
 * "type": 0,
 * "content": "到达testxu客户家",
 * "address": "",
 * "icon_id": 0,
 * "icon_path": "",
 * "tour_no": "TOUR00030002M",
 * "route_tracking_id": 0,
 * "created_at": "2020-04-01 18:03:06",
 * "updated_at": "2020-04-01 18:03:06"
 * },
 * {
 * "id": 58,
 * "company_id": 3,
 * "lon": "4.87510019",
 * "lat": "52.31153083",
 * "type": 0,
 * "content": "从testxu客户家离开",
 * "address": "",
 * "icon_id": 0,
 * "icon_path": "",
 * "tour_no": "TOUR00030002M",
 * "route_tracking_id": 0,
 * "created_at": "2020-04-01 18:04:22",
 * "updated_at": "2020-04-01 18:04:22"
 * },
 * {
 * "id": 59,
 * "company_id": 3,
 * "lon": "4.62897256",
 * "lat": "52.25347699",
 * "type": 0,
 * "content": "司机返回网点",
 * "address": "",
 * "icon_id": 0,
 * "icon_path": "",
 * "tour_no": "TOUR00030002M",
 * "route_tracking_id": 0,
 * "created_at": "2020-04-01 18:04:51",
 * "updated_at": "2020-04-01 18:04:51"
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/auth-group 权限组查询
 * @apiName 权限组查询
 * @apiGroup 22
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 权限组ID
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.name 权限组名
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "company_id": 2,
 * "name": "最高权限",
 * "created_at": "2020-03-27 17:27:06",
 * "updated_at": "2020-03-27 17:27:08"
 * },
 * {
 * "id": 2,
 * "company_id": 2,
 * "name": "权限组4",
 * "created_at": "2020-04-01 16:24:16",
 * "updated_at": "2020-04-01 16:24:16"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/auth-group?page=1",
 * "last": "http://tms-api.test/api/admin/auth-group?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/auth-group",
 * "per_page": 10,
 * "to": 2,
 * "total": 2
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/auth-group 权限组新增
 * @apiName 权限组新增
 * @apiGroup 22
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 权限组名
 * @apiParam {string} permission 权限代号列表
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

/**
 * @api {put} /admin/auth-group/{id} 权限组修改
 * @apiName 权限组修改
 * @apiGroup 22
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 权限组名
 * @apiParam {string} permission 权限代号列表
 * @apiParam {string} id 权限组ID
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

/**
 * @api {delete} /admin/auth-group/{id} 权限组删除
 * @apiName 权限组删除
 * @apiGroup 22
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 权限组ID
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

/**
 * @api {post} /admin/auth-group/auth 获取所有权限
 * @apiName 获取所有权限
 * @apiGroup 22
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data 权限编号列表（字符串）
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": "2.1,3.1,3.2,3.3,3.4,3.5,3.6,3.7,4.2,4.3,4.4,5.2,5.3,5.1,5.4,5.5,6.1,6.2,6.3,6.4,6.5,6.6,7.1,7.2,7.3,7.4,8.1,8.2,9.1,9.2,9.3,9.4,9.5,9.6,10.1,10.2,10.3,10.4,10.5,11.1,11.2,11.3,11.4,14.1,14.2,14.3,14.7,14.4,14.5,14.6,17.1,17.2,17.3,17.4,18.1,18.2,18.3,18.4,18.5,18.6,19.1,19.2,19.3,19.4,20.1,20.2,20.3,20.4,20.5",
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/auth-group/{id} 权限组详情
 * @apiName 权限组详情
 * @apiGroup 22
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 权限组ID
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.name 权限组名
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "company_id": 2,
 * "name": "最高权限",
 * "created_at": "2020-03-27 17:27:06",
 * "updated_at": "2020-03-27 17:27:08"
 * },
 * {
 * "id": 2,
 * "company_id": 2,
 * "name": "权限组4",
 * "created_at": "2020-04-01 16:24:16",
 * "updated_at": "2020-04-01 16:24:16"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/auth-group?page=1",
 * "last": "http://tms-api.test/api/admin/auth-group?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/auth-group",
 * "per_page": 10,
 * "to": 2,
 * "total": 2
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/auth-group/{id}/member 权限组成员
 * @apiName 权限组成员
 * @apiGroup 22
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.current_page
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 员工ID
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.email 员工邮箱
 * @apiSuccess {string} data.data.username 员工用户名
 * @apiSuccess {string} data.data.phone 员工号码
 * @apiSuccess {string} data.data.encrypt
 * @apiSuccess {string} data.data.fullname 员工全名
 * @apiSuccess {string} data.data.auth_group_id 权限组ID
 * @apiSuccess {string} data.data.institution_id 组织结构ID
 * @apiSuccess {string} data.data.remark 备注
 * @apiSuccess {string} data.data.forbid_login 禁止登录
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.first_page_url
 * @apiSuccess {string} data.from
 * @apiSuccess {string} data.last_page
 * @apiSuccess {string} data.last_page_url
 * @apiSuccess {string} data.next_page_url
 * @apiSuccess {string} data.path
 * @apiSuccess {string} data.per_page
 * @apiSuccess {string} data.prev_page_url
 * @apiSuccess {string} data.to
 * @apiSuccess {string} data.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "current_page": 1,
 * "data": [
 * {
 * "id": 27,
 * "company_id": 19,
 * "email": "398352614@qq.com",
 * "username": "398352614@qq.com",
 * "phone": "",
 * "encrypt": "",
 * "fullname": "398352614@qq.com",
 * "auth_group_id": 1,
 * "institution_id": 43,
 * "remark": "",
 * "forbid_login": false,
 * "created_at": "2020-05-09 18:23:49",
 * "updated_at": "2020-05-09 18:23:49"
 * }
 * ],
 * "first_page_url": "http://tms-api.test/api/admin/auth-group/1/member?page=1",
 * "from": 1,
 * "last_page": 1,
 * "last_page_url": "http://tms-api.test/api/admin/auth-group/1/member?page=1",
 * "next_page_url": null,
 * "path": "http://tms-api.test/api/admin/auth-group/1/member",
 * "per_page": 15,
 * "prev_page_url": null,
 * "to": 1,
 * "total": 1
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/auth-group/move 将成员移动至权限组
 * @apiName 将成员移动至权限组
 * @apiGroup 22
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} ids 员工名列表
 * @apiParam {string} auth_group_id 权限组名
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

/**
 * @api {post} /admin/order-import 订单导入
 * @apiName 订单导入
 * @apiGroup 23
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} file 文件
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.create_date
 * @apiSuccess {string} data.type
 * @apiSuccess {string} data.merchant
 * @apiSuccess {string} data.out_user_id
 * @apiSuccess {string} data.out_order_no
 * @apiSuccess {string} data.place_fullname
 * @apiSuccess {string} data.place_phone
 * @apiSuccess {string} data.place_post_code
 * @apiSuccess {string} data.place_house_number
 * @apiSuccess {string} data.place_city
 * @apiSuccess {string} data.place_street
 * @apiSuccess {string} data.execution_date
 * @apiSuccess {string} data.second_place_fullname
 * @apiSuccess {string} data.second_place_phone
 * @apiSuccess {string} data.second_place_post_code
 * @apiSuccess {string} data.second_place_house_number
 * @apiSuccess {string} data.second_place_city
 * @apiSuccess {string} data.second_place_street
 * @apiSuccess {string} data.second_execution_date
 * @apiSuccess {string} data.amount_1
 * @apiSuccess {string} data.amount_2
 * @apiSuccess {string} data.amount_3
 * @apiSuccess {string} data.amount_4
 * @apiSuccess {string} data.amount_5
 * @apiSuccess {string} data.amount_6
 * @apiSuccess {string} data.amount_7
 * @apiSuccess {string} data.amount_8
 * @apiSuccess {string} data.amount_9
 * @apiSuccess {string} data.amount_10
 * @apiSuccess {string} data.amount_11
 * @apiSuccess {string} data.settlement_amount
 * @apiSuccess {string} data.settlement_type
 * @apiSuccess {string} data.control_mode
 * @apiSuccess {string} data.receipt_type
 * @apiSuccess {string} data.receipt_count
 * @apiSuccess {string} data.special_remark
 * @apiSuccess {string} data.mask_code
 * @apiSuccess {string} data.package_no_1
 * @apiSuccess {string} data.package_name_1
 * @apiSuccess {string} data.package_weight_1
 * @apiSuccess {string} data.package_feature_1
 * @apiSuccess {string} data.package_remark_1
 * @apiSuccess {string} data.package_expiration_date_1
 * @apiSuccess {string} data.package_out_order_no_1
 * @apiSuccess {string} data.package_no_2
 * @apiSuccess {string} data.package_name_2
 * @apiSuccess {string} data.package_weight_2
 * @apiSuccess {string} data.package_feature_2
 * @apiSuccess {string} data.package_remark_2
 * @apiSuccess {string} data.package_expiration_date_2
 * @apiSuccess {string} data.package_out_order_no_2
 * @apiSuccess {string} data.package_no_3
 * @apiSuccess {string} data.package_name_3
 * @apiSuccess {string} data.package_weight_3
 * @apiSuccess {string} data.package_feature_3
 * @apiSuccess {string} data.package_remark_3
 * @apiSuccess {string} data.package_expiration_date_3
 * @apiSuccess {string} data.package_out_order_no_3
 * @apiSuccess {string} data.package_no_4
 * @apiSuccess {string} data.package_name_4
 * @apiSuccess {string} data.package_weight_4
 * @apiSuccess {string} data.package_feature_4
 * @apiSuccess {string} data.package_remark_4
 * @apiSuccess {string} data.package_expiration_date_4
 * @apiSuccess {string} data.package_out_order_no_4
 * @apiSuccess {string} data.package_no_5
 * @apiSuccess {string} data.package_name_5
 * @apiSuccess {string} data.package_weight_5
 * @apiSuccess {string} data.package_feature_5
 * @apiSuccess {string} data.package_remark_5
 * @apiSuccess {string} data.package_expiration_date_5
 * @apiSuccess {string} data.package_out_order_no_5
 * @apiSuccess {string} data.material_code_1
 * @apiSuccess {string} data.material_name_1
 * @apiSuccess {string} data.material_count_1
 * @apiSuccess {string} data.material_weight_1
 * @apiSuccess {string} data.material_size_1
 * @apiSuccess {string} data.material_type_1
 * @apiSuccess {string} data.material_pack_type_1
 * @apiSuccess {string} data.material_price_1
 * @apiSuccess {string} data.material_remark_1
 * @apiSuccess {string} data.material_out_order_no_1
 * @apiSuccess {string} data.material_code_2
 * @apiSuccess {string} data.material_name_2
 * @apiSuccess {string} data.material_count_2
 * @apiSuccess {string} data.material_weight_2
 * @apiSuccess {string} data.material_size_2
 * @apiSuccess {string} data.material_type_2
 * @apiSuccess {string} data.material_pack_type_2
 * @apiSuccess {string} data.material_price_2
 * @apiSuccess {string} data.material_remark_2
 * @apiSuccess {string} data.material_out_order_no_2
 * @apiSuccess {string} data.material_code_3
 * @apiSuccess {string} data.material_name_3
 * @apiSuccess {string} data.material_count_3
 * @apiSuccess {string} data.material_weight_3
 * @apiSuccess {string} data.material_size_3
 * @apiSuccess {string} data.material_type_3
 * @apiSuccess {string} data.material_pack_type_3
 * @apiSuccess {string} data.material_price_3
 * @apiSuccess {string} data.material_remark_3
 * @apiSuccess {string} data.material_out_order_no_3
 * @apiSuccess {string} data.material_code_4
 * @apiSuccess {string} data.material_name_4
 * @apiSuccess {string} data.material_count_4
 * @apiSuccess {string} data.material_weight_4
 * @apiSuccess {string} data.material_size_4
 * @apiSuccess {string} data.material_type_4
 * @apiSuccess {string} data.material_pack_type_4
 * @apiSuccess {string} data.material_price_4
 * @apiSuccess {string} data.material_remark_4
 * @apiSuccess {string} data.material_out_order_no_4
 * @apiSuccess {string} data.material_code_5
 * @apiSuccess {string} data.material_name_5
 * @apiSuccess {string} data.material_count_5
 * @apiSuccess {string} data.material_weight_5
 * @apiSuccess {string} data.material_size_5
 * @apiSuccess {string} data.material_type_5
 * @apiSuccess {string} data.material_pack_type_5
 * @apiSuccess {string} data.material_price_5
 * @apiSuccess {string} data.material_remark_5
 * @apiSuccess {string} data.material_out_order_no_5
 * @apiSuccess {string} data.merchant_id
 * @apiSuccess {string} data.type_name
 * @apiSuccess {string} data.place_country
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": "",
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/order-import/check 订单检查
 * @apiName 订单检查
 * @apiGroup 23
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} create_date
 * @apiParam {string} type
 * @apiParam {string} merchant
 * @apiParam {string} out_user_id
 * @apiParam {string} out_order_no
 * @apiParam {string} place_fullname
 * @apiParam {string} place_phone
 * @apiParam {string} place_post_code
 * @apiParam {string} place_house_number
 * @apiParam {string} place_city
 * @apiParam {string} place_street
 * @apiParam {string} execution_date
 * @apiParam {string} second_place_fullname
 * @apiParam {string} second_place_phone
 * @apiParam {string} second_place_post_code
 * @apiParam {string} second_place_house_number
 * @apiParam {string} second_place_city
 * @apiParam {string} second_place_street
 * @apiParam {string} second_execution_date
 * @apiParam {string} amount_1
 * @apiParam {string} amount_2
 * @apiParam {string} amount_3
 * @apiParam {string} amount_4
 * @apiParam {string} amount_5
 * @apiParam {string} amount_6
 * @apiParam {string} amount_7
 * @apiParam {string} amount_8
 * @apiParam {string} amount_9
 * @apiParam {string} amount_10
 * @apiParam {string} amount_11
 * @apiParam {string} settlement_amount
 * @apiParam {string} settlement_type
 * @apiParam {string} control_mode
 * @apiParam {string} receipt_type
 * @apiParam {string} receipt_count
 * @apiParam {string} special_remark
 * @apiParam {string} mask_code
 * @apiParam {string} package_no_1
 * @apiParam {string} package_name_1
 * @apiParam {string} package_weight_1
 * @apiParam {string} package_feature_1
 * @apiParam {string} package_remark_1
 * @apiParam {string} package_expiration_date_1
 * @apiParam {string} package_out_order_no_1
 * @apiParam {string} package_no_2
 * @apiParam {string} package_name_2
 * @apiParam {string} package_weight_2
 * @apiParam {string} package_feature_2
 * @apiParam {string} package_remark_2
 * @apiParam {string} package_expiration_date_2
 * @apiParam {string} package_out_order_no_2
 * @apiParam {string} package_no_3
 * @apiParam {string} package_name_3
 * @apiParam {string} package_weight_3
 * @apiParam {string} package_feature_3
 * @apiParam {string} package_remark_3
 * @apiParam {string} package_expiration_date_3
 * @apiParam {string} package_out_order_no_3
 * @apiParam {string} package_no_4
 * @apiParam {string} package_name_4
 * @apiParam {string} package_weight_4
 * @apiParam {string} package_feature_4
 * @apiParam {string} package_remark_4
 * @apiParam {string} package_expiration_date_4
 * @apiParam {string} package_out_order_no_4
 * @apiParam {string} package_no_5
 * @apiParam {string} package_name_5
 * @apiParam {string} package_weight_5
 * @apiParam {string} package_feature_5
 * @apiParam {string} package_remark_5
 * @apiParam {string} package_expiration_date_5
 * @apiParam {string} package_out_order_no_5
 * @apiParam {string} material_code_1
 * @apiParam {string} material_name_1
 * @apiParam {string} material_count_1
 * @apiParam {string} material_weight_1
 * @apiParam {string} material_size_1
 * @apiParam {string} material_type_1
 * @apiParam {string} material_pack_type_1
 * @apiParam {string} material_price_1
 * @apiParam {string} material_remark_1
 * @apiParam {string} material_out_order_no_1
 * @apiParam {string} material_code_2
 * @apiParam {string} material_name_2
 * @apiParam {string} material_count_2
 * @apiParam {string} material_weight_2
 * @apiParam {string} material_size_2
 * @apiParam {string} material_type_2
 * @apiParam {string} material_pack_type_2
 * @apiParam {string} material_price_2
 * @apiParam {string} material_remark_2
 * @apiParam {string} material_out_order_no_2
 * @apiParam {string} material_code_3
 * @apiParam {string} material_name_3
 * @apiParam {string} material_count_3
 * @apiParam {string} material_weight_3
 * @apiParam {string} material_size_3
 * @apiParam {string} material_type_3
 * @apiParam {string} material_pack_type_3
 * @apiParam {string} material_price_3
 * @apiParam {string} material_remark_3
 * @apiParam {string} material_out_order_no_3
 * @apiParam {string} material_code_4
 * @apiParam {string} material_name_4
 * @apiParam {string} material_count_4
 * @apiParam {string} material_weight_4
 * @apiParam {string} material_size_4
 * @apiParam {string} material_type_4
 * @apiParam {string} material_pack_type_4
 * @apiParam {string} material_price_4
 * @apiParam {string} material_remark_4
 * @apiParam {string} material_out_order_no_4
 * @apiParam {string} material_code_5
 * @apiParam {string} material_name_5
 * @apiParam {string} material_count_5
 * @apiParam {string} material_weight_5
 * @apiParam {string} material_size_5
 * @apiParam {string} material_type_5
 * @apiParam {string} material_pack_type_5
 * @apiParam {string} material_price_5
 * @apiParam {string} material_remark_5
 * @apiParam {string} material_out_order_no_5
 * @apiParam {string} merchant_id
 * @apiParam {string} type_name
 * @apiParam {string} place_country
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.status
 * @apiSuccess {string} data.error
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.create_date
 * @apiSuccess {string} data.data.type
 * @apiSuccess {string} data.data.merchant
 * @apiSuccess {string} data.data.out_user_id
 * @apiSuccess {string} data.data.out_order_no
 * @apiSuccess {string} data.data.place_fullname
 * @apiSuccess {string} data.data.place_phone
 * @apiSuccess {string} data.data.place_post_code
 * @apiSuccess {string} data.data.place_house_number
 * @apiSuccess {string} data.data.place_city
 * @apiSuccess {string} data.data.place_street
 * @apiSuccess {string} data.data.execution_date
 * @apiSuccess {string} data.data.second_place_fullname
 * @apiSuccess {string} data.data.second_place_phone
 * @apiSuccess {string} data.data.second_place_post_code
 * @apiSuccess {string} data.data.second_place_house_number
 * @apiSuccess {string} data.data.second_place_city
 * @apiSuccess {string} data.data.second_place_street
 * @apiSuccess {string} data.data.second_execution_date
 * @apiSuccess {string} data.data.amount_1
 * @apiSuccess {string} data.data.amount_2
 * @apiSuccess {string} data.data.amount_3
 * @apiSuccess {string} data.data.amount_4
 * @apiSuccess {string} data.data.amount_5
 * @apiSuccess {string} data.data.amount_6
 * @apiSuccess {string} data.data.amount_7
 * @apiSuccess {string} data.data.amount_8
 * @apiSuccess {string} data.data.amount_9
 * @apiSuccess {string} data.data.amount_10
 * @apiSuccess {string} data.data.amount_11
 * @apiSuccess {string} data.data.settlement_amount
 * @apiSuccess {string} data.data.settlement_type
 * @apiSuccess {string} data.data.control_mode
 * @apiSuccess {string} data.data.receipt_type
 * @apiSuccess {string} data.data.receipt_count
 * @apiSuccess {string} data.data.special_remark
 * @apiSuccess {string} data.data.mask_code
 * @apiSuccess {string} data.data.package_no_1
 * @apiSuccess {string} data.data.package_name_1
 * @apiSuccess {string} data.data.package_weight_1
 * @apiSuccess {string} data.data.package_feature_1
 * @apiSuccess {string} data.data.package_remark_1
 * @apiSuccess {string} data.data.package_expiration_date_1
 * @apiSuccess {string} data.data.package_out_order_no_1
 * @apiSuccess {string} data.data.package_no_2
 * @apiSuccess {string} data.data.package_name_2
 * @apiSuccess {string} data.data.package_weight_2
 * @apiSuccess {string} data.data.package_feature_2
 * @apiSuccess {string} data.data.package_remark_2
 * @apiSuccess {string} data.data.package_expiration_date_2
 * @apiSuccess {string} data.data.package_out_order_no_2
 * @apiSuccess {string} data.data.package_no_3
 * @apiSuccess {string} data.data.package_name_3
 * @apiSuccess {string} data.data.package_weight_3
 * @apiSuccess {string} data.data.package_feature_3
 * @apiSuccess {string} data.data.package_remark_3
 * @apiSuccess {string} data.data.package_expiration_date_3
 * @apiSuccess {string} data.data.package_out_order_no_3
 * @apiSuccess {string} data.data.package_no_4
 * @apiSuccess {string} data.data.package_name_4
 * @apiSuccess {string} data.data.package_weight_4
 * @apiSuccess {string} data.data.package_feature_4
 * @apiSuccess {string} data.data.package_remark_4
 * @apiSuccess {string} data.data.package_expiration_date_4
 * @apiSuccess {string} data.data.package_out_order_no_4
 * @apiSuccess {string} data.data.package_no_5
 * @apiSuccess {string} data.data.package_name_5
 * @apiSuccess {string} data.data.package_weight_5
 * @apiSuccess {string} data.data.package_feature_5
 * @apiSuccess {string} data.data.package_remark_5
 * @apiSuccess {string} data.data.package_expiration_date_5
 * @apiSuccess {string} data.data.package_out_order_no_5
 * @apiSuccess {string} data.data.material_code_1
 * @apiSuccess {string} data.data.material_name_1
 * @apiSuccess {string} data.data.material_count_1
 * @apiSuccess {string} data.data.material_weight_1
 * @apiSuccess {string} data.data.material_size_1
 * @apiSuccess {string} data.data.material_type_1
 * @apiSuccess {string} data.data.material_pack_type_1
 * @apiSuccess {string} data.data.material_price_1
 * @apiSuccess {string} data.data.material_remark_1
 * @apiSuccess {string} data.data.material_out_order_no_1
 * @apiSuccess {string} data.data.material_code_2
 * @apiSuccess {string} data.data.material_name_2
 * @apiSuccess {string} data.data.material_count_2
 * @apiSuccess {string} data.data.material_weight_2
 * @apiSuccess {string} data.data.material_size_2
 * @apiSuccess {string} data.data.material_type_2
 * @apiSuccess {string} data.data.material_pack_type_2
 * @apiSuccess {string} data.data.material_price_2
 * @apiSuccess {string} data.data.material_remark_2
 * @apiSuccess {string} data.data.material_out_order_no_2
 * @apiSuccess {string} data.data.material_code_3
 * @apiSuccess {string} data.data.material_name_3
 * @apiSuccess {string} data.data.material_count_3
 * @apiSuccess {string} data.data.material_weight_3
 * @apiSuccess {string} data.data.material_size_3
 * @apiSuccess {string} data.data.material_type_3
 * @apiSuccess {string} data.data.material_pack_type_3
 * @apiSuccess {string} data.data.material_price_3
 * @apiSuccess {string} data.data.material_remark_3
 * @apiSuccess {string} data.data.material_out_order_no_3
 * @apiSuccess {string} data.data.material_code_4
 * @apiSuccess {string} data.data.material_name_4
 * @apiSuccess {string} data.data.material_count_4
 * @apiSuccess {string} data.data.material_weight_4
 * @apiSuccess {string} data.data.material_size_4
 * @apiSuccess {string} data.data.material_type_4
 * @apiSuccess {string} data.data.material_pack_type_4
 * @apiSuccess {string} data.data.material_price_4
 * @apiSuccess {string} data.data.material_remark_4
 * @apiSuccess {string} data.data.material_out_order_no_4
 * @apiSuccess {string} data.data.material_code_5
 * @apiSuccess {string} data.data.material_name_5
 * @apiSuccess {string} data.data.material_count_5
 * @apiSuccess {string} data.data.material_weight_5
 * @apiSuccess {string} data.data.material_size_5
 * @apiSuccess {string} data.data.material_type_5
 * @apiSuccess {string} data.data.material_pack_type_5
 * @apiSuccess {string} data.data.material_price_5
 * @apiSuccess {string} data.data.material_remark_5
 * @apiSuccess {string} data.data.material_out_order_no_5
 * @apiSuccess {string} data.data.merchant_id
 * @apiSuccess {string} data.data.type_name
 * @apiSuccess {string} data.data.place_country
 * @apiSuccess {string} data.data.place_province
 * @apiSuccess {string} data.data.place_district
 * @apiSuccess {string} data.data.place_lat
 * @apiSuccess {string} data.data.place_lon
 * @apiSuccess {string} data.data.place_address
 * @apiSuccess {string} data.data.second_place_address
 * @apiSuccess {string} data.data.distance
 * @apiSuccess {string} data.data.count_settlement_amount
 * @apiSuccess {string} data.data.package_settlement_amount
 * @apiSuccess {string} data.data.starting_price
 * @apiSuccess {string} data.data.transport_price_id
 * @apiSuccess {string} data.data.transport_price_type
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "status": 1,
 * "error": {
 * "merchant": "merchant 字段是必须的"
 * },
 * "data": {
 * "create_date": "2021-06-04",
 * "type": "1",
 * "merchant": "欧亚商城",
 * "out_user_id": "",
 * "out_order_no": "",
 * "place_fullname": "aad",
 * "place_phone": "123213",
 * "place_post_code": "2153PJ",
 * "place_house_number": 20,
 * "place_city": "Nieuw-Vennep",
 * "place_street": "Pesetaweg",
 * "execution_date": "2021-06-04",
 * "second_place_fullname": "",
 * "second_place_phone": "",
 * "second_place_post_code": "",
 * "second_place_house_number": "",
 * "second_place_city": "",
 * "second_place_street": "",
 * "second_execution_date": "",
 * "amount_1": "",
 * "amount_2": "",
 * "amount_3": "",
 * "amount_4": "",
 * "amount_5": "",
 * "amount_6": "",
 * "amount_7": "",
 * "amount_8": "",
 * "amount_9": "",
 * "amount_10": "",
 * "amount_11": "",
 * "settlement_amount": "10.00",
 * "settlement_type": "",
 * "control_mode": "",
 * "receipt_type": "",
 * "receipt_count": "",
 * "special_remark": "",
 * "mask_code": "",
 * "package_no_1": "06041411",
 * "package_name_1": "",
 * "package_weight_1": "",
 * "package_feature_1": "",
 * "package_remark_1": "",
 * "package_expiration_date_1": "",
 * "package_out_order_no_1": "",
 * "package_no_2": "",
 * "package_name_2": "",
 * "package_weight_2": "",
 * "package_feature_2": "",
 * "package_remark_2": "",
 * "package_expiration_date_2": "",
 * "package_out_order_no_2": "",
 * "package_no_3": "",
 * "package_name_3": "",
 * "package_weight_3": "",
 * "package_feature_3": "",
 * "package_remark_3": "",
 * "package_expiration_date_3": "",
 * "package_out_order_no_3": "",
 * "package_no_4": "",
 * "package_name_4": "",
 * "package_weight_4": "",
 * "package_feature_4": "",
 * "package_remark_4": "",
 * "package_expiration_date_4": "",
 * "package_out_order_no_4": "",
 * "package_no_5": "",
 * "package_name_5": "",
 * "package_weight_5": "",
 * "package_feature_5": "",
 * "package_remark_5": "",
 * "package_expiration_date_5": "",
 * "package_out_order_no_5": "",
 * "material_code_1": "",
 * "material_name_1": "",
 * "material_count_1": "",
 * "material_weight_1": "",
 * "material_size_1": "",
 * "material_type_1": "",
 * "material_pack_type_1": "",
 * "material_price_1": "",
 * "material_remark_1": "",
 * "material_out_order_no_1": "",
 * "material_code_2": "",
 * "material_name_2": "",
 * "material_count_2": "",
 * "material_weight_2": "",
 * "material_size_2": "",
 * "material_type_2": "",
 * "material_pack_type_2": "",
 * "material_price_2": "",
 * "material_remark_2": "",
 * "material_out_order_no_2": "",
 * "material_code_3": "",
 * "material_name_3": "",
 * "material_count_3": "",
 * "material_weight_3": "",
 * "material_size_3": "",
 * "material_type_3": "",
 * "material_pack_type_3": "",
 * "material_price_3": "",
 * "material_remark_3": "",
 * "material_out_order_no_3": "",
 * "material_code_4": "",
 * "material_name_4": "",
 * "material_count_4": "",
 * "material_weight_4": "",
 * "material_size_4": "",
 * "material_type_4": "",
 * "material_pack_type_4": "",
 * "material_price_4": "",
 * "material_remark_4": "",
 * "material_out_order_no_4": "",
 * "material_code_5": "",
 * "material_name_5": "",
 * "material_count_5": "",
 * "material_weight_5": "",
 * "material_size_5": "",
 * "material_type_5": "",
 * "material_pack_type_5": "",
 * "material_price_5": "",
 * "material_remark_5": "",
 * "material_out_order_no_5": "",
 * "merchant_id": "3",
 * "type_name": "提货->网点",
 * "place_country": "NL",
 * "place_province": "Noord-Holland",
 * "place_district": "Haarlemmermeer",
 * "place_lat": 52.25347699,
 * "place_lon": 4.62897256,
 * "place_address": "NL Nieuw-Vennep Pesetaweg 20 2153PJ",
 * "second_place_address": "",
 * "distance": 1,
 * "count_settlement_amount": "10.00",
 * "package_settlement_amount": "0.00",
 * "starting_price": "10.00",
 * "transport_price_id": 69,
 * "transport_price_type": 1
 * }
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/order-import/list 订单批量新增
 * @apiName 订单批量新增
 * @apiGroup 23
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} status
 * @apiParam {string} error
 * @apiParam {string} data
 * @apiParam {string} data>>create_date
 * @apiParam {string} data>>type
 * @apiParam {string} data>>merchant
 * @apiParam {string} data>>out_user_id
 * @apiParam {string} data>>out_order_no
 * @apiParam {string} data>>place_fullname
 * @apiParam {string} data>>place_phone
 * @apiParam {string} data>>place_post_code
 * @apiParam {string} data>>place_house_number
 * @apiParam {string} data>>place_city
 * @apiParam {string} data>>place_street
 * @apiParam {string} data>>execution_date
 * @apiParam {string} data>>second_place_fullname
 * @apiParam {string} data>>second_place_phone
 * @apiParam {string} data>>second_place_post_code
 * @apiParam {string} data>>second_place_house_number
 * @apiParam {string} data>>second_place_city
 * @apiParam {string} data>>second_place_street
 * @apiParam {string} data>>second_execution_date
 * @apiParam {string} data>>amount_1
 * @apiParam {string} data>>amount_2
 * @apiParam {string} data>>amount_3
 * @apiParam {string} data>>amount_4
 * @apiParam {string} data>>amount_5
 * @apiParam {string} data>>amount_6
 * @apiParam {string} data>>amount_7
 * @apiParam {string} data>>amount_8
 * @apiParam {string} data>>amount_9
 * @apiParam {string} data>>amount_10
 * @apiParam {string} data>>amount_11
 * @apiParam {string} data>>settlement_amount
 * @apiParam {string} data>>settlement_type
 * @apiParam {string} data>>control_mode
 * @apiParam {string} data>>receipt_type
 * @apiParam {string} data>>receipt_count
 * @apiParam {string} data>>special_remark
 * @apiParam {string} data>>mask_code
 * @apiParam {string} data>>package_no_1
 * @apiParam {string} data>>package_name_1
 * @apiParam {string} data>>package_weight_1
 * @apiParam {string} data>>package_feature_1
 * @apiParam {string} data>>package_remark_1
 * @apiParam {string} data>>package_expiration_date_1
 * @apiParam {string} data>>package_out_order_no_1
 * @apiParam {string} data>>package_no_2
 * @apiParam {string} data>>package_name_2
 * @apiParam {string} data>>package_weight_2
 * @apiParam {string} data>>package_feature_2
 * @apiParam {string} data>>package_remark_2
 * @apiParam {string} data>>package_expiration_date_2
 * @apiParam {string} data>>package_out_order_no_2
 * @apiParam {string} data>>package_no_3
 * @apiParam {string} data>>package_name_3
 * @apiParam {string} data>>package_weight_3
 * @apiParam {string} data>>package_feature_3
 * @apiParam {string} data>>package_remark_3
 * @apiParam {string} data>>package_expiration_date_3
 * @apiParam {string} data>>package_out_order_no_3
 * @apiParam {string} data>>package_no_4
 * @apiParam {string} data>>package_name_4
 * @apiParam {string} data>>package_weight_4
 * @apiParam {string} data>>package_feature_4
 * @apiParam {string} data>>package_remark_4
 * @apiParam {string} data>>package_expiration_date_4
 * @apiParam {string} data>>package_out_order_no_4
 * @apiParam {string} data>>package_no_5
 * @apiParam {string} data>>package_name_5
 * @apiParam {string} data>>package_weight_5
 * @apiParam {string} data>>package_feature_5
 * @apiParam {string} data>>package_remark_5
 * @apiParam {string} data>>package_expiration_date_5
 * @apiParam {string} data>>package_out_order_no_5
 * @apiParam {string} data>>material_code_1
 * @apiParam {string} data>>material_name_1
 * @apiParam {string} data>>material_count_1
 * @apiParam {string} data>>material_weight_1
 * @apiParam {string} data>>material_size_1
 * @apiParam {string} data>>material_type_1
 * @apiParam {string} data>>material_pack_type_1
 * @apiParam {string} data>>material_price_1
 * @apiParam {string} data>>material_remark_1
 * @apiParam {string} data>>material_out_order_no_1
 * @apiParam {string} data>>material_code_2
 * @apiParam {string} data>>material_name_2
 * @apiParam {string} data>>material_count_2
 * @apiParam {string} data>>material_weight_2
 * @apiParam {string} data>>material_size_2
 * @apiParam {string} data>>material_type_2
 * @apiParam {string} data>>material_pack_type_2
 * @apiParam {string} data>>material_price_2
 * @apiParam {string} data>>material_remark_2
 * @apiParam {string} data>>material_out_order_no_2
 * @apiParam {string} data>>material_code_3
 * @apiParam {string} data>>material_name_3
 * @apiParam {string} data>>material_count_3
 * @apiParam {string} data>>material_weight_3
 * @apiParam {string} data>>material_size_3
 * @apiParam {string} data>>material_type_3
 * @apiParam {string} data>>material_pack_type_3
 * @apiParam {string} data>>material_price_3
 * @apiParam {string} data>>material_remark_3
 * @apiParam {string} data>>material_out_order_no_3
 * @apiParam {string} data>>material_code_4
 * @apiParam {string} data>>material_name_4
 * @apiParam {string} data>>material_count_4
 * @apiParam {string} data>>material_weight_4
 * @apiParam {string} data>>material_size_4
 * @apiParam {string} data>>material_type_4
 * @apiParam {string} data>>material_pack_type_4
 * @apiParam {string} data>>material_price_4
 * @apiParam {string} data>>material_remark_4
 * @apiParam {string} data>>material_out_order_no_4
 * @apiParam {string} data>>material_code_5
 * @apiParam {string} data>>material_name_5
 * @apiParam {string} data>>material_count_5
 * @apiParam {string} data>>material_weight_5
 * @apiParam {string} data>>material_size_5
 * @apiParam {string} data>>material_type_5
 * @apiParam {string} data>>material_pack_type_5
 * @apiParam {string} data>>material_price_5
 * @apiParam {string} data>>material_remark_5
 * @apiParam {string} data>>material_out_order_no_5
 * @apiParam {string} data>>merchant_id
 * @apiParam {string} data>>type_name
 * @apiParam {string} data>>place_country
 * @apiParam {string} data>>place_province
 * @apiParam {string} data>>place_district
 * @apiParam {string} data>>place_lat
 * @apiParam {string} data>>place_lon
 * @apiParam {string} data>>place_address
 * @apiParam {string} data>>second_place_address
 * @apiParam {string} data>>distance
 * @apiParam {string} data>>count_settlement_amount
 * @apiParam {string} data>>package_settlement_amount
 * @apiParam {string} data>>starting_price
 * @apiParam {string} data>>transport_price_id
 * @apiParam {string} data>>transport_price_type
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

/**
 * @api {get} /admin/order-import/template 订单导入模板
 * @apiName 订单导入模板
 * @apiGroup 23
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data 模板表格路径
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": "tms-api.test/storage/admin/file/2\\template\\order_import_template.xlsx",
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/order-no-rule 列表查询
 * @apiName 列表查询
 * @apiGroup 24
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id ID
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.type 类型
 * @apiSuccess {string} data.data.type_name 类型名称
 * @apiSuccess {string} data.data.prefix 开始字符
 * @apiSuccess {string} data.data.start_index
 * @apiSuccess {string} data.data.int_length 数字长度
 * @apiSuccess {string} data.data.start_string_index
 * @apiSuccess {string} data.data.string_length 字符长度
 * @apiSuccess {string} data.data.max_no 最大单号
 * @apiSuccess {string} data.data.status 状态1-启用2-禁用
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 49,
 * "company_id": 3,
 * "type": "order",
 * "type_name": "订单编号规则",
 * "prefix": "TMS",
 * "start_index": 1,
 * "int_length": 2,
 * "start_string_index": "",
 * "string_length": 0,
 * "max_no": "",
 * "status": 1,
 * "created_at": "2020-05-18T06:06:22.000000Z",
 * "updated_at": "2020-05-18T06:06:22.000000Z"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/order-no-rule?page=1",
 * "last": "http://tms-api.test/api/admin/order-no-rule?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/order-no-rule",
 * "per_page": 10,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/order-no-rule/{id} 获取详情
 * @apiName 获取详情
 * @apiGroup 24
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id ID
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.type 类型
 * @apiSuccess {string} data.prefix 开始字符
 * @apiSuccess {string} data.start_index
 * @apiSuccess {string} data.int_length 数字长度
 * @apiSuccess {string} data.start_string_index
 * @apiSuccess {string} data.string_length 字符长度
 * @apiSuccess {string} data.max_no 最大单号
 * @apiSuccess {string} data.status 状态
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.type_name 类型名称
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 49,
 * "company_id": 3,
 * "type": "order",
 * "prefix": "TMS",
 * "start_index": 1,
 * "int_length": 2,
 * "start_string_index": "",
 * "string_length": 0,
 * "max_no": "",
 * "status": 1,
 * "created_at": "2020-05-18 14:06:22",
 * "updated_at": "2020-05-18 14:06:22",
 * "type_name": "订单编号规则"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/order-no-rule/init 新增初始化
 * @apiName 新增初始化
 * @apiGroup 24
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.type_list
 * @apiSuccess {string} data.type_list.id
 * @apiSuccess {string} data.type_list.name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "type_list": [
 * {
 * "id": "batch",
 * "name": "站点编号规则"
 * },
 * {
 * "id": "batch_exception",
 * "name": "站点异常编号规则"
 * },
 * {
 * "id": "tour",
 * "name": "取件线路编号规则"
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/order-no-rule 新增
 * @apiName 新增
 * @apiGroup 24
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} type 类型
 * @apiParam {string} prefix 开始字符
 * @apiParam {string} string_length 字符长度 >0
 * @apiParam {string} int_length 数字长度 >0
 * @apiParam {string} status 状态1-启用2-禁用
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

/**
 * @api {put} /admin/order-no-rule/{id} 修改
 * @apiName 修改
 * @apiGroup 24
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiParam {string} prefix 开始字符
 * @apiParam {string} string_length 字符长度
 * @apiParam {string} int_length 数字长度
 * @apiParam {string} status 状态1-启用2-禁用
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

/**
 * @api {delete} /admin/order-no-rule/{id} 删除
 * @apiName 删除
 * @apiGroup 24
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
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

/**
 * @api {get} /admin/print-template/show 获取详情
 * @apiName 获取详情
 * @apiGroup 25
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.type 类型
 * @apiSuccess {string} data.type_name 类型名称
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1,
 * "company_id": 13,
 * "type": 2,
 * "type_name": "通用模板"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/print-template/update 创建或修改
 * @apiName 创建或修改
 * @apiGroup 25
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} type 模板类型;1-标准模板2-通用模板
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

/**
 * @api {get} /admin/print-template/init 初始化
 * @apiName 初始化
 * @apiGroup 25
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.template_list
 * @apiSuccess {string} data.template_list.id id
 * @apiSuccess {string} data.template_list.name 名称
 * @apiSuccess {string} data.template_list.url 地址
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "template_list": [
 * {
 * "id": 1,
 * "name": "标准模板",
 * "url": "https://dev-tms.nle-tech.com/storage/admin/print_template/1.png"
 * },
 * {
 * "id": 2,
 * "name": "通用模板",
 * "url": "https://dev-tms.nle-tech.com/storage/admin/print_template/2.png"
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/version 版本上传
 * @apiName 版本上传
 * @apiGroup 26
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} file 文件
 * @apiParam {string} name 应用名
 * @apiParam {string} version 版本
 * @apiParam {string} change_log 更新日志
 * @apiParam {string} status 状态（1-强制更新，2可选更新）
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.uploader_email
 * @apiSuccess {string} data.name
 * @apiSuccess {string} data.url
 * @apiSuccess {string} data.version
 * @apiSuccess {string} data.change_log
 * @apiSuccess {string} data.status
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.id
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "company_id": 1,
 * "uploader_email": "398352614@qq.com",
 * "name": "TMS",
 * "url": "tms-api.test/storage/admin/file/1/package/20200608113813.apk",
 * "version": "1.0",
 * "change_log": "版本更新说明1，绝不意气用事",
 * "status": "3",
 * "updated_at": "2020-06-08 11:38:13",
 * "created_at": "2020-06-08 11:38:13",
 * "id": 1
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/fee 列表查询
 * @apiName 列表查询
 * @apiGroup 27
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 名称
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id ID
 * @apiSuccess {string} data.data.name 名称
 * @apiSuccess {string} data.data.code 编码
 * @apiSuccess {string} data.data.amount 金额
 * @apiSuccess {string} data.data.level 等级1-系统级2-自定义
 * @apiSuccess {string} data.data.status 状态
 * @apiSuccess {string} data.data.status_name 状态名称
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 16,
 * "name": "费用名称1",
 * "code": "FEE_1",
 * "amount": "26.00",
 * "level": 1,
 * "status": 1,
 * "status_name": "待分配",
 * "created_at": "2020-06-22T06:43:15.000000Z",
 * "updated_at": "2020-06-22T06:44:56.000000Z"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/fee?page=1",
 * "last": "http://tms-api.test/api/admin/fee?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/fee",
 * "per_page": 10,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/fee/{id} 获取详情
 * @apiName 获取详情
 * @apiGroup 27
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id ID
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.name 名称
 * @apiSuccess {string} data.code 编码
 * @apiSuccess {string} data.amount 金额
 * @apiSuccess {string} data.level 等级
 * @apiSuccess {string} data.status 状态
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.status_name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 16,
 * "company_id": 3,
 * "name": "费用名称1",
 * "code": "FEE_1",
 * "amount": "26.00",
 * "level": 1,
 * "status": 1,
 * "created_at": "2020-06-22 14:43:15",
 * "updated_at": "2020-06-22 14:44:56",
 * "status_name": "待分配"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/fee/init 初始化
 * @apiName 初始化
 * @apiGroup 27
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.level_list 等级列表
 * @apiSuccess {string} data.level_list.id
 * @apiSuccess {string} data.level_list.name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "level_list": [
 * {
 * "id": 1,
 * "name": "系统级"
 * },
 * {
 * "id": 2,
 * "name": "自定义"
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/fee 新增
 * @apiName 新增
 * @apiGroup 27
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 名称
 * @apiParam {string} code 编码
 * @apiParam {string} amount 金额
 * @apiParam {string} status 状态1-启用2-禁用
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

/**
 * @api {put} /admin/fee/{id} 修改
 * @apiName 修改
 * @apiGroup 27
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 名称
 * @apiParam {string} code 编码
 * @apiParam {string} amount 金额
 * @apiParam {string} status 状态1-启用2-禁用
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

/**
 * @api {delete} /admin/fee/{id} 删除
 * @apiName 删除
 * @apiGroup 27
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
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

/**
 * @api {get} /admin/holiday 列表查询
 * @apiName 列表查询
 * @apiGroup 28
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id ID
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.name 名称
 * @apiSuccess {string} data.data.status 状态1-开启2-禁用
 * @apiSuccess {string} data.data.date_list 日期列表
 * @apiSuccess {string} data.data.merchant_list 货主列表
 * @apiSuccess {string} data.data.merchant_list.id
 * @apiSuccess {string} data.data.merchant_list.name 货主名称
 * @apiSuccess {string} data.data.merchant_list.settlement_type_name
 * @apiSuccess {string} data.data.merchant_list.status_name
 * @apiSuccess {string} data.data.merchant_list.type_name
 * @apiSuccess {string} data.data.merchant_list.country_name
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 4,
 * "company_id": 3,
 * "name": "劳动节",
 * "status": 1,
 * "date_list": "2020-05-01",
 * "merchant_list": [],
 * "created_at": "2020-07-27 11:51:32"
 * },
 * {
 * "id": 3,
 * "company_id": 3,
 * "name": "国庆假2",
 * "status": 1,
 * "date_list": "2020-10-01,2020-10-02,2020-10-03,2020-10-04",
 * "merchant_list": [
 * {
 * "id": 17,
 * "name": "ERP",
 * "settlement_type_name": null,
 * "status_name": null,
 * "type_name": null,
 * "country_name": null
 * }
 * ],
 * "created_at": "2020-07-27 11:05:09"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/holiday?page=1",
 * "last": "http://tms-api.test/api/admin/holiday?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/holiday",
 * "per_page": 10,
 * "to": 2,
 * "total": 2
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/holiday/{id} 获取详情
 * @apiName 获取详情
 * @apiGroup 28
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id ID
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.name
 * @apiSuccess {string} data.status
 * @apiSuccess {string} data.date_list 日期列表
 * @apiSuccess {string} data.merchant_list 货主列表
 * @apiSuccess {string} data.merchant_list.id
 * @apiSuccess {string} data.merchant_list.name 货主名称
 * @apiSuccess {string} data.merchant_list.settlement_type_name
 * @apiSuccess {string} data.merchant_list.status_name
 * @apiSuccess {string} data.merchant_list.type_name
 * @apiSuccess {string} data.merchant_list.country_name
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 3,
 * "company_id": 3,
 * "name": "国庆假2",
 * "status": 1,
 * "date_list": "2020-10-01,2020-10-02,2020-10-03,2020-10-04",
 * "merchant_list": [
 * {
 * "id": 17,
 * "name": "ERP",
 * "settlement_type_name": null,
 * "status_name": null,
 * "type_name": null,
 * "country_name": null
 * }
 * ],
 * "created_at": "2020-07-27 11:05:09"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/holiday 新增
 * @apiName 新增
 * @apiGroup 28
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 名称
 * @apiParam {string} date_list 日期列表,以逗哈分隔
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

/**
 * @api {put} /admin/holiday/{id} 修改
 * @apiName 修改
 * @apiGroup 28
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiParam {string} name 名称
 * @apiParam {string} date_list 日期列表,以逗号分隔
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

/**
 * @api {delete} /admin/holiday/{id} 删除
 * @apiName 删除
 * @apiGroup 28
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
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

/**
 * @api {get} /admin/holiday/merchant 放假货主列表查询
 * @apiName 放假货主列表查询
 * @apiGroup 28
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id ID
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.type
 * @apiSuccess {string} data.data.name 货主名称
 * @apiSuccess {string} data.data.email
 * @apiSuccess {string} data.data.country
 * @apiSuccess {string} data.data.settlement_type
 * @apiSuccess {string} data.data.settlement_type_name
 * @apiSuccess {string} data.data.merchant_group_id
 * @apiSuccess {string} data.data.merchant_group_name
 * @apiSuccess {string} data.data.contacter
 * @apiSuccess {string} data.data.phone
 * @apiSuccess {string} data.data.address
 * @apiSuccess {string} data.data.avatar
 * @apiSuccess {string} data.data.status
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 17,
 * "company_id": 3,
 * "type": 2,
 * "name": "ERP",
 * "email": "xjq219@qq.com",
 * "country": "NL",
 * "settlement_type": 1,
 * "settlement_type_name": "票结",
 * "merchant_group_id": 3,
 * "merchant_group_name": "tianyaox",
 * "contacter": "ERPx",
 * "phone": "18207420802",
 * "address": "TEST",
 * "avatar": "https://dev-tms.nle-tech.com/storage/admin/images/3/merchant/202003311454175e82e919a2b18.jpg",
 * "status": 1,
 * "created_at": "2020-03-31 14:54:31",
 * "updated_at": "2020-06-18 19:06:06"
 * },
 * {
 * "id": 65,
 * "company_id": 3,
 * "type": 2,
 * "name": "ERP-TEST",
 * "email": "erp@nle-tech.com",
 * "country": "",
 * "settlement_type": 1,
 * "settlement_type_name": "票结",
 * "merchant_group_id": 3,
 * "merchant_group_name": "tianyaox",
 * "contacter": "ERP",
 * "phone": "3124567899",
 * "address": "NL",
 * "avatar": "",
 * "status": 1,
 * "created_at": "2020-07-14 16:45:36",
 * "updated_at": "2020-07-14 16:45:36"
 * },
 * {
 * "id": 25,
 * "company_id": 3,
 * "type": 1,
 * "name": "name",
 * "email": "17745874523@163.com",
 * "country": "CN",
 * "settlement_type": 1,
 * "settlement_type_name": "票结",
 * "merchant_group_id": 13,
 * "merchant_group_name": "quanquan",
 * "contacter": "demo",
 * "phone": "123123123",
 * "address": "123213",
 * "avatar": "https://dev-tms.nle-tech.com/storage/admin/images/3/merchant/202004281551325ea7e084e9091.png",
 * "status": 1,
 * "created_at": "2020-04-28 15:51:41",
 * "updated_at": "2020-04-29 11:22:43"
 * },
 * {
 * "id": 23,
 * "company_id": 3,
 * "type": 1,
 * "name": "qq",
 * "email": "3608932952@qq.com",
 * "country": "NL",
 * "settlement_type": 2,
 * "settlement_type_name": "日结",
 * "merchant_group_id": 15,
 * "merchant_group_name": "431",
 * "contacter": "qq",
 * "phone": "123456789",
 * "address": "changsha",
 * "avatar": "https://dev-tms.nle-tech.com/storage/admin/images/3/merchant/202004221347565e9fda8c3144d.jpg",
 * "status": 1,
 * "created_at": "2020-04-22 13:48:01",
 * "updated_at": "2020-05-08 14:16:31"
 * },
 * {
 * "id": 19,
 * "company_id": 3,
 * "type": 2,
 * "name": "test",
 * "email": "nle@qq.com",
 * "country": "NL",
 * "settlement_type": 1,
 * "settlement_type_name": "票结",
 * "merchant_group_id": 3,
 * "merchant_group_name": "tianyaox",
 * "contacter": "hu",
 * "phone": "123",
 * "address": "123",
 * "avatar": "https://dev-tms.nle-tech.com/storage/admin/images/3/merchant/202004091121045e8e94a0544ee.png",
 * "status": 1,
 * "created_at": "2020-04-09 11:21:17",
 * "updated_at": "2020-05-13 17:36:22"
 * },
 * {
 * "id": 54,
 * "company_id": 3,
 * "type": 2,
 * "name": "test_test",
 * "email": "12345678@qq.com",
 * "country": "",
 * "settlement_type": 1,
 * "settlement_type_name": "票结",
 * "merchant_group_id": 3,
 * "merchant_group_name": "tianyaox",
 * "contacter": "test",
 * "phone": "13051515",
 * "address": "wqwqwqsq",
 * "avatar": null,
 * "status": 1,
 * "created_at": "2020-06-16 18:41:53",
 * "updated_at": "2020-06-16 18:41:53"
 * },
 * {
 * "id": 3,
 * "company_id": 3,
 * "type": 2,
 * "name": "tianyaox",
 * "email": "827193289@qq.com",
 * "country": "NL",
 * "settlement_type": 1,
 * "settlement_type_name": "票结",
 * "merchant_group_id": 3,
 * "merchant_group_name": "tianyaox",
 * "contacter": "827193289",
 * "phone": "1234567890",
 * "address": "隋东风",
 * "avatar": "",
 * "status": 1,
 * "created_at": "2020-03-13 12:00:10",
 * "updated_at": "2020-06-27 14:28:17"
 * },
 * {
 * "id": 26,
 * "company_id": 3,
 * "type": 2,
 * "name": "奇亚籽",
 * "email": "1546233@qq.com",
 * "country": "NL",
 * "settlement_type": 1,
 * "settlement_type_name": "票结",
 * "merchant_group_id": 3,
 * "merchant_group_name": "tianyaox",
 * "contacter": "王五",
 * "phone": "13698989898",
 * "address": "5611HW 314",
 * "avatar": "https://dev-tms.nle-tech.com/storage/admin/images/3/merchant/202005051836445eb141bc9940d.jpg",
 * "status": 2,
 * "created_at": "2020-05-05 18:36:47",
 * "updated_at": "2020-06-09 18:25:57"
 * },
 * {
 * "id": 27,
 * "company_id": 3,
 * "type": 2,
 * "name": "张三",
 * "email": "test@bccto.me",
 * "country": "CN",
 * "settlement_type": 1,
 * "settlement_type_name": "票结",
 * "merchant_group_id": 3,
 * "merchant_group_name": "tianyaox",
 * "contacter": "张三",
 * "phone": "13500006666",
 * "address": "长沙",
 * "avatar": "https://dev-tms.nle-tech.com/storage/admin/images/3/merchant/202005071702375eb3cead49bfe.jpg",
 * "status": 1,
 * "created_at": "2020-05-07 17:02:47",
 * "updated_at": "2020-07-10 11:17:07"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/holiday/merchantIndex?page=1",
 * "last": "http://tms-api.test/api/admin/holiday/merchantIndex?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/holiday/merchantIndex",
 * "per_page": 15,
 * "to": 9,
 * "total": 9
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/holiday/{id}/merchant 新增放假货主列表
 * @apiName 新增放假货主列表
 * @apiGroup 28
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiParam {string} merchant_id_list 货主ID列表,以逗号分隔
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

/**
 * @api {delete} /admin/holiday/{id}/merchant 删除放假-货主
 * @apiName 删除放假-货主
 * @apiGroup 28
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiParam {string} merchant_id 货主ID
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

/**
 * @api {put} /admin/holiday/{id}/status 禁用启用
 * @apiName 禁用启用
 * @apiGroup 28
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiParam {string} status 状态1-启用2-禁用
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

/**
 * @api {get} /admin/package 包裹查询
 * @apiName 包裹查询
 * @apiGroup 29
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} keyword
 * @apiParam {string} begin_date 起始日期
 * @apiParam {string} end_date 截止日期
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.batch_no 站点编号
 * @apiSuccess {string} data.data.tour_no 取件线路编号
 * @apiSuccess {string} data.data.order_no 订单编号
 * @apiSuccess {string} data.data.type 类型1-取2-派
 * @apiSuccess {string} data.data.type_name
 * @apiSuccess {string} data.data.name 包裹名称
 * @apiSuccess {string} data.data.out_order_no 快递单号1
 * @apiSuccess {string} data.data.weight 重量
 * @apiSuccess {string} data.data.expect_quantity 预计数量
 * @apiSuccess {string} data.data.actual_quantity 实际数量
 * @apiSuccess {string} data.data.sticker_no 贴单号
 * @apiSuccess {string} data.data.sticker_amount 贴单费用
 * @apiSuccess {string} data.data.delivery_amount 提货费用
 * @apiSuccess {string} data.data.remark 备注
 * @apiSuccess {string} data.data.express_first_no 快递单号1
 * @apiSuccess {string} data.data.express_second_no 快递单号2
 * @apiSuccess {string} data.data.status 状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站
 * @apiSuccess {string} data.data.status_name
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.data.merchant_id 货主ID
 * @apiSuccess {string} data.data.execution_date 取派日期
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 558,
 * "company_id": 3,
 * "merchant_id": 3,
 * "batch_no": "ZD1226",
 * "tour_no": "4ABQ01",
 * "order_no": "SMAAABTP0001",
 * "execution_date": null,
 * "type": 2,
 * "type_name": "派件",
 * "name": "PPD12Z02A232",
 * "out_order_no": "PPD12Z02A232",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": "0.00",
 * "delivery_amount": "0.00",
 * "remark": "",
 * "express_first_no": "PPD12Z02A232",
 * "express_second_no": "",
 * "status": 1,
 * "status_name": "待分配",
 * "execution_date": null,
 * "created_at": "2020-08-05 11:51:56",
 * "updated_at": "2020-08-05 11:51:56"
 * },
 * {
 * "id": 559,
 * "company_id": 3,
 * "batch_no": "ZD1226",
 * "tour_no": "4ABQ01",
 * "order_no": "SMAAABTP0001",
 * "type": 2,
 * "type_name": "派件",
 * "name": "PPD12Z04A232",
 * "out_order_no": "PPD12Z04A232",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": "0.00",
 * "delivery_amount": "0.00",
 * "remark": "",
 * "express_first_no": "PPD12Z04A232",
 * "express_second_no": "",
 * "status": 1,
 * "status_name": "待分配",
 * "execution_date": null,
 * "created_at": "2020-08-05 11:51:56",
 * "updated_at": "2020-08-05 11:51:56"
 * },
 * {
 * "id": 557,
 * "company_id": 3,
 * "batch_no": "ZD1225",
 * "tour_no": "4ACU01",
 * "order_no": "SMAAABTO0001",
 * "type": 2,
 * "type_name": "派件",
 * "name": "PPD12Z039231",
 * "out_order_no": "PPD12Z039231",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": "0.00",
 * "delivery_amount": "0.00",
 * "remark": "",
 * "express_first_no": "PPD12Z039231",
 * "express_second_no": "",
 * "status": 1,
 * "status_name": "待分配",
 * "execution_date": null,
 * "created_at": "2020-08-05 11:39:47",
 * "updated_at": "2020-08-05 11:39:47"
 * },
 * {
 * "id": 556,
 * "company_id": 3,
 * "batch_no": "ZD1224",
 * "tour_no": "4ADK01",
 * "order_no": "SMAAABTN0001",
 * "type": 2,
 * "type_name": "派件",
 * "name": "PPD12Z028230",
 * "out_order_no": "PPD12Z028230",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": "0.00",
 * "delivery_amount": "0.00",
 * "remark": "",
 * "express_first_no": "PPD12Z028230",
 * "express_second_no": "",
 * "status": 1,
 * "status_name": "待分配",
 * "execution_date": null,
 * "created_at": "2020-08-05 11:31:35",
 * "updated_at": "2020-08-05 11:31:35"
 * },
 * {
 * "id": 554,
 * "company_id": 3,
 * "batch_no": "ZD1117",
 * "tour_no": "4ACE01",
 * "order_no": "SMAAABTM0001",
 * "type": 2,
 * "type_name": "派件",
 * "name": "PPD12Z027229",
 * "out_order_no": "PPD12Z027229",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": "0.00",
 * "delivery_amount": "0.00",
 * "remark": "",
 * "express_first_no": "PPD12Z027229",
 * "express_second_no": "",
 * "status": 1,
 * "status_name": "待分配",
 * "execution_date": null,
 * "created_at": "2020-08-05 11:08:41",
 * "updated_at": "2020-08-05 11:08:41"
 * },
 * {
 * "id": 555,
 * "company_id": 3,
 * "batch_no": "ZD1117",
 * "tour_no": "4ACE01",
 * "order_no": "SMAAABTM0001",
 * "type": 2,
 * "type_name": "派件",
 * "name": "PPD12Z047229",
 * "out_order_no": "PPD12Z047229",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": "0.00",
 * "delivery_amount": "0.00",
 * "remark": "",
 * "express_first_no": "PPD12Z047229",
 * "express_second_no": "",
 * "status": 1,
 * "status_name": "待分配",
 * "execution_date": null,
 * "created_at": "2020-08-05 11:08:41",
 * "updated_at": "2020-08-05 11:08:41"
 * },
 * {
 * "id": 549,
 * "company_id": 3,
 * "batch_no": "ZD1223",
 * "tour_no": "4UL01",
 * "order_no": "SMAAABTH0001",
 * "type": 2,
 * "type_name": "派件",
 * "name": "PPD11Z023225",
 * "out_order_no": "PPD11Z023225",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": "0.00",
 * "delivery_amount": "0.00",
 * "remark": "",
 * "express_first_no": "PPD11Z023225",
 * "express_second_no": "",
 * "status": 1,
 * "status_name": "待分配",
 * "execution_date": null,
 * "created_at": "2020-08-04 11:50:28",
 * "updated_at": "2020-08-04 16:08:37"
 * },
 * {
 * "id": 553,
 * "company_id": 3,
 * "batch_no": "ZD1220",
 * "tour_no": "4ACR01",
 * "order_no": "SMAAABTL0001",
 * "type": 2,
 * "type_name": "派件",
 * "name": "PPD11Z026228",
 * "out_order_no": "PPD11Z026228",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": "0.00",
 * "delivery_amount": "0.00",
 * "remark": "",
 * "express_first_no": "PPD11Z026228",
 * "express_second_no": "",
 * "status": 2,
 * "status_name": "已分配",
 * "execution_date": null,
 * "created_at": "2020-08-04 14:13:01",
 * "updated_at": "2020-08-04 14:13:01"
 * },
 * {
 * "id": 552,
 * "company_id": 3,
 * "batch_no": "ZD1220",
 * "tour_no": "4ACR01",
 * "order_no": "SMAAABTK0001",
 * "type": 2,
 * "type_name": "派件",
 * "name": "PPD11Z025227",
 * "out_order_no": "PPD11Z025227",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": "0.00",
 * "delivery_amount": "0.00",
 * "remark": "",
 * "express_first_no": "PPD11Z025227",
 * "express_second_no": "",
 * "status": 2,
 * "status_name": "已分配",
 * "execution_date": null,
 * "created_at": "2020-08-04 14:06:59",
 * "updated_at": "2020-08-04 14:06:59"
 * },
 * {
 * "id": 550,
 * "company_id": 3,
 * "batch_no": "ZD1222",
 * "tour_no": "4ADP01",
 * "order_no": "SMAAABTI0001",
 * "type": 1,
 * "type_name": "取件",
 * "name": "PEH49045660004",
 * "out_order_no": "382546",
 * "weight": "2.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": "0.00",
 * "delivery_amount": "0.00",
 * "remark": "",
 * "express_first_no": "EAXOND110005",
 * "express_second_no": "SF1028562801282",
 * "status": 1,
 * "status_name": "待分配",
 * "execution_date": null,
 * "created_at": "2020-08-04 11:55:39",
 * "updated_at": "2020-08-04 12:02:39"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/package?page=1",
 * "last": "http://tms-api.test/api/admin/package?page=41",
 * "prev": null,
 * "next": "http://tms-api.test/api/admin/package?page=2"
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 41,
 * "path": "http://tms-api.test/api/admin/package",
 * "per_page": 10,
 * "to": 10,
 * "total": 410
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/package/{id} 包裹详情
 * @apiName 包裹详情
 * @apiGroup 29
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 包裹ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.tour_no 取件线路编号
 * @apiSuccess {string} data.batch_no 站点编号
 * @apiSuccess {string} data.order_no 订单编号
 * @apiSuccess {string} data.type 类型1-取2-派
 * @apiSuccess {string} data.name 包裹名称
 * @apiSuccess {string} data.express_first_no 快递单号1
 * @apiSuccess {string} data.express_second_no 快递单号2
 * @apiSuccess {string} data.feature_logo 特性
 * @apiSuccess {string} data.out_order_no 外部订单号/标识
 * @apiSuccess {string} data.weight 重量
 * @apiSuccess {string} data.expect_quantity 预计数量
 * @apiSuccess {string} data.actual_quantity 实际数量
 * @apiSuccess {string} data.status 状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站
 * @apiSuccess {string} data.sticker_no 贴单号
 * @apiSuccess {string} data.sticker_amount 贴单费用
 * @apiSuccess {string} data.delivery_amount 提货费用
 * @apiSuccess {string} data.remark 备注
 * @apiSuccess {string} data.is_auth 是否需要身份验证1-是2-否
 * @apiSuccess {string} data.auth_fullname 身份人姓名
 * @apiSuccess {string} data.auth_birth_date 身份人出身年月
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.status_name
 * @apiSuccess {string} data.type_name
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.merchant_id 货主ID
 * @apiSuccess {string} data.execution_date 取派日期
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1,
 * "company_id": 3,
 * "merchant_id": 3,
 * "tour_no": "",
 * "batch_no": "",
 * "order_no": "SMAAABFI0001",
 * "execution_date": null,
 * "type": 1,
 * "name": "",
 * "express_first_no": "123",
 * "express_second_no": "",
 * "feature_logo": "",
 * "out_order_no": "",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "status": 7,
 * "sticker_no": "",
 * "sticker_amount": "0.00",
 * "delivery_amount": "0.00",
 * "remark": "",
 * "is_auth": 2,
 * "auth_fullname": "",
 * "auth_birth_date": null,
 * "created_at": "2020-07-23 13:35:57",
 * "updated_at": "2020-07-23 14:27:19",
 * "status_name": "回收站",
 * "type_name": "取件"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/additional-package 顺带包裹查询
 * @apiName 顺带包裹查询
 * @apiGroup 29
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} begin_date
 * @apiParam {string} end_date
 * @apiParam {string} merchant_id
 * @apiParam {string} package_no
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.merchant_id 货主ID
 * @apiSuccess {string} data.data.batch_no 站点编号
 * @apiSuccess {string} data.data.package_no 包裹编号
 * @apiSuccess {string} data.data.execution_date 取派日期
 * @apiSuccess {string} data.data.status 状态1-已推送2-未推送
 * @apiSuccess {string} data.data.receiver_fullname 收件人姓名
 * @apiSuccess {string} data.data.receiver_phone 收件人电话
 * @apiSuccess {string} data.data.receiver_country 收件人国家
 * @apiSuccess {string} data.data.receiver_post_code 收件人邮编
 * @apiSuccess {string} data.data.receiver_house_number 收件人门牌号
 * @apiSuccess {string} data.data.receiver_city 收件人城市
 * @apiSuccess {string} data.data.receiver_street 收件人街道
 * @apiSuccess {string} data.data.receiver_address 地址
 * @apiSuccess {string} data.data.receiver_lon 收件人经度
 * @apiSuccess {string} data.data.receiver_lat 收件人纬度
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.data.sticker_no 贴单号
 * @apiSuccess {string} data.data.sticker_amount 贴单费
 * @apiSuccess {string} data.data.delivery_amount 提货费
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "company_id": 3,
 * "merchant_id": 7,
 * "batch_no": "ZD1463",
 * "package_no": "100",
 * "execution_date": "2020-08-26",
 * "sticker_no": "123",
 * "sticker_amount": "52.30",
 * "delivery_amount": "2.30",
 * "status": 1,
 * "receiver_fullname": "1117EE",
 * "receiver_phone": "0031231312213",
 * "receiver_country": "NL",
 * "receiver_post_code": "1117 EE",
 * "receiver_house_number": "3",
 * "receiver_city": "Schiphol",
 * "receiver_street": "Piet Guilonardweg",
 * "receiver_address": "NL Schiphol Piet Guilonardweg 3 1117 EE",
 * "receiver_lon": "4.80641638",
 * "receiver_lat": "52.30578015",
 * "created_at": "2020-08-24T02:38:33.000000Z",
 * "updated_at": "2020-08-24T02:38:33.000000Z"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/additional-package?page=1",
 * "last": "http://tms-api.test/api/admin/additional-package?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/additional-package",
 * "per_page": 10,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/package 包裹查询
 * @apiName 包裹查询
 * @apiGroup 29
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} begin_date 起始日期
 * @apiParam {string} end_date 截止日期
 * @apiParam {string} express_first_no 包裹号
 * @apiParam {string} order_no 订单号
 * @apiParam {string} tracking_order_no 运单号
 * @apiParam {string} shift_no 车次号
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.merchant_id
 * @apiSuccess {string} data.data.batch_no
 * @apiSuccess {string} data.data.tour_no
 * @apiSuccess {string} data.data.order_no
 * @apiSuccess {string} data.data.execution_date
 * @apiSuccess {string} data.data.type
 * @apiSuccess {string} data.data.type_name
 * @apiSuccess {string} data.data.name
 * @apiSuccess {string} data.data.out_order_no
 * @apiSuccess {string} data.data.weight 重量
 * @apiSuccess {string} data.data.expect_quantity
 * @apiSuccess {string} data.data.actual_quantity
 * @apiSuccess {string} data.data.sticker_no
 * @apiSuccess {string} data.data.sticker_amount
 * @apiSuccess {string} data.data.delivery_amount
 * @apiSuccess {string} data.data.remark
 * @apiSuccess {string} data.data.express_first_no 包裹号
 * @apiSuccess {string} data.data.express_second_no
 * @apiSuccess {string} data.data.status
 * @apiSuccess {string} data.data.status_name
 * @apiSuccess {string} data.data.stage
 * @apiSuccess {string} data.data.stage_name
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.data.second_execution_date 预计派件日期
 * @apiSuccess {string} data.data.stock_in_time 揽收时间
 * @apiSuccess {string} data.data.warehouse_id
 * @apiSuccess {string} data.data.next_warehouse_id
 * @apiSuccess {string} data.data.warehouse_name 当前网点名称
 * @apiSuccess {string} data.data.next_warehouse_name 下一站网点名称
 * @apiSuccess {string} data.data.shift_no 车次
 * @apiSuccess {string} data.data.bag_no 袋号
 * @apiSuccess {string} data.data.stage_status_name 状态名称
 * @apiSuccess {string} data.data.expiration_date 保质期
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 4434,
 * "company_id": 3,
 * "merchant_id": 121,
 * "batch_no": null,
 * "tour_no": null,
 * "order_no": "SMAAAKLV0001",
 * "execution_date": "2021-05-20",
 * "type": 3,
 * "type_name": "提货->网点->配送",
 * "name": "",
 * "out_order_no": "",
 * "weight": "12.12",
 * "expect_quantity": 12,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": null,
 * "delivery_amount": null,
 * "remark": "12",
 * "express_first_no": "123419",
 * "express_second_no": "",
 * "status": 1,
 * "status_name": "待受理",
 * "stage": 1,
 * "stage_name": "取件",
 * "created_at": "2021-05-20 08:29:10",
 * "updated_at": "2021-05-20 08:29:10",
 * "expiration_date": null,
 * "second_execution_date": null,
 * "stock_in_time": null,
 * "warehouse_id": null,
 * "next_warehouse_id": null,
 * "warehouse_name": null,
 * "next_warehouse_name": null,
 * "shift_no": null,
 * "bag_no": null,
 * "stage_status_name": "取件-待受理"
 * },
 * {
 * "id": 4433,
 * "company_id": 3,
 * "merchant_id": 121,
 * "batch_no": null,
 * "tour_no": null,
 * "order_no": "SMAAAKLU0001",
 * "execution_date": "2021-05-20",
 * "type": 3,
 * "type_name": "提货->网点->配送",
 * "name": "",
 * "out_order_no": "",
 * "weight": "12.12",
 * "expect_quantity": 12,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": null,
 * "delivery_amount": null,
 * "remark": "12",
 * "express_first_no": "123575",
 * "express_second_no": "",
 * "status": 1,
 * "status_name": "待受理",
 * "stage": 1,
 * "stage_name": "取件",
 * "created_at": "2021-05-20 08:29:00",
 * "updated_at": "2021-05-20 08:29:00",
 * "expiration_date": null,
 * "second_execution_date": null,
 * "stock_in_time": null,
 * "warehouse_id": null,
 * "next_warehouse_id": null,
 * "warehouse_name": null,
 * "next_warehouse_name": null,
 * "shift_no": null,
 * "bag_no": null,
 * "stage_status_name": "取件-待受理"
 * },
 * {
 * "id": 4432,
 * "company_id": 3,
 * "merchant_id": 121,
 * "batch_no": null,
 * "tour_no": null,
 * "order_no": "SMAAAKLT0001",
 * "execution_date": "2021-05-20",
 * "type": 3,
 * "type_name": "提货->网点->配送",
 * "name": "",
 * "out_order_no": "",
 * "weight": "12.12",
 * "expect_quantity": 12,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": null,
 * "delivery_amount": null,
 * "remark": "12",
 * "express_first_no": "12364",
 * "express_second_no": "",
 * "status": 1,
 * "status_name": "待受理",
 * "stage": 1,
 * "stage_name": "取件",
 * "created_at": "2021-05-20 08:22:08",
 * "updated_at": "2021-05-20 08:22:08",
 * "expiration_date": null,
 * "second_execution_date": null,
 * "stock_in_time": null,
 * "warehouse_id": null,
 * "next_warehouse_id": null,
 * "warehouse_name": null,
 * "next_warehouse_name": null,
 * "shift_no": null,
 * "bag_no": null,
 * "stage_status_name": "取件-待受理"
 * },
 * {
 * "id": 4431,
 * "company_id": 3,
 * "merchant_id": 121,
 * "batch_no": null,
 * "tour_no": null,
 * "order_no": "SMAAAKLS0001",
 * "execution_date": "2021-05-20",
 * "type": 3,
 * "type_name": "提货->网点->配送",
 * "name": "",
 * "out_order_no": "",
 * "weight": "12.12",
 * "expect_quantity": 12,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": null,
 * "delivery_amount": null,
 * "remark": "12",
 * "express_first_no": "123307",
 * "express_second_no": "",
 * "status": 1,
 * "status_name": "待受理",
 * "stage": 1,
 * "stage_name": "取件",
 * "created_at": "2021-05-20 08:20:29",
 * "updated_at": "2021-05-20 08:20:29",
 * "expiration_date": null,
 * "second_execution_date": null,
 * "stock_in_time": null,
 * "warehouse_id": null,
 * "next_warehouse_id": null,
 * "warehouse_name": null,
 * "next_warehouse_name": null,
 * "shift_no": null,
 * "bag_no": null,
 * "stage_status_name": "取件-待受理"
 * },
 * {
 * "id": 4429,
 * "company_id": 3,
 * "merchant_id": 3,
 * "batch_no": null,
 * "tour_no": null,
 * "order_no": "SMAAAKLR0001",
 * "execution_date": "2021-05-21",
 * "type": 3,
 * "type_name": "提货->网点->配送",
 * "name": "563",
 * "out_order_no": "",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": null,
 * "delivery_amount": null,
 * "remark": "",
 * "express_first_no": "35635",
 * "express_second_no": "",
 * "status": 2,
 * "status_name": "已接单",
 * "stage": 1,
 * "stage_name": "取件",
 * "created_at": "2021-05-20 03:59:38",
 * "updated_at": "2021-05-20 03:59:38",
 * "expiration_date": "2021-05-21",
 * "second_execution_date": null,
 * "stock_in_time": null,
 * "warehouse_id": null,
 * "next_warehouse_id": null,
 * "warehouse_name": null,
 * "next_warehouse_name": null,
 * "shift_no": null,
 * "bag_no": null,
 * "stage_status_name": "取件-已接单"
 * },
 * {
 * "id": 4425,
 * "company_id": 3,
 * "merchant_id": 3,
 * "batch_no": null,
 * "tour_no": null,
 * "order_no": "SMAAAKLP0001",
 * "execution_date": "2021-05-18",
 * "type": 3,
 * "type_name": "提货->网点->配送",
 * "name": "",
 * "out_order_no": "",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": null,
 * "delivery_amount": null,
 * "remark": "",
 * "express_first_no": "565325",
 * "express_second_no": "",
 * "status": 6,
 * "status_name": "取消",
 * "stage": 1,
 * "stage_name": "取件",
 * "created_at": "2021-05-18 14:21:25",
 * "updated_at": "2021-05-19 12:26:36",
 * "expiration_date": null,
 * "second_execution_date": null,
 * "stock_in_time": null,
 * "warehouse_id": null,
 * "next_warehouse_id": null,
 * "warehouse_name": null,
 * "next_warehouse_name": null,
 * "shift_no": null,
 * "bag_no": null,
 * "stage_status_name": "取件-取消"
 * },
 * {
 * "id": 4428,
 * "company_id": 3,
 * "merchant_id": 3,
 * "batch_no": null,
 * "tour_no": null,
 * "order_no": "SMAAAKLQ0001",
 * "execution_date": "2021-05-21",
 * "type": 3,
 * "type_name": "提货->网点->配送",
 * "name": "",
 * "out_order_no": "",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": null,
 * "delivery_amount": null,
 * "remark": "",
 * "express_first_no": "56523201",
 * "express_second_no": "",
 * "status": 2,
 * "status_name": "未装车",
 * "stage": 2,
 * "stage_name": "中转",
 * "created_at": "2021-05-19 10:06:04",
 * "updated_at": "2021-05-19 12:26:21",
 * "expiration_date": null,
 * "second_execution_date": null,
 * "stock_in_time": "2021-05-19 10:08:55",
 * "warehouse_id": 4,
 * "next_warehouse_id": 205,
 * "warehouse_name": "总部",
 * "next_warehouse_name": "AMS",
 * "shift_no": "",
 * "bag_no": "BAG00030000053",
 * "stage_status_name": "中转-未装车"
 * },
 * {
 * "id": 4420,
 * "company_id": 3,
 * "merchant_id": 3,
 * "batch_no": null,
 * "tour_no": null,
 * "order_no": "SMAAAKLK0001",
 * "execution_date": "2021-05-19",
 * "type": 2,
 * "type_name": "网点->配送",
 * "name": "",
 * "out_order_no": "",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": null,
 * "delivery_amount": null,
 * "remark": "",
 * "express_first_no": "5465",
 * "express_second_no": "",
 * "status": 1,
 * "status_name": "未装袋",
 * "stage": 2,
 * "stage_name": "中转",
 * "created_at": "2021-05-18 20:08:34",
 * "updated_at": "2021-05-18 20:08:34",
 * "expiration_date": null,
 * "second_execution_date": null,
 * "stock_in_time": "2021-05-18 20:08:34",
 * "warehouse_id": 205,
 * "next_warehouse_id": 222,
 * "warehouse_name": "AMS",
 * "next_warehouse_name": "YYY-cs",
 * "shift_no": "",
 * "bag_no": "",
 * "stage_status_name": "中转-未装袋"
 * },
 * {
 * "id": 4424,
 * "company_id": 3,
 * "merchant_id": 3,
 * "batch_no": null,
 * "tour_no": null,
 * "order_no": "SMAAAKLO0001",
 * "execution_date": "2021-05-19",
 * "type": 2,
 * "type_name": "网点->配送",
 * "name": "",
 * "out_order_no": "",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": null,
 * "delivery_amount": null,
 * "remark": "",
 * "express_first_no": "6532322",
 * "express_second_no": "",
 * "status": 1,
 * "status_name": "未装袋",
 * "stage": 2,
 * "stage_name": "中转",
 * "created_at": "2021-05-18 14:21:00",
 * "updated_at": "2021-05-18 14:21:00",
 * "expiration_date": null,
 * "second_execution_date": null,
 * "stock_in_time": "2021-05-18 14:21:00",
 * "warehouse_id": 205,
 * "next_warehouse_id": 222,
 * "warehouse_name": "AMS",
 * "next_warehouse_name": "YYY-cs",
 * "shift_no": "",
 * "bag_no": "",
 * "stage_status_name": "中转-未装袋"
 * },
 * {
 * "id": 4423,
 * "company_id": 3,
 * "merchant_id": 3,
 * "batch_no": null,
 * "tour_no": null,
 * "order_no": "SMAAAKLN0001",
 * "execution_date": "2021-05-19",
 * "type": 1,
 * "type_name": "提货->网点",
 * "name": "",
 * "out_order_no": "",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "sticker_no": "",
 * "sticker_amount": null,
 * "delivery_amount": null,
 * "remark": "",
 * "express_first_no": "565323",
 * "express_second_no": "",
 * "status": 2,
 * "status_name": "已接单",
 * "stage": 1,
 * "stage_name": "取件",
 * "created_at": "2021-05-18 14:20:06",
 * "updated_at": "2021-05-18 14:20:06",
 * "expiration_date": null,
 * "second_execution_date": null,
 * "stock_in_time": null,
 * "warehouse_id": null,
 * "next_warehouse_id": null,
 * "warehouse_name": null,
 * "next_warehouse_name": null,
 * "shift_no": null,
 * "bag_no": null,
 * "stage_status_name": "取件-已接单"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test:10002/api/admin/package?page=1",
 * "last": "http://tms-api.test:10002/api/admin/package?page=233",
 * "prev": null,
 * "next": "http://tms-api.test:10002/api/admin/package?page=2"
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 233,
 * "path": "http://tms-api.test:10002/api/admin/package",
 * "per_page": "10",
 * "to": 10,
 * "total": 2321
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/material 材料查询
 * @apiName 材料查询
 * @apiGroup 30
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} keyword
 * @apiParam {string} begin_date 起始日期
 * @apiParam {string} end_date 中止日期
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.merchant_id 货主ID
 * @apiSuccess {string} data.data.batch_no 站点编号
 * @apiSuccess {string} data.data.tour_no 货主ID
 * @apiSuccess {string} data.data.order_no 站点编号
 * @apiSuccess {string} data.data.execution_date 取派日期
 * @apiSuccess {string} data.data.name 材料名称
 * @apiSuccess {string} data.data.code 材料代码
 * @apiSuccess {string} data.data.out_order_no 外部订单号/标识
 * @apiSuccess {string} data.data.expect_quantity 预计数量
 * @apiSuccess {string} data.data.actual_quantity 实际数量
 * @apiSuccess {string} data.data.remark 备注
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 114,
 * "company_id": 3,
 * "merchant_id": null,
 * "batch_no": "ZD1010",
 * "tour_no": "4ZA01",
 * "order_no": "SMAAABMI0001",
 * "execution_date": null,
 * "name": "快递纸箱",
 * "code": "MFOB",
 * "out_order_no": "19855",
 * "expect_quantity": 1,
 * "actual_quantity": 1,
 * "remark": "",
 * "created_at": "2020-07-28 16:36:05",
 * "updated_at": "2020-07-28 19:18:39"
 * },
 * {
 * "id": 115,
 * "company_id": 3,
 * "merchant_id": null,
 * "batch_no": "ZD1010",
 * "tour_no": "4ZA01",
 * "order_no": "SMAAABMI0001",
 * "execution_date": null,
 * "name": "气柱袋",
 * "code": "ACB",
 * "out_order_no": "19856",
 * "expect_quantity": 1,
 * "actual_quantity": 1,
 * "remark": "",
 * "created_at": "2020-07-28 16:36:05",
 * "updated_at": "2020-07-28 19:18:39"
 * },
 * {
 * "id": 116,
 * "company_id": 3,
 * "merchant_id": null,
 * "batch_no": "ZD1010",
 * "tour_no": "4ZA01",
 * "order_no": "SMAAABMI0001",
 * "execution_date": null,
 * "name": "填充塑料",
 * "code": "FOAM",
 * "out_order_no": "19857",
 * "expect_quantity": 1,
 * "actual_quantity": 1,
 * "remark": "",
 * "created_at": "2020-07-28 16:36:05",
 * "updated_at": "2020-07-28 19:18:39"
 * },
 * {
 * "id": 111,
 * "company_id": 3,
 * "merchant_id": null,
 * "batch_no": "ZD1009",
 * "tour_no": "4YZ01",
 * "order_no": "SMAAABMH0001",
 * "execution_date": null,
 * "name": "测试纸箱",
 * "code": "TEST02",
 * "out_order_no": "19852",
 * "expect_quantity": 1,
 * "actual_quantity": 1,
 * "remark": "",
 * "created_at": "2020-07-28 16:29:54",
 * "updated_at": "2020-07-28 19:12:11"
 * },
 * {
 * "id": 112,
 * "company_id": 3,
 * "merchant_id": null,
 * "batch_no": "ZD1009",
 * "tour_no": "4YZ01",
 * "order_no": "SMAAABMH0001",
 * "execution_date": null,
 * "name": "棕色胶带",
 * "code": "Tape-B",
 * "out_order_no": "19853",
 * "expect_quantity": 1,
 * "actual_quantity": 1,
 * "remark": "",
 * "created_at": "2020-07-28 16:29:54",
 * "updated_at": "2020-07-28 19:12:11"
 * },
 * {
 * "id": 113,
 * "company_id": 3,
 * "merchant_id": null,
 * "batch_no": "ZD1009",
 * "tour_no": "4YZ01",
 * "order_no": "SMAAABMH0001",
 * "execution_date": null,
 * "name": "填充塑料",
 * "code": "FOAM",
 * "out_order_no": "19854",
 * "expect_quantity": 1,
 * "actual_quantity": 1,
 * "remark": "",
 * "created_at": "2020-07-28 16:29:54",
 * "updated_at": "2020-07-28 19:12:11"
 * },
 * {
 * "id": 119,
 * "company_id": 3,
 * "merchant_id": null,
 * "batch_no": "ZD1014",
 * "tour_no": "4ZD01",
 * "order_no": "SMAAABMO0001",
 * "execution_date": null,
 * "name": "72822",
 * "code": "72822",
 * "out_order_no": "",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "remark": "",
 * "created_at": "2020-07-28 18:18:08",
 * "updated_at": "2020-07-28 18:18:08"
 * },
 * {
 * "id": 118,
 * "company_id": 3,
 * "merchant_id": null,
 * "batch_no": "",
 * "tour_no": "",
 * "order_no": "SMAAABMM0001",
 * "execution_date": null,
 * "name": "72807",
 * "code": "72807",
 * "out_order_no": "",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "remark": "",
 * "created_at": "2020-07-28 18:10:10",
 * "updated_at": "2020-07-28 18:10:47"
 * },
 * {
 * "id": 117,
 * "company_id": 3,
 * "merchant_id": null,
 * "batch_no": "ZD1000",
 * "tour_no": "4YU01",
 * "order_no": "SMAAABMK0001",
 * "execution_date": null,
 * "name": "72803",
 * "code": "72803",
 * "out_order_no": "",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "remark": "",
 * "created_at": "2020-07-28 17:00:36",
 * "updated_at": "2020-07-28 17:00:36"
 * },
 * {
 * "id": 109,
 * "company_id": 3,
 * "merchant_id": null,
 * "batch_no": "ZD1008",
 * "tour_no": "4YS01",
 * "order_no": "SMAAABMG0001",
 * "execution_date": null,
 * "name": "快递纸箱",
 * "code": "MFOB",
 * "out_order_no": "19850",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "remark": "",
 * "created_at": "2020-07-28 16:24:39",
 * "updated_at": "2020-07-28 16:24:39"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/material?page=1",
 * "last": "http://tms-api.test/api/admin/material?page=9",
 * "prev": null,
 * "next": "http://tms-api.test/api/admin/material?page=2"
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 9,
 * "path": "http://tms-api.test/api/admin/material",
 * "per_page": 10,
 * "to": 10,
 * "total": 89
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/material/{id} 材料详情
 * @apiName 材料详情
 * @apiGroup 30
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 材料ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.merchant_id 货主ID
 * @apiSuccess {string} data.batch_no 站点编号
 * @apiSuccess {string} data.tour_no 货主ID
 * @apiSuccess {string} data.order_no 站点编号
 * @apiSuccess {string} data.execution_date 取派日期
 * @apiSuccess {string} data.name 材料名称
 * @apiSuccess {string} data.code 材料代码
 * @apiSuccess {string} data.out_order_no 外部订单号/标识
 * @apiSuccess {string} data.expect_quantity 预计数量
 * @apiSuccess {string} data.actual_quantity 实际数量
 * @apiSuccess {string} data.remark 备注
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1,
 * "company_id": 3,
 * "merchant_id": 3,
 * "tour_no": "",
 * "batch_no": "",
 * "order_no": "SMAAABFJ0001",
 * "execution_date": null,
 * "name": "PB新的纸箱",
 * "code": "PB",
 * "out_order_no": "19800",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "remark": "",
 * "created_at": "2020-07-23 13:41:17",
 * "updated_at": "2020-07-23 14:27:20"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/recharge/statistics 充值审核
 * @apiName 充值审核
 * @apiGroup 31
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} verify_remark 审核备注
 * @apiParam {string} verify_recharge_amount 审核金额
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

/**
 * @api {get} /admin/recharge 充值查询
 * @apiName 充值查询
 * @apiGroup 31
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} begin_date 起始时间
 * @apiParam {string} end_date 结束时间
 * @apiParam {string} out_user_id 外部用户ID
 * @apiParam {string} recharge_no 充值记录编号
 * @apiParam {string} verify_status 审核状态
 * @apiParam {string} merchant_id 货主ID
 * @apiParam {string} status 充值状态
 * @apiParam {string} key_word 关键字（货主ID，司机姓名）
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.merchant_id 货主ID
 * @apiSuccess {string} data.data.merchant_name 货主名
 * @apiSuccess {string} data.data.recharge_no 充值记录编号
 * @apiSuccess {string} data.data.transaction_number 外部充值号
 * @apiSuccess {string} data.data.out_user_id 外部用户ID
 * @apiSuccess {string} data.data.out_user_name 外部用户名
 * @apiSuccess {string} data.data.recharge_amount 充值金额
 * @apiSuccess {string} data.data.remerk 充值备注
 * @apiSuccess {string} data.data.status 状态
 * @apiSuccess {string} data.data.verify_recharge_amount 核准充值金额
 * @apiSuccess {string} data.data.verify_status 审核状态
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.data.status_name 充值状态名称
 * @apiSuccess {string} data.data.verify_status_name 审核状态名称
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "company_id": 3,
 * "merchant_id": 17,
 * "merchant_name": "ERP",
 * "recharge_no": "471444630031",
 * "transaction_number": "",
 * "out_user_id": "",
 * "out_user_name": "398352614@qq.com",
 * "recharge_amount": "0.00",
 * "remerk": null,
 * "status": 1,
 * "verify_recharge_amount": "0.00",
 * "verify_status": 1,
 * "created_at": "2020-08-26 20:30:50",
 * "updated_at": "2020-08-26 20:30:50",
 * "status_name": "充值中",
 * "verify_status_name": "未审核"
 * },
 * {
 * "id": 2,
 * "company_id": 3,
 * "merchant_id": 17,
 * "merchant_name": "ERP",
 * "recharge_no": "924688059859",
 * "transaction_number": "",
 * "out_user_id": "",
 * "out_user_name": "398352614@qq.com",
 * "recharge_amount": "1.00",
 * "remerk": null,
 * "status": 1,
 * "verify_recharge_amount": "0.99",
 * "verify_status": 2,
 * "created_at": "2020-08-26 20:31:09",
 * "updated_at": "2020-08-27 10:32:16",
 * "status_name": "充值中",
 * "verify_status_name": "已审核"
 * },
 * {
 * "id": 3,
 * "company_id": 3,
 * "merchant_id": 17,
 * "merchant_name": "ERP",
 * "recharge_no": "776665325482",
 * "transaction_number": "",
 * "out_user_id": "",
 * "out_user_name": "398352614@qq.com",
 * "recharge_amount": "1.00",
 * "remerk": null,
 * "status": 1,
 * "verify_recharge_amount": "0.99",
 * "verify_status": 2,
 * "created_at": "2020-08-26 20:33:45",
 * "updated_at": "2020-08-26 20:38:44",
 * "status_name": "充值中",
 * "verify_status_name": "已审核"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/recharge?page=1",
 * "last": "http://tms-api.test/api/admin/recharge?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/recharge",
 * "per_page": 10,
 * "to": 3,
 * "total": 3
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/recharge/{id} 充值详情
 * @apiName 充值详情
 * @apiGroup 31
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 充值记录ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.merchant_id 货主ID
 * @apiSuccess {string} data.merchant_name 货主名
 * @apiSuccess {string} data.recharge_no 充值记录编号
 * @apiSuccess {string} data.transaction_number 外部充值记录编号
 * @apiSuccess {string} data.out_user_id 外部用户ID
 * @apiSuccess {string} data.out_user_name 外部用户名
 * @apiSuccess {string} data.recharge_date 充值日期
 * @apiSuccess {string} data.recharge_amount 充值金额
 * @apiSuccess {string} data.recharge_first_pic 充值图片1
 * @apiSuccess {string} data.recharge_second_pic 充值图片2
 * @apiSuccess {string} data.recharge_third_pic 充值图片3
 * @apiSuccess {string} data.signature 签名图片
 * @apiSuccess {string} data.remark 备注
 * @apiSuccess {string} data.status 状态
 * @apiSuccess {string} data.verify_status 审核状态
 * @apiSuccess {string} data.verify_remark 审核备注
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.status_name 状态名
 * @apiSuccess {string} data.verify_status_name 审核状态名
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1,
 * "company_id": 3,
 * "merchant_id": 17,
 * "merchant_name": "ERP",
 * "recharge_no": "998267663816",
 * "transaction_number": "",
 * "out_user_id": "",
 * "out_user_name": "398352614@qq.com",
 * "recharge_date": null,
 * "recharge_amount": "1.00",
 * "recharge_first_pic": "1.jpg",
 * "recharge_second_pic": "",
 * "recharge_third_pic": "",
 * "signature": "2.jpg",
 * "remark": "备注",
 * "status": 1,
 * "verify_status": 1,
 * "verify_remark": "",
 * "created_at": "2020-08-27 10:40:18",
 * "updated_at": "2020-08-27 10:40:18",
 * "status_name": "充值中",
 * "verify_status_name": "未审核"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/recharge/statistics 充值统计查询
 * @apiName 充值统计查询
 * @apiGroup 31
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} begin_date 起始时间
 * @apiParam {string} end_date 结束时间
 * @apiParam {string} merchant_id 货主ID
 * @apiParam {string} status 充值状态
 * @apiParam {string} key_word 关键字（货主ID，司机姓名）
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 充值统计ID
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.merchant_id 货主ID
 * @apiSuccess {string} data.data.merchant_name 货主名
 * @apiSuccess {string} data.data.driver_id 司机ID
 * @apiSuccess {string} data.data.driver_name 司机名
 * @apiSuccess {string} data.data.recharge_date 充值日期
 * @apiSuccess {string} data.data.total_recharge_amount 充值总金额
 * @apiSuccess {string} data.data.status 审核状态1-未审核2-已审核
 * @apiSuccess {string} data.data.verify_recharge_amount 审核金额
 * @apiSuccess {string} data.data.verify_date 审核日期
 * @apiSuccess {string} data.data.verify_time 审核时间
 * @apiSuccess {string} data.data.verify_remark 审核备注
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.data.status_name 状态名
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "company_id": 3,
 * "merchant_id": 65,
 * "merchant_name": "货主1",
 * "driver_id": 65,
 * "driver_name": "Yiki",
 * "recharge_date": "2020-09-08",
 * "total_recharge_amount": "1110.00",
 * "status": 1,
 * "verify_recharge_amount": "0.00",
 * "verify_date": null,
 * "verify_time": null,
 * "verify_remark": "",
 * "created_at": "2020-09-08 19:13:30",
 * "updated_at": "2020-09-08 19:13:30",
 * "status_name": "未审核",
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/recharge/statistics?page=1",
 * "last": "http://tms-api.test/api/admin/recharge/statistics?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/recharge/statistics",
 * "per_page": 200,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/recharge/statistics/{id} 充值统计详情
 * @apiName 充值统计详情
 * @apiGroup 31
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} begin_date 起始时间
 * @apiParam {string} end_date 结束时间
 * @apiParam {string} merchant_id 货主ID
 * @apiParam {string} status 充值状态
 * @apiParam {string} key_word 关键字（货主ID，司机姓名）
 * @apiParam {string} id 充值统计ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 充值统计ID
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.merchant_id 货主ID
 * @apiSuccess {string} data.recharge_date 充值日期
 * @apiSuccess {string} data.driver_id 司机ID
 * @apiSuccess {string} data.driver_name 司机姓名
 * @apiSuccess {string} data.total_recharge_amount 充值总金额
 * @apiSuccess {string} data.recharge_count 充值单数
 * @apiSuccess {string} data.status 审核状态
 * @apiSuccess {string} data.verify_date 审核日期
 * @apiSuccess {string} data.verify_time 审核时间
 * @apiSuccess {string} data.verify_recharge_amount 审核金额
 * @apiSuccess {string} data.verify_remark 审核备注
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.recharge_list
 * @apiSuccess {string} data.recharge_list.id 充值ID
 * @apiSuccess {string} data.recharge_list.company_id 公司ID
 * @apiSuccess {string} data.recharge_list.merchant_id 货主ID
 * @apiSuccess {string} data.recharge_list.merchant_name 货主姓名
 * @apiSuccess {string} data.recharge_list.recharge_no 充值单号
 * @apiSuccess {string} data.recharge_list.transaction_number 外部充值单号
 * @apiSuccess {string} data.recharge_list.driver_name 司机姓名
 * @apiSuccess {string} data.recharge_list.out_user_id 外部用户ID
 * @apiSuccess {string} data.recharge_list.out_user_name 外部用户名
 * @apiSuccess {string} data.recharge_list.recharge_date 充值日期
 * @apiSuccess {string} data.recharge_list.recharge_time 充值时间
 * @apiSuccess {string} data.recharge_list.recharge_amount 充值金额
 * @apiSuccess {string} data.recharge_list.remark 备注
 * @apiSuccess {string} data.recharge_list.status 充值状态
 * @apiSuccess {string} data.recharge_list.created_at
 * @apiSuccess {string} data.recharge_list.updated_at
 * @apiSuccess {string} data.recharge_list.status_name
 * @apiSuccess {string} data.merchant_name 货主名
 * @apiSuccess {string} data.status_name 状态名
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1,
 * "company_id": 3,
 * "merchant_id": 65,
 * "recharge_date": "2020-09-08",
 * "driver_id": 65,
 * "driver_name": "Yiki",
 * "total_recharge_amount": "1110.00",
 * "recharge_count": 0,
 * "status": 1,
 * "verify_date": null,
 * "verify_time": null,
 * "verify_recharge_amount": "0.00",
 * "verify_remark": "",
 * "created_at": "2020-09-08 19:13:30",
 * "updated_at": "2020-09-08 19:13:30",
 * "recharge_list": [
 * {
 * "id": 37,
 * "company_id": 3,
 * "merchant_id": 65,
 * "merchant_name": "货主1",
 * "recharge_no": "00030000081",
 * "transaction_number": "1586819103307",
 * "driver_name": "Yiki",
 * "out_user_id": "904567",
 * "out_user_name": "wanglihui@nle-tech.com",
 * "recharge_date": "2020-09-08",
 * "recharge_time": "2020-09-08 18:51:51",
 * "recharge_amount": "222.00",
 * "remark": "备注",
 * "status": 3,
 * "created_at": "2020-09-08 18:43:35",
 * "updated_at": "2020-09-08 18:51:51",
 * "status_name": "充值完成",
 * "verify_status_name": "未审核"
 * },
 * {
 * "id": 38,
 * "company_id": 3,
 * "merchant_id": 65,
 * "merchant_name": "货主1",
 * "recharge_no": "00030000082",
 * "transaction_number": "1586819104568",
 * "driver_name": "Yiki",
 * "out_user_id": "904567",
 * "out_user_name": "wanglihui@nle-tech.com",
 * "recharge_date": "2020-09-08",
 * "recharge_time": "2020-09-08 18:54:12",
 * "recharge_amount": "222.00",
 * "remark": "备注",
 * "status": 3,
 * "created_at": "2020-09-08 18:53:38",
 * "updated_at": "2020-09-08 18:54:12",
 * "status_name": "充值完成",
 * "verify_status_name": "未审核"
 * },
 * {
 * "id": 39,
 * "company_id": 3,
 * "merchant_id": 65,
 * "merchant_name": "货主1",
 * "recharge_no": "00030000083",
 * "transaction_number": "1586819105488",
 * "driver_name": "Yiki",
 * "out_user_id": "904567",
 * "out_user_name": "wanglihui@nle-tech.com",
 * "recharge_date": "2020-09-08",
 * "recharge_time": "2020-09-08 18:55:05",
 * "recharge_amount": "222.00",
 * "remark": "备注",
 * "status": 3,
 * "created_at": "2020-09-08 18:54:46",
 * "updated_at": "2020-09-08 18:55:05",
 * "status_name": "充值完成",
 * "verify_status_name": "未审核"
 * },
 * {
 * "id": 40,
 * "company_id": 3,
 * "merchant_id": 65,
 * "merchant_name": "货主1",
 * "recharge_no": "00030000084",
 * "transaction_number": "1586819106849",
 * "driver_name": "Yiki",
 * "out_user_id": "904567",
 * "out_user_name": "wanglihui@nle-tech.com",
 * "recharge_date": "2020-09-08",
 * "recharge_time": "2020-09-08 18:59:07",
 * "recharge_amount": "222.00",
 * "remark": "备注",
 * "status": 3,
 * "created_at": "2020-09-08 18:58:43",
 * "updated_at": "2020-09-08 18:59:07",
 * "status_name": "充值完成",
 * "verify_status_name": "未审核"
 * },
 * {
 * "id": 41,
 * "company_id": 3,
 * "merchant_id": 65,
 * "merchant_name": "货主1",
 * "recharge_no": "00030000085",
 * "transaction_number": "1586819107947",
 * "driver_name": "Yiki",
 * "out_user_id": "904567",
 * "out_user_name": "wanglihui@nle-tech.com",
 * "recharge_date": "2020-09-08",
 * "recharge_time": "2020-09-08 19:13:30",
 * "recharge_amount": "222.00",
 * "remark": "备注",
 * "status": 3,
 * "created_at": "2020-09-08 19:04:30",
 * "updated_at": "2020-09-08 19:13:30",
 * "status_name": "充值完成",
 * "verify_status_name": "未审核"
 * }
 * ],
 * "merchant_name": "货主1",
 * "status_name": "未审核"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/device 列表查询
 * @apiName 列表查询
 * @apiGroup 32
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} driver_id 司机ID
 * @apiParam {string} keyword 关键字
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id ID
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.driver_id
 * @apiSuccess {string} data.data.number 型号
 * @apiSuccess {string} data.data.driver_name 司机名称
 * @apiSuccess {string} data.data.status 状态1-在线2-离线
 * @apiSuccess {string} data.data.status_name 状态名称
 * @apiSuccess {string} data.data.mode 模式
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 */

/**
 * @api {get} /admin/device/{id} 获取详情
 * @apiName 获取详情
 * @apiGroup 32
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 设备ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.driver_id
 * @apiSuccess {string} data.number 设备型号
 * @apiSuccess {string} data.status 状态1-在线2-离线
 * @apiSuccess {string} data.mode 模式
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.driver_id_name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 2,
 * "company_id": 3,
 * "driver_id": 7,
 * "number": "1112313245613",
 * "status": 1,
 * "mode": "GPS",
 * "created_at": "2020-09-25 10:53:55",
 * "updated_at": "2020-09-25 11:02:22",
 * "driver_id_name": "小龙"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/device 新增
 * @apiName 新增
 * @apiGroup 32
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} number 型号
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

/**
 * @api {put} /admin/device/{id} 修改
 * @apiName 修改
 * @apiGroup 32
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 设备ID
 * @apiParam {string} number 型号
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

/**
 * @api {put} /admin/device/{id}/bind 绑定
 * @apiName 绑定
 * @apiGroup 32
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 设备ID
 * @apiParam {string} driver_id 司机ID
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

/**
 * @api {put} /admin/device/{id/}/unBind 解绑
 * @apiName 解绑
 * @apiGroup 32
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 设备ID
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

/**
 * @api {delete} /admin/device/{id} 删除
 * @apiName 删除
 * @apiGroup 32
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 设备ID
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

/**
 * @api {get} /admin/device/driver 司机查询
 * @apiName 司机查询
 * @apiGroup 32
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id ID
 * @apiSuccess {string} data.data.email 用户邮箱
 * @apiSuccess {string} data.data.fullname 姓名
 * @apiSuccess {string} data.data.gender 性别
 * @apiSuccess {string} data.data.birthday 生日
 * @apiSuccess {string} data.data.phone 手机
 * @apiSuccess {string} data.data.duty_paragraph 税号
 * @apiSuccess {string} data.data.post_code 邮编
 * @apiSuccess {string} data.data.door_no 门牌号
 * @apiSuccess {string} data.data.street 街道
 * @apiSuccess {string} data.data.city 城市
 * @apiSuccess {string} data.data.country 国家
 * @apiSuccess {string} data.data.lisence_number 驾照编号
 * @apiSuccess {string} data.data.lisence_valid_date 有效期
 * @apiSuccess {string} data.data.lisence_type 驾照类型
 * @apiSuccess {string} data.data.lisence_material 驾照材料
 * @apiSuccess {string} data.data.government_material 政府信件
 * @apiSuccess {string} data.data.avatar 头像
 * @apiSuccess {string} data.data.bank_name 银行名称
 * @apiSuccess {string} data.data.iban IBAN
 * @apiSuccess {string} data.data.bic BIC
 * @apiSuccess {string} data.data.crop_type 合作类型（1-雇佣，2-包线）
 * @apiSuccess {string} data.data.is_locked 是否锁定1-正常2-锁定
 * @apiSuccess {string} data.data.is_locked_name 是否锁定名称
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "email": "398352614@qq.com",
 * "last_name": "胡",
 * "first_name": "洋铭",
 * "gender": "男",
 * "birthday": "1993-09-03",
 * "phone": "17570715315",
 * "duty_paragraph": "42302134",
 * "post_code": "4938AC",
 * "door_no": "233",
 * "street": "麓谷大道",
 * "city": "长沙",
 * "country": "中国",
 * "lisence_number": "21303203349",
 * "lisence_valid_date": "2019-12-31",
 * "lisence_type": "C1",
 * "lisence_material": "\"https:\\/\\/www.header_picture.png\"",
 * "government_material": "\"https:\\/\\/www.header1_picture.png\"",
 * "avatar": "https://www.header2_picture.png",
 * "bank_name": "中国银行",
 * "iban": "324912938912481203",
 * "bic": "328491023",
 * "crop_type": 1,
 * "is_locked": 2
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/driver?page=1",
 * "last": "http://tms-api.test/api/admin/driver?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/driver",
 * "per_page": 15,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/tracking-order/{id}/get-batch 获取可分配站点列表
 * @apiName 获取可分配站点列表
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} execution_date 重新指定的取派日期
 * @apiParam {string} id 运单ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.batch_no 站点编号
 * @apiSuccess {string} data.data.tour_no 取件线路编号
 * @apiSuccess {string} data.data.line_id 线路ID
 * @apiSuccess {string} data.data.line_name 线路名称
 * @apiSuccess {string} data.data.execution_date 取派日期
 * @apiSuccess {string} data.data.status 状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派
 * @apiSuccess {string} data.data.driver_id 标签1-正常2-异常
 * @apiSuccess {string} data.data.driver_name 司机姓名
 * @apiSuccess {string} data.data.driver_phone 司机电话
 * @apiSuccess {string} data.data.driver_rest_time 司机休息时长-秒
 * @apiSuccess {string} data.data.car_id 车辆ID
 * @apiSuccess {string} data.data.car_no 车牌号
 * @apiSuccess {string} data.data.sort_id 排序ID
 * @apiSuccess {string} data.data.receiver 收件人姓名
 * @apiSuccess {string} data.data.receiver_phone 收件人电话
 * @apiSuccess {string} data.data.receiver_country 收件人国家
 * @apiSuccess {string} data.data.receiver_post_code 收件人邮编
 * @apiSuccess {string} data.data.receiver_house_number 收件人门牌号
 * @apiSuccess {string} data.data.receiver_city 收件人城市
 * @apiSuccess {string} data.data.receiver_street 收件人街道
 * @apiSuccess {string} data.data.receiver_address 收件人详细地址
 * @apiSuccess {string} data.data.receiver_lon 收件人经度
 * @apiSuccess {string} data.data.receiver_lat 收件人纬度
 * @apiSuccess {string} data.data.expect_arrive_time 预计到达时间
 * @apiSuccess {string} data.data.actual_arrive_time 实际到达时间
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 17,
 * "company_id": 2,
 * "batch_no": "BATCH00020001M",
 * "tour_no": "TOUR00020001G",
 * "line_id": 7,
 * "line_name": "万能星期天线",
 * "execution_date": "2020-02-07",
 * "status": 1,
 * "driver_id": null,
 * "driver_name": "",
 * "driver_phone": "",
 * "driver_rest_time": 0,
 * "car_id": null,
 * "car_no": "",
 * "sort_id": 1000,
 * "expect_pickup_quantity": 0,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 2,
 * "actual_pie_quantity": 0,
 * "receiver": "自动添加地址库测试收1",
 * "receiver_phone": "18825558852",
 * "receiver_country": "NL",
 * "receiver_post_code": "5611HW",
 * "receiver_house_number": "314",
 * "receiver_city": "Eindhoven",
 * "receiver_street": "De Regent",
 * "receiver_address": "SAN SA454",
 * "receiver_lon": "5.4740944",
 * "receiver_lat": "51.4384193",
 * "expect_arrive_time": null,
 * "actual_arrive_time": null,
 * "expect_distance": 0,
 * "actual_time": null,
 * "sticker_amount": "0.00",
 * "replace_amount": "0.00",
 * "created_at": "2020-02-06 17:39:59",
 * "updated_at": "2020-02-06 18:04:10"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/order/23/getBatchPageListByOrder?page=1",
 * "last": "http://tms-api.test/api/admin/order/23/getBatchPageListByOrder?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/order/23/getBatchPageListByOrder",
 * "per_page": 10,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {delete} /admin/tracking-order/{id}/remove-batch 订单从站点移除
 * @apiName 订单从站点移除
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 订单ID
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

/**
 * @api {put} /admin/tracking-order/{id}/assign-batch 订单分配至站点
 * @apiName 订单分配至站点
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} execution_date 重新制定的取派日期
 * @apiParam {string} batch_no 站点编号;若为空,则认为是创建新站点
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

/**
 * @api {get} /admin/order/{id}/get-date 获取可选日期-id
 * @apiName 获取可选日期-id
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 订单ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data 日期列表
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * "2020-04-23",
 * "2020-04-26",
 * "2020-04-30",
 * "2020-05-03",
 * "2020-05-07",
 * "2020-05-10",
 * "2020-05-14",
 * "2020-05-17",
 * "2020-05-21"
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/order/get-date 获取可选日期-地址
 * @apiName 获取可选日期-地址
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} type 取派类型1取2派
 * @apiParam {string} receiver_post_code 收件人邮编（模板1）
 * @apiParam {string} receiver_house_number 收件人门牌号（模板1）
 * @apiParam {string} receiver_city 收件人城市（模板1）
 * @apiParam {string} receiver_street 收件人街道（模板1）
 * @apiParam {string} receiver_address 地址（模板2）
 * @apiParam {string} receiver_lat 纬度（模板1，模板2）
 * @apiParam {string} receiver_lon 经度（模板1，模板2）
 * @apiSuccess {string} code
 * @apiSuccess {string} data 日期列表
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * "2020-04-23",
 * "2020-04-26",
 * "2020-04-30",
 * "2020-05-03",
 * "2020-05-07",
 * "2020-05-10",
 * "2020-05-14",
 * "2020-05-17",
 * "2020-05-21"
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {delete} /admin/tracking-order/remove-batch 批量订单从站点移除
 * @apiName 批量订单从站点移除
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list id列表,以逗号分隔
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

/**
 * @api {get} /admin/order/get-tour 获取可加单的取件线路列表
 * @apiName 获取可加单的取件线路列表
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} order_id_list 订单ID列表，以逗号分隔
 * @apiParam {string} key_word 关键字(司机或者线路)
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id ID
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.tour_no 取件线路编号
 * @apiSuccess {string} data.data.line_id
 * @apiSuccess {string} data.data.line_name 线路名称
 * @apiSuccess {string} data.data.execution_date
 * @apiSuccess {string} data.data.driver_id
 * @apiSuccess {string} data.data.driver_name 司机名称
 * @apiSuccess {string} data.data.driver_phone
 * @apiSuccess {string} data.data.driver_assign_status
 * @apiSuccess {string} data.data.driver_rest_time
 * @apiSuccess {string} data.data.driver_avt_id
 * @apiSuccess {string} data.data.car_id
 * @apiSuccess {string} data.data.car_no
 * @apiSuccess {string} data.data.car_assign_status
 * @apiSuccess {string} data.data.warehouse_id
 * @apiSuccess {string} data.data.warehouse_name
 * @apiSuccess {string} data.data.warehouse_phone
 * @apiSuccess {string} data.data.warehouse_country
 * @apiSuccess {string} data.data.warehouse_post_code
 * @apiSuccess {string} data.data.warehouse_city
 * @apiSuccess {string} data.data.warehouse_street
 * @apiSuccess {string} data.data.warehouse_house_number
 * @apiSuccess {string} data.data.warehouse_address
 * @apiSuccess {string} data.data.warehouse_lon
 * @apiSuccess {string} data.data.warehouse_lat
 * @apiSuccess {string} data.data.status
 * @apiSuccess {string} data.data.status_name 状态
 * @apiSuccess {string} data.data.begin_signature
 * @apiSuccess {string} data.data.begin_signature_remark
 * @apiSuccess {string} data.data.begin_signature_first_pic
 * @apiSuccess {string} data.data.begin_signature_second_pic
 * @apiSuccess {string} data.data.begin_signature_third_pic
 * @apiSuccess {string} data.data.end_signature
 * @apiSuccess {string} data.data.end_signature_remark
 * @apiSuccess {string} data.data.expect_distance
 * @apiSuccess {string} data.data.actual_distance
 * @apiSuccess {string} data.data.expect_pickup_quantity
 * @apiSuccess {string} data.data.actual_pickup_quantity
 * @apiSuccess {string} data.data.expect_pie_quantity
 * @apiSuccess {string} data.data.actual_pie_quantity
 * @apiSuccess {string} data.data.sticker_amount
 * @apiSuccess {string} data.data.replace_amount
 * @apiSuccess {string} data.data.remark
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 216,
 * "company_id": 3,
 * "tour_no": "TOUR00030005E",
 * "line_id": 11,
 * "line_name": "AMS",
 * "execution_date": "2020-05-03",
 * "driver_id": null,
 * "driver_name": "",
 * "driver_phone": "",
 * "driver_assign_status": 1,
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": null,
 * "car_no": "",
 * "car_assign_status": 1,
 * "warehouse_id": 4,
 * "warehouse_name": "tianyaox",
 * "warehouse_phone": "",
 * "warehouse_country": "NL",
 * "warehouse_post_code": "2153PJ",
 * "warehouse_city": "Nieuw-Vennep",
 * "warehouse_street": "Pesetaweg",
 * "warehouse_house_number": "20",
 * "warehouse_address": null,
 * "warehouse_lon": "4.62897256",
 * "warehouse_lat": "52.25347699",
 * "status": 1,
 * "status_name": "待分配",
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": 0,
 * "actual_distance": null,
 * "expect_pickup_quantity": 5,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 2,
 * "actual_pie_quantity": 0,
 * "sticker_amount": "0.00",
 * "replace_amount": "0.00",
 * "remark": null,
 * "created_at": "2020-04-10 17:43:42",
 * "updated_at": "2020-04-14 16:40:24"
 * },
 * {
 * "id": 210,
 * "company_id": 3,
 * "tour_no": "TOUR00030005B",
 * "line_id": 11,
 * "line_name": "AMS",
 * "execution_date": "2020-04-21",
 * "driver_id": null,
 * "driver_name": "",
 * "driver_phone": "",
 * "driver_assign_status": 1,
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": null,
 * "car_no": "",
 * "car_assign_status": 1,
 * "warehouse_id": 4,
 * "warehouse_name": "tianyaox",
 * "warehouse_phone": "",
 * "warehouse_country": "NL",
 * "warehouse_post_code": "2153PJ",
 * "warehouse_city": "Nieuw-Vennep",
 * "warehouse_street": "Pesetaweg",
 * "warehouse_house_number": "20",
 * "warehouse_address": null,
 * "warehouse_lon": "4.62897256",
 * "warehouse_lat": "52.25347699",
 * "status": 1,
 * "status_name": "待分配",
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": 457.26,
 * "actual_distance": null,
 * "expect_pickup_quantity": 2,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 3,
 * "actual_pie_quantity": 0,
 * "sticker_amount": "0.00",
 * "replace_amount": "1.00",
 * "remark": null,
 * "created_at": "2020-04-10 14:37:08",
 * "updated_at": "2020-04-22 14:30:15"
 * },
 * {
 * "id": 207,
 * "company_id": 3,
 * "tour_no": "TOUR00030004Z",
 * "line_id": 11,
 * "line_name": "AMS",
 * "execution_date": "2020-05-06",
 * "driver_id": null,
 * "driver_name": "",
 * "driver_phone": "",
 * "driver_assign_status": 1,
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": null,
 * "car_no": "",
 * "car_assign_status": 1,
 * "warehouse_id": 4,
 * "warehouse_name": "tianyaox",
 * "warehouse_phone": "",
 * "warehouse_country": "NL",
 * "warehouse_post_code": "2153PJ",
 * "warehouse_city": "Nieuw-Vennep",
 * "warehouse_street": "Pesetaweg",
 * "warehouse_house_number": "20",
 * "warehouse_address": null,
 * "warehouse_lon": "4.62897256",
 * "warehouse_lat": "52.25347699",
 * "status": 1,
 * "status_name": "待分配",
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": 0,
 * "actual_distance": null,
 * "expect_pickup_quantity": 2,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": -1,
 * "actual_pie_quantity": 0,
 * "sticker_amount": "0.00",
 * "replace_amount": "0.00",
 * "remark": null,
 * "created_at": "2020-04-09 19:02:00",
 * "updated_at": "2020-04-15 17:46:07"
 * },
 * {
 * "id": 201,
 * "company_id": 3,
 * "tour_no": "TOUR00030004W",
 * "line_id": 11,
 * "line_name": "AMS",
 * "execution_date": "2020-04-15",
 * "driver_id": null,
 * "driver_name": "",
 * "driver_phone": "",
 * "driver_assign_status": 1,
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": null,
 * "car_no": "",
 * "car_assign_status": 1,
 * "warehouse_id": 4,
 * "warehouse_name": "tianyaox",
 * "warehouse_phone": "",
 * "warehouse_country": "NL",
 * "warehouse_post_code": "2153PJ",
 * "warehouse_city": "Nieuw-Vennep",
 * "warehouse_street": "Pesetaweg",
 * "warehouse_house_number": "20",
 * "warehouse_address": null,
 * "warehouse_lon": "4.62897256",
 * "warehouse_lat": "52.25347699",
 * "status": 1,
 * "status_name": "待分配",
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": 0,
 * "actual_distance": null,
 * "expect_pickup_quantity": 4,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": -1,
 * "actual_pie_quantity": 0,
 * "sticker_amount": "0.00",
 * "replace_amount": "0.00",
 * "remark": null,
 * "created_at": "2020-04-09 17:07:55",
 * "updated_at": "2020-04-09 17:56:56"
 * },
 * {
 * "id": 189,
 * "company_id": 3,
 * "tour_no": "TOUR00030004N",
 * "line_id": 11,
 * "line_name": "AMS",
 * "execution_date": "2020-04-10",
 * "driver_id": null,
 * "driver_name": "",
 * "driver_phone": "",
 * "driver_assign_status": 1,
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": null,
 * "car_no": "",
 * "car_assign_status": 1,
 * "warehouse_id": 4,
 * "warehouse_name": "tianyaox",
 * "warehouse_phone": "",
 * "warehouse_country": "NL",
 * "warehouse_post_code": "2153PJ",
 * "warehouse_city": "Nieuw-Vennep",
 * "warehouse_street": "Pesetaweg",
 * "warehouse_house_number": "20",
 * "warehouse_address": null,
 * "warehouse_lon": "4.62897256",
 * "warehouse_lat": "52.25347699",
 * "status": 1,
 * "status_name": "待分配",
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": 0,
 * "actual_distance": null,
 * "expect_pickup_quantity": 7,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 2,
 * "actual_pie_quantity": 0,
 * "sticker_amount": "0.00",
 * "replace_amount": "0.00",
 * "remark": null,
 * "created_at": "2020-04-07 18:25:35",
 * "updated_at": "2020-04-07 18:26:01"
 * },
 * {
 * "id": 187,
 * "company_id": 3,
 * "tour_no": "TOUR00030004L",
 * "line_id": 11,
 * "line_name": "AMS",
 * "execution_date": "2020-04-09",
 * "driver_id": null,
 * "driver_name": "",
 * "driver_phone": "",
 * "driver_assign_status": 1,
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": null,
 * "car_no": "",
 * "car_assign_status": 1,
 * "warehouse_id": 4,
 * "warehouse_name": "tianyaox",
 * "warehouse_phone": "",
 * "warehouse_country": "NL",
 * "warehouse_post_code": "2153PJ",
 * "warehouse_city": "Nieuw-Vennep",
 * "warehouse_street": "Pesetaweg",
 * "warehouse_house_number": "20",
 * "warehouse_address": null,
 * "warehouse_lon": "4.62897256",
 * "warehouse_lat": "52.25347699",
 * "status": 1,
 * "status_name": "待分配",
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": 0,
 * "actual_distance": null,
 * "expect_pickup_quantity": 2,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 1,
 * "actual_pie_quantity": 0,
 * "sticker_amount": "0.00",
 * "replace_amount": "0.00",
 * "remark": null,
 * "created_at": "2020-04-07 18:19:46",
 * "updated_at": "2020-04-08 14:32:06"
 * },
 * {
 * "id": 186,
 * "company_id": 3,
 * "tour_no": "TOUR00030004K",
 * "line_id": 11,
 * "line_name": "AMS",
 * "execution_date": "2020-04-27",
 * "driver_id": null,
 * "driver_name": "",
 * "driver_phone": "",
 * "driver_assign_status": 1,
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": null,
 * "car_no": "",
 * "car_assign_status": 1,
 * "warehouse_id": 4,
 * "warehouse_name": "tianyaox",
 * "warehouse_phone": "",
 * "warehouse_country": "NL",
 * "warehouse_post_code": "2153PJ",
 * "warehouse_city": "Nieuw-Vennep",
 * "warehouse_street": "Pesetaweg",
 * "warehouse_house_number": "20",
 * "warehouse_address": null,
 * "warehouse_lon": "4.62897256",
 * "warehouse_lat": "52.25347699",
 * "status": 1,
 * "status_name": "待分配",
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": 0,
 * "actual_distance": null,
 * "expect_pickup_quantity": 1,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 1,
 * "actual_pie_quantity": 0,
 * "sticker_amount": "0.00",
 * "replace_amount": "0.00",
 * "remark": null,
 * "created_at": "2020-04-07 18:13:26",
 * "updated_at": "2020-04-13 16:46:38"
 * },
 * {
 * "id": 184,
 * "company_id": 3,
 * "tour_no": "TOUR00030004I",
 * "line_id": 11,
 * "line_name": "AMS",
 * "execution_date": "2020-04-19",
 * "driver_id": null,
 * "driver_name": "",
 * "driver_phone": "",
 * "driver_assign_status": 1,
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": null,
 * "car_no": "",
 * "car_assign_status": 1,
 * "warehouse_id": 4,
 * "warehouse_name": "tianyaox",
 * "warehouse_phone": "",
 * "warehouse_country": "NL",
 * "warehouse_post_code": "2153PJ",
 * "warehouse_city": "Nieuw-Vennep",
 * "warehouse_street": "Pesetaweg",
 * "warehouse_house_number": "20",
 * "warehouse_address": null,
 * "warehouse_lon": "4.62897256",
 * "warehouse_lat": "52.25347699",
 * "status": 1,
 * "status_name": "待分配",
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": 0,
 * "actual_distance": null,
 * "expect_pickup_quantity": 1,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 0,
 * "actual_pie_quantity": 0,
 * "sticker_amount": "0.00",
 * "replace_amount": "0.00",
 * "remark": null,
 * "created_at": "2020-04-07 17:15:11",
 * "updated_at": "2020-04-07 17:15:11"
 * },
 * {
 * "id": 182,
 * "company_id": 3,
 * "tour_no": "TOUR00030004G",
 * "line_id": 11,
 * "line_name": "AMS",
 * "execution_date": "2020-04-13",
 * "driver_id": null,
 * "driver_name": "",
 * "driver_phone": "",
 * "driver_assign_status": 1,
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": null,
 * "car_no": "",
 * "car_assign_status": 1,
 * "warehouse_id": 4,
 * "warehouse_name": "tianyaox",
 * "warehouse_phone": "",
 * "warehouse_country": "NL",
 * "warehouse_post_code": "2153PJ",
 * "warehouse_city": "Nieuw-Vennep",
 * "warehouse_street": "Pesetaweg",
 * "warehouse_house_number": "20",
 * "warehouse_address": null,
 * "warehouse_lon": "4.62897256",
 * "warehouse_lat": "52.25347699",
 * "status": 1,
 * "status_name": "待分配",
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": 0,
 * "actual_distance": null,
 * "expect_pickup_quantity": 6,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": -3,
 * "actual_pie_quantity": 0,
 * "sticker_amount": "0.00",
 * "replace_amount": "0.00",
 * "remark": null,
 * "created_at": "2020-04-07 16:38:44",
 * "updated_at": "2020-04-13 16:46:38"
 * },
 * {
 * "id": 180,
 * "company_id": 3,
 * "tour_no": "TOUR00030004E",
 * "line_id": 11,
 * "line_name": "AMS",
 * "execution_date": "2020-04-20",
 * "driver_id": null,
 * "driver_name": "",
 * "driver_phone": "",
 * "driver_assign_status": 1,
 * "driver_rest_time": null,
 * "driver_avt_id": null,
 * "car_id": null,
 * "car_no": "",
 * "car_assign_status": 1,
 * "warehouse_id": 4,
 * "warehouse_name": "tianyaox",
 * "warehouse_phone": "",
 * "warehouse_country": "NL",
 * "warehouse_post_code": "2153PJ",
 * "warehouse_city": "Nieuw-Vennep",
 * "warehouse_street": "Pesetaweg",
 * "warehouse_house_number": "20",
 * "warehouse_address": null,
 * "warehouse_lon": "4.62897256",
 * "warehouse_lat": "52.25347699",
 * "status": 1,
 * "status_name": "待分配",
 * "begin_signature": null,
 * "begin_signature_remark": null,
 * "begin_signature_first_pic": null,
 * "begin_signature_second_pic": null,
 * "begin_signature_third_pic": null,
 * "end_signature": null,
 * "end_signature_remark": null,
 * "expect_distance": 0,
 * "actual_distance": null,
 * "expect_pickup_quantity": 55,
 * "actual_pickup_quantity": 0,
 * "expect_pie_quantity": 62,
 * "actual_pie_quantity": 0,
 * "sticker_amount": "0.00",
 * "replace_amount": "0.00",
 * "remark": null,
 * "created_at": "2020-04-07 16:29:48",
 * "updated_at": "2020-04-21 16:55:47"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/tour/getAddOrderPageList?page=1",
 * "last": "http://tms-api.test/api/admin/tour/getAddOrderPageList?page=3",
 * "prev": null,
 * "next": "http://tms-api.test/api/admin/tour/getAddOrderPageList?page=2"
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 3,
 * "path": "http://tms-api.test/api/admin/tour/getAddOrderPageList",
 * "per_page": 10,
 * "to": 10,
 * "total": 23
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} admin/tracking-order/get-line 获取线路
 * @apiName 获取线路
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 线路ID
 * @apiSuccess {string} data.name 线路名称
 * @apiSuccess {string} data.country_name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 208,
 * "name": "NL",
 * "country_name": null
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/tracking-order/init 查询初始化
 * @apiName 查询初始化
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.status_list 状态列表
 * @apiSuccess {string} data.status_list.id
 * @apiSuccess {string} data.status_list.name
 * @apiSuccess {string} data.type_list 类型列表
 * @apiSuccess {string} data.type_list.id
 * @apiSuccess {string} data.type_list.name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "status_list": [
 * {
 * "id": 1,
 * "name": "待分配"
 * },
 * {
 * "id": 2,
 * "name": "已分配"
 * },
 * {
 * "id": 3,
 * "name": "待出库"
 * },
 * {
 * "id": 4,
 * "name": "取派中"
 * },
 * {
 * "id": 5,
 * "name": "已完成"
 * },
 * {
 * "id": 6,
 * "name": "取消取派"
 * },
 * {
 * "id": 7,
 * "name": "回收站"
 * }
 * ],
 * "type_list": [
 * {
 * "id": "0",
 * "name": "全部"
 * },
 * {
 * "id": 1,
 * "name": "取件"
 * },
 * {
 * "id": 2,
 * "name": "派件"
 * }
 * ]
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/tracking-order/count 运单统计
 * @apiName 运单统计
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.all_count 全部数量
 * @apiSuccess {string} data.no_take 待取派数量
 * @apiSuccess {string} data.assign 已分配数量
 * @apiSuccess {string} data.wait_out 待出库数量
 * @apiSuccess {string} data.taking 取派中数量
 * @apiSuccess {string} data.singed 已签收数量
 * @apiSuccess {string} data.cancel_count 取消取派数量
 * @apiSuccess {string} data.delete_count 回收站数量
 * @apiSuccess {string} data.exception_count 异常数量
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "all_count": 1,
 * "no_take": 1,
 * "assign": 0,
 * "wait_out": 0,
 * "taking": 0,
 * "singed": 0,
 * "cancel_count": 0,
 * "delete_count": 0,
 * "exception_count": 0
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/tracking-order 列表查询
 * @apiName 列表查询
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} status 状态(1到6)
 * @apiParam {string} type 类型(1取2-派)
 * @apiParam {string} begin_date 开始日期
 * @apiParam {string} end_date 结束日期
 * @apiParam {string} merchant_id 货主ID
 * @apiParam {string} keyword 关键字
 * @apiParam {string} exception_label 异常标签(1-正常2-异常)
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.merchant_id
 * @apiSuccess {string} data.data.merchant_id_name 所属货主
 * @apiSuccess {string} data.data.order_no 订单号
 * @apiSuccess {string} data.data.tracking_order_no 运单号
 * @apiSuccess {string} data.data.mask_code 掩码
 * @apiSuccess {string} data.data.type
 * @apiSuccess {string} data.data.type_name 类型名称
 * @apiSuccess {string} data.data.out_user_id 外部客户ID
 * @apiSuccess {string} data.data.status
 * @apiSuccess {string} data.data.status_name 状态名称
 * @apiSuccess {string} data.data.out_status
 * @apiSuccess {string} data.data.out_status_name 可出库名称
 * @apiSuccess {string} data.data.execution_date 取派日期
 * @apiSuccess {string} data.data.out_order_no 外部订单号
 * @apiSuccess {string} data.data.exception_label
 * @apiSuccess {string} data.data.exception_label_name 异常标签名称
 * @apiSuccess {string} data.data.place_post_code 邮编
 * @apiSuccess {string} data.data.place_house_number 门牌号
 * @apiSuccess {string} data.data.driver_name 司机名称
 * @apiSuccess {string} data.data.batch_no 站点编号
 * @apiSuccess {string} data.data.tour_no 取件线路编号
 * @apiSuccess {string} data.data.line_id 线路ID
 * @apiSuccess {string} data.data.line_name 线路名称
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 12,
 * "company_id": 3,
 * "merchant_id": 3,
 * "merchant_id_name": "货主asd",
 * "order_no": "SMAAADLX0001",
 * "tracking_order_no": "YD00030000005",
 * "mask_code": "3322s",
 * "type": 1,
 * "type_name": "取件",
 * "out_user_id": 200212,
 * "express_first_no": null,
 * "express_second_no": null,
 * "status": 1,
 * "status_name": "待分配",
 * "out_status": 1,
 * "out_status_name": "是",
 * "execution_date": "2020-11-03",
 * "out_order_no": "",
 * "exception_label": 1,
 * "exception_label_name": null,
 * "place_post_code": "1117EE",
 * "place_house_number": "5",
 * "driver_fullname": null,
 * "batch_no": "",
 * "tour_no": "",
 * "line_id": "",
 * "line_name": "",
 * "created_at": "2020-10-30 13:35:35",
 * "updated_at": "2020-11-02 11:51:52"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/tracking-order?page=1",
 * "last": "http://tms-api.test/api/admin/tracking-order?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/tracking-order",
 * "per_page": 200,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/tracking-order/{id}/get-date 获取可分配的日期
 * @apiName 获取可分配的日期
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 运单ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * "2020-11-02",
 * "2020-11-03",
 * "2020-11-04",
 * "2020-11-05",
 * "2020-11-06",
 * "2020-11-07",
 * "2020-11-08",
 * "2020-11-09",
 * "2020-11-10",
 * "2020-11-11",
 * "2020-11-12",
 * "2020-11-13",
 * "2020-11-14",
 * "2020-11-15",
 * "2020-11-16",
 * "2020-11-17",
 * "2020-11-18",
 * "2020-11-19",
 * "2020-11-20",
 * "2020-11-21",
 * "2020-11-22",
 * "2020-11-23",
 * "2020-11-24",
 * "2020-11-25",
 * "2020-11-26",
 * "2020-11-27",
 * "2020-11-28",
 * "2020-11-29",
 * "2020-11-30",
 * "2020-12-01"
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/tracking-order/assign-list 运单批量分配至取件线路
 * @apiName 运单批量分配至取件线路
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list 运单ID列表,以逗号分隔
 * @apiParam {string} tour_no 取件线路编号
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

/**
 * @api {get} /admin/tracking-order/order-excel 订单导出
 * @apiName 订单导出
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} begin_date 起始时间
 * @apiParam {string} end_date 终止时间
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.name
 * @apiSuccess {string} data.path
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "name": "202007011910167.xlsx",
 * "path": "tms-api.test/storage/admin/excel/2/orderOut/202007011910167.xlsx"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/tracking-order/get-tour 获取可加单取件线路
 * @apiName 获取可加单取件线路
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} tracking_order_id_list 订单ID列表
 * @apiParam {string} keyword 关键词
 * @apiParam {string} execution_date 取派日期
 */

/**
 * @api {get} /admin/tracking-order/{id} 获取详情
 * @apiName 获取详情
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 运单ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.merchant_id
 * @apiSuccess {string} data.merchant_id_name 货主名称
 * @apiSuccess {string} data.out_user_id 外部用户ID
 * @apiSuccess {string} data.out_order_no 外部订单号
 * @apiSuccess {string} data.order_no 订单号
 * @apiSuccess {string} data.tracking_order_no 运单号
 * @apiSuccess {string} data.batch_no 站点编号
 * @apiSuccess {string} data.tour_no 取件线路编号
 * @apiSuccess {string} data.type
 * @apiSuccess {string} data.type_name 类型名称
 * @apiSuccess {string} data.execution_date 日期
 * @apiSuccess {string} data.warehouse_fullname 网点-姓名
 * @apiSuccess {string} data.warehouse_phone 网点-电话
 * @apiSuccess {string} data.warehouse_country 网点-国家
 * @apiSuccess {string} data.warehouse_country_name 网点-国家名称
 * @apiSuccess {string} data.warehouse_post_code 网点-邮编
 * @apiSuccess {string} data.warehouse_house_number 网点-门牌号
 * @apiSuccess {string} data.warehouse_city 网点-城市
 * @apiSuccess {string} data.warehouse_street 网点-街道
 * @apiSuccess {string} data.warehouse_address 网点-地址
 * @apiSuccess {string} data.warehouse_lon 网点-经度
 * @apiSuccess {string} data.warehouse_lat 网点-纬度
 * @apiSuccess {string} data.place_fullname 收货人(发货人)-姓名
 * @apiSuccess {string} data.place_phone
 * @apiSuccess {string} data.place_country
 * @apiSuccess {string} data.place_country_name
 * @apiSuccess {string} data.place_post_code
 * @apiSuccess {string} data.place_house_number
 * @apiSuccess {string} data.place_city
 * @apiSuccess {string} data.place_street
 * @apiSuccess {string} data.place_address
 * @apiSuccess {string} data.place_lon
 * @apiSuccess {string} data.place_lat
 * @apiSuccess {string} data.driver_id
 * @apiSuccess {string} data.driver_name
 * @apiSuccess {string} data.driver_phone
 * @apiSuccess {string} data.car_id
 * @apiSuccess {string} data.car_no
 * @apiSuccess {string} data.status
 * @apiSuccess {string} data.status_name
 * @apiSuccess {string} data.out_status
 * @apiSuccess {string} data.out_status_name
 * @apiSuccess {string} data.exception_label
 * @apiSuccess {string} data.exception_label_name
 * @apiSuccess {string} data.cancel_type
 * @apiSuccess {string} data.cancel_remark
 * @apiSuccess {string} data.cancel_picture
 * @apiSuccess {string} data.mask_code
 * @apiSuccess {string} data.special_remark
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 3,
 * "merchant_id": 3,
 * "merchant_id_name": "货主asd",
 * "out_user_id": "908022",
 * "out_order_no": "MES20908022100",
 * "order_no": "SMAAADQQ0001",
 * "tracking_order_no": "YD00030000143",
 * "batch_no": "ZD2367",
 * "tour_no": "4BAM01",
 * "type": 1,
 * "type_name": "取件",
 * "execution_date": "2020-12-01",
 * "warehouse_fullname": "827193289@qq.com",
 * "warehouse_phone": "23145654",
 * "warehouse_country": "NL",
 * "warehouse_post_code": "2153PJ",
 * "warehouse_house_number": "20",
 * "warehouse_city": "Nieuw-Vennep",
 * "warehouse_street": "Pesetaweg",
 * "warehouse_address": "NL Nieuw-Vennep Pesetaweg 20 2153PJ",
 * "warehouse_lon": "4.62897256",
 * "warehouse_lat": "52.25347699",
 * "place_fullname": "测试发货人-取件订单",
 * "place_phone": "0031123123123",
 * "place_country": "NL",
 * "place_post_code": "1086ZK",
 * "place_house_number": "46",
 * "place_city": "Amsterdam",
 * "place_street": "Cornelis Zillesenlaan",
 * "place_address": "NL Amsterdam Cornelis Zillesenlaan 46 1086ZK",
 * "place_lon": "4.98113818",
 * "place_lat": "52.36200569",
 * "driver_id": 71,
 * "driver_name": "herry",
 * "driver_phone": "005263",
 * "car_id": 7,
 * "car_no": "荷—A000",
 * "status": 5,
 * "status_name": "已完成",
 * "out_status": 1,
 * "out_status_name": "是",
 * "exception_label": 1,
 * "exception_label_name": null,
 * "cancel_type": null,
 * "cancel_remark": "",
 * "cancel_picture": "",
 * "mask_code": "",
 * "special_remark": "测试特别事项",
 * "created_at": "2020-11-25T05:42:57.000000Z",
 * "updated_at": "2020-11-25T08:40:10.000000Z"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/tracking-order/out-status 批量修改运单出库状态
 * @apiName 批量修改运单出库状态
 * @apiGroup 33
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list ID列表
 * @apiParam {string} out_status 状态1-可出库2-不可出库
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

/**
 * @api {get} /admin/tracking-order-trail/{tracking_order_no} 查看轨迹
 * @apiName 查看轨迹
 * @apiGroup 34
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} tracking_order_no 运单号
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 轨迹ID
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.tracking_order_no 运单号
 * @apiSuccess {string} data.data.content 内容
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.data.updated_at 修改时间
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 253,
 * "company_id": 1,
 * "order_no": "TMS00010000000000196",
 * "content": "订单已创建",
 * "created_at": "2020-01-06 07:54:04",
 * "updated_at": "2020-01-06 07:54:04",
 * "tracking_order_no":"YD0001"
 * },
 * {
 * "id": 254,
 * "company_id": 1,
 * "order_no": "TMS00010000000000196",
 * "content": "已加入站点",
 * "created_at": "2020-01-06 07:54:04",
 * "updated_at": "2020-01-06 07:54:04",
 * "tracking_order_no":"YD0001"
 * },
 * {
 * "id": 261,
 * "company_id": 1,
 * "order_no": "TMS00010000000000196",
 * "content": "已分配司机",
 * "created_at": "2020-01-06 08:09:10",
 * "updated_at": "2020-01-06 08:09:10",
 * "tracking_order_no":"YD0001"
 * },
 * {
 * "id": 268,
 * "company_id": 1,
 * "order_no": "TMS00010000000000196",
 * "content": "订单装货中",
 * "created_at": "2020-01-06 08:11:50",
 * "updated_at": "2020-01-06 08:11:50",
 * "tracking_order_no":"YD0001"
 * },
 * {
 * "id": 275,
 * "company_id": 1,
 * "order_no": "TMS00010000000000196",
 * "content": "订单派送中",
 * "created_at": "2020-01-06 08:12:00",
 * "updated_at": "2020-01-06 08:12:00",
 * "tracking_order_no":"YD0001"
 * },
 * {
 * "id": 277,
 * "company_id": 1,
 * "order_no": "TMS00010000000000196",
 * "content": "派件成功",
 * "created_at": "2020-01-06 08:16:21",
 * "updated_at": "2020-01-06 08:16:21",
 * "tracking_order_no":"YD0001"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/order-trail?page=1",
 * "last": "http://tms-api.test/api/admin/order-trail?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/order-trail",
 * "per_page": 10,
 * "to": 6,
 * "total": 6
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {delete} /admin/tracking-order-trail/{id} 删除轨迹
 * @apiName 删除轨迹
 * @apiGroup 34
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 轨迹ID
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

/**
 * @api {get} /admin/order-trail/{order_no} 查看轨迹
 * @apiName 查看轨迹
 * @apiGroup 35
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} order_no 订单号
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.merchant_id
 * @apiSuccess {string} data.order_no 订单号
 * @apiSuccess {string} data.tracking_order_no
 * @apiSuccess {string} data.execution_date
 * @apiSuccess {string} data.second_execution_date
 * @apiSuccess {string} data.out_order_no
 * @apiSuccess {string} data.out_group_order_no
 * @apiSuccess {string} data.express_first_no
 * @apiSuccess {string} data.express_second_no
 * @apiSuccess {string} data.mask_code
 * @apiSuccess {string} data.source
 * @apiSuccess {string} data.list_mode
 * @apiSuccess {string} data.type 订单类型1-取2-派3取派
 * @apiSuccess {string} data.out_user_id
 * @apiSuccess {string} data.nature
 * @apiSuccess {string} data.settlement_type
 * @apiSuccess {string} data.delivery
 * @apiSuccess {string} data.status
 * @apiSuccess {string} data.exception_label
 * @apiSuccess {string} data.cancel_type
 * @apiSuccess {string} data.cancel_remark
 * @apiSuccess {string} data.cancel_picture
 * @apiSuccess {string} data.second_place_fullname 第二姓名
 * @apiSuccess {string} data.second_place_phone
 * @apiSuccess {string} data.second_place_country
 * @apiSuccess {string} data.second_place_post_code
 * @apiSuccess {string} data.second_place_house_number
 * @apiSuccess {string} data.second_place_city
 * @apiSuccess {string} data.second_place_street
 * @apiSuccess {string} data.second_place_address 第二地址
 * @apiSuccess {string} data.second_place_lon
 * @apiSuccess {string} data.second_place_lat
 * @apiSuccess {string} data.place_fullname 姓名
 * @apiSuccess {string} data.place_phone
 * @apiSuccess {string} data.place_country
 * @apiSuccess {string} data.place_post_code
 * @apiSuccess {string} data.place_house_number
 * @apiSuccess {string} data.place_city
 * @apiSuccess {string} data.place_street
 * @apiSuccess {string} data.place_address 地址
 * @apiSuccess {string} data.place_lon
 * @apiSuccess {string} data.place_lat
 * @apiSuccess {string} data.special_remark
 * @apiSuccess {string} data.remark
 * @apiSuccess {string} data.unique_code
 * @apiSuccess {string} data.replace_amount
 * @apiSuccess {string} data.settlement_amount
 * @apiSuccess {string} data.sticker_amount
 * @apiSuccess {string} data.delivery_amount
 * @apiSuccess {string} data.out_status
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.order_trail_list
 * @apiSuccess {string} data.order_trail_list.id 订单轨迹列表
 * @apiSuccess {string} data.order_trail_list.company_id
 * @apiSuccess {string} data.order_trail_list.merchant_id
 * @apiSuccess {string} data.order_trail_list.order_no
 * @apiSuccess {string} data.order_trail_list.content
 * @apiSuccess {string} data.order_trail_list.created_at
 * @apiSuccess {string} data.order_trail_list.updated_at
 * @apiSuccess {string} data.package_list
 * @apiSuccess {string} data.package_list.id
 * @apiSuccess {string} data.package_list.company_id
 * @apiSuccess {string} data.package_list.merchant_id
 * @apiSuccess {string} data.package_list.order_no
 * @apiSuccess {string} data.package_list.tracking_order_no
 * @apiSuccess {string} data.package_list.execution_date
 * @apiSuccess {string} data.package_list.second_execution_date
 * @apiSuccess {string} data.package_list.type
 * @apiSuccess {string} data.package_list.name
 * @apiSuccess {string} data.package_list.express_first_no
 * @apiSuccess {string} data.package_list.express_second_no
 * @apiSuccess {string} data.package_list.feature_logo
 * @apiSuccess {string} data.package_list.out_order_no
 * @apiSuccess {string} data.package_list.weight
 * @apiSuccess {string} data.package_list.expect_quantity
 * @apiSuccess {string} data.package_list.actual_quantity
 * @apiSuccess {string} data.package_list.status
 * @apiSuccess {string} data.package_list.sticker_no
 * @apiSuccess {string} data.package_list.sticker_amount
 * @apiSuccess {string} data.package_list.delivery_amount
 * @apiSuccess {string} data.package_list.remark
 * @apiSuccess {string} data.package_list.is_auth
 * @apiSuccess {string} data.package_list.auth_fullname
 * @apiSuccess {string} data.package_list.auth_birth_date
 * @apiSuccess {string} data.package_list.created_at
 * @apiSuccess {string} data.package_list.updated_at
 * @apiSuccess {string} data.package_list.status_name
 * @apiSuccess {string} data.package_list.type_name
 * @apiSuccess {string} data.material_list
 * @apiSuccess {string} data.status_name
 * @apiSuccess {string} data.out_status_name
 * @apiSuccess {string} data.exception_label_name
 * @apiSuccess {string} data.type_name
 * @apiSuccess {string} data.merchant_id_name
 * @apiSuccess {string} data.merchant_id_code
 * @apiSuccess {string} data.place_country_name
 * @apiSuccess {string} data.second_place_country_name
 * @apiSuccess {string} data.country_name
 * @apiSuccess {string} data.settlement_type_name
 * @apiSuccess {string} data.source_name
 * @apiSuccess {string} data.merchant
 * @apiSuccess {string} data.merchant.id
 * @apiSuccess {string} data.merchant.company_id
 * @apiSuccess {string} data.merchant.code
 * @apiSuccess {string} data.merchant.type
 * @apiSuccess {string} data.merchant.name
 * @apiSuccess {string} data.merchant.email
 * @apiSuccess {string} data.merchant.country
 * @apiSuccess {string} data.merchant.settlement_type
 * @apiSuccess {string} data.merchant.merchant_group_id
 * @apiSuccess {string} data.merchant.contacter
 * @apiSuccess {string} data.merchant.phone
 * @apiSuccess {string} data.merchant.address
 * @apiSuccess {string} data.merchant.avatar
 * @apiSuccess {string} data.merchant.status
 * @apiSuccess {string} data.merchant.created_at
 * @apiSuccess {string} data.merchant.updated_at
 * @apiSuccess {string} data.merchant.settlement_type_name
 * @apiSuccess {string} data.merchant.status_name
 * @apiSuccess {string} data.merchant.type_name
 * @apiSuccess {string} data.merchant.country_name
 * @apiSuccess {string} data.merchant.additional_status
 * @apiSuccess {string} data.merchant.advance_days
 * @apiSuccess {string} data.merchant.appointment_days
 * @apiSuccess {string} data.merchant.delay_time
 * @apiSuccess {string} data.merchant.pickup_count
 * @apiSuccess {string} data.merchant.pie_count
 * @apiSuccess {string} data.merchant.merchant_group
 * @apiSuccess {string} data.merchant.merchant_group.id
 * @apiSuccess {string} data.merchant.merchant_group.company_id
 * @apiSuccess {string} data.merchant.merchant_group.name
 * @apiSuccess {string} data.merchant.merchant_group.transport_price_id
 * @apiSuccess {string} data.merchant.merchant_group.count
 * @apiSuccess {string} data.merchant.merchant_group.is_default
 * @apiSuccess {string} data.merchant.merchant_group.additional_status
 * @apiSuccess {string} data.merchant.merchant_group.advance_days
 * @apiSuccess {string} data.merchant.merchant_group.appointment_days
 * @apiSuccess {string} data.merchant.merchant_group.delay_time
 * @apiSuccess {string} data.merchant.merchant_group.pickup_count
 * @apiSuccess {string} data.merchant.merchant_group.pie_count
 * @apiSuccess {string} data.merchant.merchant_group.created_at
 * @apiSuccess {string} data.merchant.merchant_group.updated_at
 * @apiSuccess {string} data.merchant.merchant_group.additional_status_name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1870,
 * "company_id": 3,
 * "merchant_id": 3,
 * "order_no": "SMAAAIKR0001",
 * "tracking_order_no": "YD00030003440",
 * "execution_date": "2021-01-22",
 * "second_execution_date": null,
 * "out_order_no": "DEVV21904566101",
 * "out_group_order_no": null,
 * "express_first_no": "",
 * "express_second_no": "",
 * "mask_code": "B131",
 * "source": "3",
 * "list_mode": 1,
 * "type": 2,
 * "out_user_id": "904566",
 * "nature": 1,
 * "settlement_type": 1,
 * "delivery": 2,
 * "status": 1,
 * "exception_label": 1,
 * "cancel_type": null,
 * "cancel_remark": "",
 * "cancel_picture": "",
 * "second_place_fullname": "",
 * "second_place_phone": "",
 * "second_place_country": "",
 * "second_place_post_code": "",
 * "second_place_house_number": "",
 * "second_place_city": "",
 * "second_place_street": "",
 * "second_place_address": "",
 * "second_place_lon": "",
 * "second_place_lat": "",
 * "place_fullname": "location",
 * "place_phone": "0031562310000",
 * "place_country": "NL",
 * "place_post_code": "6709PG",
 * "place_house_number": "28-3c14",
 * "place_city": "Wageningen",
 * "place_street": "Marijkeweg",
 * "place_address": "NL Wageningen Marijkeweg 28-3c14 6709PG",
 * "place_lon": "5.65001431",
 * "place_lat": "51.96914212",
 * "special_remark": "",
 * "remark": "",
 * "unique_code": "",
 * "replace_amount": "0.00",
 * "settlement_amount": "0.00",
 * "sticker_amount": "0.00",
 * "delivery_amount": "0.00",
 * "out_status": 2,
 * "created_at": "2021-01-20 07:59:33",
 * "updated_at": "2021-01-20 07:59:33",
 * "order_trail_list": [
 * {
 * "id": 4355,
 * "company_id": 3,
 * "merchant_id": 3,
 * "order_no": "SMAAAIKR0001",
 * "content": "订单创建成功，订单号[SMAAAIKR0001]，生成运单号[YD00030003440]",
 * "created_at": "2021-01-20 07:59:33",
 * "updated_at": "2021-01-20 07:59:33"
 * }
 * ],
 * "package_list": [
 * {
 * "id": 1843,
 * "company_id": 3,
 * "merchant_id": 3,
 * "order_no": "SMAAAIKR0001",
 * "tracking_order_no": "YD00030003440",
 * "execution_date": "2021-01-22",
 * "second_execution_date": null,
 * "type": 2,
 * "name": "PPE8041T508",
 * "express_first_no": "PPE8041T508",
 * "express_second_no": "",
 * "feature_logo": "海鲜预售",
 * "out_order_no": "PPE8041T508",
 * "weight": "1.00",
 * "expect_quantity": 1,
 * "actual_quantity": 0,
 * "status": 1,
 * "sticker_no": "",
 * "sticker_amount": null,
 * "delivery_amount": null,
 * "remark": "",
 * "is_auth": 2,
 * "auth_fullname": "",
 * "auth_birth_date": null,
 * "created_at": "2021-01-20 07:59:33",
 * "updated_at": "2021-01-20 07:59:33",
 * "status_name": "未取派",
 * "type_name": "派件"
 * }
 * ],
 * "material_list": [],
 * "status_name": "待取派",
 * "out_status_name": "否",
 * "exception_label_name": "正常",
 * "type_name": "派件",
 * "merchant_id_name": "欧亚商城",
 * "merchant_id_code": "00003",
 * "place_country_name": "荷兰",
 * "second_place_country_name": null,
 * "country_name": null,
 * "settlement_type_name": "寄付",
 * "source_name": "第三方",
 * "merchant": {
 * "id": 3,
 * "company_id": 3,
 * "code": "00003",
 * "type": 2,
 * "name": "欧亚商城",
 * "email": "myeushop@nle-tech.com",
 * "country": "NL",
 * "settlement_type": 1,
 * "merchant_group_id": 54,
 * "contacter": "胡洋铭",
 * "phone": "17570715315",
 * "address": "湖南长沙",
 * "avatar": "",
 * "status": 1,
 * "created_at": "2020-03-13 12:00:10",
 * "updated_at": "2021-01-18 15:12:51",
 * "settlement_type_name": "票结",
 * "status_name": "启用",
 * "type_name": "货主",
 * "country_name": "荷兰",
 * "additional_status": 1,
 * "advance_days": 0,
 * "appointment_days": 10,
 * "delay_time": 0,
 * "pickup_count": 1,
 * "pie_count": 1,
 * "merchant_group": {
 * "id": 54,
 * "company_id": 3,
 * "name": "欧亚商城组",
 * "transport_price_id": 3,
 * "count": 2,
 * "is_default": 2,
 * "additional_status": 1,
 * "advance_days": 0,
 * "appointment_days": 10,
 * "delay_time": 0,
 * "pickup_count": 1,
 * "pie_count": 1,
 * "created_at": "2020-12-28 03:26:54",
 * "updated_at": "2021-01-19 04:50:19",
 * "additional_status_name": "开启"
 * }
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/stock 列表查询
 * @apiName 列表查询
 * @apiGroup 36
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} begin_date 预计出库日期开始
 * @apiParam {string} end_date 预计出库日期结束
 * @apiParam {string} line_id 线路ID
 * @apiParam {string} keyword 关键字
 * @apiParam {string} expiration_status 超期状态1-未超期2-已超期3-超期已处理
 * @apiParam {string} warehouse_id 网点ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.line_id
 * @apiSuccess {string} data.data.line_name 线路名称
 * @apiSuccess {string} data.data.order_no 包裹单号
 * @apiSuccess {string} data.data.tracking_order_no 运单号
 * @apiSuccess {string} data.data.express_first_no
 * @apiSuccess {string} data.data.execution_date 预计出库日期
 * @apiSuccess {string} data.data.operator 操作人
 * @apiSuccess {string} data.data.in_warehouse_time 入库时间
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.data.expiration_date 有效日期
 * @apiSuccess {string} data.data.expiration_status 超期状态
 * @apiSuccess {string} data.data.warehouse_id 网点ID
 * @apiSuccess {string} data.data.warehouse_name 网点名称
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "company_id": 3,
 * "line_id": 1001,
 * "line_name": "Same day delivery (MES)",
 * "order_no": "SMAAADVD0001",
 * "tracking_order_no": "YD00030000299",
 * "express_first_no": "NLEONA1000034",
 * "execution_date": "2020-11-30",
 * "operator": "大大小小",
 * "expiration_date":"2020-03-10",
 * "expiration_status":3,
 * "in_warehouse_time": "2020-11-29 18:32:27",
 * "created_at": "2020-11-29 18:32:27",
 * "updated_at": "2020-11-29 18:32:27"
 * },
 * {
 * "id": 2,
 * "company_id": 3,
 * "line_id": 1052,
 * "line_name": "Enchede（6）",
 * "order_no": "SMAAADVH0001",
 * "tracking_order_no": "YD00030000300",
 * "express_first_no": "NLEONA1000082",
 * "execution_date": "2020-12-02",
 * "operator": "大大小小",
 * "expiration_date":"2020-03-10",
 * "expiration_status":3,
 * "in_warehouse_time": "2020-11-29 18:32:47",
 * "created_at": "2020-11-29 18:32:47",
 * "updated_at": "2020-11-29 18:32:47"
 * },
 * {
 * "id": 3,
 * "company_id": 3,
 * "line_id": 1048,
 * "line_name": "Rotterdam (2)",
 * "order_no": "SMAAADVI0001",
 * "tracking_order_no": "YD00030000301",
 * "express_first_no": "NLEONA1000096",
 * "execution_date": "2020-12-02",
 * "operator": "大大小小",
 * "expiration_date":"2020-03-10",
 * "expiration_status":3,
 * "in_warehouse_time": "2020-11-29 18:32:59",
 * "created_at": "2020-11-29 18:32:59",
 * "updated_at": "2020-11-29 18:32:59"
 * },
 * {
 * "id": 4,
 * "company_id": 3,
 * "line_id": 1001,
 * "line_name": "Same day delivery (MES)",
 * "order_no": "SMAAADVJ0001",
 * "tracking_order_no": "YD00030000302",
 * "express_first_no": "NLEONA1000048",
 * "execution_date": "2020-11-30",
 * "operator": "大大小小",
 * "expiration_date":"2020-03-10",
 * "expiration_status":3,
 * "in_warehouse_time": "2020-11-29 18:33:07",
 * "created_at": "2020-11-29 18:33:07",
 * "updated_at": "2020-11-29 18:33:07"
 * },
 * {
 * "id": 5,
 * "company_id": 3,
 * "line_id": 1001,
 * "line_name": "Same day delivery (MES)",
 * "order_no": "SMAAADVK0001",
 * "tracking_order_no": "YD00030000303",
 * "express_first_no": "NLEONA1000051",
 * "execution_date": "2020-11-30",
 * "operator": "大大小小",
 * "expiration_date":"2020-03-10",
 * "expiration_status":3,
 * "in_warehouse_time": "2020-11-29 18:33:15",
 * "created_at": "2020-11-29 18:33:15",
 * "updated_at": "2020-11-29 18:33:15"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/stock?page=1",
 * "last": "http://tms-api.test/api/admin/stock?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/stock",
 * "per_page": 200,
 * "to": 5,
 * "total": 5
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/stock/{express_first_no}/log 日志列表
 * @apiName 日志列表
 * @apiGroup 36
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} express_first_no 包裹号
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.type_name 类型
 * @apiSuccess {string} data.line_id
 * @apiSuccess {string} data.line_name
 * @apiSuccess {string} data.order_no
 * @apiSuccess {string} data.tracking_order_no
 * @apiSuccess {string} data.express_first_no
 * @apiSuccess {string} data.execution_date
 * @apiSuccess {string} data.operator
 * @apiSuccess {string} data.in_warehouse_time
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 1,
 * "company_id": 3,
 * "line_id": 1001,
 * "line_name": "Same day delivery (MES)",
 * "order_no": "SMAAADVD0001",
 * "tracking_order_no": "YD00030000299",
 * "express_first_no": "NLEONA1000034",
 * "execution_date": "2020-11-30",
 * "operator": "大大小小",
 * "in_warehouse_time": "2020-11-29 18:32:27",
 * "created_at": "2020-11-29 18:32:27",
 * "updated_at": "2020-11-29 18:32:27"
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/stock-in-log 入库管理列表查询
 * @apiName 入库管理列表查询
 * @apiGroup 36
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} begin_date 预计出库日期开始
 * @apiParam {string} end_date 预计出库日期结束
 * @apiParam {string} line_id 线路ID
 * @apiParam {string} keyword 关键字
 * @apiParam {string} warehouse_id 网点ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.line_id
 * @apiSuccess {string} data.data.line_name 线路名称
 * @apiSuccess {string} data.data.order_no 包裹单号
 * @apiSuccess {string} data.data.tracking_order_no 运单号
 * @apiSuccess {string} data.data.express_first_no
 * @apiSuccess {string} data.data.execution_date 预计出库日期
 * @apiSuccess {string} data.data.operator 操作人
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.data.warehouse_id 网点ID
 * @apiSuccess {string} data.data. warehouse_name 网点名称
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "company_id": 3,
 * "line_id": 1001,
 * "line_name": "Same day delivery (MES)",
 * "order_no": "SMAAADVD0001",
 * "tracking_order_no": "YD00030000299",
 * "express_first_no": "NLEONA1000034",
 * "execution_date": "2020-11-30",
 * "operator": "大大小小",
 * "in_warehouse_time": "2020-11-29 18:32:27",
 * "created_at": "2020-11-29 18:32:27",
 * "updated_at": "2020-11-29 18:32:27"
 * },
 * {
 * "id": 2,
 * "company_id": 3,
 * "line_id": 1052,
 * "line_name": "Enchede（6）",
 * "order_no": "SMAAADVH0001",
 * "tracking_order_no": "YD00030000300",
 * "express_first_no": "NLEONA1000082",
 * "execution_date": "2020-12-02",
 * "operator": "大大小小",
 * "in_warehouse_time": "2020-11-29 18:32:47",
 * "created_at": "2020-11-29 18:32:47",
 * "updated_at": "2020-11-29 18:32:47"
 * },
 * {
 * "id": 3,
 * "company_id": 3,
 * "line_id": 1048,
 * "line_name": "Rotterdam (2)",
 * "order_no": "SMAAADVI0001",
 * "tracking_order_no": "YD00030000301",
 * "express_first_no": "NLEONA1000096",
 * "execution_date": "2020-12-02",
 * "operator": "大大小小",
 * "in_warehouse_time": "2020-11-29 18:32:59",
 * "created_at": "2020-11-29 18:32:59",
 * "updated_at": "2020-11-29 18:32:59"
 * },
 * {
 * "id": 4,
 * "company_id": 3,
 * "line_id": 1001,
 * "line_name": "Same day delivery (MES)",
 * "order_no": "SMAAADVJ0001",
 * "tracking_order_no": "YD00030000302",
 * "express_first_no": "NLEONA1000048",
 * "execution_date": "2020-11-30",
 * "operator": "大大小小",
 * "in_warehouse_time": "2020-11-29 18:33:07",
 * "created_at": "2020-11-29 18:33:07",
 * "updated_at": "2020-11-29 18:33:07"
 * },
 * {
 * "id": 5,
 * "company_id": 3,
 * "line_id": 1001,
 * "line_name": "Same day delivery (MES)",
 * "order_no": "SMAAADVK0001",
 * "tracking_order_no": "YD00030000303",
 * "express_first_no": "NLEONA1000051",
 * "execution_date": "2020-11-30",
 * "operator": "大大小小",
 * "in_warehouse_time": "2020-11-29 18:33:15",
 * "created_at": "2020-11-29 18:33:15",
 * "updated_at": "2020-11-29 18:33:15"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/stock?page=1",
 * "last": "http://tms-api.test/api/admin/stock?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/stock",
 * "per_page": 200,
 * "to": 5,
 * "total": 5
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/stock-out-log 出库管理列表查询
 * @apiName 出库管理列表查询
 * @apiGroup 36
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} begin_date 预计出库日期开始
 * @apiParam {string} end_date 预计出库日期结束
 * @apiParam {string} line_id 线路ID
 * @apiParam {string} keyword 关键字
 * @apiParam {string} warehouse_id 网点ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.line_id
 * @apiSuccess {string} data.data.line_name 线路名称
 * @apiSuccess {string} data.data.order_no 包裹单号
 * @apiSuccess {string} data.data.tracking_order_no 运单号
 * @apiSuccess {string} data.data.express_first_no
 * @apiSuccess {string} data.data.execution_date 预计出库日期
 * @apiSuccess {string} data.data.operator 操作人
 * @apiSuccess {string} data.data.created_at 出库时间
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.data.warehouse_id 网点ID
 * @apiSuccess {string} data.data.warehouse_name 网点名称
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "company_id": 3,
 * "line_id": 1001,
 * "line_name": "Same day delivery (MES)",
 * "order_no": "SMAAADVD0001",
 * "tracking_order_no": "YD00030000299",
 * "express_first_no": "NLEONA1000034",
 * "execution_date": "2020-11-30",
 * "operator": "大大小小",
 * "in_warehouse_time": "2020-11-29 18:32:27",
 * "created_at": "2020-11-29 18:32:27",
 * "updated_at": "2020-11-29 18:32:27"
 * },
 * {
 * "id": 2,
 * "company_id": 3,
 * "line_id": 1052,
 * "line_name": "Enchede（6）",
 * "order_no": "SMAAADVH0001",
 * "tracking_order_no": "YD00030000300",
 * "express_first_no": "NLEONA1000082",
 * "execution_date": "2020-12-02",
 * "operator": "大大小小",
 * "in_warehouse_time": "2020-11-29 18:32:47",
 * "created_at": "2020-11-29 18:32:47",
 * "updated_at": "2020-11-29 18:32:47"
 * },
 * {
 * "id": 3,
 * "company_id": 3,
 * "line_id": 1048,
 * "line_name": "Rotterdam (2)",
 * "order_no": "SMAAADVI0001",
 * "tracking_order_no": "YD00030000301",
 * "express_first_no": "NLEONA1000096",
 * "execution_date": "2020-12-02",
 * "operator": "大大小小",
 * "in_warehouse_time": "2020-11-29 18:32:59",
 * "created_at": "2020-11-29 18:32:59",
 * "updated_at": "2020-11-29 18:32:59"
 * },
 * {
 * "id": 4,
 * "company_id": 3,
 * "line_id": 1001,
 * "line_name": "Same day delivery (MES)",
 * "order_no": "SMAAADVJ0001",
 * "tracking_order_no": "YD00030000302",
 * "express_first_no": "NLEONA1000048",
 * "execution_date": "2020-11-30",
 * "operator": "大大小小",
 * "in_warehouse_time": "2020-11-29 18:33:07",
 * "created_at": "2020-11-29 18:33:07",
 * "updated_at": "2020-11-29 18:33:07"
 * },
 * {
 * "id": 5,
 * "company_id": 3,
 * "line_id": 1001,
 * "line_name": "Same day delivery (MES)",
 * "order_no": "SMAAADVK0001",
 * "tracking_order_no": "YD00030000303",
 * "express_first_no": "NLEONA1000051",
 * "execution_date": "2020-11-30",
 * "operator": "大大小小",
 * "in_warehouse_time": "2020-11-29 18:33:15",
 * "created_at": "2020-11-29 18:33:15",
 * "updated_at": "2020-11-29 18:33:15"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/stock?page=1",
 * "last": "http://tms-api.test/api/admin/stock?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/stock",
 * "per_page": 200,
 * "to": 5,
 * "total": 5
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/package-no-rule 包裹编号规则查询
 * @apiName 包裹编号规则查询
 * @apiGroup 37
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} status 状态（1-开启，2-禁用）
 * @apiParam {string} name 名称
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 规则ID
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.name 规则名称
 * @apiSuccess {string} data.data.prefix 前缀
 * @apiSuccess {string} data.data.length 长度限制
 * @apiSuccess {string} data.data.status 状态1-开启2-禁用
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 2,
 * "company_id": 3,
 * "name": "配置规则1",
 * "prefix": "TMS",
 * "length": 10,
 * "status": 1,
 * "created_at": "2020-12-22 14:45:25",
 * "updated_at": "2020-12-22 14:45:25"
 * },
 * {
 * "id": 3,
 * "company_id": 3,
 * "name": "配置规则2",
 * "prefix": "TMS",
 * "length": 10,
 * "status": 1,
 * "created_at": "2020-12-22 14:47:30",
 * "updated_at": "2020-12-22 14:47:30"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test:10002/api/admin/package-no-rule?page=1",
 * "last": "http://tms-api.test:10002/api/admin/package-no-rule?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test:10002/api/admin/package-no-rule",
 * "per_page": 200,
 * "to": 2,
 * "total": 2
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/package-no-rule/{id} 包裹编号规则详情
 * @apiName 包裹编号规则详情
 * @apiGroup 37
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id 规则ID
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.name 规则名称
 * @apiSuccess {string} data.prefix 前缀
 * @apiSuccess {string} data.length 长度限制
 * @apiSuccess {string} data.status 状态1-开启2-禁用
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 2,
 * "company_id": 3,
 * "name": "配置规则1",
 * "prefix": "TMS",
 * "length": 10,
 * "status": 1,
 * "created_at": "2020-12-22 14:45:25",
 * "updated_at": "2020-12-22 14:45:25"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/package-no-rule/{id} 包裹编号规则修改
 * @apiName 包裹编号规则修改
 * @apiGroup 37
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 规则名称
 * @apiParam {string} prefix 前缀
 * @apiParam {string} length 长度限制
 * @apiParam {string} status 状态1-开启2-禁用
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

/**
 * @api {post} /admin/package-no-rule 包裹编号规则新增
 * @apiName 包裹编号规则新增
 * @apiGroup 37
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 规则名称
 * @apiParam {string} prefix 前缀
 * @apiParam {string} length 长度限制
 * @apiParam {string} status 状态1-开启2-禁用
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

/**
 * @api {delete} /admin/package-no-rule/{id} 包裹编号规则删除
 * @apiName 包裹编号规则删除
 * @apiGroup 37
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 规则ID
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

/**
 * @api {get} /admin/merchant-api/{id} 详情
 * @apiName 详情
 * @apiGroup 38
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id API.ID
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.merchant_id 货主ID
 * @apiSuccess {string} data.key KEY
 * @apiSuccess {string} data.secret SECRET
 * @apiSuccess {string} data.url 推送URL
 * @apiSuccess {string} data.white_ip_list 白名单IP列表
 * @apiSuccess {string} data.status 推送1-是2-否
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.recharge_status 充值1-开启2-禁用
 * @apiSuccess {string} data.merchant_id_name 用户名称
 * @apiSuccess {string} data.merchant_id_code 用户编码
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 3,
 * "company_id": 1,
 * "merchant_id": 5,
 * "key": "DEGw3nRM3xG0D3BNYJbm",
 * "secret": "QaWBb46GA3yJ6KQk0A1Ql2gqdpkZ81R0",
 * "url": "",
 * "white_ip_list": "",
 * "status": 1,
 * "created_at": "2020-02-20 16:03:21",
 * "updated_at": "2020-02-20 16:03:21"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/merchant-api/{id} 修改
 * @apiName 修改
 * @apiGroup 38
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} url 推送url
 * @apiParam {string} white_ip_list 白名单IP列表
 * @apiParam {string} status 推送1-是2-否
 * @apiParam {string} recharge_status 充值1-启用2禁用
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

/**
 * @api {post} /admin/merchant-api 新增
 * @apiName 新增
 * @apiGroup 38
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} merchant_id 货主ID
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

/**
 * @api {delete} /admin/merchant-api/{id} 删除
 * @apiName 删除
 * @apiGroup 38
 * @apiVersion 1.0.0
 * @apiUse auth
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

/**
 * @api {post} /admin/merchant-api 列表查询
 * @apiName 列表查询
 * @apiGroup 38
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} keyword 关键字查询
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.key
 * @apiSuccess {string} data.data.secret
 * @apiSuccess {string} data.data.status
 * @apiSuccess {string} data.data.status_name
 * @apiSuccess {string} data.data.merchant_id_name
 * @apiSuccess {string} data.data.merchant_id_code
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 65,
 * "key": "5WKyJBO7jAKrQYBaV0Nz",
 * "secret": "oepK9gmbBMxLZMj41e6DlzdnO0WD16Rr",
 * "status": 1,
 * "status_name": "是",
 * "merchant_id_name": "ERP国际",
 * "merchant_id_code": "",
 * "created_at": "2020-07-14 16:45:37",
 * "updated_at": "2020-09-04 10:43:01"
 * },
 * {
 * "id": 102,
 * "key": "OY17evPzPwmZRwrndobq",
 * "secret": "MmkEv1pRgVyN9QKBr6YBZN5X7j4WA96o",
 * "status": 1,
 * "status_name": "是",
 * "merchant_id_name": "测试",
 * "merchant_id_code": "",
 * "created_at": "2020-08-19 14:30:51",
 * "updated_at": "2020-08-28 10:52:42"
 * },
 * {
 * "id": 103,
 * "key": "mexKbnqAwXN0yy8nz04Q",
 * "secret": "EqjyaVBYAv4Lk2RQVgzzrl5b92m0wg7X",
 * "status": 1,
 * "status_name": "是",
 * "merchant_id_name": "test",
 * "merchant_id_code": "",
 * "created_at": "2020-08-19 16:14:40",
 * "updated_at": "2020-08-19 16:14:40"
 * },
 * {
 * "id": 104,
 * "key": "zdQE1BoQz6rpYdpkyKo5",
 * "secret": "9k8Pyo7wGnzJPOWrA3OnDN56Vq4Z1AY0",
 * "status": 1,
 * "status_name": "是",
 * "merchant_id_name": "test-test",
 * "merchant_id_code": "",
 * "created_at": "2020-08-24 19:19:22",
 * "updated_at": "2020-08-24 19:19:22"
 * },
 * {
 * "id": 113,
 * "key": "7ZRYek4NY2ro8pDBrpXy",
 * "secret": "boXK50Q47rYLezK30eAk6NgVzn9vkeWT",
 * "status": 1,
 * "status_name": "是",
 * "merchant_id_name": "123",
 * "merchant_id_code": "",
 * "created_at": "2020-09-23 10:38:43",
 * "updated_at": "2020-09-23 10:38:43"
 * },
 * {
 * "id": 114,
 * "key": "DEGw3nR5oOR23adBNYJb",
 * "secret": "QaWBb46GA3yJ6BjeyOQmpJ2gqdpkZ81R",
 * "status": 1,
 * "status_name": "是",
 * "merchant_id_name": "10",
 * "merchant_id_code": "",
 * "created_at": "2020-09-23 10:39:28",
 * "updated_at": "2020-11-24 17:04:49"
 * },
 * {
 * "id": 116,
 * "key": "d5ygrv2WeE5DjxGBMGKE",
 * "secret": "boXK50Q47rYLezK30eAk6NgVzn9vkeWM",
 * "status": 1,
 * "status_name": "是",
 * "merchant_id_name": "erp",
 * "merchant_id_code": "",
 * "created_at": "2020-11-16 14:15:30",
 * "updated_at": "2020-12-03 09:55:30"
 * },
 * {
 * "id": 117,
 * "key": "LwpREv9z3xErrL4BrxYb",
 * "secret": "DqA92gRrad5NBxWPgnnMrl7xw0mXGZyb",
 * "status": 1,
 * "status_name": "是",
 * "merchant_id_name": "ada",
 * "merchant_id_code": "",
 * "created_at": "2020-11-24 17:19:44",
 * "updated_at": "2020-11-24 17:19:44"
 * },
 * {
 * "id": 119,
 * "key": "o9N5JkVzMqVaZoykXD24",
 * "secret": "X3ekBZY7EArNXD62YGmg0N4Gbv9M0ym6",
 * "status": 1,
 * "status_name": "是",
 * "merchant_id_name": "同城派送",
 * "merchant_id_code": "",
 * "created_at": "2020-12-04 04:58:06",
 * "updated_at": "2020-12-04 04:58:25"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/merchant-api?page=1",
 * "last": "http://tms-api.test/api/admin/merchant-api?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/merchant-api",
 * "per_page": 200,
 * "to": 9,
 * "total": 9
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/merchant-api/id/status 修改状态
 * @apiName 修改状态
 * @apiGroup 38
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiParam {string} status 状态1-启用2-禁用
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

/**
 * @api {get} /admin/stock-exception 列表查询
 * @apiName 列表查询
 * @apiGroup 39
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} status 状态1-未处理2-已处理
 * @apiParam {string} keyword 入库异常编号，快递单号1，运单编号
 * @apiParam {string} begin_date 开始日期
 * @apiParam {string} end_date 结束日期
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.stock_exception_no 入库异常编号
 * @apiSuccess {string} data.data.tracking_order_no 运单编号
 * @apiSuccess {string} data.data.express_first_no 快递单号1
 * @apiSuccess {string} data.data.driver_id 司机ID
 * @apiSuccess {string} data.data.driver_name 司机姓名
 * @apiSuccess {string} data.data.remark 异常内容
 * @apiSuccess {string} data.data.status 异常状态1-未处理2-已处理
 * @apiSuccess {string} data.data.status_name 异常状态名称
 * @apiSuccess {string} data.data.deal_remark 处理内容
 * @apiSuccess {string} data.data.deal_time 处理时间
 * @apiSuccess {string} data.data.operator 操作人
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "company_id": 3,
 * "stock_exception_no": "SE00030000001",
 * "tracking_order_no": "YD00030002578",
 * "express_first_no": "202012271",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "remark": "",
 * "status": 2,
 * "status_name": "已处理",
 * "deal_remark": "自动处理",
 * "deal_time": "2020-12-28 10:23:34",
 * "operator": "系统",
 * "created_at": "2020-12-28 10:23:34",
 * "updated_at": "2020-12-28 10:23:34"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test:10002/api/driver/stock-exception?page=1",
 * "last": "http://tms-api.test:10002/api/driver/stock-exception?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test:10002/api/driver/stock-exception",
 * "per_page": 200,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/stock-exception/{id} 获取详情
 * @apiName 获取详情
 * @apiGroup 39
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id ID
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.batch_exception_no 异常编号
 * @apiSuccess {string} data.batch_no 站点编号
 * @apiSuccess {string} data.receiver 收获方
 * @apiSuccess {string} data.status 状态1-未处理2-已处理
 * @apiSuccess {string} data.source 来源
 * @apiSuccess {string} data.stage 异常阶段1-在途异常2-装货异常
 * @apiSuccess {string} data.type 异常类型（在途异常：1道路2车辆3其他，装货异常1少货2货损3其他）
 * @apiSuccess {string} data.remark 异常内容
 * @apiSuccess {string} data.picture 异常图片
 * @apiSuccess {string} data.deal_remark 处理内容
 * @apiSuccess {string} data.deal_id 处理人ID
 * @apiSuccess {string} data.deal_name 处理人姓名
 * @apiSuccess {string} data.deal_time 处理事件
 * @apiSuccess {string} data.driver_id 司机ID
 * @apiSuccess {string} data.driver_name 司机姓名(创建人姓名)
 * @apiSuccess {string} data.created_at 创建事件
 * @apiSuccess {string} data.updated_at 修改事件
 * @apiSuccess {string} data.status_name 状态名称
 * @apiSuccess {string} data.stage_name 异常阶段名称
 * @apiSuccess {string} data.type_name 异常类型名称
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1,
 * "company_id": 1,
 * "batch_exception_no": "BE00010000000000002",
 * "batch_no": "BATCH00010000000000074",
 * "receiver": "龙放耀",
 * "status": 1,
 * "source": "司机来源",
 * "stage": 1,
 * "type": 1,
 * "remark": "1212121",
 * "picture": "http://www.test.com/1.png",
 * "deal_remark": "",
 * "deal_id": null,
 * "deal_name": "",
 * "deal_time": null,
 * "driver_id": 1,
 * "driver_name": "ZhangqiHuo",
 * "created_at": "2020-01-03 10:58:52",
 * "updated_at": "2020-01-03 10:58:52",
 * "status_name": "未取派",
 * "stage_name": "在途异常",
 * "type_name": "道路"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/stock-exception/{id}/deal 异常处理
 * @apiName 异常处理
 * @apiGroup 39
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 入库异常ID
 * @apiParam {string} status 审核状态2成功3失败
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.stock_exception_no 入库异常编号
 * @apiSuccess {string} data.tracking_order_no 运单编号
 * @apiSuccess {string} data.express_first_no 快递单号1
 * @apiSuccess {string} data.driver_id 司机ID
 * @apiSuccess {string} data.driver_name 司机名称
 * @apiSuccess {string} data.remark 异常内容
 * @apiSuccess {string} data.status 异常状态1-未处理2-已处理
 * @apiSuccess {string} data.deal_remark 处理内容
 * @apiSuccess {string} data.deal_time 处理时间
 * @apiSuccess {string} data.operator 操作人
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.status_name 异常状态名称
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 12,
 * "company_id": 3,
 * "stock_exception_no": "SE00030000002",
 * "tracking_order_no": "YD00030002580",
 * "express_first_no": "2020122801",
 * "driver_id": 23,
 * "driver_name": "胡洋铭",
 * "remark": "",
 * "status": 2,
 * "deal_remark": "自动处理",
 * "deal_time": "2020-12-28 11:03:11",
 * "operator": "系统",
 * "created_at": "2020-12-28 11:03:11",
 * "updated_at": "2020-12-28 11:03:11",
 * "status_name": "已处理"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/role 列表查询
 * @apiName 列表查询
 * @apiGroup 40
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.name 名称
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "company_id": 3,
 * "name": "管理员组",
 * "created_at": "2021-01-14T05:37:31.000000Z",
 * "updated_at": "2021-01-14T05:37:31.000000Z"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/role?page=1",
 * "last": "http://tms-api.test/api/admin/role?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test/api/admin/role",
 * "per_page": 200,
 * "to": 1,
 * "total": 1
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/role 新增
 * @apiName 新增
 * @apiGroup 40
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} name 名称
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

/**
 * @api {put} /admin/role/{id} 修改
 * @apiName 修改
 * @apiGroup 40
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiParam {string} name 名称
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

/**
 * @api {get} /admin/role/{id} 获取详情
 * @apiName 获取详情
 * @apiGroup 40
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.name 名称
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 1,
 * "company_id": 3,
 * "name": "管理员组",
 * "created_at": "2021-01-14 13:37:31",
 * "updated_at": "2021-01-14 13:37:31"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {delete} /admin/role/{id} 删除
 * @apiName 删除
 * @apiGroup 40
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
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

/**
 * @api {get} /admin/role/{id}/permission-tree 获取权限树
 * @apiName 获取权限树
 * @apiGroup 40
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id ID
 * @apiSuccess {string} data.parent_id 父级ID
 * @apiSuccess {string} data.name 名称
 * @apiSuccess {string} data.route_as 路由别名
 * @apiSuccess {string} data.type 类型1-菜单2-按钮
 * @apiSuccess {string} data.is_auth 是否有权限1-是2-否
 * @apiSuccess {string} data.children
 * @apiSuccess {string} data.children.id
 * @apiSuccess {string} data.children.parent_id
 * @apiSuccess {string} data.children.name
 * @apiSuccess {string} data.children.route_as
 * @apiSuccess {string} data.children.type
 * @apiSuccess {string} data.children.is_auth
 * @apiSuccess {string} data.children.children
 * @apiSuccess {string} data.children.children.id
 * @apiSuccess {string} data.children.children.parent_id
 * @apiSuccess {string} data.children.children.name
 * @apiSuccess {string} data.children.children.route_as
 * @apiSuccess {string} data.children.children.type
 * @apiSuccess {string} data.children.children.is_auth
 * @apiSuccess {string} data.children.children.children
 * @apiSuccess {string} data.children.children.children.id
 * @apiSuccess {string} data.children.children.children.parent_id
 * @apiSuccess {string} data.children.children.children.name
 * @apiSuccess {string} data.children.children.children.route_as
 * @apiSuccess {string} data.children.children.children.type
 * @apiSuccess {string} data.children.children.children.is_auth
 * @apiSuccess {string} msg
 */

/**
 * @api {put} /admin/role/{id}/assign-permission 分配权限
 * @apiName 分配权限
 * @apiGroup 40
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiParam {string} permission_id_list 权限ID(以逗号分隔)，即所有选中的菜单ID和功能ID
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

/**
 * @api {put} /admin/role/{id}/assign-employee-list 权限组新增员工
 * @apiName 权限组新增员工
 * @apiGroup 40
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiParam {string} employee_id_list 员工ID(以逗号分隔)
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

/**
 * @api {delete} /admin/role/{id}/remove-employee-list 权限组移除用户
 * @apiName 权限组移除用户
 * @apiGroup 40
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiParam {string} employee_id_list 员工ID(以逗号分隔)
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

/**
 * @api {get} /admin/role/{id}/employee-list 获取权限组的员工列表
 * @apiName 获取权限组的员工列表
 * @apiGroup 40
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id ID
 * @apiSuccess {string} data.data.email 邮箱
 * @apiSuccess {string} data.data.fullname 姓名
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 28,
 * "email": "chrystal@holland-at-home.nl",
 * "fullname": "123"
 * },
 * {
 * "id": 30,
 * "email": "799880548@qq.com",
 * "fullname": "12"
 * },
 * {
 * "id": 31,
 * "email": "cctv@163.com",
 * "fullname": "阿西吧"
 * },
 * {
 * "id": 32,
 * "email": "ccav@163.com",
 * "fullname": "sas"
 * },
 * {
 * "id": 36,
 * "email": "827193963@qq.com",
 * "fullname": "张飞"
 * },
 * {
 * "id": 37,
 * "email": "827193562@qq.com",
 * "fullname": "奶茶"
 * },
 * {
 * "id": 39,
 * "email": "827193289@qq.com4",
 * "fullname": "1"
 * },
 * {
 * "id": 41,
 * "email": "17774657855@qq.com",
 * "fullname": "nnt"
 * },
 * {
 * "id": 44,
 * "email": "827193289@qq.com3",
 * "fullname": "2"
 * },
 * {
 * "id": 46,
 * "email": "827193546@qq.com",
 * "fullname": "13"
 * },
 * {
 * "id": 47,
 * "email": "8271998645@qq.com",
 * "fullname": "453"
 * },
 * {
 * "id": 48,
 * "email": "277848787@qq.com",
 * "fullname": "5435"
 * },
 * {
 * "id": 49,
 * "email": "785454345@qq.com",
 * "fullname": "8754"
 * },
 * {
 * "id": 50,
 * "email": "78974656546@qq.com",
 * "fullname": "7869"
 * },
 * {
 * "id": 51,
 * "email": "45635321@qq.com",
 * "fullname": "456"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/role/employee-list?page=1",
 * "last": "http://tms-api.test/api/admin/role/employee-list?page=2",
 * "prev": null,
 * "next": "http://tms-api.test/api/admin/role/employee-list?page=2"
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 2,
 * "path": "http://tms-api.test/api/admin/role/employee-list",
 * "per_page": 15,
 * "to": 15,
 * "total": 18
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/role/employee-list 获取员工列表
 * @apiName 获取员工列表
 * @apiGroup 40
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.email
 * @apiSuccess {string} data.data.fullname
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 28,
 * "email": "chrystal@holland-at-home.nl",
 * "fullname": "123"
 * },
 * {
 * "id": 30,
 * "email": "799880548@qq.com",
 * "fullname": "12"
 * },
 * {
 * "id": 31,
 * "email": "cctv@163.com",
 * "fullname": "阿西吧"
 * },
 * {
 * "id": 32,
 * "email": "ccav@163.com",
 * "fullname": "sas"
 * },
 * {
 * "id": 36,
 * "email": "827193963@qq.com",
 * "fullname": "张飞"
 * },
 * {
 * "id": 37,
 * "email": "827193562@qq.com",
 * "fullname": "奶茶"
 * },
 * {
 * "id": 39,
 * "email": "827193289@qq.com4",
 * "fullname": "1"
 * },
 * {
 * "id": 41,
 * "email": "17774657855@qq.com",
 * "fullname": "nnt"
 * },
 * {
 * "id": 44,
 * "email": "827193289@qq.com3",
 * "fullname": "2"
 * },
 * {
 * "id": 46,
 * "email": "827193546@qq.com",
 * "fullname": "13"
 * },
 * {
 * "id": 47,
 * "email": "8271998645@qq.com",
 * "fullname": "453"
 * },
 * {
 * "id": 48,
 * "email": "277848787@qq.com",
 * "fullname": "5435"
 * },
 * {
 * "id": 49,
 * "email": "785454345@qq.com",
 * "fullname": "8754"
 * },
 * {
 * "id": 50,
 * "email": "78974656546@qq.com",
 * "fullname": "7869"
 * },
 * {
 * "id": 51,
 * "email": "45635321@qq.com",
 * "fullname": "456"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test/api/admin/role/employee-list?page=1",
 * "last": "http://tms-api.test/api/admin/role/employee-list?page=2",
 * "prev": null,
 * "next": "http://tms-api.test/api/admin/role/employee-list?page=2"
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 2,
 * "path": "http://tms-api.test/api/admin/role/employee-list",
 * "per_page": 15,
 * "to": 15,
 * "total": 18
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/role/all 获取所有权限
 * @apiName 获取所有权限
 * @apiGroup 40
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id ID
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.name 权限组名
 * @apiSuccess {string} data.is_admin
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": [
 * {
 * "id": 24,
 * "company_id": 3,
 * "name": "A组",
 * "is_admin": 2,
 * "created_at": "2021-01-29 07:39:06",
 * "updated_at": "2021-01-29 07:39:06"
 * },
 * {
 * "id": 10,
 * "company_id": 3,
 * "name": "客服",
 * "is_admin": 2,
 * "created_at": "2021-01-21 09:45:43",
 * "updated_at": "2021-04-11 10:39:53"
 * },
 * {
 * "id": 8,
 * "company_id": 3,
 * "name": "扫描员",
 * "is_admin": 2,
 * "created_at": "2021-01-21 09:30:47",
 * "updated_at": "2021-04-11 10:39:45"
 * },
 * {
 * "id": 22,
 * "company_id": 3,
 * "name": "测试组",
 * "is_admin": 2,
 * "created_at": "2021-01-25 04:59:02",
 * "updated_at": "2021-01-25 04:59:02"
 * },
 * {
 * "id": 1,
 * "company_id": 3,
 * "name": "管理员组",
 * "is_admin": 1,
 * "created_at": "2021-01-16 17:31:27",
 * "updated_at": "2021-01-20 12:50:02"
 * }
 * ],
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/car-maintain/ 维护列表
 * @apiName 维护列表
 * @apiGroup 41
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} car_no 车牌号
 * @apiParam {string} maintain_type 维保类型:1-保养2-维修
 * @apiParam {string} is_ticket 是否收票:1-是2-否
 * @apiParam {string} maintain_factory 维修厂名称
 * @apiParam {string} begin_date 维保时间开始
 * @apiParam {string} end_date 维保时间结束
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.maintain_no 流水号
 * @apiSuccess {string} data.data.car_id
 * @apiSuccess {string} data.data.car_no 车牌号
 * @apiSuccess {string} data.data.distance 车辆行驶里程
 * @apiSuccess {string} data.data.maintain_type 维保类型:1-保养2-维修
 * @apiSuccess {string} data.data.maintain_date 维保时间
 * @apiSuccess {string} data.data.maintain_factory 维修厂名称
 * @apiSuccess {string} data.data.is_ticket 收票状态
 * @apiSuccess {string} data.data.maintain_description
 * @apiSuccess {string} data.data.maintain_picture
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.data.maintain_price 费用总计
 * @apiSuccess {string} data.data.operator 操作人
 */

/**
 * @api {post} /admin/car-maintain/ 维护新增
 * @apiName 维护新增
 * @apiGroup 41
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} car_id 车辆ID
 * @apiParam {string} car_no 车牌号
 * @apiParam {string} distance 车辆行驶里程
 * @apiParam {string} maintain_type 维保类型:1-保养2-维修
 * @apiParam {string} maintain_date 维保时间
 * @apiParam {string} maintain_factory 维修厂名称
 * @apiParam {string} is_ticket 是否收票:1-是2-否
 * @apiParam {string} maintain_price 费用总计
 * @apiParam {string} maintain_description 问题描述
 * @apiParam {string} maintain_picture 附件图片
 * @apiParam {string} maintain_detail[0][maintain_name] 费用明细 - 维修项目
 * @apiParam {string} maintain_detail[0][fitting_name] 费用明细 - 配件名称
 * @apiParam {string} maintain_detail[0][fitting_brand] 费用明细 - 配件品牌
 * @apiParam {string} maintain_detail[0][fitting_model] 费用明细 - 配件型号
 * @apiParam {string} maintain_detail[0][fitting_quantity] 费用明细 - 数量
 * @apiParam {string} maintain_detail[0][fitting_unit] 费用明细 - 单位
 * @apiParam {string} maintain_detail[0][fitting_price] 费用明细 - 单价
 * @apiParam {string} maintain_detail[0][material_price] 费用明细 - 材料费
 * @apiParam {string} maintain_detail[0][hour_price] 费用明细 - 工时费
 */

/**
 * @api {delete} /admin/car-maintain/list 批量删除
 * @apiName 批量删除
 * @apiGroup 41
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list 多个ID用逗号分割
 */

/**
 * @api {put} /admin/car-maintain/ticket 批量收票
 * @apiName 批量收票
 * @apiGroup 41
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list 多个ID用逗号分隔
 */

/**
 * @api {get} /admin/car-maintain/export 批量导出
 * @apiName 批量导出
 * @apiGroup 41
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list id列表，多个用逗号分隔
 */

/**
 * @api {get} /admin/car-maintain/:id 维护详情
 * @apiName 维护详情
 * @apiGroup 41
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 维护ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.maintain_no
 * @apiSuccess {string} data.car_id
 * @apiSuccess {string} data.car_no
 * @apiSuccess {string} data.distance
 * @apiSuccess {string} data.maintain_type
 * @apiSuccess {string} data.maintain_date
 * @apiSuccess {string} data.maintain_factory
 * @apiSuccess {string} data.is_ticket
 * @apiSuccess {string} data.maintain_description
 * @apiSuccess {string} data.maintain_picture
 * @apiSuccess {string} data.maintain_price
 * @apiSuccess {string} data.operator
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.maintain_detail
 * @apiSuccess {string} data.maintain_detail.id
 * @apiSuccess {string} data.maintain_detail.company_id
 * @apiSuccess {string} data.maintain_detail.maintain_no
 * @apiSuccess {string} data.maintain_detail.maintain_name
 * @apiSuccess {string} data.maintain_detail.fitting_name
 * @apiSuccess {string} data.maintain_detail.fitting_brand
 * @apiSuccess {string} data.maintain_detail.fitting_model
 * @apiSuccess {string} data.maintain_detail.fitting_quantity
 * @apiSuccess {string} data.maintain_detail.fitting_unit
 * @apiSuccess {string} data.maintain_detail.fitting_price
 * @apiSuccess {string} data.maintain_detail.material_price
 * @apiSuccess {string} data.maintain_detail.hour_price
 * @apiSuccess {string} data.maintain_detail.created_at
 * @apiSuccess {string} data.maintain_detail.updated_at
 * @apiSuccess {string} data.maintain_type_name
 * @apiSuccess {string} data.is_ticket_name
 * @apiSuccess {string} msg
 */

/**
 * @api {put} /admin/car-maintain/:id 维护更新
 * @apiName 维护更新
 * @apiGroup 41
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 维护ID
 */

/**
 * @api {get} /admin/car-accident/ 事故列表
 * @apiName 事故列表
 * @apiGroup 42
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} car_no 车牌号
 * @apiParam {string} accident_duty 主被动,责任方：1-主动2-被动
 * @apiParam {string} begin_date 事故时间开始
 * @apiParam {string} end_date 事故时间结束
 * @apiParam {string} deal_type 处理方式：1-保险2-公司赔付
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.car_id
 * @apiSuccess {string} data.data.car_no 车牌号
 * @apiSuccess {string} data.data.driver_id
 * @apiSuccess {string} data.data.driver_fullname 司机姓名
 * @apiSuccess {string} data.data.driver_phone
 * @apiSuccess {string} data.data.deal_type 处理方式：1-保险2-公司赔付
 * @apiSuccess {string} data.data.accident_location 事故地点
 * @apiSuccess {string} data.data.accident_date 事故时间
 * @apiSuccess {string} data.data.accident_duty 主被动,责任方：1-主动2-被动
 * @apiSuccess {string} data.data.accident_description
 * @apiSuccess {string} data.data.accident_picture
 * @apiSuccess {string} data.data.accident_no 事故处理单号
 * @apiSuccess {string} data.data.insurance_indemnity
 * @apiSuccess {string} data.data.insurance_payment 垫付金额
 * @apiSuccess {string} data.data.insurance_description
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.data.operator 操作人
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.data.insurance_price 赔付金额
 * @apiSuccess {string} data.data.insurance_date 赔付时间
 */

/**
 * @api {post} /admin/car-accident/ 事故新增
 * @apiName 事故新增
 * @apiGroup 42
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} car_id 车辆ID
 * @apiParam {string} car_no 车牌号
 * @apiParam {string} driver_id 司机ID
 * @apiParam {string} driver_fullname 司机名称
 * @apiParam {string} driver_phone 司机电话
 * @apiParam {string} deal_type 处理方式：1-保险2-公司赔付
 * @apiParam {string} accident_location 事故地点
 * @apiParam {string} accident_date 事故时间
 * @apiParam {string} accident_duty 主被动,责任方：1-主动2-被动
 * @apiParam {string} accident_description 事故描述
 * @apiParam {string} accident_picture 事故照片
 * @apiParam {string} insurance_indemnity 保险是否赔付：1-是2-否
 * @apiParam {string} insurance_payment 保险垫付款
 * @apiParam {string} insurance_description 赔付描述
 * @apiParam {string} insurance_price 赔付金额
 * @apiParam {string} insurance_date 赔付时间
 */

/**
 * @api {get} /admin/car-accident/:id 事故详情
 * @apiName 事故详情
 * @apiGroup 42
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 1
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.car_id
 * @apiSuccess {string} data.car_no
 * @apiSuccess {string} data.driver_id
 * @apiSuccess {string} data.driver_fullname
 * @apiSuccess {string} data.driver_phone
 * @apiSuccess {string} data.deal_type
 * @apiSuccess {string} data.accident_location
 * @apiSuccess {string} data.accident_date
 * @apiSuccess {string} data.accident_duty
 * @apiSuccess {string} data.accident_description
 * @apiSuccess {string} data.accident_picture
 * @apiSuccess {string} data.accident_no
 * @apiSuccess {string} data.insurance_indemnity
 * @apiSuccess {string} data.insurance_payment
 * @apiSuccess {string} data.insurance_date
 * @apiSuccess {string} data.insurance_price
 * @apiSuccess {string} data.insurance_description
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.operator
 * @apiSuccess {string} msg
 */

/**
 * @api {put} /admin/car-accident/2 事故更新
 * @apiName 事故更新
 * @apiGroup 42
 * @apiVersion 1.0.0
 * @apiUse auth
 */

/**
 * @api {delete} /admin/car-accident/list 批量删除
 * @apiName 批量删除
 * @apiGroup 42
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list
 */

/**
 * @api {get} /admin/order-bill-template/{id} 订单面单模板详情
 * @apiName 订单面单模板详情
 * @apiGroup 43
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.type 类型1-模板一2-模板二
 * @apiSuccess {string} data.destination_mode 目的地模式1-省市区2-省市-3市区4-邮编
 * @apiSuccess {string} data.logo 标志
 * @apiSuccess {string} data.sender 发件人
 * @apiSuccess {string} data.receiver 收件人
 * @apiSuccess {string} data.destination 目的地
 * @apiSuccess {string} data.carrier 承运人
 * @apiSuccess {string} data.carrier_address 承运地址
 * @apiSuccess {string} data.contents 物品信息
 * @apiSuccess {string} data.package 包裹
 * @apiSuccess {string} data.material 材料
 * @apiSuccess {string} data.count 数量
 * @apiSuccess {string} data.replace_amount 代收货款
 * @apiSuccess {string} data.settlement_amount 运费金额
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.destination_mode_name
 * @apiSuccess {string} data.type_name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 3,
 * "company_id": 3,
 * "type": 1,
 * "destination_mode": 1,
 * "logo": "",
 * "sender": "发件人",
 * "receiver": "收件人",
 * "destination": "目的地",
 * "carrier": "承运人",
 * "carrier_address": "承运人地址",
 * "contents": "物品信息",
 * "package": "包裹",
 * "material": "材料",
 * "count": "数量",
 * "replace_amount": "代收货款",
 * "settlement_amount": "运费金额",
 * "created_at": "2021-03-24 13:52:41",
 * "updated_at": "2021-03-24 13:52:41",
 * "destination_mode_name": "省市区",
 * "type_name": "模板一"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/order-bill-template/{id} 订单面单模板修改
 * @apiName 订单面单模板修改
 * @apiGroup 43
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} type 类型1-模板一2-模板二
 * @apiParam {string} destination_mode 目的地模式1-省市区2-省市-3市区4-邮编
 * @apiParam {string} logo 标志
 * @apiParam {string} sender 发件人
 * @apiParam {string} receiver 收件人
 * @apiParam {string} destination 目的地
 * @apiParam {string} carrier 承运人
 * @apiParam {string} carrier_address 承运人地址
 * @apiParam {string} contents 物品信息
 * @apiParam {string} package 包裹
 * @apiParam {string} material 材料
 * @apiParam {string} count 数量
 * @apiParam {string} replace_amount 代收货款
 * @apiParam {string} settlement_amount 运费金额
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.type 类型1-模板一2-模板二
 * @apiSuccess {string} data.destination_mode 目的地模式1-省市区2-省市-3市区4-邮编
 * @apiSuccess {string} data.logo 标志
 * @apiSuccess {string} data.sender 发件人
 * @apiSuccess {string} data.receiver 收件人
 * @apiSuccess {string} data.destination 目的地
 * @apiSuccess {string} data.carrier 承运人
 * @apiSuccess {string} data.carrier_address 承运地址
 * @apiSuccess {string} data.contents 物品信息
 * @apiSuccess {string} data.package 包裹
 * @apiSuccess {string} data.material 材料
 * @apiSuccess {string} data.count 数量
 * @apiSuccess {string} data.replace_amount 代收货款
 * @apiSuccess {string} data.settlement_amount 运费金额
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.destination_mode_name
 * @apiSuccess {string} data.type_name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 3,
 * "company_id": 3,
 * "type": 1,
 * "destination_mode": 1,
 * "logo": "",
 * "sender": "发件人",
 * "receiver": "收件人",
 * "destination": "目的地",
 * "carrier": "承运人",
 * "carrier_address": "承运人地址",
 * "contents": "物品信息",
 * "package": "包裹",
 * "material": "材料",
 * "count": "数量",
 * "replace_amount": "代收货款",
 * "settlement_amount": "运费金额",
 * "created_at": "2021-03-24 13:52:41",
 * "updated_at": "2021-03-24 13:52:41",
 * "destination_mode_name": "省市区",
 * "type_name": "模板一"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/order-bill-template 订单面单模板查询
 * @apiName 订单面单模板查询
 * @apiGroup 43
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.type 模板类型1-模板一2-模板二
 * @apiSuccess {string} data.data.is_default 是否默认1-默认2-不默认
 * @apiSuccess {string} data.data.destination_mode 目的地模式1-省市区2-省市3-市区4-邮编
 * @apiSuccess {string} data.data.logo 标志
 * @apiSuccess {string} data.data.sender
 * @apiSuccess {string} data.data.receiver
 * @apiSuccess {string} data.data.destination
 * @apiSuccess {string} data.data.carrier
 * @apiSuccess {string} data.data.carrier_address
 * @apiSuccess {string} data.data.contents
 * @apiSuccess {string} data.data.package
 * @apiSuccess {string} data.data.material
 * @apiSuccess {string} data.data.count
 * @apiSuccess {string} data.data.replace_amount
 * @apiSuccess {string} data.data.settlement_amount
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.data.destination_mode_name
 * @apiSuccess {string} data.data.type_name
 * @apiSuccess {string} data.data.is_default_name
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 3,
 * "company_id": 3,
 * "type": 1,
 * "is_default": 1,
 * "destination_mode": 1,
 * "logo": "1",
 * "sender": "1",
 * "receiver": "1",
 * "destination": "1",
 * "carrier": "1",
 * "carrier_address": "1",
 * "contents": "1",
 * "package": "1",
 * "material": "1",
 * "count": "1",
 * "replace_amount": "1",
 * "settlement_amount": "1",
 * "created_at": "2021-03-24 13:52:41",
 * "updated_at": "2021-04-12 14:43:39",
 * "destination_mode_name": "省市区",
 * "type_name": "模板一",
 * "is_default_name": "默认"
 * },
 * {
 * "id": 60,
 * "company_id": 3,
 * "type": 1,
 * "is_default": 1,
 * "destination_mode": 1,
 * "logo": "1",
 * "sender": "1",
 * "receiver": "1",
 * "destination": "1",
 * "carrier": "1",
 * "carrier_address": "1",
 * "contents": "1",
 * "package": "1",
 * "material": "1",
 * "count": "1",
 * "replace_amount": "1",
 * "settlement_amount": "1",
 * "created_at": null,
 * "updated_at": "2021-04-12 14:43:39",
 * "destination_mode_name": "省市区",
 * "type_name": "模板一",
 * "is_default_name": "默认"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test:10002/api/admin/order-bill-template/init?page=1",
 * "last": "http://tms-api.test:10002/api/admin/order-bill-template/init?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test:10002/api/admin/order-bill-template/init",
 * "per_page": 200,
 * "to": 2,
 * "total": 2
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/order-bill-template/type 选择默认模板
 * @apiName 选择默认模板
 * @apiGroup 43
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} type 模板1-模板一2-模板二
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

/**
 * @api {put} /admin/order-bill-template/{id}/default 订单面单模板设置默认
 * @apiName 订单面单模板设置默认
 * @apiGroup 43
 * @apiVersion 1.0.0
 * @apiUse auth
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

/**
 * @api {get} /admin/spare-parts 备品列表
 * @apiName 备品列表
 * @apiGroup 44
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} sp_no 备品编号
 * @apiParam {string} sp_name 备品名称
 * @apiParam {string} begin_date 开始时间
 * @apiParam {string} end_date 结束时间
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 序号
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.sp_no 备品编号
 * @apiSuccess {string} data.data.sp_name 备品名称
 * @apiSuccess {string} data.data.sp_brand 备品品牌
 * @apiSuccess {string} data.data.sp_model 备品型号
 * @apiSuccess {string} data.data.sp_unit 备品单位
 * @apiSuccess {string} data.data.operator 操作人
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.data.updated_at 更新时间
 * @apiSuccess {string} data.data.deleted_at 删除时间
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 */

/**
 * @api {get} /admin/spare-parts/init 备品新增初始化
 * @apiName 备品新增初始化
 * @apiGroup 44
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.unit_list 单位列表
 * @apiSuccess {string} data.unit_list.id ID
 * @apiSuccess {string} data.unit_list.name 名称
 * @apiSuccess {string} msg
 */

/**
 * @api {post} /admin/spare-parts 备品新增
 * @apiName 备品新增
 * @apiGroup 44
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} sp_name 备品名称
 * @apiParam {string} sp_brand 备品品牌
 * @apiParam {string} sp_model 备品型号
 * @apiParam {string} sp_unit 备品单位ID
 * @apiParam {string} sp_no 备品编号
 */

/**
 * @api {post} /admin/spare-parts/stock 新增入库
 * @apiName 新增入库
 * @apiGroup 44
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} sp_no 备品编号
 * @apiParam {string} stock_quantity 入库数量
 */

/**
 * @api {get} /admin/spare-parts/stock 备品库存
 * @apiName 备品库存
 * @apiGroup 44
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} sp_no 备品编号
 * @apiParam {string} sp_name 备品名称
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 序号
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.sp_no 备品编号
 * @apiSuccess {string} data.data.stock_quantity 库存数量
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.data.updated_at 更新时间
 * @apiSuccess {string} data.data.sp_name 备品名称
 * @apiSuccess {string} data.data.sp_brand 备品品牌
 * @apiSuccess {string} data.data.sp_model 备品型号
 * @apiSuccess {string} data.data.sp_unit 备品单位
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 */

/**
 * @api {post} /admin/spare-parts/record 备品领用
 * @apiName 备品领用
 * @apiGroup 44
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} receive_price 单价
 * @apiParam {string} receive_quantity 领用数量
 * @apiParam {string} car_id 车辆ID
 * @apiParam {string} car_no 车辆编号
 * @apiParam {string} sp_no 备品编号
 * @apiParam {string} receive_person 领用人
 * @apiParam {string} receive_remark 备注
 * @apiParam {string} receive_date 领用时间
 */

/**
 * @api {get} /admin/spare-parts/record 领用记录
 * @apiName 领用记录
 * @apiGroup 44
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 序号
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.sp_no 备品编号
 * @apiSuccess {string} data.data.car_id 车辆ID
 * @apiSuccess {string} data.data.car_no 车辆编号
 * @apiSuccess {string} data.data.receive_price 领用单价
 * @apiSuccess {string} data.data.receive_quantity 领用数量
 * @apiSuccess {string} data.data.receive_date 领用时间
 * @apiSuccess {string} data.data.receive_person 领用人
 * @apiSuccess {string} data.data.receive_remark 备注
 * @apiSuccess {string} data.data.receive_status 领用状态
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.data.sp_name 备品名称
 * @apiSuccess {string} data.data.sp_brand 备品编号
 * @apiSuccess {string} data.data.sp_model 备品型号
 * @apiSuccess {string} data.data.sp_unit 备品单位
 * @apiSuccess {string} data.data.price_total 总价
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 */

/**
 * @api {put} /admin/spare-parts/record/:id 领取记录作废
 * @apiName 领取记录作废
 * @apiGroup 44
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 备品领取序号
 */

/**
 * @api {put} /admin/spare-parts/:id 备品更新
 * @apiName 备品更新
 * @apiGroup 44
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 备品序号
 */

/**
 * @api {delete} /admin/spare-parts/:id 备品删除
 * @apiName 备品删除
 * @apiGroup 44
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 备品序号
 */

/**
 * @api {post} /admin/order-amount 订单费用新增
 * @apiName 订单费用新增
 * @apiGroup 45
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} order_no 订单号
 * @apiParam {string} expect_amount 金额
 * @apiParam {string} type 类型
 * @apiParam {string} remark 备注
 * @apiParam {string} in_total 计入总金额1-计入2-不计入
 * @apiParam {string} status 状态（默认填2）
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

/**
 * @api {get} /admin/order-amount 订单费用查询
 * @apiName 订单费用查询
 * @apiGroup 45
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} order_no 订单号
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id 公司ID
 * @apiSuccess {string} data.data.order_no 订单号
 * @apiSuccess {string} data.data.expect_amount 预计金额
 * @apiSuccess {string} data.data.actual_amount 实际金额
 * @apiSuccess {string} data.data.type 类型
 * @apiSuccess {string} data.data.remark 备注
 * @apiSuccess {string} data.data.in_total 是否计入总价
 * @apiSuccess {string} data.data.status 状态
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 1,
 * "company_id": 3,
 * "order_no": "",
 * "expect_amount": "0.00",
 * "actual_amount": "0.00",
 * "type": 0,
 * "remark": "",
 * "in_total": 1,
 * "status": 2,
 * "created_at": "2021-03-29 16:40:37",
 * "updated_at": "2021-03-29 16:40:37"
 * },
 * {
 * "id": 2,
 * "company_id": 3,
 * "order_no": "SMAAAJWG0001",
 * "expect_amount": "2.00",
 * "actual_amount": "0.00",
 * "type": 2,
 * "remark": "2",
 * "in_total": 2,
 * "status": 2,
 * "created_at": "2021-03-29 16:48:53",
 * "updated_at": "2021-03-29 16:48:53"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test:10002/api/admin/order-amount?page=1",
 * "last": "http://tms-api.test:10002/api/admin/order-amount?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test:10002/api/admin/order-amount",
 * "per_page": 200,
 * "to": 2,
 * "total": 2
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {put} /admin/order-amount/{id} 订单费用修改
 * @apiName 订单费用修改
 * @apiGroup 45
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} order_no 订单号
 * @apiParam {string} expect_amount 金额
 * @apiParam {string} type 类型
 * @apiParam {string} remark 备注
 * @apiParam {string} in_total 计入总金额1-计入2-不计入
 * @apiParam {string} status 状态（默认填2）
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

/**
 * @api {delete} /admin/order-amount/{id} 订单费用删除
 * @apiName 订单费用删除
 * @apiGroup 45
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 费用id
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

/**
 * @api {get} /admin/order-customer 客服记录列表
 * @apiName 客服记录列表
 * @apiGroup 46
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} order_no 订单号
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 客服记录ID
 * @apiSuccess {string} data.data.order_no 订单号
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.content 文字内容
 * @apiSuccess {string} data.data.file_urls 文件链接数组
 * @apiSuccess {string} data.data.picture_urls 图片链接数组
 * @apiSuccess {string} data.data.operator_id
 * @apiSuccess {string} data.data.created_at 创建时间
 * @apiSuccess {string} data.data.updated_at 更新时间
 * @apiSuccess {string} data.data.fullname 客服姓名
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 */

/**
 * @api {post} /admin/order-customer 客服记录新增
 * @apiName 客服记录新增
 * @apiGroup 46
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} order_no 订单号
 * @apiParam {string} content 文字
 * @apiParam {string} file_urls[] 文件链接数组
 * @apiParam {string} picture_urls[] 图片链接数组
 */

/**
 * @api {delete} /admin/order-customer/:id 客服记录删除
 * @apiName 客服记录删除
 * @apiGroup 46
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 客服记录ID
 */

/**
 * @api {post} /admin/order-receipt 回单新增
 * @apiName 回单新增
 * @apiGroup 47
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} order_no 订单号
 * @apiParam {string} file_name 文件名
 * @apiParam {string} file_size 文件大小
 * @apiParam {string} file_url URL
 * @apiParam {string} file_type 文件类型
 */

/**
 * @api {get} /admin/order-receipt 回单列表
 * @apiName 回单列表
 * @apiGroup 47
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} order_no 订单号
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id 序号
 * @apiSuccess {string} data.data.order_no 订单号
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.file_name 文件名
 * @apiSuccess {string} data.data.file_type 文件类型
 * @apiSuccess {string} data.data.file_size 文件大小
 * @apiSuccess {string} data.data.file_url URL
 * @apiSuccess {string} data.data.operator_id
 * @apiSuccess {string} data.data.operator_type
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.data.operator 上传人
 */

/**
 * @api {put} /admin/order-receipt/:id 回单更新
 * @apiName 回单更新
 * @apiGroup 47
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 序号
 * @apiParam {string} file_name 文件名
 */

/**
 * @api {delete} /admin/order-receipt/:id 回单删除
 * @apiName 回单删除
 * @apiGroup 47
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 序号
 */

/**
 * @api {get} /admin/order-config 获取配置
 * @apiName 获取配置
 * @apiGroup 48
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.type 订单默认类型
 * @apiSuccess {string} data.settlement_type 订单付款方式
 * @apiSuccess {string} data.receipt_type 订单回单类型
 * @apiSuccess {string} data.receipt_count 订单回单数量
 * @apiSuccess {string} data.control_mode 订单控货方式
 * @apiSuccess {string} data.nature 订单包裹内容
 * @apiSuccess {string} data.address_template_id 地址模板ID
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 */

/**
 * @api {put} /admin/order-config 更新配置
 * @apiName 更新配置
 * @apiGroup 48
 * @apiVersion 1.0.0
 * @apiUse auth
 */

/**
 * @api {put} /admin/map-config 订单设置修改
 * @apiName 订单设置修改
 * @apiGroup 49
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} back_type 后端地图引擎1-谷歌2-百度3-腾讯
 * @apiParam {string} google_key 谷歌key
 * @apiParam {string} google_secret 谷歌secret
 * @apiParam {string} baidu_key 百度key
 * @apiParam {string} baidu_secret 百度secret
 * @apiParam {string} tencent_key 腾讯key
 * @apiParam {string} tencent_secret 腾讯secret
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

/**
 * @api {get} /admin/map-config 订单设置详情
 * @apiName 订单设置详情
 * @apiGroup 49
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id
 * @apiSuccess {string} data.front_type 前端地图引擎1-谷歌2-百度3-腾讯
 * @apiSuccess {string} data.back_type 后端地图引擎
 * @apiSuccess {string} data.mobile_type 手持端地图引擎
 * @apiSuccess {string} data.google_key 谷歌key
 * @apiSuccess {string} data.google_secret 谷歌secret
 * @apiSuccess {string} data.baidu_key 百度key
 * @apiSuccess {string} data.baidu_secret 百度secret
 * @apiSuccess {string} data.tencent_key 腾讯key
 * @apiSuccess {string} data.tencent_secret 腾讯secret
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} data.front_type_name
 * @apiSuccess {string} data.back_type_name
 * @apiSuccess {string} data.mobile_type_name
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 3,
 * "company_id": 3,
 * "front_type": 1,
 * "back_type": 1,
 * "mobile_type": 1,
 * "google_key": "asd",
 * "google_secret": "",
 * "baidu_key": "",
 * "baidu_secret": "",
 * "tencent_key": "",
 * "tencent_secret": "",
 * "created_at": "2021-04-12 18:26:44",
 * "updated_at": "2021-04-13 16:27:10",
 * "front_type_name": "谷歌",
 * "back_type_name": "谷歌",
 * "mobile_type_name": "谷歌"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/email-template 模板列表
 * @apiName 模板列表
 * @apiGroup 50
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.data
 * @apiSuccess {string} data.data.id
 * @apiSuccess {string} data.data.company_id
 * @apiSuccess {string} data.data.type 模板类型
 * @apiSuccess {string} data.data.title 模板标题
 * @apiSuccess {string} data.data.content 模板内容
 * @apiSuccess {string} data.data.status 模板状态
 * @apiSuccess {string} data.data.created_at
 * @apiSuccess {string} data.data.updated_at
 * @apiSuccess {string} data.data.type_name
 * @apiSuccess {string} data.data.status_name
 * @apiSuccess {string} data.links
 * @apiSuccess {string} data.links.first
 * @apiSuccess {string} data.links.last
 * @apiSuccess {string} data.links.prev
 * @apiSuccess {string} data.links.next
 * @apiSuccess {string} data.meta
 * @apiSuccess {string} data.meta.current_page
 * @apiSuccess {string} data.meta.from
 * @apiSuccess {string} data.meta.last_page
 * @apiSuccess {string} data.meta.path
 * @apiSuccess {string} data.meta.per_page
 * @apiSuccess {string} data.meta.to
 * @apiSuccess {string} data.meta.total
 * @apiSuccess {string} msg
 */

/**
 * @api {post} /admin/email-template 模板新增
 * @apiName 模板新增
 * @apiGroup 50
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} type 模板类型
 * @apiParam {string} title 模板标题
 * @apiParam {string} content 模板内容
 */

/**
 * @api {get} /admin/email-template/:id 模板详情
 * @apiName 模板详情
 * @apiGroup 50
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id ID
 */

/**
 * @api {put} /admin/email-template/:id 模板更新
 * @apiName 模板更新
 * @apiGroup 50
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} type
 * @apiParam {string} title
 * @apiParam {string} content
 * @apiParam {string} status 模板状态
 */

/**
 * @api {delete} /admin/email-template/:id 模板删除
 * @apiName 模板删除
 * @apiGroup 50
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 1
 */

/**
 * @api {get} /admin/trail/package/{express_first_no} 包裹轨迹
 * @apiName 包裹轨迹
 * @apiGroup 51
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} express_first_no 包裹号
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.express_first_no 包裹单号
 * @apiSuccess {string} data.package_trail_list
 * @apiSuccess {string} data.package_trail_list.id
 * @apiSuccess {string} data.package_trail_list.company_id
 * @apiSuccess {string} data.package_trail_list.order_no
 * @apiSuccess {string} data.package_trail_list.content 内容
 * @apiSuccess {string} data.package_trail_list.created_at 创建时间
 * @apiSuccess {string} data.package_trail_list.updated_at
 * @apiSuccess {string} data.package_trail_list.type
 * @apiSuccess {string} data.package_trail_list.type_name 类型名称
 * @apiSuccess {string} data.status_name
 * @apiSuccess {string} data.type_name
 * @apiSuccess {string} msg
 * @apiSuccess {string} data.place_city 发件城市
 * @apiSuccess {string} data.second_place_city 收件城市
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "express_first_no": "05271918",
 * "place_city": "Amstelveen",
 * "second_place_city": "Amsterdam",
 * "package_trail_list": [
 * {
 * "id": 1,
 * "company_id": 3,
 * "order_no": "SMAAAKVT0001",
 * "content": "下单成功",
 * "created_at": "2021-05-27 13:18:56",
 * "updated_at": "2021-05-27 13:18:56",
 * "type": 1,
 * "type_name": "待取件"
 * }
 * ],
 * "status_name": null,
 * "type_name": null
 * },
 * "msg": "successful"
 * }
 */

