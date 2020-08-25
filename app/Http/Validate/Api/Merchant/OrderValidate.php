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
        'out_order_no' => 'nullable|string|max:50',
        'execution_date' => 'required|date|after_or_equal:today',
        'list_mode' => 'sometimes|required|in:1,2',
        'type' => 'required|integer|in:1,2',
        'out_user_id' => 'nullable|integer',
        'mask_code' => 'nullable|string|max:50',
        'nature' => 'nullable|integer|in:1,2,3,4,5',
        'settlement_type' => 'required|in:1,2',
        'settlement_amount' => 'nullable|required_if:settlement_type,2|numeric|gte:0',
        'replace_amount' => 'nullable|numeric|gte:0',
        'delivery' => 'nullable|integer|in:1,2',
        'receiver_fullname' => 'required|string|max:50',
        'receiver_phone' => 'required|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
        'receiver_post_code' => 'required|string|max:50',
        'receiver_house_number' => 'required|string|max:50',
        'receiver_city' => 'required|string|max:50',
        'receiver_street' => 'required|string|max:50',
        'receiver_address' => 'checkAddress|nullable|string|max:250',
        'lon' => 'nullable|string|max:50',
        'lat' => 'nullable|string|max:50',
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
        //材料列表
        'material_list.*.name' => 'nullable|string|max:50',
        'material_list.*.code' => 'required_with:material_list|string|max:50',
        //'material_list.*.code' => 'required_with:material_list|string|max:50',
        'material_list.*.out_order_no' => 'nullable|string|max:50',
        'material_list.*.expect_quantity' => 'required_with:material_list|integer|gte:0',
        'material_list.*.remark' => 'nullable|string|max:250',

        'order_no' => 'nullable|string|max:50',
    ];

    public $scene = [
        'store' => [
            'merchant_id', 'execution_date',
            'out_order_no', 'list_mode', 'type', 'out_user_id', 'nature', 'settlement_type', 'settlement_amount', 'replace_amount', 'delivery',
            //发货人信息
            //'sender_fullname', 'sender_phone', 'sender_country', 'sender_post_code', 'sender_house_number',
            //'sender_city', 'sender_street', 'sender_address',
            //收货人信息
            'receiver_fullname', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number',
            'receiver_city', 'receiver_street', 'receiver_address',
            //备注
            'special_remark', 'remark', 'lon', 'lat',
            //包裹列表
            'package_list.*.name', 'package_list.*.weight', 'package_list.*.expect_quantity', 'package_list.*.remark', 'package_list.*.out_order_no', 'package_list.*.express_first_no', 'package_list.*.express_second_no',
            //材料列表
            'material_list.*.name', 'material_list.*.code', 'material_list.*.out_order_no', 'material_list.*.expect_quantity', 'material_list.*.remark'
        ],
        'update' => [
            'merchant_id', 'execution_date', 'mask_code',
            'out_order_no', 'list_mode', 'type', 'out_user_id', 'nature', 'settlement_type', 'settlement_amount', 'replace_amount', 'delivery',
            //发货人信息
            //'sender_fullname', 'sender_phone', 'sender_country', 'sender_post_code', 'sender_house_number',
            //'sender_city', 'sender_street', 'sender_address',
            //收货人信息
            'receiver_fullname', 'receiver_phone', 'receiver_post_code', 'receiver_house_number',
            'receiver_city', 'receiver_street', 'receiver_address',
            //备注
            'special_remark', 'remark', 'lon', 'lat',
            //包裹列表
            'package_list.*.name', 'package_list.*.weight', 'package_list.*.expect_quantity', 'package_list.*.remark', 'package_list.*.out_order_no', 'package_list.*.express_first_no', 'package_list.*.express_second_no',
            //材料列表
            'material_list.*.name', 'material_list.*.code', 'material_list.*.out_order_no', 'material_list.*.expect_quantity', 'material_list.*.remark'
        ],
        'getBatchPageListByOrder' => ['execution_date'],
        'assignToBatch' => ['execution_date', 'batch_no'],
        'recovery' => ['execution_date'],
        'destroy' => ['remark'],
        'updateOutStatus' => ['order_no', 'out_status'],
        'getDateListByPostCode' => ['receiver_post_code'],
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

