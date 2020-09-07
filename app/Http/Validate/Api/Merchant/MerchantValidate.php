<?php
/**
 * 商户 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Merchant;

use App\Http\Validate\BaseValidate;

class MerchantValidate extends BaseValidate
{
    public $customAttributes = [


    ];


    public $rules = [
        'type' => 'required|integer|in:1,2',
        'name' => 'required|string|max:100',
        'email' => 'required|string|max:50',
        'country' => 'nullable|string|max:50',
        'settlement_type' => 'required|integer|in:1,2,3',
        'merchant_group_id' => 'required|integer',
        'contacter' => 'required|string|max:50',
        'phone' => 'required|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
        'address' => 'required|string|max:250',
        'avatar' => 'required|string|max:250',
        'status' => 'required|integer|in:1,2',
        'additional_status' => 'required|integer|in:1,2',
        'password' => 'required|string|max:100',
        'confirm_password' => 'required|string|same:password',
        'advance_days' => 'nullable|integer|gte:0|lte:7',
        'appointment_days' => 'nullable|integer|gte:1|lte:30',
        'delay_time' => 'nullable|integer|gte:0|lte:60',
    ];

    public $scene = [
        'update' => [
            'name',
            'contacter',
            'phone',
            'address',
            'country',
            'advance_days',
            'appointment_days',
            'delay_time',
            //'additional_status'
        ]
    ];
}

