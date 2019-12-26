<?php
/**
 * 仓库 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class WareHouseValidate extends BaseValidate
{
    public $customAttributes = [
        'name' => '名称',
        'country' => '国家',
        'warehouse_id' => '仓库ID',
        'order_max_count' => '最大订单量',
        'remark' => '备注',
        'work_day_list' => '工作日'
    ];


    public $rules = [
        'name' => 'required|string|max:50',
        'contacter' => 'required|string|max:50',
        'phone' => 'required|string|max:20',
        'country' => 'required|string|max:50',
        'post_code' => 'required|string|max:50',
        'house_number' => 'required|string|max:50',
        'city' => 'required|string|max:50',
        'street' => 'required|string|max:50',
        'address' => 'required|string|max:250',
    ];

    public $scene = [
        'store' => ['name', 'contacter', 'phone', 'country', 'post_code', 'house_number', 'city', 'street', 'address'],
        'update' => ['name', 'contacter', 'phone', 'country', 'post_code', 'house_number', 'city', 'street', 'address']
    ];
}

