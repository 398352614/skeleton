<?php
/**
 * 商户组 验证类
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
        'transport_price_id' => '运价ID',
        'name' => '名称',
        'is_default' => '是否默认'
    ];


    public $rules = [
        'name' => 'required|string|max:50',
        'transport_price_id' => 'required|integer',
        'is_default' => 'required|integer|in:1,2',
    ];

    public $scene = [
        'store' => ['name', 'transport_price_id', 'is_default'],
        'update' => ['name', 'transport_price_id', 'is_default'],
    ];
}

