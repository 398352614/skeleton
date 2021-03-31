<?php
/**
 * 运单 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2020/11/02
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class TrackingOrderTrailValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'type' => 'required|integer|in:1,2,3,4,6,7,8,9,10,11,12,13,14,15',
        'tracking_order_no' => 'required|string|max:50',
        'content' => 'required|string|max:250',
    ];

    public $scene = [
        'store' => ['type','tracking_order_no','content'],
    ];
}

