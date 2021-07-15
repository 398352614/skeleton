<?php
/**
 * 线路任务
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
        'cancel_tracking_order_id_list' => 'nullable|string',
        'out_tracking_order_id_list' => 'nullable|string',
        'tracking_order_count' => 'required|integer',
        //确认出库
        'begin_distance' => 'required|integer|gte:0',
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
        'pay_type' => 'required|integer|in:1,2,3,4',
        'pay_picture' => 'nullable|required_if:pay_type,2|string|max:250',
        'total_sticker_amount' => 'required|numeric',
        'total_delivery_amount' => 'required|numeric',
        'total_replace_amount' => 'required|numeric',
        'total_settlement_amount' => 'required|numeric',
        'auth_fullname' => 'nullable|string|checkSpecialChar|max:100',
        'auth_birth_date' => 'nullable|date|date_format:Y-m-d',
        //入库
        'end_signature' => 'required|string|max:250',
        'end_signature_remark' => 'nullable|string|max:250',
        'end_distance' => 'required|integer|gte:0',

        //材料列表
        'material_list.*.id' => 'required_with:material_list|string|max:50',
        'material_list.*.order_no' => 'required_with:material_list|string|max:50',
        'material_list.*.name' => 'nullable|string|max:50',
        'material_list.*.code' => 'required_with:material_list|string|max:50',
        'material_list.*.out_order_no' => 'nullable|string|max:50',
        'material_list.*.expect_quantity' => 'required_with:material_list|integer|gte:material_list.*.actual_quantity',
        'material_list.*.actual_quantity' => 'required_with:material_list|integer',

        //包裹列表
        'package_list.*.id' => 'required_with:package_list|integer',
        'package_list.*.sticker_no' => 'nullable|string|max:50',

        //顺带包裹列表
        'additional_package_list.*.package_no' => 'required_with:additional_package_list|string|max:50',
        'additional_package_list.*.merchant_id' => 'required_with:additional_package_list|integer',
        'additional_package_list.*.sticker_no' => 'nullable|string|max:250',
        'additional_package_list.*.delivery_charge' => 'required_with:additional_package_list|integer|in:1,2',
        //跳过
        'is_skipped' => 'required|integer|in:1,2',

        //导出站点表格
        'year' => 'required|integer',
        'month' => 'required|integer',

        //延迟
        'delay_time' => 'required|integer',
        'delay_type' => 'required|integer|in:1,2,3,4',
        'delay_remark' => 'required|string|max:250'
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
            'begin_signature', 'begin_signature_remark', 'begin_signature_first_pic', 'begin_signature_second_pic', 'begin_signature_third_pic',
            'cancel_tracking_order_id_list', 'out_tracking_order_id_list', 'tracking_order_count'
        ],
        'checkOutWarehouse' => [
            //材料列表
            'material_list.*.name', 'material_list.*.code', 'material_list.*.expect_quantity', 'material_list.*.actual_quantity',
            'cancel_tracking_order_id_list', 'out_tracking_order_id_list',
            'tracking_order_count'
        ],
        'actualOutWarehouse' => ['begin_distance'],
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
            'material_list.*.id', 'material_list.*.actual_quantity',

            'auth_fullname', 'auth_birth_date'

        ],
        'batchSign' => [
            'batch_id', 'package_list', 'material_list', 'signature', 'pay_type', 'pay_picture',
            //包裹列表
            'package_list.*.id', 'package_list.*.sticker_no',
            //材料列表
            'material_list.*.id', 'material_list.*.actual_quantity',
            'total_sticker_amount', 'total_delivery_amount', 'total_replace_amount', 'total_settlement_amount',
            //顺带包裹
            'additional_package_list.*.package_no',
            'additional_package_list.*.merchant_id',
            'additional_package_list.*.sticker_no',
            'additional_package_list.*.delivery_charge',

            'auth_fullname', 'auth_birth_date'
        ],
        'inWarehouse' => ['end_signature', 'end_signature_remark', 'end_distance'],
        'batchSkip' => ['batch_id'],
    ];
}

