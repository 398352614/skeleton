<?php
/**
 * 取件线路 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class StockExceptionValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'deal_remark' => 'nullable|string|max:250|min:5',
    ];

    public $scene = [
        'deal' => ['deal_remark'],
    ];

    public $message = [

    ];
}

