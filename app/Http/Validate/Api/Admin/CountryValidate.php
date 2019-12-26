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

class CountryValidate extends BaseValidate
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
        'name' => 'required|string|max:50|uniqueIgnore:country,id,company_id',
    ];

    public $scene = [
        'store' => ['name']
    ];
}

