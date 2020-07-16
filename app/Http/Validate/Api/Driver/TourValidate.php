<?php
/**
 * 取件线路
 */

namespace App\Http\Validate\Api\Driver;

use App\Http\Validate\BaseValidate;

class TourValidate extends BaseValidate
{
    public $customAttributes = [


    ];


    public $rules = [
        'remark' => 'nullable|string|max:250',
        'car_id' => 'required|integer',
        'batch_id' => 'required|integer',
        //出库
        'begin_signature' => 'required|string|max:250',
        'begin_signature_remark' => 'nullable|string|max:250',
        'begin_signature_first_pic' => 'nullable|string|max:250',
        'begin_signature_second_pic' => 'nullable|string|max:250',
        'begin_signature_third_pic' => 'nullable|string|max:250',
        'cancel_order_id_list' => 'nullable|string',
        'out_order_id_list' => 'nullable|string',
        'order_count' => 'required|integer',
        //异常上报
        'stage' => 'required|integer|in:1,2',
        'type' => 'required|integer|in:1,2,3',
        'exception_remark' => 'required|string|max:250',
        'picture' => 'required|string|max:250',
        //取消取派
        'cancel_type' => 'required|integer|in:1,2,3',
        'cancel_remark' => 'required|string|max:250',
        'cancel_picture' => 'nullable|string|max:250',
        //签收
        'signature' => 'required|string|max:250',
        'pay_type' => 'required|integer|in:1,2',
        'pay_picture' => 'nullable|required_if:pay_type,2|string|max:250',
        'total_sticker_amount' => 'required|numeric',
        'total_replace_amount' => 'required|numeric',
        'total_settlement_amount' => 'required|numeric',
        //入库
        'end_signature' => 'required|string|max:250',
        'end_signature_remark' => 'nullable|string|max:250',

        //材料列表
        'material_list.*.order_no' => 'required_with:material_list|string|max:50',
        'material_list.*.name' => 'nullable|string|max:50',
        'material_list.*.code' => 'required_with:material_list|string|max:50',
        'material_list.*.out_order_no' => 'nullable|string|max:50',
        'material_list.*.expect_quantity' => 'required_with:material_list|integer|gte:material_list.*.expect_quantity',
        'material_list.*.actual_quantity' => 'required_with:material_list|integer',

        //包裹列表
        'package_list.*.id' => 'required_with:package_list|integer',
        'package_list.*.sticker_no' => 'nullable|string|max:50',
    ];

    public $item_rules = [
        'order_id' => 'required|integer',
        'sticker_no' => 'nullable|string|max:50'
    ];


    public $scene = [
        'remark' => ['remark'],
        'changeCar' => ['car_id'],
        'outWarehouse' => [
            //材料列表
            'material_list.*.name', 'material_list.*.code', 'material_list.*.expect_quantity', 'material_list.*.actual_quantity',
            'cancel_order_id_list', 'out_order_id_list', 'begin_signature', 'begin_signature_remark', 'begin_signature_first_pic', 'begin_signature_second_pic', 'begin_signature_third_pic',
            'order_count'
        ],
        'checkOutWarehouse' => [
            //材料列表
            'material_list.*.name', 'material_list.*.code', 'material_list.*.expect_quantity', 'material_list.*.actual_quantity',
            'cancel_order_id_list', 'out_order_id_list',
            'order_count'
        ],
        'getBatchOrderList' => ['batch_id'],
        'batchArrive' => ['batch_id'],
        'getBatchInfo' => ['batch_id'],
        'batchException' => ['batch_id', 'stage', 'type', 'exception_remark', 'picture'],
        'batchCancel' => ['batch_id', 'cancel_type', 'cancel_remark', 'cancel_picture'],
        'checkBatchSign' => [
            'batch_id', 'package_list', 'material_list',
            //包裹列表
            'package_list.*.id', 'package_list.*.sticker_no',
            //材料列表
            'material_list.*.order_no', 'material_list.*.code', 'material_list.*.out_order_no', 'material_list.*.actual_quantity',
        ],
        'batchSign' => [
            'batch_id', 'package_list', 'material_list', 'signature', 'pay_type', 'pay_picture',
            //包裹列表
            'package_list.*.id', 'package_list.*.sticker_no',
            //材料列表
            'material_list.*.order_no', 'material_list.*.code', 'material_list.*.out_order_no', 'material_list.*.actual_quantity',
            'total_sticker_amount', 'total_replace_amount', 'total_settlement_amount'
        ],
        'inWarehouse' => ['end_signature', 'end_signature_remark']
    ];
}

