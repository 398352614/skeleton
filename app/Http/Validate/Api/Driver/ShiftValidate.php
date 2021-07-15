<?php


namespace App\Http\Validate\Api\Driver;


use App\Http\Validate\BaseValidate;

class ShiftValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'shift_no' => 'nullable|string',
        'status' => 'nullable|integer|in:1,2,3,4',
        'weight' => 'nullable|decimal|gte:0',
        'package_count' => 'nullable|decimal|gte:0',
        'driver_id' => 'nullable|integer',
        'driver_name' => 'nullable|string',
        'car_no' => 'nullable|string',
        'car_id' => 'required|string',
        'remark' => 'nullable|string',
        'next_warehouse_id' => 'required|string',
        'item_list' => 'required',
        'item_list.*.shift_type' => 'required|integer',
        'item_list.*.item_no' => 'required|string',
        'item_no' => 'required|string',
        'ignore_rule' => 'required|integer|in:1,2'
    ];

    public $scene = [
        'store' => ['shift_no', 'status', 'weight', 'package_count', 'driver_id', 'driver_name', 'car_no', 'car_id', 'remark'],
        'update' => ['shift_no', 'status', 'weight', 'package_count', 'driver_id', 'driver_name', 'car_no', 'car_id', 'remark'],
        'unloadItemList' => ['item_list', 'item_list.*.shift_type', 'item_list.*.item_no'],
        'loadItem'=>['ignore_rule']
    ];

    public $message = [
    ];
}
