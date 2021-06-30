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
        'tour_no' => 'required|string',
        'year' => 'required|integer',
        'month' => 'required|integer|lte:12',
        'tracking_order_id_list' => 'required|string|checkIdList:20',
    ];

    public $scene = [
        'assignDriver' => ['driver_id'],
        'assignCar' => ['car_id'],
        'getAddOrderPageList' => ['tracking_order_id_list', 'execution_date'],
        'getLineDate' => ['line_id'],
        'assignTourToTour' => ['execution_date', 'line_id', 'tour_no'],
        'getListJoinByLineId' => ['line_id', 'execution_date'],
        'batchExport' => ['year', 'month'],
    ];

    public $message = [
        'driver_id.required' => '请选择司机',
        'car_id.required' => '请选择车辆',
    ];
}

