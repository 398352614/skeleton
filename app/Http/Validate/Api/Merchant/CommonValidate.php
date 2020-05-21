<?php
/**
 * 线路 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Merchant;

use App\Http\Validate\BaseValidate;

class CommonValidate extends BaseValidate
{
    public $customAttributes = [
        'country' => '国家',
        'post_code' => '邮编',
        'house_number' => '门牌号',
        'city' => '城市',
        'street' => '街道',
    ];


    public $rules = [
        'country' => 'nullable|string|max:50',
        'post_code' => 'required|string|max:50',
        'house_number' => 'required|string|max:50',
        'city' => 'nullable|string|max:50',
        'street' => 'nullable|string|max:50',
    ];

    public $scene = [
        'getLocation' => ['country', 'post_code', 'house_number', 'city', 'street'],
    ];
}

