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

class MerchantGroupLineRangeValidate extends BaseValidate
{
    public $rules = [
        'merchant_group_line_range_list' => 'required',
        'merchant_group_line_range_list.*.post_code_range' => 'required|string|max:50',
        'merchant_group_line_range_list.*.merchant_group_id' => 'required|integer',
        'merchant_group_line_range_list.*.is_alone' => 'required|integer|in:1,2',
        'merchant_group_line_range_list.*.workday_list' => 'required|string|max:50',
    ];

    public $scene = [
        'createOrUpdate' => [
            'merchant_group_line_range_list',
            'merchant_group_line_range_list.*.post_code_range',
            'merchant_group_line_range_list.*.merchant_group_id',
            'merchant_group_line_range_list.*.is_alone',
            'merchant_group_line_range_list.*.workday_list',
        ]
    ];
}

