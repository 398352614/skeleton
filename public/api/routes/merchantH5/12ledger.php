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
 * @apiDefine 11recharge 财务账户管理
 */

/**
 * @api {get} /admin/ledger 财务账户详情
 * @apiName 分户账簿查询
 * @apiGroup 52
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {string} begin_date 起始时间
 * @apiParam {string} end_date 截止时间
 * @apiParam {string} code 货主编号
 * @apiParam {string} merchant_group_id 货主组ID
 * @apiSuccess {string} code
 * @apiSuccess {string} msg
 * @apiSuccess {string} data
 * @apiSuccess {string} data.user_name 货主名称
 * @apiSuccess {string} data.code 货主编号
 * @apiSuccess {string} data.credit 信用额度
 * @apiSuccess {string} data.balance 账户余额
 * @apiSuccess {string} data.status 状态1-限制2-不限制
 * @apiSuccess {string} data.phone 手机号
 * @apiSuccess {string} data.email 邮箱
 * @apiSuccess {string} data.create_date 注册日期
 * @apiSuccess {string} data.merchant_group_id 货主组ID
 * @apiSuccess {string} data.merchant_group_name 货主组名称
 * @apiSuccessExample {json} Success-Response:
 * {
 * "code": 200,
 * "data": {
 * }
 */
