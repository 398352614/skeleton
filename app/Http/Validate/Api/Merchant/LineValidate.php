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

class LineValidate extends BaseValidate
{
    public $customAttributes = [


    ];


    public $rules = [
        'place_post_code' => 'required|string|max:50',
        'type' => 'nullable|integer|in:1,2,3'
    ];

    public $scene = [
        'getDateListByPostCode' => ['place_post_code', 'type'],
    ];
}

