<?php
/**
 * 货主API 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class MerchantApiValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'merchant_id' => 'required|integer',
        'url' => 'nullable|string|max:250|url',
        'white_ip_list' => 'nullable|string|max:250',
        'status' => 'required|integer|in:1,2',
        'recharge_status' => 'required|integer|in:1,2'
    ];

    public $scene = [
        'store' => ['merchant_id'],
        'update' => ['url', 'white_ip_list', 'status', 'recharge_status'],
    ];
}

