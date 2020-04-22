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
        'driver_id' => '司机ID',
        'car_id' => '车辆ID',
    ];


    public $rules = [
        'driver_id' => 'required|integer',
        'car_id' => 'required|integer',
        'order_id_list' => 'required|string|checkIdList:20'
    ];

    public $scene = [
        'assignDriver' => ['driver_id'],
        'assignCar' => ['car_id'],
        'getAddOrderPageList' => ['order_id_list']
    ];
}

