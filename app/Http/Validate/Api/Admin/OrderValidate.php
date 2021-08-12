<?php
/**
 * 订单 验证类
 * Created by PhpStorm
 * User: place_long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class OrderValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'merchant_id' => 'required|integer',
        'batch_no' => 'nullable|string|max:50',
        'out_order_no' => 'nullable|string|max:50',
        'mask_code' => "nullable|string|max:50",
        'execution_date' => 'nullable|date|after_or_equal:today',
        'second_execution_date' => 'nullable|date|after_or_equal:today',
        'list_mode' => 'nullable',
        'type' => 'required|integer|in:1,2,3,4',
        'out_user_id' => 'nullable|integer',
        'nature' => 'nullable|integer|in:1,2,3,4,5',
        'settlement_type' => 'nullable|in:1,2,3,4,5',
        'settlement_amount' => 'nullable|required_if:settlement_type,2|numeric|gte:0',
        'expect_total_amount' => 'nullable|required_if:settlement_type,2|numeric|gte:0',
        'actual_total_amount' => 'nullable|required_if:settlement_type,2|numeric|gte:0',
        'replace_amount' => 'nullable|numeric|gte:0',
        'delivery' => 'nullable|integer|in:1,2',
        'place_country' => 'required|string|max:50',
        'place_fullname' => 'required|string|max:50',
        'place_phone' => 'required|max:20|regex:/^[0-9 ]([0-9- ])*[0-9 ]$/',
        'place_province' => 'nullable|string|max:50',
        'place_post_code' => 'required|string|max:50',
        'place_house_number' => 'required|string|max:50',
        'place_city' => 'required|string|max:50',
        'place_district' => 'nullable|string|max:50',
        'place_street' => 'required|string|max:50',
        'place_address' => 'checkAddress|nullable|string|max:250',
        'place_lon' => 'required_unless:type,2|string|max:50',
        'place_lat' => 'required_unless:type,2|string|max:50',
        'second_place_country' => 'required_if:type,3|string|max:50',
        'second_place_fullname' => 'required_if:type,3|string|max:50',
        'second_place_phone' => 'required_if:type,3|string|max:20|regex:/^[0-9 ]([0-9- ])*[0-9 ]$/',
        'second_place_province' => 'nullable|string|max:50',
        'second_place_post_code' => 'required_if:type,3|string|max:50',
        'second_place_house_number' => 'required_if:type,3|string|max:50',
        'second_place_city' => 'required_if:type,3|string|max:50',
        'second_place_district' => 'nullable|string|max:50',
        'second_place_street' => 'required_if:type,3|string|max:50',
        'second_place_address' => 'checkAddress|nullable|string|max:250',
        'second_place_lon' => 'required_unless:type,1|string|max:50',
        'second_place_lat' => 'required_unless:type,1|string|max:50',
        'special_remark' => 'nullable|string|max:250',
        'remark' => 'nullable|string|max:250',
        'out_status' => 'sometimes|integer|in:1,2',
        //包裹列表
        'package_list.*.name' => 'nullable|string|max:50',
        'package_list.*.weight' => 'nullable|numeric|gte:0',
        'package_list.*.expect_quantity' => 'required_with:package_list|integer|gte:0',
        'package_list.*.remark' => 'nullable|string|max:250',
        'package_list.*.out_order_no' => 'nullable|string|max:50',
        'package_list.*.express_first_no' => 'required_with:package_list|string|max:50|regex:/^[0-9a-zA-Z-]([0-9a-zA-Z-])*[0-9a-zA-Z-]$/',
        'package_list.*.express_second_no' => 'nullable|string|max:50',
        'package_list.*.is_auth' => 'sometimes|integer|in:1,2',
        'package_list.*.expiration_date' => 'nullable|date|',
        //材料列表
        'material_list.*.name' => 'nullable|string|max:50',
        'material_list.*.code' => 'required_with:material_list|string|max:50',
        'material_list.*.out_order_no' => 'nullable|string|max:50',
        'material_list.*.expect_quantity' => 'required_with:material_list|integer|gte:0',
        'material_list.*.remark' => 'nullable|string|max:250',
        'distance' => 'nullable|integer|gte:0',
        'id_list' => 'required|string|checkIdList:100',
        'tour_no' => 'nullable|string|max:50',
        'receipt_type' => 'nullable|integer|in:1',
        'receipt_count' => 'nullable|integer|gte:0',
        'create_date' => 'nullable|date',

        'amount_list.*.expect_amount' => 'required_with:amount_list|gte:0',
        'amount_list.*.type' => 'required_with:amount_list|integer|in:1,2,3,4,5,6,7,8,9,10,11',

    ];

    public $scene = [
        'store' => [
            'merchant_id', 'execution_date', 'second_execution_date',
            'out_order_no', 'mask_code', 'list_mode', 'type', 'out_user_id', 'nature', 'settlement_type', 'settlement_amount', 'replace_amount', 'delivery', 'expect_total_amount', 'actual_amount',
            //发货人信息
            'second_place_fullname', 'second_place_phone', 'second_place_country', 'second_place_post_code', 'second_place_house_number',
            'second_place_city', 'second_place_street', 'second_place_address', 'second_place_lon', 'second_place_lat', 'second_place_country',
            //收货人信息
            'place_fullname', 'place_phone', 'place_country', 'place_post_code', 'place_house_number',
            'place_city', 'place_street', 'place_address', 'place_lon', 'place_lat', 'place_country',
            //备注
            'special_remark', 'remark',
            //包裹列表
            'package_list.*.name', 'package_list.*.weight', 'package_list.*.expect_quantity', 'package_list.*.remark', 'package_list.*.out_order_no', 'package_list.*.express_first_no', 'package_list.*.express_second_no', 'package_list.*.expiration_date',
            //材料列表
            'material_list.*.name', 'material_list.*.code', 'material_list.*.out_order_no', 'material_list.*.expect_quantity', 'material_list.*.remark',
            'receipt_type', 'receipt_count', 'create_date', 'amount_list.*.expect_amount', 'amount_list.*.type'
        ],
        'update' => [
            'merchant_id', 'execution_date', 'second_execution_date', 'expect_total_amount', 'actual_amount',
            'out_order_no', 'mask_code', 'list_mode', 'type', 'out_user_id', 'nature', 'settlement_type', 'settlement_amount', 'replace_amount', 'delivery',
            //发货人信息
            'second_place_fullname', 'second_place_phone', 'second_place_country', 'second_place_post_code', 'second_place_house_number',
            'second_place_city', 'second_place_street', 'second_place_address', 'second_place_lon', 'second_place_lat', 'second_place_country',
            //收货人信息
            'place_fullname', 'place_phone', 'place_post_code', 'place_house_number',
            'place_city', 'place_street', 'place_address', 'place_lon', 'place_lat', 'place_country',
            //备注
            'special_remark', 'remark',
            //包裹列表
            'package_list.*.name', 'package_list.*.weight', 'package_list.*.expect_quantity', 'package_list.*.remark', 'package_list.*.out_order_no', 'package_list.*.express_first_no', 'package_list.*.express_second_no', 'package_list.*.expiration_date',
            //材料列表
            'material_list.*.name', 'material_list.*.code', 'material_list.*.out_order_no', 'material_list.*.expect_quantity', 'material_list.*.remark',
            'receipt_type', 'receipt_count', 'create_date', 'amount_list.*.expect_amount', 'amount_list.*.type'
        ],
        'again' => [
            'execution_date',
        ],
        'recovery' => ['execution_date'],
        'destroy' => ['remark'],
        'destroyAll' => ['id_list'],
        'orderPrintAll' => ['id_list'],
        //'orderExport'=>['id_list']
        'synchronizeStatusList' => ['id_list'],
        'priceCount' => ['distance',
            'merchant_id', 'execution_date', 'second_execution_date',
            'out_order_no', 'mask_code', 'list_mode', 'type', 'out_user_id', 'nature', 'settlement_type', 'settlement_amount', 'replace_amount', 'delivery', 'expect_total_amount', 'actual_amount',
            //发货人信息
            'second_place_fullname', 'second_place_phone', 'second_place_country', 'second_place_post_code', 'second_place_house_number',
            'second_place_city', 'second_place_street', 'second_place_address', 'second_place_lon', 'second_place_lat', 'second_place_country',
            //收货人信息
            'place_fullname', 'place_phone', 'place_country', 'place_post_code', 'place_house_number',
            'place_city', 'place_street', 'place_address', 'place_lon', 'place_lat', 'place_country',
            //备注
            'special_remark', 'remark',
            //包裹列表
            'package_list.*.name', 'package_list.*.weight', 'package_list.*.expect_quantity', 'package_list.*.remark', 'package_list.*.out_order_no', 'package_list.*.express_first_no', 'package_list.*.express_second_no', 'package_list.*.expiration_date',
            //材料列表
            'material_list.*.name', 'material_list.*.code', 'material_list.*.out_order_no', 'material_list.*.expect_quantity', 'material_list.*.remark', 'amount_list.*.expect_amount', 'amount_list.*.type'
        ],
        'getWareHouse' => 'place_country', 'place_post_code', 'execution_date', 'type', 'merchant_id'
    ];

    public $message = [
    ];
}

