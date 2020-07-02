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

    ];


    public $rules = [
        'name' => 'required|string|max:50|uniqueIgnore:merchant_group,id,company_id',
        'transport_price_id' => 'required|integer',
        'is_default' => 'required|integer|in:1,2',
    ];

    public $scene = [
        'store' => ['name', 'transport_price_id', 'is_default'],
        'update' => ['name', 'transport_price_id', 'is_default'],
    ];
}

