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

class TourValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'driver_id' => 'required|integer',
        'car_id' => 'required|integer',
        'order_id_list' => 'required|string|checkIdList:20',
        'line_id' => 'required|integer',
        'execution_date' => 'required|date|after_or_equal:today',
    ];

    public $scene = [
        'assignDriver' => ['driver_id'],
        'assignCar' => ['car_id'],
        'getAddOrderPageList' => ['order_id_list'],
        'assignTourToTour' => ['execution_date', 'line_id']
    ];

    public $message = [
        'driver_id.required' => '请选择司机',
        'car_id.required' => '请选择车辆',
    ];
}

