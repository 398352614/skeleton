<?php
/**
 * @apiDefine 15 条款配置
 */

/**
 * @api {get} /admin/service-agreement 条款查询
 * @apiName 条款查询
 * @apiGroup 15
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 地址ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.tittle 条款标题
 * @apiSuccess {string} data.text 条款正文
 * @apiSuccess {string} data.type 类型1-注册服务条款2-快递服务条款
 * @apiSuccess {string} data.type_name 类型名称
 * @apiSuccess {string} data.operator_name 操作人名称
 * @apiSuccess {string} data.operator_id 操作人ID
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "data": [
 * {
 * "id": 4,
 * "company_id": 3,
 * "tittle": "1",
 * "text": "2",
 * "type": 2,
 * "type_name": "入门教程",
 * "operator_name": "tms@nle-tech.com",
 * "operator_id": 3,
 * "created_at": "2021-11-09 09:01:31",
 * "updated_at": "2021-11-09 09:01:31"
 * },
 * {
 * "id": 3,
 * "company_id": 3,
 * "tittle": "1",
 * "text": "2",
 * "type": 1,
 * "type_name": "新闻通知",
 * "operator_name": "tms@nle-tech.com",
 * "operator_id": 3,
 * "created_at": "2021-11-09 09:00:29",
 * "updated_at": "2021-11-09 09:00:29"
 * },
 * {
 * "id": 2,
 * "company_id": 3,
 * "tittle": "1",
 * "text": "2",
 * "type": 1,
 * "type_name": "新闻通知",
 * "operator_name": "tms@nle-tech.com",
 * "operator_id": 3,
 * "created_at": "2021-11-09 08:58:55",
 * "updated_at": "2021-11-09 08:58:55"
 * }
 * ],
 * "links": {
 * "first": "http://tms-api.test:14280/api/admin/service-agreement?page=1",
 * "last": "http://tms-api.test:14280/api/admin/service-agreement?page=1",
 * "prev": null,
 * "next": null
 * },
 * "meta": {
 * "current_page": 1,
 * "from": 1,
 * "last_page": 1,
 * "path": "http://tms-api.test:14280/api/admin/service-agreement",
 * "per_page": 200,
 * "to": 3,
 * "total": 3
 * }
 * },
 * "msg": "successful"
 * }
 */

/**
 * @api {get} /admin/service-agreement/{id} 条款详情
 * @apiName 条款详情
 * @apiGroup 15
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} id 地址ID
 * @apiSuccess {string} code
 * @apiSuccess {string} data
 * @apiSuccess {string} data.id
 * @apiSuccess {string} data.company_id 公司ID
 * @apiSuccess {string} data.tittle 条款标题
 * @apiSuccess {string} data.text 条款正文
 * @apiSuccess {string} data.type 类型1-注册服务条款2-快递服务条款
 * @apiSuccess {string} data.type_name 类型名称
 * @apiSuccess {string} data.operator_name 操作人名称
 * @apiSuccess {string} data.operator_id 操作人ID
 * @apiSuccess {string} data.created_at
 * @apiSuccess {string} data.updated_at
 * @apiSuccess {string} msg
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * "id": 3,
 * "company_id": 3,
 * "tittle": "1",
 * "text": "2",
 * "type": 1,
 * "type_name": "新闻通知",
 * "operator_name": "tms@nle-tech.com",
 * "operator_id": 3,
 * "created_at": "2021-11-09 09:00:29",
 * "updated_at": "2021-11-09 09:00:29"
 * },
 * "msg": "successful"
 * }
 */

