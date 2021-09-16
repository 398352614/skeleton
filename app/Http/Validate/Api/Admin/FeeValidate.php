<?php
/**
 * 费用 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class FeeValidate extends BaseValidate
{
    public $customAttributes = [
        'name' => '名称',
        'code' => '编码',
        'amount' => '费用',
        'status' => '状态',
    ];

    public $rules = [
        'name' => 'required|string|max:50|uniqueIgnore:fee,id,company_id',
        'code' => 'nullable|string|max:50|uniqueIgnore:fee,id,company_id|regex:/[A-Z]+/',
        'amount' => 'required|numeric|gte:0',
        'status' => 'required|integer|in:1,2',
        'is_valuable' => 'nullable|integer|in:1,2',
        'payer' => 'required|integer|in:1,2',

    ];

    public $scene = [
        'store' => ['name', 'code', 'amount', 'status', 'is_valuable'],
        'update' => ['name', 'code', 'amount', 'status', 'payer'],
    ];

    public $message = [
    ];
}

