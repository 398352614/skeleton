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
        'cn_name' => '中文名称',
        'en_name' => '英文名称',
    ];


    public $rules = [
        'en_name' => 'required|string|max:50|uniqueIgnore:country,id,company_id',
        'cn_name' => 'required|string|max:50|uniqueIgnore:country,id,company_id',
    ];

    public $scene = [
        'store' => ['en_name', 'cn_name']
    ];
}

