<?php
/**
 * 车辆品牌 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class CarBrandValidate extends BaseValidate
{

    public $rules = [
        'cn_name' => 'required|string|max:50|uniqueIgnore:car_brand,id,company_id',
        'en_name' => 'required|string|max:50|uniqueIgnore:car_brand,id,company_id',
    ];

    public $scene = [
        'store' => [
            'cn_name', 'en_name'
        ],
    ];
}

