<?php
/**
 * 线路任务 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Driver;

use App\Http\Validate\BaseValidate;

class RouteTrackingValidate extends BaseValidate
{
    public $customAttributes = [
        'device_number' => '设备号'
    ];


    public $rules = [
        //'device_number' => 'required|string|max:50',
        'location_list.*.lon' => 'required|string',
        'location_list.*.lat' => 'required|string',
        'location_list.*.time' => 'required|date_format:Y-m-d H:i:s',
        'location_list' => 'required',
    ];

    public $scene = [
        'storeByList' => [/*'device_number', */'location_list.*.lon', 'location_list.*.lat', 'location_list.*.time', 'location_list'],
    ];

    public $message = [
    ];
}

