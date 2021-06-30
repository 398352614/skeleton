<?php
/**
 * 线路任务 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class RouteTrackingValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'driver_id' => 'required_without:tour_no|integer',
        'tour_no' => 'required_without:driver_id|string',
    ];

    public $scene = [
        'show' => ['driver_id','tour_no'],
    ];

    public $message = [
    ];
}

