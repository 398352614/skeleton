<?php
/**
 * 订单 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class MapConfigValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'front_type' => 'nullable|integer|in:1,2,3',
        'back_type' => 'required|integer|in:1,2,3',
        'mobile_type' => 'nullable|integer|in:1,2,3',
        'google_key' => 'nullable|string|max:250',
        'google_secret' => 'nullable|string|max:250',
        'baidu_key' => 'nullable|string|max:250',
        'baidu_secret' => 'nullable|string|max:250',
        'tencent_key' => 'nullable|string|max:250',
        'tencent_secret' => 'nullable|string|max:250'
    ];

    public $scene = [
        //保存
        'store' => [
            'front_type',
            'back_type',
            'mobile_type',
            'google_key',
            'google_secret',
            'baidu_key',
            'baidu_secret',
            'tencent_key',
            'tencent_secret'

        ],
        'update' => [
            'front_type',
            'back_type',
            'mobile_type',
            'google_key',
            'google_secret',
            'baidu_key',
            'baidu_secret',
            'tencent_key',
            'tencent_secret'
        ]
    ];
}

