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
 * @apiDefine 10warehouse 网点
 */

/**
 * @api {get} /merchant_h5/warehouse 网点查询
 * @apiName 网点查询
 * @apiGroup 10warehouse
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
 * @api {get} /merchant_h5/warehouse/{id} 网点详情
 * @apiName 网点详情
 * @apiGroup 10warehouse
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
