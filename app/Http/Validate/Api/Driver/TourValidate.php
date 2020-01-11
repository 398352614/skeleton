<?php
/**
 * 取件线路
 */

namespace App\Http\Validate\Api\Driver;

use App\Http\Validate\BaseValidate;

class TourValidate extends BaseValidate
{
    public $customAttributes = [
        'remark' => '内容',
        'car_id' => '车辆ID'
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
        //异常上报
        'stage' => 'required|integer|in:1,2',
        'type' => 'required|integer|in:1,2,3',
        'exception_remark' => 'required|string|max:250',
        //取消取派
        'cancel_type' => 'required|integer|in:1,2,3',
        'cancel_remark' => 'required|string|max:250',
        'cancel_picture' => 'nullable|string|max:250',
        //签收
        'cancel_order_id_list' => 'nullable|string',
        'signature' => 'required|string|max:250',
        'pay_type' => 'required|integer|in:1,2',
        'pay_picture' => 'nullable|required_if:pay_type,2|string|max:250',
        //入库
        'end_signature' => 'required|string|max:250',
        'end_signature_remark' => 'nullable|string|max:250',
    ];

    public $item_rules = [
        'order_id' => 'required|integer',
        'sticker_no' => 'nullable|string|max:50'
    ];


    public $scene = [
        'remark' => ['remark'],
        'changeCar' => ['car_id'],
        'outWarehouse' => ['begin_signature', 'begin_signature_remark', 'begin_signature_first_pic', 'begin_signature_second_pic', 'begin_signature_third_pic'],
        'getBatchOrderList' => ['batch_id'],
        'batchArrive' => ['batch_id'],
        'getBatchInfo' => ['batch_id'],
        'batchException' => ['batch_id', 'stage', 'type', 'exception_remark', 'picture'],
        'batchCancel' => ['batch_id', 'cancel_type', 'cancel_remark', 'cancel_picture'],
        'batchSign' => ['batch_id', 'cancel_order_id_list', 'signature', 'pay_type', 'pay_picture'],
        'inWarehouse' => ['end_signature', 'end_signature_remark']
    ];
}

