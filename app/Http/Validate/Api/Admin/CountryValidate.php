<?php
/**
 * 国家 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class CountryValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'short' => 'required|string|max:50|uniqueIgnore:country,id,company_id',
    ];

    public $scene = [
        'store' => ['short']
    ];
}

