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
 * @apiDefine 08address 地址管理
 */

//地址管理
Route::prefix('address')->group(function () {
    //列表查询
    /**
     * @api {get} /merchant_h5/address 地址查询
     * @apiName 地址查询
     * @apiGroup 08address
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiParam {String} type 类型1-取件2-派件
     * @apiParam {String} place_post_code 邮编
     * @apiParam {String} place_phone 电话
     * @apiParam {String} place_fullname 姓名
     * @apiParam {String} keyword 模糊查询
     * @apiParam {String} is_default 是否默认1是2否
     * @apiUse page
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {String} data.data    返回数据
     * @apiSuccess {String} data.data.id 地址ID
     * @apiSuccess {String} data.data.place_fullname 姓名
     * @apiSuccess {String} data.data.place_phone 电话
     * @apiSuccess {String} data.data.type 类型1-收件人2-发件人
     * @apiSuccess {String} data.data.type_name 类型名称
     * @apiSuccess {String} data.data.place_address 详细地址
     * @apiSuccess {String} data.data.created_at 创建日期
     * @apiSuccess {String} data.data.updated_at 修改日期
     * @apiUse meta
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":{"data":[{"id":3,"place_fullname":"wangwenxuan","place_phone":"0031612345678","type":1,"type_name":"\u53d1\u4ef6\u4eba","place_address":"NL Amstelveen Straat van Gibraltar 11 1183GT","created_at":"2020-07-23 13:42:38","updated_at":"2020-07-23 13:42:38"},{"id":4,"place_fullname":"wwx","place_phone":"0031612345678","type":1,"type_name":"\u53d1\u4ef6\u4eba","place_address":"NL Nieuw-Vennep Pesetaweg 20 2153PJ","created_at":"2020-07-23 13:47:59","updated_at":"2020-07-23 13:47:59"},{"id":5,"place_fullname":"wwx","place_phone":"0031321231231","type":1,"type_name":"\u53d1\u4ef6\u4eba","place_address":"NL Amstelveen Kierkegaardstraat 7 1185AH","created_at":"2020-07-23 13:52:54","updated_at":"2020-07-23 13:52:54"},{"id":8,"place_fullname":"xiaoxao","place_phone":"0031329109023","type":1,"type_name":"\u53d1\u4ef6\u4eba","place_address":"NL Amstelveen Straat van Gibraltar 7 1183GT","created_at":"2020-07-23 14:11:40","updated_at":"2020-07-23 14:11:40"},{"id":16,"place_fullname":"yefen","place_phone":"0031612345678","type":1,"type_name":"\u53d1\u4ef6\u4eba","place_address":"NL Amstelveen Straat van Gibraltar 11 1183GT","created_at":"2020-07-23 15:56:28","updated_at":"2020-07-23 15:56:28"},{"id":17,"place_fullname":"ada","place_phone":"0031123456789","type":1,"type_name":"\u53d1\u4ef6\u4eba","place_address":"NL Schiphol Evert van de Beekstraat 202 1118 CP","created_at":"2020-07-23 15:56:57","updated_at":"2020-07-23 15:56:57"},{"id":18,"place_fullname":"CS","place_phone":"0031912365487","type":1,"type_name":"\u53d1\u4ef6\u4eba","place_address":"NL Amstelveen Meerpaal 11 1186ZM","created_at":"2020-07-23 15:58:03","updated_at":"2020-07-23 15:58:03"},{"id":19,"place_fullname":"DD","place_phone":"0031632145678","type":1,"type_name":"\u53d1\u4ef6\u4eba","place_address":"NL Amstelveen Tamarindelaan 29 1187EM","created_at":"2020-07-23 15:58:52","updated_at":"2020-07-23 15:58:52"},{"id":20,"place_fullname":"Cui","place_phone":"0031698754321","type":1,"type_name":"\u53d1\u4ef6\u4eba","place_address":"NL Amstelveen Terschellingstraat 14 1181HK","created_at":"2020-07-23 15:59:33","updated_at":"2020-07-23 15:59:33"},{"id":21,"place_fullname":"Ak","place_phone":"0031623541789","type":1,"type_name":"\u53d1\u4ef6\u4eba","place_address":"NL Amstelveen Birkhoven 49 1187JW","created_at":"2020-07-23 16:00:00","updated_at":"2020-07-23 16:00:00"}],"links":{"first":"http:\/\/tms-api.test:10002\/api\/merchant\/address?page=1","last":"http:\/\/tms-api.test:10002\/api\/merchant\/address?page=48","prev":null,"next":"http:\/\/tms-api.test:10002\/api\/merchant\/address?page=2"},"meta":{"current_page":1,"from":1,"last_page":48,"path":"http:\/\/tms-api.test:10002\/api\/merchant\/address","per_page":"10","to":10,"total":474}},"msg":"successful"}
     */
    Route::get('/', 'AddressController@index');
    //获取详情
    /**
     * @api {get} /merchant_h5/address/{id} 地址详情
     * @apiName 地址详情
     * @apiGroup 08address
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiParam {String} id 地址ID
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {String} data.id 地址ID
     * @apiSuccess {String} data.place_fullname 姓名
     * @apiSuccess {String} data.place_phone 电话
     * @apiSuccess {String} data.type 类型1-收件人2-发件人
     * @apiSuccess {String} data.type_name 类型名称
     * @apiSuccess {String} data.place_address 详细地址
     * @apiSuccess {String} data.place_country 国家
     * @apiSuccess {String} data.place_country_name 国家名称
     * @apiSuccess {String} data.place_province 省份
     * @apiSuccess {String} data.place_post_code 邮编
     * @apiSuccess {String} data.place_house_number 门牌号
     * @apiSuccess {String} data.place_city 城市
     * @apiSuccess {String} data.place_district 区域
     * @apiSuccess {String} data.place_street 街道
     * @apiSuccess {String} data.place_lon 经度
     * @apiSuccess {String} data.place_lat 纬度
     * @apiSuccess {String} data.created_at 创建日期
     * @apiSuccess {String} data.updated_at 修改日期
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":{"id":3,"place_fullname":"wangwenxuan","place_phone":"0031612345678","type":1,"type_name":"\u53d1\u4ef6\u4eba","place_address":"NL Amstelveen Straat van Gibraltar 11 1183GT","place_country":"NL","place_country_name":"\u8377\u5170","place_province":"","place_post_code":"1183GT","place_house_number":"11","place_city":"Amstelveen","place_district":"","place_street":"Straat van Gibraltar","place_lon":"4.87510019","place_lat":"52.31153083","created_at":"2020-07-23 13:42:38","updated_at":"2020-07-23 13:42:38"},"msg":"successful"}
     */
    Route::get('/{id}', 'AddressController@show');
    //新增
    /**
     * @api {post} /merchant_h5/address/{id} 地址新增
     * @apiName 地址新增
     * @apiGroup 08address
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiParam {String} place_fullname 姓名
     * @apiParam {String} place_phone 电话
     * @apiParam {String} type 类型1-收件人2-发件人
     * @apiParam {String} type_name 类型名称
     * @apiParam {String} place_address 详细地址
     * @apiParam {String} place_country 国家
     * @apiParam {String} place_country_name 国家名称
     * @apiParam {String} place_province 省份
     * @apiParam {String} place_post_code 邮编
     * @apiParam {String} place_house_number 门牌号
     * @apiParam {String} place_city 城市
     * @apiParam {String} place_district 区域
     * @apiParam {String} place_street 街道
     * @apiParam {String} place_lon 经度
     * @apiParam {String} place_lat 纬度
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[],"msg":"successful"}
     */
    Route::post('/', 'AddressController@store');
    //修改
    /**
     * @api {put} /merchant_h5/address/{id} 地址修改
     * @apiName 地址修改
     * @apiGroup 08address
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiParam {String} id 地址ID
     * @apiParam {String} place_fullname 姓名
     * @apiParam {String} place_phone 电话
     * @apiParam {String} type 类型1-收件人2-发件人
     * @apiParam {String} type_name 类型名称
     * @apiParam {String} place_address 详细地址
     * @apiParam {String} place_country 国家
     * @apiParam {String} place_province 省份
     * @apiParam {String} place_post_code 邮编
     * @apiParam {String} place_house_number 门牌号
     * @apiParam {String} place_city 城市
     * @apiParam {String} place_district 区域
     * @apiParam {String} place_street 街道
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[],"msg":"successful"}
     */
    Route::put('/{id}', 'AddressController@update');
    //删除
    /**
     * @api {delete} /merchant_h5/address/{id} 地址删除
     * @apiName 地址删除
     * @apiGroup 08address
     * @apiVersion 1.0.0
     * @apiUse auth
     *
     * @apiParam {String} id 地址ID
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {String} data.data1    返回数据
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[],"msg":"successful"}
     */
    Route::delete('/{id}', 'AddressController@destroy');

    /**
     * @api {put} /merchant_h5/address/{id}/default 设置默认地址
     * @apiName 设置默认地址
     * @apiGroup 08address
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiParam {String} id 地址ID
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
});


