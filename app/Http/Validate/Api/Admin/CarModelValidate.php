<?php
/**
 * 车辆模型 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class CarModelValidate extends BaseValidate
{

    public $customAttributes = [

    ];

    public $rules = [
        'cn_name' => 'required|string|max:50|uniqueIgnore:car_model,id,company_id,brand_id',
        'en_name' => 'required|string|max:50|uniqueIgnore:car_model,id,company_id,brand_id',
        'brand_id' => 'required|integer',
    ];

    public $scene = [
        'getListByBrand' => ['brand_id'],
        'store' => ['cn_name', 'en_name', 'brand_id'],
    ];
}

