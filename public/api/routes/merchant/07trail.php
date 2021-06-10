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
 * @apiDefine 07trail 轨迹
 */

/**
 * @apiDefine meta
 * @apiSuccess {Object} data    返回数据
 * @apiSuccess {Object} data.links 跳转信息
 * @apiSuccess {String} data.links.first   第一页
 * @apiSuccess {String} data.links.last   最后一页
 * @apiSuccess {String} data.links.prev   前一页
 * @apiSuccess {String} data.links.next   后一页
 *
 * @apiSuccess {Object} data.meta 页码信息
 * @apiSuccess {String} data.meta.current_page   当前页码
 * @apiSuccess {String} data.meta.from   起始条数
 * @apiSuccess {String} data.meta.last_page   末页页码
 * @apiSuccess {String} data.meta.path   地址
 * @apiSuccess {String} data.meta.per_page   每页显示条数
 * @apiSuccess {String} data.meta.to   终止条数
 * @apiSuccess {String} data.meta.total   总条数
 */

//物流状态管理
Route::prefix('trail')->group(function () {
    //rest api 放在最后
    /**
     * @api {get} /trail/order 订单轨迹
     * @apiName 订单轨迹
     * @apiGroup 07trail
     * @apiVersion 1.0.0
     * @apiUse auth
     *
     * @apiParamExample {json} Param-Response:
     * {"timezone":"GMT+00:00"}
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {String} data.data1    返回数据


     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[],"msg":"successful"}
     */
    Route::get('/order/{order_no}', 'OrderTrailController@index')->name('order-trail.index');
});

