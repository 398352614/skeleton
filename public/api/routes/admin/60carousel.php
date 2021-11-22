<?php
/**
 * @apiDefine 60 轮播图配置
 */

/**
 * @api {get} /admin/carousel 轮播图查询
 * @apiName 轮播图查询
 * @apiGroup 60
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 地址ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.status 状态1-开启2-禁用
 * @apiSuccess {string} data.name 图片名称
 * @apiSuccess {string} data.picture_url 图片地址
 * @apiSuccess {string} data.sort_id 排序ID
 * @apiSuccess {string} data.rolling_time 滚动时间(秒)
 * @apiSuccess {string} data.rolling_time_name 滚动时间名称
 * @apiSuccess {string} data.jump_type 跳转类型1-外部跳转2-内部跳转
 * @apiSuccess {string} data.jump_type_name 跳转类型名称
 * @apiSuccess {string} data.inside_jump_type  内部跳转类型1-新闻通知2-入门教程3-禁运物品4-常见问题
 * @apiSuccess {string} data.outside_jump_url 外部跳转URL
 * @apiSuccess {string} data.inside_jump_type_name 内部跳转名称
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 6,
 * "company_id": 3,
 * "status": 1,
 * "name": "",
 * "picture_url": "1",
 * "sort_id": "",
 * "rolling_time": 1,
 * "rolling_time_name": "1秒",
 * "jump_type": 1,
 * "jump_type_name": "内部跳转",
 * "inside_jump_type": 1,
 * "outside_jump_url": "",
 * "inside_jump_type_name": "新闻通知",
 * "created_at": "2021-11-09 10:07:42",
 * "updated_at": "2021-11-09 10:07:42"
 * },
 * {
 * "id": 7,
 * "company_id": 3,
 * "status": 1,
 * "name": "",
 * "picture_url": "1",
 * "sort_id": "",
 * "rolling_time": 1,
 * "rolling_time_name": "1秒",
 * "jump_type": 1,
 * "jump_type_name": "内部跳转",
 * "inside_jump_type": 1,
 * "outside_jump_url": "",
 * "inside_jump_type_name": "新闻通知",
 * "created_at": "2021-11-09 10:07:44",
 * "updated_at": "2021-11-09 10:07:44"
 * },
 * {
 * "id": 8,
 * "company_id": 3,
 * "status": 1,
 * "name": "",
 * "picture_url": "1",
 * "sort_id": "",
 * "rolling_time": 1,
 * "rolling_time_name": "1秒",
 * "jump_type": 1,
 * "jump_type_name": "内部跳转",
 * "inside_jump_type": 1,
 * "outside_jump_url": "",
 * "inside_jump_type_name": "新闻通知",
 * "created_at": "2021-11-09 10:07:45",
 * "updated_at": "2021-11-09 10:07:45"
 * },
 * {
 * "id": 9,
 * "company_id": 3,
 * "status": 1,
 * "name": "",
 * "picture_url": "1",
 * "sort_id": "",
 * "rolling_time": 1,
 * "rolling_time_name": "1秒",
 * "jump_type": 1,
 * "jump_type_name": "内部跳转",
 * "inside_jump_type": 1,
 * "outside_jump_url": "",
 * "inside_jump_type_name": "新闻通知",
 * "created_at": "2021-11-09 10:07:46",
 * "updated_at": "2021-11-09 10:07:46"
 * },
 * {
 * "id": 10,
 * "company_id": 3,
 * "status": 1,
 * "name": "",
 * "picture_url": "1",
 * "sort_id": "",
 * "rolling_time": 1,
 * "rolling_time_name": "1秒",
 * "jump_type": 1,
 * "jump_type_name": "内部跳转",
 * "inside_jump_type": 1,
 * "outside_jump_url": "",
 * "inside_jump_type_name": "新闻通知",
 * "created_at": "2021-11-09 10:07:47",
 * "updated_at": "2021-11-09 10:07:47"
 * },
 * {
 * "id": 11,
 * "company_id": 3,
 * "status": 1,
 * "name": "",
 * "picture_url": "1",
 * "sort_id": "",
 * "rolling_time": 1,
 * "rolling_time_name": "1秒",
 * "jump_type": 1,
 * "jump_type_name": "内部跳转",
 * "inside_jump_type": 1,
 * "outside_jump_url": "",
 * "inside_jump_type_name": "新闻通知",
 * "created_at": "2021-11-09 10:07:48",
 * "updated_at": "2021-11-09 10:07:48"
 * },
 * {
 * "id": 12,
 * "company_id": 3,
 * "status": 1,
 * "name": "",
 * "picture_url": "1",
 * "sort_id": "",
 * "rolling_time": 1,
 * "rolling_time_name": "1秒",
 * "jump_type": 1,
 * "jump_type_name": "内部跳转",
 * "inside_jump_type": 1,
 * "outside_jump_url": "",
 * "inside_jump_type_name": "新闻通知",
 * "created_at": "2021-11-09 10:07:50",
 * "updated_at": "2021-11-09 10:07:50"
 * },
 * {
 * "id": 2,
 * "company_id": 3,
 * "status": 1,
 * "name": "",
 * "picture_url": "1",
 * "sort_id": "1",
 * "rolling_time": 1,
 * "rolling_time_name": "1秒",
 * "jump_type": 1,
 * "jump_type_name": "内部跳转",
 * "inside_jump_type": 1,
 * "outside_jump_url": "1",
 * "inside_jump_type_name": "新闻通知",
 * "created_at": "2021-11-08 12:56:45",
 * "updated_at": "2021-11-09 09:55:14"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test:14280/api/admin/carousel?page=1",
 * "last": "http://tms-api.test:14280/api/admin/carousel?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test:14280/api/admin/carousel",
 * "per_page": 200,
 * "to": 8,
 * "total": 8
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/carousel/{id} 轮播图详情
 * @apiName 轮播图详情
 * @apiGroup 60
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 地址ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.status 状态1-开启2-禁用
 * @apiSuccess {string} data.name 图片名称
 * @apiSuccess {string} data.picture_url 图片地址
 * @apiSuccess {string} data.sort_id 排序ID
 * @apiSuccess {string} data.rolling_time 滚动时间(秒)
 * @apiSuccess {string} data.rolling_time_name 滚动时间名称
 * @apiSuccess {string} data.jump_type 跳转类型1-外部跳转2-内部跳转
 * @apiSuccess {string} data.jump_type_name 跳转类型名称
 * @apiSuccess {string} data.inside_jump_type 内部跳转类型1-新闻通知2-入门教程3-禁运物品4-常见问题
 * @apiSuccess {string} data.outside_jump_url  外部跳转URL
 * @apiSuccess {string} data.inside_jump_type_name 内部跳转名称
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 6,
 * "company_id": 3,
 * "status": 1,
 * "name": "",
 * "picture_url": "1",
 * "sort_id": "",
 * "rolling_time": 1,
 * "rolling_time_name": "1秒",
 * "jump_type": 1,
 * "jump_type_name": "内部跳转",
 * "inside_jump_type": 1,
 * "outside_jump_url": "",
 * "inside_jump_type_name": "新闻通知",
 * "created_at": "2021-11-09 10:07:42",
 * "updated_at": "2021-11-09 10:07:42"
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {post} /admin/carousel 轮播图新增
 * @apiName 轮播图新增
 * @apiGroup 60
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} company_id 公司ID
 * @apiParam {string} status 状态1-开启2-禁用
 * @apiParam {string} name 图片名称
 * @apiParam {string} picture_url 图片地址
 * @apiParam {string} sort_id 排序ID
 * @apiParam {string} rolling_time 滚动时间(秒)
 * @apiParam {string} rolling_time_name 滚动时间名称
 * @apiParam {string} jump_type 跳转类型1-外部跳转2-内部跳转
 * @apiParam {string} jump_type_name 跳转类型名称
 * @apiParam {string} inside_jump_type 状态名称
 * @apiParam {string} outside_jump_url 沙盒模式名称
 * @apiParam {string} inside_jump_type_name 等待时间（可读）
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
 * @api {delete} /admin/carousel/{id} 轮播图删除
 * @apiName 轮播图删除
 * @apiGroup 60
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
 * @api {put} /admin/address/{id} 轮播图修改
 * @apiName 轮播图修改
 * @apiGroup 60
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 收件人ID
 * @apiParam {string} company_id 公司ID
 * @apiParam {string} status 状态1-开启2-禁用
 * @apiParam {string} name 图片名称
 * @apiParam {string} picture_url 图片地址
 * @apiParam {string} sort_id 排序ID
 * @apiParam {string} rolling_time 滚动时间(秒)
 * @apiParam {string} rolling_time_name 滚动时间名称
 * @apiParam {string} jump_type 跳转类型1-外部跳转2-内部跳转
 * @apiParam {string} jump_type_name 跳转类型名称
 * @apiParam {string} inside_jump_type 内部跳转类型1-新闻通知2-入门教程3-禁运物品4-常见问题
 * @apiParam {string} outside_jump_url  外部跳转URL
 * @apiParam {string} inside_jump_type_name 内部跳转名称
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
 * @api {put} /admin/carousel/sort 轮播图排序
 * @apiName 轮播图排序
 * @apiGroup 60
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id_list ID列表
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
