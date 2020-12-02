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

class MerchantLineRangeValidate extends BaseValidate
{
    public $rules = [
        'merchant_line_range_list'=>'required',
        'item_list.*.post_code_range' => 'required|string|max:50',
        'item_list.*.merchant_id' => 'required|integer',
        'item_list.*.is_alone' => 'required|integer|in:1,2',
        'item_list.*.workday_list' => 'required|string|max:50',
    ];

    public $scene = [
        'createOrUpdate' => [
            'merchant_line_range_list',
            'merchant_line_range_list.*.post_code_range',
            'merchant_line_range_list.*.merchant_id',
            'merchant_line_range_list.*.is_alone',
            'merchant_line_range_list.*.workday_list',
        ]
    ];
}

