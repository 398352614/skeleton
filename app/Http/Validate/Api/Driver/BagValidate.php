<?php


namespace App\Http\Validate\Api\Driver;


use App\Http\Validate\BaseValidate;

class BagValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'bag_no' => 'nullable|string',
        'shift_no' => 'nullable|string',
        'status' => 'nullable|integer|in:1,2',
        'weight' => 'nullable|decimal|gte:0',
        'driver_id' => 'nullable|integer',
        'driver_name' => 'nullable|string',
        'car_no' => 'nullable|string',
        'remark' => 'nullable|string',
        'next_warehouse'=>'required|string'
    ];

    public $scene = [
        'store' => ['bag_no', 'shift_no', 'status', 'weight', 'driver_id', 'driver_name', 'car_no', 'remark'],
        'update' => ['bag_no', 'shift_no', 'status', 'weight', 'driver_id', 'driver_name', 'car_no', 'remark']
    ];

    public $message = [
    ];
}
