<?php
/**
 * 线路 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class CommonValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'country' => 'required|string|max:50',
        'post_code' => 'required|string|max:50',
        'house_number' => 'required|string|max:50',
        'city' => 'nullable|string|max:50',
        'street' => 'nullable|string|max:50',
    ];

    public $scene = [
        'getLocation' => ['country', 'post_code', 'house_number', 'city', 'street'],
    ];
}

