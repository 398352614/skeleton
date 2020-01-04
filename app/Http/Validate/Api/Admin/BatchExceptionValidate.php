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

class BatchExceptionValidate extends BaseValidate
{
    public $customAttributes = [
        'deal_remark'=>'处理内容'
    ];


    public $rules = [
        'deal_remark' => 'required|string|max:250',
    ];

    public $scene = [
        'deal' => ['deal_remark'],
    ];

    public $message = [

    ];
}

