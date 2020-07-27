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
        'password' => 'required|string|max:100',
        'confirm_password' => 'required|string|same:password',
        'advance_days' => 'nullable|integer',
        'appointment_days' => 'nullable|integer',
        'delay_time' => 'nullable|integer',
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
        ],
        'updatePassword' => [
            'password', 'confirm_password'
        ],
        'status' => ['status']
    ];
}

