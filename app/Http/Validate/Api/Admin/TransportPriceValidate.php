<?php
/**
 * 运价 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class TransportPriceValidate extends BaseValidate
{
    public $customAttributes = [
        'name' => '名称',
        'starting_price' => '起步价',
        'remark' => '特殊说明',
        'status' => '状态'
    ];


    public $rules = [
        'name' => 'required|string|max:50',
        'starting_price' => 'required|integer',
        'remark' => 'nullable|string|max:250',
        'status' => 'required|integer|in:1,2',
    ];

    public $scene = [
        'store' => ['name', 'starting_price', 'remark', 'status'],
        'update' => ['name', 'starting_price', 'remark', 'status'],
    ];
}

