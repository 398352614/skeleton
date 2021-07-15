<?php
/**
 * 线路任务 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Driver;

use App\Http\Validate\BaseValidate;

class StockExceptionValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'remark' => 'nullable|string|max:250',
        'express_first_no'=>'required|string|max:50'
    ];

    public $scene = [
        'store' => ['remark','express_first_no'],
    ];

    public $message = [

    ];
}

