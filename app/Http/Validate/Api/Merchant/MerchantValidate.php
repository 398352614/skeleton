<?php
/**
 * 货主 验证类
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
        'name' => 'required|string|max:50',
        'email' => 'required|string|max:50',
        'country' => 'nullable|string|max:50',
        'settlement_type' => 'required|integer|in:1,2,3',
        'merchant_group_id' => 'required|integer',
        'contacter' => 'required|string|max:50',
        'phone' => 'required|string|max:20|regex:/^[0-9 ]([0-9- ])*[0-9 ]$/',
        'address' => 'required|string|max:50',
        'avatar' => 'required|string|max:250',
        'status' => 'required|integer|in:1,2',
        'additional_status' => 'required|integer|in:1,2',
        'password' => 'required|string|max:100',
        'confirm_password' => 'required|string|same:password',

        'package_list.*.weight' => 'required|numeric|gte:0',
        'package_list.*.express_first_no' => 'required_with:package_list|string|max:50|regex:/^[0-9a-zA-Z]([0-9a-zA-Z])*[0-9a-zA-Z]$/',
    ];

    public $scene = [
        'update' => [
            'name',
            'contacter',
            'phone',
            'address',
            'country',
        ],
    ];
}

