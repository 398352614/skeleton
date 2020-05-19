<?php
/**
 * 取件线路 验证类
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
        'driver_id' => '司机ID',
        'tour_no' => '车辆ID',
    ];


    public $rules = [
        'driver_id' => 'required|integer',
        'tour_no' => 'required_without:driver_id|integer',
    ];

    public $scene = [
        'show' => ['driver_id','tour_no'],
    ];

    public $message = [
    ];
}

