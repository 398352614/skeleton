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

class LineValidate extends BaseValidate
{
    public $customAttributes = [
        'name' => '名称',
        'country' => '国家',
        'warehouse_id' => '仓库ID',
        'order_max_count' => '最大订单量',
        'remark' => '备注',
        'work_day_list' => '工作日',
        //'item_list.*.post_code_start' => '起始邮编',
        //'item_list.*.post_code_end' => '结束邮编',
    ];

    public $rules = [
        'name' => 'required|string|max:50|uniqueIgnore:line,id',
        'country' => 'required|string|max:50',
        'warehouse_id' => 'required|integer',
        'order_max_count' => 'required|integer',
        'remark' => 'nullable|string|max:250',
        'work_day_list' => 'required|string',
        //邮编列表
        'item_list.*.post_code_start' => 'required|integer|between:1000,9999',
        'item_list.*.post_code_end' => 'required|integer|between:1000,9999',
    ];

    public $scene = [
        'store' => [
            'name', 'country', 'warehouse_id', 'order_max_count', 'remark', 'work_days_list',
            'item_list.*.post_code_start', 'item_list.*.post_code_end'
        ],
        'update' => [
            'name', 'country', 'warehouse_id', 'order_max_count', 'remark', 'work_days_list',
            'item_list.*.post_code_start', 'item_list.*.post_code_end'
        ],
    ];
}

