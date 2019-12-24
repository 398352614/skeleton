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

    ];

    public $itemCustomAttributes = [

    ];

    public $rules = [
        'name' => 'required|string|max:50',
        'country' => 'required|string|max:50',
        'warehouse_id' => 'required|integer',
        'order_max_count' => 'required|integer',
        'remark' => 'required|string|max:250',
        'work_days_list' => 'required|string'
    ];

    public $item_rules = [
        'post_code_start' => 'required|integer',
        'post_code_end' => 'required|integer',
    ];

    public $scene = [
        'store' => [
            'name', 'country', 'warehouse_id', 'order_max_count', 'remark', 'work_days_list',
            'item_list' => ['post_code_start', 'post_code_end']
        ],
    ];
}

