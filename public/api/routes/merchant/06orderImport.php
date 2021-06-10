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
 * @apiDefine 06orderImport 订单导入
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

//订单导入
Route::prefix('order-import')->group(function () {
    //获取模板
    /**
     * @api {get} /merchant/order-import/template 订单导入模板
     * @apiName 订单导入模板
     * @apiGroup 06orderImport
     * @apiVersion 1.0.0
     * @apiUse auth
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    下载链接
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":{"name":"66f6181bcb4cff4cd38fbc804a036db6.xlsx","path":"tms-api.test\/storage\/admin\/excel\/3\/order\/66f6181bcb4cff4cd38fbc804a036db6.xlsx"},"msg":"successful"}
     */
    Route::get('/template', 'OrderImportController@templateExport')->name('order.import-list');

    /**
     * @api {get} /merchant/order-import 订单导入
     * @apiName 订单导入
     * @apiGroup 06orderImport
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiParam {file} file 表格文件
     * @apiParamExample {json} Param-Response:
     * {"file":"1.xlsx"}
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[{"create_date":"2021-06-10","type":"1","merchant":"\u6b27\u4e9a\u5546\u57ce","out_user_id":"","out_order_no":"","place_fullname":"123123","place_phone":"123123","place_post_code":"2153PJ","place_house_number":"20","place_city":"","place_street":"","execution_date":"2021-06-10","second_place_fullname":"","second_place_phone":"","second_place_post_code":"","second_place_house_number":"","second_place_city":"","second_place_street":"","second_execution_date":"","amount_1":"","amount_2":"","amount_3":"","amount_4":"","amount_5":"","amount_6":"","amount_7":"","amount_8":"","amount_9":"","amount_10":"","amount_11":"","settlement_amount":"","settlement_type":"","control_mode":"","receipt_type":"","receipt_count":"","special_remark":"","mask_code":"","package_no_1":"","package_name_1":"","package_weight_1":"","package_feature_1":"","package_remark_1":"","package_expiration_date_1":"","package_out_order_no_1":"","package_no_2":"","package_name_2":"","package_weight_2":"","package_feature_2":"","package_remark_2":"","package_expiration_date_2":"","package_out_order_no_2":"","package_no_3":"","package_name_3":"","package_weight_3":"","package_feature_3":"","package_remark_3":"","package_expiration_date_3":"","package_out_order_no_3":"","package_no_4":"","package_name_4":"","package_weight_4":"","package_feature_4":"","package_remark_4":"","package_expiration_date_4":"","package_out_order_no_4":"","package_no_5":"","package_name_5":"","package_weight_5":"","package_feature_5":"","package_remark_5":"","package_expiration_date_5":"","package_out_order_no_5":"","material_code_1":"","material_name_1":"","material_count_1":"","material_weight_1":"","material_size_1":"","material_type_1":"","material_pack_type_1":"","material_price_1":"","material_remark_1":"","material_out_order_no_1":"","material_code_2":"","material_name_2":"","material_count_2":"","material_weight_2":"","material_size_2":"","material_type_2":"","material_pack_type_2":"","material_price_2":"","material_remark_2":"","material_out_order_no_2":"","material_code_3":"","material_name_3":"","material_count_3":"","material_weight_3":"","material_size_3":"","material_type_3":"","material_pack_type_3":"","material_price_3":"","material_remark_3":"","material_out_order_no_3":"","material_code_4":"","material_name_4":"","material_count_4":"","material_weight_4":"","material_size_4":"","material_type_4":"","material_pack_type_4":"","material_price_4":"","material_remark_4":"","material_out_order_no_4":"","material_code_5":"","material_name_5":"","material_count_5":"","material_weight_5":"","material_size_5":"","material_type_5":"","material_pack_type_5":"","material_price_5":"","material_remark_5":"","material_out_order_no_5":"","merchant_id":"3","type_name":"\u63d0\u8d27->\u7f51\u70b9","place_country":"NL"}],"msg":"successful"}
     */
    //导入
    Route::post('/', 'OrderImportController@import')->name('order.import-list');
    //检查
    /**
     * @api {get} /merchant/order-import/check 检查
     * @apiName 检查
     * @apiGroup 06orderImport
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiParam {String} list 多个订单数据
     * @apiParam {String} list.create_date
     * @apiParam {String} list.type
     * @apiParam {String} list.merchant
     * @apiParam {String} list.out_user_id
     * @apiParam {String} list.out_order_no
     * @apiParam {String} list.place_fullname
     * @apiParam {String} list.place_phone
     * @apiParam {String} list.place_post_code
     * @apiParam {String} list.place_house_number
     * @apiParam {String} list.place_city
     * @apiParam {String} list.place_street
     * @apiParam {String} list.execution_date
     * @apiParam {String} list.second_place_fullname
     * @apiParam {String} list.second_place_phone
     * @apiParam {String} list.second_place_post_code
     * @apiParam {String} list.second_place_house_number
     * @apiParam {String} list.second_place_city
     * @apiParam {String} list.second_place_street
     * @apiParam {String} list.second_execution_date
     * @apiParam {String} list.amount_1
     * @apiParam {String} list.amount_2
     * @apiParam {String} list.amount_3
     * @apiParam {String} list.amount_4
     * @apiParam {String} list.amount_5
     * @apiParam {String} list.amount_6
     * @apiParam {String} list.amount_7
     * @apiParam {String} list.amount_8
     * @apiParam {String} list.amount_9
     * @apiParam {String} list.amount_10
     * @apiParam {String} list.amount_11
     * @apiParam {String} list.settlement_amount
     * @apiParam {String} list.settlement_type
     * @apiParam {String} list.control_mode
     * @apiParam {String} list.receipt_type
     * @apiParam {String} list.receipt_count
     * @apiParam {String} list.special_remark
     * @apiParam {String} list.mask_code
     * @apiParam {String} list.package_no_1:
     * @apiParam {String} list.package_name_1
     * @apiParam {String} list.package_weight_1
     * @apiParam {String} list.package_feature_1
     * @apiParam {String} list.package_remark_1
     * @apiParam {String} list.package_expiration_date_1
     * @apiParam {String} list.package_out_order_no_1
     * @apiParam {String} list.package_no_2
     * @apiParam {String} list.package_name_2
     * @apiParam {String} list.package_weight_2
     * @apiParam {String} list.package_feature_2
     * @apiParam {String} list.package_remark_2
     * @apiParam {String} list.package_expiration_date_2
     * @apiParam {String} list.package_out_order_no_2
     * @apiParam {String} list.package_no_3
     * @apiParam {String} list.package_name_3
     * @apiParam {String} list.package_weight_3
     * @apiParam {String} list.package_feature_3
     * @apiParam {String} list.package_remark_3
     * @apiParam {String} list.package_expiration_date_3
     * @apiParam {String} list.package_out_order_no_3
     * @apiParam {String} list.package_no_4
     * @apiParam {String} list.package_name_4
     * @apiParam {String} list.package_weight_4
     * @apiParam {String} list.package_feature_4
     * @apiParam {String} list.package_remark_4
     * @apiParam {String} list.package_expiration_date_4
     * @apiParam {String} list.package_out_order_no_4
     * @apiParam {String} list.package_no_5
     * @apiParam {String} list.package_name_5
     * @apiParam {String} list.package_weight_5
     * @apiParam {String} list.package_feature_5
     * @apiParam {String} list.package_remark_5
     * @apiParam {String} list.package_expiration_date_5
     * @apiParam {String} list.package_out_order_no_5
     * @apiParam {String} list.material_code_1
     * @apiParam {String} list.material_name_1
     * @apiParam {String} list.material_count_1
     * @apiParam {String} list.material_weight_1
     * @apiParam {String} list.material_size_1
     * @apiParam {String} list.material_type_1
     * @apiParam {String} list.material_pack_type_1
     * @apiParam {String} list.material_price_1
     * @apiParam {String} list.material_remark_1
     * @apiParam {String} list.material_out_order_no_1
     * @apiParam {String} list.material_code_2
     * @apiParam {String} list.material_name_2
     * @apiParam {String} list.material_count_2
     * @apiParam {String} list.material_weight_2
     * @apiParam {String} list.material_size_2
     * @apiParam {String} list.material_type_2
     * @apiParam {String} list.material_pack_type_2
     * @apiParam {String} list.material_price_2
     * @apiParam {String} list.material_remark_2
     * @apiParam {String} list.material_out_order_no_2
     * @apiParam {String} list.material_code_3
     * @apiParam {String} list.material_name_3
     * @apiParam {String} list.material_count_3
     * @apiParam {String} list.material_weight_3
     * @apiParam {String} list.material_size_3
     * @apiParam {String} list.material_type_3
     * @apiParam {String} list.material_pack_type_3
     * @apiParam {String} list.material_price_3
     * @apiParam {String} list.material_remark_3
     * @apiParam {String} list.material_out_order_no_3
     * @apiParam {String} list.material_code_4
     * @apiParam {String} list.material_name_4
     * @apiParam {String} list.material_count_4
     * @apiParam {String} list.material_weight_4
     * @apiParam {String} list.material_size_4
     * @apiParam {String} list.material_type_4
     * @apiParam {String} list.material_pack_type_4
     * @apiParam {String} list.material_price_4
     * @apiParam {String} list.material_remark_4
     * @apiParam {String} list.material_out_order_no_4
     * @apiParam {String} list.material_code_5
     * @apiParam {String} list.material_name_5
     * @apiParam {String} list.material_count_5
     * @apiParam {String} list.material_weight_5
     * @apiParam {String} list.material_size_5
     * @apiParam {String} list.material_type_5
     * @apiParam {String} list.material_pack_type_5
     * @apiParam {String} list.material_price_5
     * @apiParam {String} list.material_remark_5
     * @apiParam {String} list.material_out_order_no_5
     * @apiParam {String} list.merchant_id
     * @apiParam {String} list.place_country
     * @apiParam {String} list.second_place_country
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccess {String} data.status    状态1-通过2-不通过
     * @apiSuccess {Object} data.error    错误，有错误才会有数据，没有错误返回空数组。
     * @apiSuccess {String} data.error.log 总体错误
     * @apiSuccess {String} data.error.field 字段错误，field代包含所有请求参数字段
     * @apiSuccess {Object} data.data    数据
     * @apiSuccess {String} data.data.place_lat    发件人经度
     * @apiSuccess {String} data.data.place_lon    发件人纬度
     * @apiSuccess {String} data.data.second_place_lat    收件人经度
     * @apiSuccess {String} data.data.second_place_lon    收件人纬度
     * @apiSuccess {String} data.data.count_settlement_amount    预计运价
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":[{"status":2,"error":{"out_order_no":"\u8d27\u53f7 \u5df2\u5b58\u5728"},"data":{"create_date":"2021-06-11","type":"3","merchant":"\u540c\u57ce\u6d3e\u9001","out_user_id":"","out_order_no":"01236","place_fullname":"test","place_phone":"123654791","place_post_code":"1183GT","place_house_number":1,"place_city":"Amstelveen","place_street":"Straat van Gibraltar","execution_date":"2021-06-11","second_place_fullname":"ANN","second_place_phone":"636985219","second_place_post_code":"1183GT","second_place_house_number":11,"second_place_city":"Amstelveen","second_place_street":"Straat van Gibraltar","second_execution_date":"2021-06-11","amount_1":"","amount_2":"","amount_3":"","amount_4":"","amount_5":"","amount_6":"","amount_7":"","amount_8":"","amount_9":"","amount_10":"","amount_11":"","settlement_amount":"5.00","settlement_type":"","control_mode":"","receipt_type":"","receipt_count":"","special_remark":"","mask_code":"","package_no_1":"10171","package_name_1":"","package_weight_1":"","package_feature_1":"","package_remark_1":"","package_expiration_date_1":"","package_out_order_no_1":"","package_no_2":"","package_name_2":"","package_weight_2":"","package_feature_2":"","package_remark_2":"","package_expiration_date_2":"","package_out_order_no_2":"","package_no_3":"","package_name_3":"","package_weight_3":"","package_feature_3":"","package_remark_3":"","package_expiration_date_3":"","package_out_order_no_3":"","package_no_4":"","package_name_4":"","package_weight_4":"","package_feature_4":"","package_remark_4":"","package_expiration_date_4":"","package_out_order_no_4":"","package_no_5":"","package_name_5":"","package_weight_5":"","package_feature_5":"","package_remark_5":"","package_expiration_date_5":"","package_out_order_no_5":"","material_code_1":"","material_name_1":"","material_count_1":"","material_weight_1":"","material_size_1":"","material_type_1":"","material_pack_type_1":"","material_price_1":"","material_remark_1":"","material_out_order_no_1":"","material_code_2":"","material_name_2":"","material_count_2":"","material_weight_2":"","material_size_2":"","material_type_2":"","material_pack_type_2":"","material_price_2":"","material_remark_2":"","material_out_order_no_2":"","material_code_3":"","material_name_3":"","material_count_3":"","material_weight_3":"","material_size_3":"","material_type_3":"","material_pack_type_3":"","material_price_3":"","material_remark_3":"","material_out_order_no_3":"","material_code_4":"","material_name_4":"","material_count_4":"","material_weight_4":"","material_size_4":"","material_type_4":"","material_pack_type_4":"","material_price_4":"","material_remark_4":"","material_out_order_no_4":"","material_code_5":"","material_name_5":"","material_count_5":"","material_weight_5":"","material_size_5":"","material_type_5":"","material_pack_type_5":"","material_price_5":"","material_remark_5":"","material_out_order_no_5":"","merchant_id":"121","type_name":"\u63d0\u8d27->\u7f51\u70b9->\u914d\u9001","place_country":"NL","second_place_country":"NL","place_address":"NL Amstelveen Straat van Gibraltar 1 1183GT","second_place_address":"NL Groningen Groningen 11 1183GT","place_province":"Noord-Holland","place_district":"Amstelveen","place_lat":52.31153637,"place_lon":4.87465697,"second_place_province":"Noord-Holland","second_place_district":"Amstelveen","second_place_lat":52.31153083,"second_place_lon":4.87510019,"distance":1,"count_settlement_amount":"5.00","package_settlement_amount":"0.00","starting_price":"5.00","transport_price_id":71,"transport_price_type":2}}],"msg":"successful"}     */
    Route::post('/check', 'OrderImportController@importCheck')->name('order.import-list');
    //批量新增
    /**
     * @api {post} /merchant/order-import/list 批量新增
     * @apiName 批量新增
     * @apiGroup 06orderImport
     * @apiVersion 1.0.0
     * @apiUse auth
     * @apiParam {String} list 多个订单数据
     * @apiParam {String} list.create_date
     * @apiParam {String} list.type
     * @apiParam {String} list.merchant
     * @apiParam {String} list.out_user_id
     * @apiParam {String} list.out_order_no
     * @apiParam {String} list.place_fullname
     * @apiParam {String} list.place_phone
     * @apiParam {String} list.place_post_code
     * @apiParam {String} list.place_house_number
     * @apiParam {String} list.place_city
     * @apiParam {String} list.place_street
     * @apiParam {String} list.execution_date
     * @apiParam {String} list.second_place_fullname
     * @apiParam {String} list.second_place_phone
     * @apiParam {String} list.second_place_post_code
     * @apiParam {String} list.second_place_house_number
     * @apiParam {String} list.second_place_city
     * @apiParam {String} list.second_place_street
     * @apiParam {String} list.second_execution_date
     * @apiParam {String} list.amount_1
     * @apiParam {String} list.amount_2
     * @apiParam {String} list.amount_3
     * @apiParam {String} list.amount_4
     * @apiParam {String} list.amount_5
     * @apiParam {String} list.amount_6
     * @apiParam {String} list.amount_7
     * @apiParam {String} list.amount_8
     * @apiParam {String} list.amount_9
     * @apiParam {String} list.amount_10
     * @apiParam {String} list.amount_11
     * @apiParam {String} list.settlement_amount
     * @apiParam {String} list.settlement_type
     * @apiParam {String} list.control_mode
     * @apiParam {String} list.receipt_type
     * @apiParam {String} list.receipt_count
     * @apiParam {String} list.special_remark
     * @apiParam {String} list.mask_code
     * @apiParam {String} list.package_no_1
     * @apiParam {String} list.package_name_1
     * @apiParam {String} list.package_weight_1
     * @apiParam {String} list.package_feature_1
     * @apiParam {String} list.package_remark_1
     * @apiParam {String} list.package_expiration_date_1
     * @apiParam {String} list.package_out_order_no_1
     * @apiParam {String} list.package_no_2
     * @apiParam {String} list.package_name_2
     * @apiParam {String} list.package_weight_2
     * @apiParam {String} list.package_feature_2
     * @apiParam {String} list.package_remark_2
     * @apiParam {String} list.package_expiration_date_2
     * @apiParam {String} list.package_out_order_no_2
     * @apiParam {String} list.package_no_3
     * @apiParam {String} list.package_name_3
     * @apiParam {String} list.package_weight_3
     * @apiParam {String} list.package_feature_3
     * @apiParam {String} list.package_remark_3
     * @apiParam {String} list.package_expiration_date_3
     * @apiParam {String} list.package_out_order_no_3
     * @apiParam {String} list.package_no_4
     * @apiParam {String} list.package_name_4
     * @apiParam {String} list.package_weight_4
     * @apiParam {String} list.package_feature_4
     * @apiParam {String} list.package_remark_4
     * @apiParam {String} list.package_expiration_date_4
     * @apiParam {String} list.package_out_order_no_4
     * @apiParam {String} list.package_no_5
     * @apiParam {String} list.package_name_5
     * @apiParam {String} list.package_weight_5
     * @apiParam {String} list.package_feature_5
     * @apiParam {String} list.package_remark_5
     * @apiParam {String} list.package_expiration_date_5
     * @apiParam {String} list.package_out_order_no_5
     * @apiParam {String} list.material_code_1
     * @apiParam {String} list.material_name_1
     * @apiParam {String} list.material_count_1
     * @apiParam {String} list.material_weight_1
     * @apiParam {String} list.material_size_1
     * @apiParam {String} list.material_type_1
     * @apiParam {String} list.material_pack_type_1
     * @apiParam {String} list.material_price_1
     * @apiParam {String} list.material_remark_1
     * @apiParam {String} list.material_out_order_no_1
     * @apiParam {String} list.material_code_2
     * @apiParam {String} list.material_name_2
     * @apiParam {String} list.material_count_2
     * @apiParam {String} list.material_weight_2
     * @apiParam {String} list.material_size_2
     * @apiParam {String} list.material_type_2
     * @apiParam {String} list.material_pack_type_2
     * @apiParam {String} list.material_price_2
     * @apiParam {String} list.material_remark_2
     * @apiParam {String} list.material_out_order_no_2
     * @apiParam {String} list.material_code_3
     * @apiParam {String} list.material_name_3
     * @apiParam {String} list.material_count_3
     * @apiParam {String} list.material_weight_3
     * @apiParam {String} list.material_size_3
     * @apiParam {String} list.material_type_3
     * @apiParam {String} list.material_pack_type_3
     * @apiParam {String} list.material_price_3
     * @apiParam {String} list.material_remark_3
     * @apiParam {String} list.material_out_order_no_3
     * @apiParam {String} list.material_code_4
     * @apiParam {String} list.material_name_4
     * @apiParam {String} list.material_count_4
     * @apiParam {String} list.material_weight_4
     * @apiParam {String} list.material_size_4
     * @apiParam {String} list.material_type_4
     * @apiParam {String} list.material_pack_type_4
     * @apiParam {String} list.material_price_4
     * @apiParam {String} list.material_remark_4
     * @apiParam {String} list.material_out_order_no_4
     * @apiParam {String} list.material_code_5
     * @apiParam {String} list.material_name_5
     * @apiParam {String} list.material_count_5
     * @apiParam {String} list.material_weight_5
     * @apiParam {String} list.material_size_5
     * @apiParam {String} list.material_type_5
     * @apiParam {String} list.material_pack_type_5
     * @apiParam {String} list.material_price_5
     * @apiParam {String} list.material_remark_5
     * @apiParam {String} list.material_out_order_no_5
     * @apiParam {String} list.merchant_id
     * @apiParam {String} list.place_country
     * @apiParam {String} list.second_place_country
     *
     * @apiSuccess {Number} code    状态码，200：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSuccessExample {json} Success-Response:
     * {"code":200,"data":"","msg":"successful"}
     */
    Route::post('/list', 'OrderImportController@createByList')->name('order.import-list');
});

