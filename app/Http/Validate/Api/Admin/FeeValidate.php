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
    public $rules = [
        'name' => 'required|string|max:50|uniqueIgnore:fee,id,company_id',
        'code' => 'required|string|max:50|uniqueIgnore:fee,id,company_id',
        'amount' => 'required|numeric|gte:0',
        'status' => 'required|integer|in:1,2',
    ];

    public $scene = [
        'store' => ['name', 'code', 'amount', 'status'],
        'update' => ['name', 'code', 'amount', 'status'],
    ];
}

