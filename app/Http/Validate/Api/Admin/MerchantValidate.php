<?php
/**
 * 商户 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class MerchantValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'type' => 'required|integer|in:1,2',
        'name' => 'required|string|max:100|uniqueIgnore:merchant,id,company_id',
        'email' => 'required|string|max:50|email|uniqueIgnore:merchant,id',
        'country' => 'nullable|string|max:50',
        'settlement_type' => 'required|integer|in:1,2,3',
        'merchant_group_id' => 'required|integer',
        'contacter' => 'required|string|max:50',
        'phone' => 'required|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
        'address' => 'required|string|max:250',
        'avatar' => 'nullable|string|max:250',
        'status' => 'required|integer|in:1,2',
        'additional_status' => 'nullable|integer|in:1,2',
        'password' => 'required|string|max:100',
        'confirm_password' => 'required|string|same:password',
        'advance_days' => 'nullable|integer|gte:0|lte:7',
        'appointment_days' => 'nullable|integer|gte:1|lte:30',
        'delay_time' => 'nullable|integer|gte:0|lte:60',
        'merchant_id' => 'nullable|integer',
        'fee_code_list' => 'nullable|string|max:1000'
    ];

    public $scene = [
        'store' => [
            'type',
            'name',
            'email',
            'country',
            'settlement_type',
            'merchant_group_id',
            'contacter',
            'phone',
            'address',
            'avatar',
            'status',
            'advance_days',
            'appointment_days',
            'delay_time',
            'fee_code_list',
            'additional_status'
        ],
        'update' => [
            'type',
            'name',
            'email',
            'country',
            'settlement_type',
            'merchant_group_id',
            'contacter',
            'phone',
            'address',
            'avatar',
            'status',
            'advance_days',
            'appointment_days',
            'delay_time',
            'fee_code_list',
            'additional_status'
        ],
        'updatePassword' => [
            'password', 'confirm_password'
        ],
        'status' => ['status'],
        'getFeeList' => ['merchant_id']
    ];
}

