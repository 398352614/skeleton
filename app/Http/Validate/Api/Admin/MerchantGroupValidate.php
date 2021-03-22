<?php
/**
 * 货主组 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class MerchantGroupValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'name' => 'required|string|max:50|uniqueIgnore:merchant_group,id,company_id',
        'transport_price_id' => 'required|integer',
        'is_default' => 'required|integer|in:1,2',
        'additional_status' => 'nullable|integer|in:1,2',
        'advance_days' => 'nullable|integer|gte:0|lte:7',
        'appointment_days' => 'nullable|integer|gte:1|lte:30',
        'delay_time' => 'nullable|integer|gte:0|lte:60',
        'pickup_count' => 'required|integer|gte:0|lte:5',
        'pie_count' => 'nullable|integer|gte:0|lte:5',
        'fee_code_list' => 'nullable|string|max:1000',
        'merchant_group_id' => 'nullable|integer',
        'status' => 'required|integer|in:1,2',
    ];

    public $scene = [
        'store' => ['name', 'transport_price_id', 'is_default'],
        'update' => ['name', 'transport_price_id', 'is_default'],
        'config' => ['additional_status', 'advance_days', 'appointment_days', 'delay_time', 'pickup_count', 'pie_count', 'fee_code_list'],
        'getFeeList' => ['merchant_group_id'],
        'status' => ['status']
    ];
}

