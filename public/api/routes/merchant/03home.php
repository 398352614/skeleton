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
 * @apiDefine 03home 首页
 */

//主页统计
Route::prefix('statistics')->group(function () {
    /**
     * @api {get} /merchant/statistics/this-week 本周订单总量
     * @apiName 本周订单总量
     * @apiGroup 03home
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {Object} data.date    日期
     * @apiSuccess {Object} data.order    订单数
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[{"date":"2021-06-07","order":0},{"date":"2021-06-08","order":0},{"date":"2021-06-09","order":2}],"msg":"successful"}
     */
    Route::get('/this-week', 'HomeController@thisWeekCount')->name('statistics.home');

    /**
     * @api {get} /merchant/statistics/last-week 上周订单总量
     * @apiName 上周订单总量
     * @apiGroup 03home
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {Object} data.date    日期
     * @apiSuccess {Object} data.order    订单数
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[{"date":"2021-06-07","order":0},{"date":"2021-06-08","order":0},{"date":"2021-06-09","order":2}],"msg":"successful"}
     */
    Route::get('/last-week', 'HomeController@lastWeekCount')->name('statistics.home');

    /**
     * @api {get} /merchant/statistics/this-month 本月订单总量
     * @apiName 本月订单总量
     * @apiGroup 03home
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {Object} data.date    日期
     * @apiSuccess {Object} data.order    订单数
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[{"date":"2021-06-07","order":0},{"date":"2021-06-08","order":0},{"date":"2021-06-09","order":2}],"msg":"successful"}
     */
    Route::get('/this-month', 'HomeController@thisMonthCount')->name('statistics.home');

    /**
     * @api {get} /merchant/statistics/last-month 上月订单总量
     * @apiName 上月订单总量
     * @apiGroup 03home
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {Object} data.date    日期
     * @apiSuccess {Object} data.order    订单数
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[{"date":"2021-06-07","order":0},{"date":"2021-06-08","order":0},{"date":"2021-06-09","order":2}],"msg":"successful"}
     */
    Route::get('/last-month', 'HomeController@lastMonthCount')->name('statistics.home');

    /**
     * @api {get} /merchant/statistics/period 时间段订单总量
     * @apiName 时间段订单总量
     * @apiGroup 03home
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiParam {date} begin_date    [必填]起始日期
     * @apiParam {date} end_date    [必填]终止日期
     * @apiParamExample {json} Param-Response:
     * {"begin_date":"2021-06-09","end_date":"2021-06-09"}
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {Object} data.date    日期
     * @apiSuccess {Object} data.order    订单数
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[{"date":"2021-06-07","order":0},{"date":"2021-06-08","order":0},{"date":"2021-06-09","order":2}],"msg":"successful"}
     */
    Route::get('/period', 'HomeController@periodCount')->name('statistics.home');

    /**
     * @api {get} /merchant/statistics/this-week 今日订单情况
     * @apiName 今日订单情况
     * @apiGroup 03home
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {Object} data.date    日期
     * @apiSuccess {Object} data.order    订单数
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":{"doing":0,"done":0,"cancel":0},"msg":"successful"}
     */
    Route::get('/today-overview', 'HomeController@todayOverview')->name('statistics.home');

    /**
     * @api {get} /merchant/statistics/this-week 订单动态
     * @apiName 订单动态
     * @apiGroup 03home
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {Object} data.date    日期
     * @apiSuccess {Object} data.order    订单数
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[{"date":"2021-06-07","order":0},{"date":"2021-06-08","order":0},{"date":"2021-06-09","order":2}],"msg":"successful"}
     */
    Route::get('/trail', 'HomeController@orderAnalysis')->name('statistics.home');


});
