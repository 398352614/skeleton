<?php
/**
 * 设备 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class DeviceValidate extends BaseValidate
{
    public $customAttributes = [
        'number' => '型号',
        'driver_id' => '司机ID',
        'mode' => '模式',
        'status' => '状态',
    ];

    public $rules = [
        'number' => 'required|string|max:50|uniqueIgnore:device,id',
        'driver_id' => 'required|integer',
        'mode' => 'nullable|string:max:20',
        'status' => 'required|integer|in:1,2',
    ];

    public $scene = [
        'store' => ['number', 'mode'],
        'update' => ['number', 'mode'],
        'bind' => ['driver_id']
    ];
}

