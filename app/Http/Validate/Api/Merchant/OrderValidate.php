<?php
/**
 * 订单 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Merchant;

use App\Http\Validate\BaseValidate;

class OrderValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'batch_no' => 'nullable|string|max:50',
        'out_group_order_no' => 'nullable|string|max:50',
        'out_order_no' => 'nullable|string|max:50',
        'execution_date' => 'nullable|date|after_or_equal:today',
        'second_execution_date' => 'nullable|date|after_or_equal:today',
        'type' => 'required|integer|in:1,2,3',
        'out_user_id' => 'nullable|integer',
        'mask_code' => 'nullable|string|max:50',
        'nature' => 'nullable|integer|in:1,2,3,4,5',
        'settlement_type' => 'required|in:1,2,3,4,5',
        'settlement_amount' => 'nullable|numeric|gte:0',
        'replace_amount' => 'nullable|numeric|gte:0',
        'delivery' => 'nullable|integer|in:1,2',
        'place_fullname' => 'required|string|max:50',
        'place_phone' => 'required|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
        'place_province' => 'nullable|string|max:50',
        'place_post_code' => 'required|string|max:50',
        'place_house_number' => 'required|string|max:50',
        'place_city' => 'required|string|max:50',
        'place_district' => 'nullable|string|max:50',
        'place_street' => 'required|string|max:50',
        'place_address' => 'checkAddress|nullable|string|max:250',
        'place_lon' => 'nullable|string|max:50',
        'place_lat' => 'nullable|string|max:50',
        'second_place_fullname' => 'required_if:type,3|string|max:50',
        'second_place_phone' => 'required_if:type,3|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
        'second_place_province' => 'nullable|string|max:50',
        'second_place_post_code' => 'required_if:type,3|string|max:50',
        'second_place_house_number' => 'required_if:type,3|string|max:50',
        'second_place_city' => 'required_if:type,3|string|max:50',
        'second_place_district' => 'nullable|string|max:50',
        'second_place_street' => 'required_if:type,3|string|max:50',
        'second_place_address' => 'checkAddress|nullable|string|max:250',
        'second_place_lon' => 'nullable|string|max:50',
        'second_place_lat' => 'nullable|string|max:50',
        'special_remark' => 'nullable|string|max:250',
        'remark' => 'nullable|string|max:250',
        'out_status' => 'sometimes|integer|in:1,2',
        //包裹列表
        'package_list.*.name' => 'nullable|string|max:50',
        'package_list.*.weight' => 'nullable|numeric|gte:0',
        'package_list.*.expect_quantity' => 'required_with:package_list|integer|gte:0',
        'package_list.*.remark' => 'nullable|string|max:250',
        'package_list.*.out_order_no' => 'nullable|string|max:50',
        'package_list.*.express_first_no' => 'required_with:package_list|string|max:50|regex:/^[0-9a-zA-Z]([0-9a-zA-Z-])*[0-9a-zA-Z]$/',
        'package_list.*.express_second_no' => 'nullable|string|max:50',
        'package_list.*.is_auth' => 'sometimes|integer|in:1,2',
        'package_list.*.expiration_date'=>'nullable|date|',
        //材料列表
        'material_list.*.name' => 'nullable|string|max:50',
        'material_list.*.code' => 'required_with:material_list|string|max:50',
        'material_list.*.out_order_no' => 'nullable|string|max:50',
        'material_list.*.expect_quantity' => 'required_with:material_list|integer|gte:0',
        'material_list.*.remark' => 'nullable|string|max:250',

        'order_no' => 'nullable|string|max:50',
        'order_no_list' => 'required|string',

        'amount_list.*.expect_amount' => 'required_with:amount_list|gte:0',
        'amount_list.*.type' => 'required_with:amount_list|integer|in:1,2,3,4,5,6,7,8,9,10,11',
    ];

    public $scene = [
        'store' => [
            'merchant_id', 'execution_date', 'second_execution_date',
            'out_group_order_no', 'out_order_no', 'list_mode', 'type', 'out_user_id', 'nature', 'settlement_type', 'settlement_amount', 'replace_amount', 'delivery',
            //发货人信息
            'second_place_fullname', 'second_place_phone', 'second_place_country', 'second_place_post_code', 'second_place_house_number',
            'second_place_city', 'second_place_street', 'second_place_address', 'second_place_lon', 'second_place_lat',
            //收货人信息
            'place_fullname', 'place_phone', 'place_country', 'place_post_code', 'place_house_number',
            'place_city', 'place_street', 'place_address', 'place_lon', 'place_lat',
            //备注
            'special_remark', 'remark',
            //包裹列表
            'package_list.*.name', 'package_list.*.weight', 'package_list.*.expect_quantity', 'package_list.*.remark', 'package_list.*.out_order_no', 'package_list.*.express_first_no', 'package_list.*.express_second_no','package_list.*.expiration_date',
            //材料列表
            'material_list.*.name', 'material_list.*.code', 'material_list.*.out_order_no', 'material_list.*.expect_quantity', 'material_list.*.remark',
            'amount_list.*.expect_amount','amount_list.*.type',
        ],
        'update' => [
            'merchant_id', 'execution_date', 'second_execution_date', 'mask_code',
            'out_group_order_no', 'out_order_no', 'list_mode', 'type', 'out_user_id', 'nature', 'settlement_type', 'settlement_amount', 'replace_amount', 'delivery',
            //发货人信息
            'second_place_fullname', 'second_place_phone', 'second_place_country', 'second_place_post_code', 'second_place_house_number',
            'second_place_city', 'second_place_street', 'second_place_address', 'second_place_lon', 'second_place_lat',
            //收货人信息
            'place_fullname', 'place_phone', 'place_post_code', 'place_house_number',
            'place_city', 'place_street', 'place_address', 'place_lon', 'place_lat',
            //备注
            'special_remark', 'remark',
            //包裹列表
            'package_list.*.name', 'package_list.*.weight', 'package_list.*.expect_quantity', 'package_list.*.remark', 'package_list.*.out_order_no', 'package_list.*.express_first_no', 'package_list.*.express_second_no','package_list.*.expiration_date',
            //材料列表
            'material_list.*.name', 'material_list.*.code', 'material_list.*.out_order_no', 'material_list.*.expect_quantity', 'material_list.*.remark',
            'amount_list.*.expect_amount','amount_list.*.type',
        ],
        'updateSecondDate' => ['second_execution_date'],
        'updateAddressDate' => ['place_fullname', 'place_phone', 'place_country', 'place_post_code', 'place_house_number', 'place_city', 'place_street','order_no','execution_date'],
        'recovery' => ['execution_date'],
        'destroy' => ['remark'],
        'destroyAll' => ['order_no_list'],
        'agign' => ['execution_date'],
        'updateOutStatus' => ['order_no', 'out_status'],
        'getDateListByPostCode' => ['place_post_code'],
        'updateByApiList' => ['order_no_list'],
        'assignToBatch' => ['execution_date', 'batch_no'],
        'updateItemList' => [
            //包裹列表
            'package_list.*.name', 'package_list.*.weight', 'package_list.*.expect_quantity', 'package_list.*.remark', 'package_list.*.out_order_no', 'package_list.*.express_first_no', 'package_list.*.express_second_no',
            //材料列表
            'material_list.*.name', 'material_list.*.code', 'material_list.*.out_order_no', 'material_list.*.expect_quantity', 'material_list.*.remark']
    ];
    public $message = [
        'settlement_amount.required_if' => '当结算方式为到付时,:attribute字段必填',
    ];
}

