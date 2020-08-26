<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/4/10
 * Time: 11:03
 */

namespace App\Events\Interfaces;


use App\Services\BaseConstService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

abstract class ATourNotify
{
    public $tour;

    public $batch;

    public $batchList;

    public $orderList;

    public $type;

    public static $tourFields = ['line_name', 'tour_no', 'execution_date', 'expect_distance', 'expect_time', 'driver_id', 'driver_name', 'driver_phone', 'car_id', 'car_no'];

    public static $batchFields = [
        'tour_no', 'batch_no', 'receiver_fullname', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number',
        'receiver_city', 'receiver_street', 'receiver_address', 'expect_arrive_time', 'expect_time', 'expect_distance', 'signature', 'cancel_remark',
        'pay_type', 'pay_picture', 'status', 'auth_fullname', 'auth_birth_date'
    ];

    public static $orderFields = [
        'merchant_id', 'tour_no', 'batch_no', 'order_no', 'out_order_no', 'status'
    ];

    public function __construct($tour, $batch, $batchList, $orderList)
    {
        //取件线路
        $this->tour = Arr::only($tour, self::$tourFields);
        //站点
        !empty($batch) && $this->batch = Arr::only($batch, self::$batchFields);
        //站点列表
        !empty($batchList) && $this->batchList = collect($batchList)->map(function ($batch) {
            return Arr::only($batch, self::$batchFields);
        })->toArray();
        //订单
        !empty($orderList) && $this->orderList = collect($orderList)->map(function ($order) {
            return Arr::only($order, self::$orderFields);
        })->toArray();
        $this->type=$this->notifyType();
        Log::info('notify-type:' . $this->notifyType());
    }

    /**
     * 发送的动作类型
     * @return BaseConstService 订阅及通知常量
     */
    abstract public function notifyType(): string;

    /**
     * 推送数据
     * @return array
     */
    abstract public function getDataList(): array;
}
