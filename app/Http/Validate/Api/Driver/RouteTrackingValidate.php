<?php
/**
 * 取件线路 验证类
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
        'lon' => '经度',
        'lat' => '纬度',
        'time' =>'时间',
    ];


    public $rules = [
        'location_list.*.lon' => 'required|string',
        'location_list.*.lat' => 'required|string',
        'location_list.*.time' => 'required|date_format:Y-m-d H:i:s',
        'location_list'=>'required|json',
    ];

    public $scene = [
        'storeByList' => ['location_list.*.lon','location_list.*.lat','location_list.*.time'],
    ];

    public $message = [
    ];
}

