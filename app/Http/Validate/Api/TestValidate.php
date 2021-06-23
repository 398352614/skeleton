<?php
/**
 * 测试接口 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api;

use App\Http\Validate\BaseValidate;

class TestValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'name' => 'nullable|uniqueIgnore:test,id',
    ];
    public $scene = [
        'store' => ['name'],
        'update' => ['name'],
    ];
}

