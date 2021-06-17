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
 * @apiDefine 01order 订单管理
 */

/**
 * @api {post} /merchant_api/order 订单新增
 * @apiName 订单新增
 * @apiGroup 01order
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {String} order_no 订单号
 * @apiParam {String} execution_date 取派日期
 * @apiParam {String} second_execution_date 取派日期
 * @apiParam {String} create_date 开单日期
 * @apiParam {String} out_order_no 外部订单号
 * @apiParam {String} mask_code 掩码
 * @apiParam {String} source 来源
 * @apiParam {String} source_name 来源名称
 * @apiParam {String} type 类型:1-取2-派3-取派
 * @apiParam {String} out_user_id 外部客户ID
 * @apiParam {String} nature 性质:1-包裹2-材料3-文件4-增值服务5-其他
 * @apiParam {String} settlement_type 结算类型1-寄付2-到付
 * @apiParam {String} settlement_amount 结算金额
 * @apiParam {String} replace_amount 代收货款
 * @apiParam {String} status 状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站
 * @apiParam {String} second_place_fullname 收件人姓名
 * @apiParam {String} second_place_phone 收件人电话
 * @apiParam {String} second_place_country 收件人国家
 * @apiParam {String} second_place_country_name 收件人国家名称
 * @apiParam {String} second_place_post_code 收件人邮编
 * @apiParam {String} second_place_house_number 收件人门牌号
 * @apiParam {String} second_place_city 收件人城市
 * @apiParam {String} second_place_street 收件人街道
 * @apiParam {String} second_place_address 收件人详细地址
 * @apiParam {String} place_fullname 发件人姓名
 * @apiParam {String} place_phone 发件人电话
 * @apiParam {String} place_country 发件人国家
 * @apiParam {String} place_country_name 发件人国家名称
 * @apiParam {String} place_province 发件人省份
 * @apiParam {String} place_post_code 发件人邮编
 * @apiParam {String} place_house_number 发件人门牌号
 * @apiParam {String} place_city 发件人城市
 * @apiParam {String} place_district 发件人区县
 * @apiParam {String} place_street 发件人街道
 * @apiParam {String} place_address 发件人详细地址
 * @apiParam {String} special_remark 特殊事项
 * @apiParam {String} remark 备注
 * @apiParam {String} starting_price 起步价
 * @apiParam {String} transport_price_type 运价方案ID
 * @apiParam {String} receipt_type 回单要求
 * @apiParam {String} receipt_type_name 回单要求名称
 * @apiParam {String} receipt_count 回单数量
 * @apiParam {Object} package_list 包裹列表
 * @apiParam {String} package_list.expiration_date 有效日期
 * @apiParam {String} package_list.name 包裹名称
 * @apiParam {String} package_list.express_first_no 快递单号1
 * @apiParam {String} package_list.express_second_no 快递单号2
 * @apiParam {String} package_list.feature_logo 特性标志
 * @apiParam {String} package_list.out_order_no 外部标识
 * @apiParam {String} package_list.weight 重量
 * @apiParam {String} package_list.size 重量
 * @apiParam {String} package_list.actual_weight 实际重量
 * @apiParam {String} package_list.expect_quantity 预计数量
 * @apiParam {String} package_list.actual_quantity 实际数量
 * @apiParam {String} package_list.sticker_no 贴单号
 * @apiParam {String} package_list.settlement_amount 结算金额
 * @apiParam {String} package_list.count_settlement_amount 估算运费
 * @apiParam {String} package_list.sticker_amount 贴单费用
 * @apiParam {String} package_list.delivery_amount 提货费用
 * @apiParam {String} package_list.remark 备注
 * @apiParam {String} package_list.is_auth 是否需要身份验证1-是2-否
 * @apiParam {String} package_list.auth_fullname 身份人姓名
 * @apiParam {String} package_list.auth_birth_date 身份人出身年月
 * @apiParam {Object} material_list 材料列表
 * @apiParam {String} material_list.execution_date 取派日期
 * @apiParam {String} material_list.name 材料名称
 * @apiParam {String} material_list.code 材料代码
 * @apiParam {String} material_list.out_order_no 外部标识
 * @apiParam {String} material_list.expect_quantity 预计数量
 * @apiParam {String} material_list.actual_quantity 实际数量
 * @apiParam {String} material_list.pack_type 包装类型
 * @apiParam {String} material_list.type 类型
 * @apiParam {String} material_list.weight 重量
 * @apiParam {String} material_list.size 体积
 * @apiParam {String} material_list.remark 备注
 * @apiParam {Object} amount_list 费用列表
 * @apiParam {String} amount_list.id 费用ID
 * @apiParam {String} amount_list.expect_amount 预计金额
 * @apiParam {String} amount_list.actual_amount 实际金额
 * @apiParam {String} amount_list.type 运费类型
 * @apiParam {String} amount_list.remark 备注
 * @apiSuccess {Number} code    状态码，200：请求成功
 * @apiSuccess {String} msg   提示信息
 * @apiSuccess {Object} data    返回数据
 * @apiSuccess {String} data.id    ID
 * @apiSuccess {String} data.order_no    订单号
 * @apiSuccess {String} data.out_order_no    外部订单号
 * @apiSuccessExample {json} Success-Response:
 * {"code":200,"data":{"id":4207,"order_no":"SMAAAEM0001","out_order_no":"DEVV21904566802"},"msg":"successful"}
 */
//新增
Route::post('order', 'OrderController@store')->name('merchant_api.order.store');//新增订单

/**
 * @api {post} /merchant/order-cancel 删除订单
 * @apiName 删除订单
 * @apiGroup 01order
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiDescription 订单状态分为1-待受理2-取派中3-已完成4-取派失败5-回收站，删除订单功能只有订单在待受理状态才能使用。
 * @apiParam {String} order_no 订单号
 * @apiParam {String} remark 备注
 * @apiParam {String} no_push 是否推送1-是，货主通过该API删除订单，不会通知货主该订单已删除。2-否，货主通过该API删除订单，仍会通知货主订单已删除。
 * @apiSuccess {Number} code    状态码，200：请求成功
 * @apiSuccess {String} msg   提示信息
 * @apiSuccess {Object} data    返回数据
 * @apiSuccess {String} data.data1    返回数据
 * @apiSuccessExample {json} Success-Response:
 * {"code":200,"data":[],"msg":"successful"}
 */
Route::post('cancel-order', 'OrderController@destroy');//删除订单

Route::post('order-update-address', 'OrderController@updateAddressDate');//修改订单地址日期
Route::post('cancel-all-order', 'OrderController@destroyAll');//批量删除订单
Route::post('order-out-status', 'OrderController@updateOutStatus');//出库

/**
 * @api {post} /merchant_api/post-code-date-list 通过地址获取可选日期
 * @apiName 通过地址获取可选日期
 * @apiGroup 01order
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiDescription 地址模板为一时，经纬度必填；地址模板为二时，邮编必填。
 * @apiParam {String} id 订单ID
 * @apiParam {String} type 类型1-取件2-派件
 * @apiParam {String} place_lon 经度
 * @apiParam {String} place_lat 纬度
 * @apiParam {String} place_post_code 邮编
 *
 * @apiSuccess {Number} code    状态码，200：请求成功
 * @apiSuccess {String} msg   提示信息
 * @apiSuccess {Object} data    返回数据
 * @apiSuccessExample {json} Success-Response:
 * {"code":200,"data":["2021-06-11","2021-06-13","2021-06-16","2021-06-18","2021-06-20"],"msg":"successful"}
 */
Route::post('post-code-date-list', 'LineController@getDateListByPostCode');//获取可选日期


/**
 * @api {get} /merchant/order/get-date 物流查询
 * @apiName 物流查询
 * @apiGroup 01order
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {String} id 订单ID
 * @apiParam {String} type 类型1-取件2-派件
 * @apiParam {String} place_lon 经度
 * @apiParam {String} place_lat 纬度
 * @apiParam {String} place_post_code 邮编
 *
 * @apiSuccess {Number} code    状态码，200：请求成功
 * @apiSuccess {String} msg   提示信息
 * @apiSuccess {Object} data    返回数据
 * @apiSuccessExample {json} Success-Response:
 * {"code":200,"data":["2021-06-11","2021-06-13","2021-06-16","2021-06-18","2021-06-20"],"msg":"successful"}
 */
Route::post('order-dispatch-info', 'OrderController@getOrderDispatchInfo');//派送情况
Route::post('order-update-phone-date', 'OrderController@updateByApi');//修改订单
Route::post('order-update-phone-date-list', 'OrderController@updateByApiList');//修改订单
Route::post('package-info', 'PackageController@showByApi');//包裹查询
Route::post('order-info', 'OrderController@showByApi');//订单查询
Route::post('update-order-item-list', 'OrderController@updateItemList');//修改明细
Route::post('/again-order-info', 'OrderController@getAgainInfo');//获取继续派送(再次取派)信息

/**
 * @api {get} /merchant/order/again-order 继续派送
 * @apiName 继续派送
 * @apiGroup 01order
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {String} id 订单ID
 * @apiParam {String} type 类型1-取件2-派件
 * @apiParam {String} place_lon 经度
 * @apiParam {String} place_lat 纬度
 * @apiParam {String} place_post_code 邮编
 *
 * @apiSuccess {Number} code    状态码，200：请求成功
 * @apiSuccess {String} msg   提示信息
 * @apiSuccess {Object} data    返回数据
 * @apiSuccessExample {json} Success-Response:
 * {"code":200,"data":["2021-06-11","2021-06-13","2021-06-16","2021-06-18","2021-06-20"],"msg":"successful"}
 */
Route::post('/again-order', 'OrderController@again'); //继续派送(再次取派)
/**
 * @api {get} /merchant/order/get-date 物流查询
 * @apiName 物流查询
 * @apiGroup 01order
 * @apiVersion 1.0.0
 * @apiUse auth
 * @apiParam {String} id 订单ID
 * @apiParam {String} type 类型1-取件2-派件
 * @apiParam {String} place_lon 经度
 * @apiParam {String} place_lat 纬度
 * @apiParam {String} place_post_code 邮编
 *
 * @apiSuccess {Number} code    状态码，200：请求成功
 * @apiSuccess {String} msg   提示信息
 * @apiSuccess {Object} data    返回数据
 * @apiSuccessExample {json} Success-Response:
 * {"code":200,"data":["2021-06-11","2021-06-13","2021-06-16","2021-06-18","2021-06-20"],"msg":"successful"}
 */
Route::post('/end-order', 'OrderController@end');//终止派送
Route::post('/order-update-second-date', 'OrderController@updateSecondDate');//修改派送日期
//    Route::post('/get-all-line-range', 'LineController@getAllLineRange');//获取所有邮编
