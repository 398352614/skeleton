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
        'short' => '简称',
        'cn_name' => '中文名称',
        'en_name' => '英文名称',
        'tel' => '区号'
    ];


    public $rules = [
        'short' => 'required|string|max:50|uniqueIgnore:country,id,company_id',
        'en_name' => 'required|string|max:50|uniqueIgnore:country,id,company_id',
        'cn_name' => 'required|string|max:50|uniqueIgnore:country,id,company_id',
        'tel' => 'required|string|max:10'
    ];

    public $scene = [
        'store' => ['short', 'en_name', 'cn_name', 'tel']
    ];
}

