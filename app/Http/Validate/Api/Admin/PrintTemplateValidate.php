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

class PrintTemplateValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'type' => 'required|integer|in:1,2'
    ];

    public $scene = [
        'update' => ['type']
    ];
}

