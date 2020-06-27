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
        'type' => '类型',
        'name' => '全称',
        'email' => '邮箱',
        'country' => '国家',
        'settlement_type' => '结算方式',
        'merchant_group_id' => '商户组ID',
        'contacter' => '联系人',
        'phone' => '电话',
        'address' => '联系地址',
        'avatar' => '头像',
        'status' => '状态',
        'password' => '密码',
        'confirm_password' => '密码确认',
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
        ],
        'updatePassword' => [
            'password', 'confirm_password'
        ],
        'status' => ['status']
    ];
}

