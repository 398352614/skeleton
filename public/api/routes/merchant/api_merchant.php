<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//公共接口
Route::namespace('Api\Merchant')->group(function () {
    /**
     * @api {post} /merchant/login 登录
     * @apiGroup 用户认证
     * @apiName 登录
     * @apiVersion 1.0.0
     *
     * @apiHeader {string} language 语言：cn-中文；en-英文。

     * @apiParam {String} username    [必填]用户名
     * @apiParam {String} password    [必填]密码
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {String} data.username    用户名
     * @apiSuccess {String} data.access_token    认证令牌
     * @apiSuccess {String} data.token_type    令牌类型
     * @apiSuccess {String} data.expires_in    令牌有效时间
     *
     * @apiSuccess {Object} data.company_config   公司配置
     * @apiSuccess {String} data.company_config.id    公司配置ID
     * @apiSuccess {String} data.company_config.company_code    公司编号
     * @apiSuccess {String} data.company_config.name    公司名称
     * @apiSuccess {String} data.company_config.line_rule    线路规则：1-邮编；2-区域。
     * @apiSuccess {String} data.company_config.show_type    展示方式：1-全部展示；2-按线路规则展示。
     * @apiSuccess {String} data.company_config.address_template_id   地址模板ID：1-模板一；2-模板二。
     * @apiSuccess {String} data.company_config.stock_exception_verify    是否开启入库异常审核：1-开启；2-关闭。
     * @apiSuccess {String} data.company_config.weight_unit    重量单位
     * @apiSuccess {String} data.company_config.currency_unit    货币单位
     * @apiSuccess {String} data.company_config.volume_unit    体积单位
     * @apiSuccess {String} data.company_config.map    地图引擎
     * @apiSuccess {String} data.company_config.country    国家代号
     * @apiSuccess {String} data.company_config.country_en_name    国家英文名
     * @apiSuccess {String} data.company_config.country_cn_name    国家中文名
     *
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":{"username":"ERP\u56fd\u9645","company_id":3,"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9kZXYtdG1zLm5sZS10ZWNoLmNvbTo0NDNcL2FwaVwvbWVyY2hhbnRcL2xvZ2luIiwiaWF0IjoxNjIzMjI4ODY1LCJleHAiOjE2MjgwNjcyNjUsIm5iZiI6MTYyMzIyODg2NSwianRpIjoiTEJOaXpOTGhIVTZrd2ttZCIsInN1YiI6NjUsInBydiI6IjkzYmRjYzU4ZGQwMWNlMzZlYzU2ZTMyYjViYjU4MGQ4MzAzMmZkMTgiLCJyb2xlIjoibWVyY2hhbnQifQ.LpBWSItYcjeFuSwEf_FIqa2qO7BXe57biqSrsELk6n4","token_type":"bearer","expires_in":4838400,"company_config":{"id":3,"company_code":"0003","name":"\u7ea2\u5154TMS","line_rule":1,"show_type":1,"address_template_id":1,"stock_exception_verify":2,"weight_unit":2,"currency_unit":3,"volume_unit":2,"map":"google","country":"NL","country_en_name":"Netherlands","country_cn_name":"\u8377\u5170"}},"msg":"successful"}
     */
    Route::post('login', 'AuthController@login');

    /**
     * @api {post} /merchant/password-reset/apply 获取重置密码验证码
     * @apiGroup 用户认证
     * @apiName 获取重置密码验证码
     * @apiVersion 1.0.0
     *
     * @apiHeader {string} Authorization [必填]令牌，以bearer加空格加令牌为格式。
     * @apiHeaderExample {json} Header-Example:
     * {
     *       "language": "en"
     *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9kZXYtdG1zLm5sZS10ZWNoLmNvbTo0NDNcL2FwaVwvYWRtaW5cL2xvZ2luIiwiaWF0IjoxNTkxMjU4NDAzLCJleHAiOjE1OTI0NjgwMDMsIm5iZiI6MTU5MTI1ODQwMywianRpIjoidGV2MG1hQlM1T0lDVm5JRCIsInN1YiI6NjEsInBydiI6IjMyOTYzYTYwNmMyZjE3MWYxYzE0MzMxZTc2OTc2NmNkNTkxMmVkMTUiLCJyb2xlIjoiZW1wbG95ZWUifQ.8NVjy4OyITV3Cu3k3m_BwNc5Yqf2Ld-ibRQ7r9Q82kw"
     *     }
     * @apiParam {String} email    [必填]邮箱
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     *
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":"\u9a8c\u8bc1\u7801\u53d1\u9001\u6210\u529f","msg":"successful"}
     */
    Route::post('password-reset/apply', 'RegisterController@applyOfReset');

    /**
     * @api {post} /merchant/password-reset 重置密码
     * @apiGroup 用户认证
     * @apiName 重置密码
     * @apiVersion 1.0.0
     *
     * @apiParam {String} email    [必填]邮箱
     * @apiParam {String} code    [必填]验证码
     * @apiParam {String} new_password    [必填]新密码
     * @apiParam {String} confirm_new_password    [必填]重复新密码
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     *
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[],"msg":"successful"}
     */
    Route::put('password-reset', 'RegisterController@resetPassword');
    //修改密码
    //Route::post('register', 'RegisterController@store');
    //Route::post('register/apply', 'RegisterController@applyOfRegister');
    //Route::put('password-reset/verify', 'RegisterController@verifyResetCode');
});

//认证
Route::namespace('Api\Merchant')->middleware(['companyValidate:merchant', 'auth:merchant'])->group(function () {
    /**
     * @api {put} /merchant/password-reset 个人资料
     * @apiGroup 用户认证
     * @apiName 个人资料
     * @apiVersion 1.0.0
     *
     * @apiParam {String} origin_password    [必填]原密码
     * @apiParam {String} new_password    [必填]新密码
     * @apiParam {String} new_confirm_password    [必填]重复新密码
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {String} data.id
     * @apiSuccess {String} data.company_id
     * @apiSuccess {String} data.code
     * @apiSuccess {String} data.type
     * @apiSuccess {String} data.name
     * @apiSuccess {String} data.below_warehouse
     * @apiSuccess {String} data.warehouse_id
     * @apiSuccess {String} data.short_name
     * @apiSuccess {String} data.introduction
     * @apiSuccess {String} data.email
     * @apiSuccess {String} data.country
     * @apiSuccess {String} data.settlement_type
     * @apiSuccess {String} data.merchant_group_id
     * @apiSuccess {String} data.contacter
     * @apiSuccess {String} data.phone
     * @apiSuccess {String} data.address
     * @apiSuccess {String} data.avatar
     * @apiSuccess {String} data.invoice_title
     * @apiSuccess {String} data.taxpayer_code
     * @apiSuccess {String} data.
     * @apiSuccess {String} data.
     * @apiSuccess {String} data.
     * @apiSuccess {String} data.
     * @apiSuccess {String} data.
     * @apiSuccess {String} data.
     * @apiSuccess {String} data.
     * @apiSuccess {String} data.
     * @apiSuccess {String} data.
     * @apiSuccess {String} data.
     * @apiSuccess {String} data.
     * @apiSuccess {String} data.
     * @apiSuccess {String} data.
     * @apiSuccess {String} data.
     * @apiSuccess {String} data.
     * @apiSuccess {String} data.

     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":{"id":65,"company_id":3,"code":"00065","type":2,"name":"ERP\u56fd\u9645","below_warehouse":2,"warehouse_id":null,"short_name":"0","introduction":"Nederlands Express\uff0cNLE\u8377\u5170\u5feb\u9012\uff08\u4ee5\u4e0b\u7b80\u79f0NLE\uff09\u603b\u90e8\u4f4d\u4e8e\u8377\u5170\uff0c\u662f\u8377\u5170\u6700\u65e9\u4e14\u6700\u5927\u4e00\u5bb6\u4ece\u4e8b\u56fd\u9645\u7269\u6d41\u901f\u9012\u3001\u4ed3\u50a8\u8fd0\u8425\u3001\u7a7a\u8fd0\u3001\u8d27\u4ee3\u7b49\u7269\u6d41\u914d\u9001\u89e3\u51b3\u65b9\u6848\u7684\u4e13\u4e1a\u56fd\u9645\u7269\u6d41\u516c\u53f8\u3002","email":"erp@nle-tech.com","country":"NL","settlement_type":1,"merchant_group_id":53,"contacter":"\u8054\u7cfb\u4eba1","phone":"1312121211","address":"\u8be6\u7ec6\u5730\u57401","avatar":"\u5934\u50cf","invoice_title":"1","taxpayer_code":"0000-00-00","bank":"0000-00-00","bank_account":"0000-00-00","invoice_address":"0000-00-00","invoice_email":"0000-00-00","status":1,"created_at":"2020-07-14 16:45:36","updated_at":"2021-06-09 12:54:46","company_config":{"id":3,"company_id":3,"line_rule":1,"show_type":1,"address_template_id":1,"stock_exception_verify":2,"weight_unit":2,"currency_unit":3,"volume_unit":2,"map":"google","created_at":"2020-03-13 12:00:09","updated_at":"2021-06-08 06:14:09","scheduling_rule":1},"settlement_type_name":"\u7968\u7ed3","status_name":"\u542f\u7528","type_name":"\u8d27\u4e3b","country_name":"\u8377\u5170","additional_status":1,"advance_days":0,"appointment_days":10,"delay_time":0,"pickup_count":1,"pie_count":2,"merchant_group":{"id":53,"company_id":3,"name":"ERP\u56fd\u9645\u7ec4","transport_price_id":67,"count":3,"is_default":2,"additional_status":1,"advance_days":0,"appointment_days":10,"delay_time":0,"pickup_count":1,"pie_count":2,"created_at":"2020-12-28 03:26:41","updated_at":"2021-03-18 09:00:48","additional_status_name":"\u5f00\u542f"}},"msg":"successful"}
     */
    Route::get('me', 'AuthController@me');
    //登出
    Route::post('logout', 'AuthController@logout');
    /**
     * @api {put} /merchant/password-reset 修改密码
     * @apiGroup 用户认证
     * @apiName 修改密码
     * @apiVersion 1.0.0
     *
     * @apiParam {String} origin_password    [必填]原密码
     * @apiParam {String} new_password    [必填]新密码
     * @apiParam {String} new_confirm_password    [必填]重复新密码
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     *
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[],"msg":"successful"}
     */
    Route::put('my-password', 'AuthController@updatePassword');
    //修改个人信息
    Route::put('', 'MerchantController@update');
    //切换时区
    Route::put('/timezone', 'AuthController@updateTimezone');

    //主页统计
    Route::prefix('statistics')->group(function () {
        /**
         * @api {get} /merchant/statistics/this-week 本周订单总量
         * @apiGroup 首页
         * @apiName 本周订单总量
         * @apiVersion 1.0.0
         * @apiHeader {string} Authorization [必填]令牌，以bearer加空格加令牌为格式。
         * @apiHeaderExample {json} Header-Example:
         * {
         *       "language": "en"
         *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9kZXYtdG1zLm5sZS10ZWNoLmNvbTo0NDNcL2FwaVwvYWRtaW5cL2xvZ2luIiwiaWF0IjoxNTkxMjU4NDAzLCJleHAiOjE1OTI0NjgwMDMsIm5iZiI6MTU5MTI1ODQwMywianRpIjoidGV2MG1hQlM1T0lDVm5JRCIsInN1YiI6NjEsInBydiI6IjMyOTYzYTYwNmMyZjE3MWYxYzE0MzMxZTc2OTc2NmNkNTkxMmVkMTUiLCJyb2xlIjoiZW1wbG95ZWUifQ.8NVjy4OyITV3Cu3k3m_BwNc5Yqf2Ld-ibRQ7r9Q82kw"
         *     }
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
         * @apiGroup 首页
         * @apiName 上周订单总量
         * @apiVersion 1.0.0
         * @apiHeader {string} Authorization [必填]令牌，以bearer加空格加令牌为格式。
         * @apiHeaderExample {json} Header-Example:
         * {
         *       "language": "en"
         *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9kZXYtdG1zLm5sZS10ZWNoLmNvbTo0NDNcL2FwaVwvYWRtaW5cL2xvZ2luIiwiaWF0IjoxNTkxMjU4NDAzLCJleHAiOjE1OTI0NjgwMDMsIm5iZiI6MTU5MTI1ODQwMywianRpIjoidGV2MG1hQlM1T0lDVm5JRCIsInN1YiI6NjEsInBydiI6IjMyOTYzYTYwNmMyZjE3MWYxYzE0MzMxZTc2OTc2NmNkNTkxMmVkMTUiLCJyb2xlIjoiZW1wbG95ZWUifQ.8NVjy4OyITV3Cu3k3m_BwNc5Yqf2Ld-ibRQ7r9Q82kw"
         *     }
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
         * @apiGroup 首页
         * @apiName 本月订单总量
         * @apiVersion 1.0.0
         * @apiHeader {string} Authorization [必填]令牌，以bearer加空格加令牌为格式。
         * @apiHeaderExample {json} Header-Example:
         * {
         *       "language": "en"
         *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9kZXYtdG1zLm5sZS10ZWNoLmNvbTo0NDNcL2FwaVwvYWRtaW5cL2xvZ2luIiwiaWF0IjoxNTkxMjU4NDAzLCJleHAiOjE1OTI0NjgwMDMsIm5iZiI6MTU5MTI1ODQwMywianRpIjoidGV2MG1hQlM1T0lDVm5JRCIsInN1YiI6NjEsInBydiI6IjMyOTYzYTYwNmMyZjE3MWYxYzE0MzMxZTc2OTc2NmNkNTkxMmVkMTUiLCJyb2xlIjoiZW1wbG95ZWUifQ.8NVjy4OyITV3Cu3k3m_BwNc5Yqf2Ld-ibRQ7r9Q82kw"
         *     }
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
         * @apiGroup 首页
         * @apiName 上月订单总量
         * @apiVersion 1.0.0
         * @apiHeader {string} Authorization [必填]令牌，以bearer加空格加令牌为格式。
         * @apiHeaderExample {json} Header-Example:
         * {
         *       "language": "en"
         *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9kZXYtdG1zLm5sZS10ZWNoLmNvbTo0NDNcL2FwaVwvYWRtaW5cL2xvZ2luIiwiaWF0IjoxNTkxMjU4NDAzLCJleHAiOjE1OTI0NjgwMDMsIm5iZiI6MTU5MTI1ODQwMywianRpIjoidGV2MG1hQlM1T0lDVm5JRCIsInN1YiI6NjEsInBydiI6IjMyOTYzYTYwNmMyZjE3MWYxYzE0MzMxZTc2OTc2NmNkNTkxMmVkMTUiLCJyb2xlIjoiZW1wbG95ZWUifQ.8NVjy4OyITV3Cu3k3m_BwNc5Yqf2Ld-ibRQ7r9Q82kw"
         *     }
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
         * @apiGroup 首页
         * @apiName 时间段订单总量
         * @apiVersion 1.0.0
         * @apiHeader {string} Authorization [必填]令牌，以bearer加空格加令牌为格式。
         * @apiHeaderExample {json} Header-Example:
         * {
         *       "language": "en"
         *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9kZXYtdG1zLm5sZS10ZWNoLmNvbTo0NDNcL2FwaVwvYWRtaW5cL2xvZ2luIiwiaWF0IjoxNTkxMjU4NDAzLCJleHAiOjE1OTI0NjgwMDMsIm5iZiI6MTU5MTI1ODQwMywianRpIjoidGV2MG1hQlM1T0lDVm5JRCIsInN1YiI6NjEsInBydiI6IjMyOTYzYTYwNmMyZjE3MWYxYzE0MzMxZTc2OTc2NmNkNTkxMmVkMTUiLCJyb2xlIjoiZW1wbG95ZWUifQ.8NVjy4OyITV3Cu3k3m_BwNc5Yqf2Ld-ibRQ7r9Q82kw"
         *     }
         * @apiParam {date} begin_date    [必填]起始日期
         * @apiParam {date} end_date    [必填]终止日期
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
         * @apiGroup 首页
         * @apiName 今日订单情况
         * @apiVersion 1.0.0
         * @apiHeader {string} Authorization [必填]令牌，以bearer加空格加令牌为格式。
         * @apiHeaderExample {json} Header-Example:
         * {
         *       "language": "en"
         *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9kZXYtdG1zLm5sZS10ZWNoLmNvbTo0NDNcL2FwaVwvYWRtaW5cL2xvZ2luIiwiaWF0IjoxNTkxMjU4NDAzLCJleHAiOjE1OTI0NjgwMDMsIm5iZiI6MTU5MTI1ODQwMywianRpIjoidGV2MG1hQlM1T0lDVm5JRCIsInN1YiI6NjEsInBydiI6IjMyOTYzYTYwNmMyZjE3MWYxYzE0MzMxZTc2OTc2NmNkNTkxMmVkMTUiLCJyb2xlIjoiZW1wbG95ZWUifQ.8NVjy4OyITV3Cu3k3m_BwNc5Yqf2Ld-ibRQ7r9Q82kw"
         *     }
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
         * @apiGroup 首页
         * @apiName 订单动态
         * @apiVersion 1.0.0
         * @apiHeader {string} Authorization [必填]令牌，以bearer加空格加令牌为格式。
         * @apiHeaderExample {json} Header-Example:
         * {
         *       "language": "Accept-Encoding: gzip, deflate"
         *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9kZXYtdG1zLm5sZS10ZWNoLmNvbTo0NDNcL2FwaVwvYWRtaW5cL2xvZ2luIiwiaWF0IjoxNTkxMjU4NDAzLCJleHAiOjE1OTI0NjgwMDMsIm5iZiI6MTU5MTI1ODQwMywianRpIjoidGV2MG1hQlM1T0lDVm5JRCIsInN1YiI6NjEsInBydiI6IjMyOTYzYTYwNmMyZjE3MWYxYzE0MzMxZTc2OTc2NmNkNTkxMmVkMTUiLCJyb2xlIjoiZW1wbG95ZWUifQ.8NVjy4OyITV3Cu3k3m_BwNc5Yqf2Ld-ibRQ7r9Q82kw"
         *     }
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

    //订单管理
    Route::prefix('order')->group(function () {
        //订单统计
        Route::get('/count', 'OrderController@ordercount');
        //查询初始化
        Route::get('/initIndex', 'OrderController@initIndex');
        //列表查询
        Route::get('/', 'OrderController@index');
        //获取详情
        Route::get('/{id}', 'OrderController@show');
        //新增初始化
        Route::get('/initStore', 'OrderController@initStore');
        //新增
        Route::post('/', 'OrderController@store');
        //修改
        Route::put('/{id}', 'OrderController@update');
        //获取继续派送(再次取派)信息
        Route::get('/{id}/again-info', 'OrderController@getAgainInfo');
        //继续派送(再次取派)
        Route::put('/{id}/again', 'OrderController@again');
        //终止派送
        Route::put('/{id}/end', 'OrderController@end');
        //删除
        Route::delete('/{id}', 'OrderController@destroy');
        //订单追踪
        Route::get('/{id}/track', 'OrderController@track');
        //批量更新电话日期
        Route::post('/update-phone-date-list', 'OrderController@updateByApiList');
        //获取订单的运单列表
        Route::get('/{id}/tracking-order', 'OrderController@getTrackingOrderList');
        //修改订单地址
        Route::put('/{id}/update-address-date', 'OrderController@updateAddressDate');
        //获取可分配路线日期
        Route::get('/{id}/get-date', 'OrderController@getAbleDateList');
        //通过地址获取可分配的路线日期列表
        Route::get('/get-date', 'OrderController@getAbleDateListByAddress');
        //分配至站点
        Route::put('/{id}/assign-batch', 'OrderController@assignToBatch');
        //从站点移除
        Route::delete('/{id}/remove-batch', 'OrderController@removeFromBatch');
        //批量打印面单
        Route::get('/bill', 'OrderController@orderBillPrint')->name('order.print');

    });

    //物流状态管理
    Route::prefix('order-trail')->group(function () {
        //rest api 放在最后
        Route::get('/{order_no}', 'OrderTrailController@index')->name('order-trail.index');
    });

    //订单导入
    Route::prefix('order-import')->group(function () {
        //获取模板
        Route::get('/template', 'OrderImportController@templateExport')->name('order.import-list');
        //导入
        Route::post('/', 'OrderImportController@import')->name('order.import-list');
        //检查
        Route::post('/check', 'OrderImportController@importCheck')->name('order.import-list');
        //批量新增
        Route::post('/list', 'OrderImportController@createByList')->name('order.import-list');
    });

    //收件人地址管理
    Route::prefix('address')->group(function () {
        //列表查询
        Route::get('/', 'AddressController@index');
        //获取详情
        Route::get('/{id}', 'AddressController@show');
        //新增
        Route::post('/', 'AddressController@store');
        //修改
        Route::put('/{id}', 'AddressController@update');
        //删除
        Route::delete('/{id}', 'AddressController@destroy');
    });

    //运价管理
    Route::prefix('transport-price')->group(function () {
        Route::get('/', 'TransportPriceController@show');
    });

    //API管理
    Route::prefix('api')->group(function () {
        Route::get('/', 'MerchantApiController@show');//获取详情
        Route::put('/', 'MerchantApiController@update');//修改
    });

    //公共接口
    Route::prefix('common')->group(function () {
        //获取具体地址经纬度
        Route::get('/location', 'CommonController@getLocation');
        //获取所有国家列表
        Route::get('/country', 'CommonController@getCountryList');
        //获取邮编信息
        Route::get('/postcode', 'CommonController@getPostcode');
        //字典
        Route::get('/dictionary', 'CommonController@dictionary');
        //获取所有线路范围
        Route::get('/line-range', 'LineController@getAllLineRange');
    });

    //    //运单管理
//    Route::prefix('tracking-order')->group(function () {
//        //查询初始化
//        Route::get('/init-index', 'TrackingOrderController@initIndex');
//        //运单统计
//        Route::get('/count', 'TrackingOrderController@trackingOrderCount');
//        //列表查询
//        Route::get('/', 'TrackingOrderController@index');
//    });
//
//    Route::prefix('package')->group(function () {
//        //列表查询
//        Route::get('/', 'PackageController@index');
//        //获取详情
//        Route::get('/{id}', 'PackageController@show');
//    });
//
//    Route::prefix('material')->group(function () {
//        //列表查询
//        Route::get('/', 'MaterialController@index');
//        //获取详情
//        Route::get('/{id}', 'MaterialController@show');
//    });
//    //取件线路
//    Route::prefix('tour')->group(function () {
//        //列表查询
//        Route::get('/', 'TourController@index')->name('tour.index');
//        //详情
//        Route::get('/{id}', 'TourController@show')->name('tour.show');
//        //追踪
//        Route::get('/track', 'RouteTrackingController@show')->name('tour.track');
//        //路径
//        Route::get('/driver', 'TourDriverController@getListByTourNo')->name('tour.driver');
//    });
});
