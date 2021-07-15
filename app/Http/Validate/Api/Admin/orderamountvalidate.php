<?php
/**
 * 打印模板 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class OrderAmountValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'order_no' => 'required|string|max:50',
        'expect_amount' => 'required|numeric|gte:0',
        'type' => 'required|integer|in:1,2,3,4,5,6,7,8,9,10,11',
        'remark' => 'nullable|string',
        'in_total' => 'nullable|integer|in:1,2',
        'status' => 'nullable|integer|in:1,2,3,4'

    ];

    public $scene = [
        'store' => ['order_no', 'expect_amount', 'type', 'remark', 'in_total', 'status'],
        'update' => ['expect_amount', 'type', 'remark', 'in_total', 'status'],
    ];
}

