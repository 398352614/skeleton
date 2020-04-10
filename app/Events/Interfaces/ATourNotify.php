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

abstract class ATourNotify
{
    public $tour;

    public $batch;

    public $batchList;

    public $orderList;

    public static $tourFields = [];

    public static $batchFields = [];

    public static $orderFields = [];

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