<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/4/10
 * Time: 11:03
 */

namespace App\Events\Interfaces;


use App\Models\Order;
use App\Models\Package;
use App\Models\TrackingOrderMaterial;
use App\Models\TrackingOrderPackage;
use App\Services\BaseConstService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class ATourNotify2
{
    public $tour;

    public $batch;

    public $batchList;

    public $trackingOrderList;

    public $type;

    public static $tourFields = ['line_name', 'tour_no', 'execution_date', 'expect_distance', 'expect_time', 'driver_id', 'driver_name', 'driver_phone', 'car_id', 'car_no',
        'expect_distance','expect_time','actual_time','actual_distance'];

    public static $batchFields = [
        'batch_no','place_fullname', 'place_phone', 'place_country', 'place_post_code', 'place_house_number',
        'place_city', 'place_street', 'place_address', 'expect_arrive_time', 'expect_time', 'expect_distance', 'signature',
        'pay_type', 'pay_picture', 'status', 'auth_fullname', 'auth_birth_date'
    ];

    public static $trackingOrderFields = [
        'merchant_id', 'tour_no', 'batch_no', 'tracking_order_no', 'order_no', 'out_order_no', 'status', 'type'
    ];

    public function __construct($tour, $batch, $batchList, $trackingOrderList)
    {
        //取件线路
        $this->tour = $tour;
        //站点
        !empty($batch) && $this->batch = Arr::only($batch, self::$batchFields);
        //站点列表
        !empty($batchList) && $this->batchList = collect($batchList)->map(function ($batch) {
            return Arr::only($batch, self::$batchFields);
        })->toArray();
        //运单
        !empty($trackingOrderList) && $this->trackingOrderList = collect($trackingOrderList)->map(function ($order) {
            return Arr::only($order, self::$trackingOrderFields);
        })->toArray();
        $this->type = $this->notifyType();
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

    /**
     * 获取第三方对接内容
     * @param $status
     * @param string $msg
     * @return string
     */
    public function getThirdPartyContent(bool $status, string $msg = ''): string
    {
        return '';
    }

    /**
     * 填充运单列表
     */
    public function fillTrackingOrderList()
    {
        $orderNoList = array_column($this->trackingOrderList, 'order_no');
        $trackingOrderNoList = array_column($this->trackingOrderList, 'tracking_order_no');
        //获取订单列表
        $orderList = Order::query()->whereIn('order_no', $orderNoList)->get(['order_no', 'out_order_no', DB::raw('type as order_type'), DB::raw('status as order_status')])->toArray();
        $orderList = array_create_index($orderList, 'order_no');
        //获取包裹
        $packageList = TrackingOrderPackage::query()->whereIn('tracking_order_no', $trackingOrderNoList)->get([
            'name', 'order_no', 'express_first_no', 'express_second_no', 'out_order_no', 'expect_quantity', 'actual_quantity','sticker_no', 'sticker_amount', 'is_auth', 'auth_fullname', 'auth_birth_date',
            DB::raw('type as tracking_type'),
            DB::raw('status as tracking_status'),
            DB::raw('IFNULL(delivery_amount,0.00) as delivery_amount'),
            DB::raw('IF(IFNULL(delivery_amount,0.00)=0.00,0,1) as delivery_count')])->toArray();
        $dbPackageList = Package::query()->whereIn('express_first_no', array_column($packageList, 'express_first_no'))->get(['status', 'type', 'express_first_no'])->toArray();
        $dbPackageList = array_create_index($dbPackageList, 'express_first_no');
        foreach ($packageList as &$package) {
            $package = array_merge($package, $dbPackageList[$package['express_first_no']]);
        }
        $packageList = array_create_group_index($packageList, 'order_no');
        Log::info('package_list', $packageList);
        //获取材料
        $materialList = TrackingOrderMaterial::query()->whereIn('tracking_order_no', $trackingOrderNoList)->get(['name', 'code', 'out_order_no', 'expect_quantity', 'actual_quantity'])->toArray();
        $materialList = array_create_group_index($materialList, 'order_no');
        Log::info('material_list', $materialList);
        //获取站点
        $batchList = empty($this->batchList) ? [$this->batch] : $this->batchList;
        Log::info('batch_list', $this->batchList);
        $batchList = array_create_group_index($batchList, 'batch_no');
        //组装
        $batchFieldsInTrackingOrderList = ['place_fullname', 'place_phone', 'place_country', 'place_post_code', 'place_house_number', 'place_city', 'place_street', 'place_address', 'actual_arrive_time', 'actual_distance', 'actual_time', 'actual_arrive_time', 'actual_distance', 'actual_time', 'signature', 'pay_type', 'pay_picture'];
        $tourFieldsInTrackingOrderList = ['line_name', 'execution_date', 'driver_id', 'driver_name', 'driver_phone', 'car_id', 'car_no'];
        $orderFieldsInTrackingOrderList = ['out_order_no', 'order_type', 'order_status'];
        $trackingOrderList = $this->trackingOrderList;
        foreach ($trackingOrderList as $k => $v) {
            $trackingOrderList[$k]['tour_expect_distance'] = $this->tour['expect_distance'];
            $trackingOrderList[$k]['tour_expect_time'] = $this->tour['expect_time'];
            $trackingOrderList[$k]['tour_actual_distance'] = $this->tour['actual_distance'];
            $trackingOrderList[$k]['tour_actual_time'] = $this->tour['actual_time'];
            $trackingOrderList[$k]['delivery_count'] = !empty($packageList[$v['order_no']]) ? array_sum(array_column($packageList[$v['order_no']], 'delivery_count')) : 0;
            $trackingOrderList[$k] = array_merge($trackingOrderList[$k], Arr::only($this->tour, $tourFieldsInTrackingOrderList));
            $trackingOrderList[$k] = array_merge($trackingOrderList[$k], Arr::only($batchList[$v['batch_no']] ?? [], $batchFieldsInTrackingOrderList));
            $trackingOrderList[$k] = array_merge($trackingOrderList[$k], Arr::only($orderList[$v['order_no']] ?? [], $orderFieldsInTrackingOrderList));
            $trackingOrderList[$k]['package_list'] = $packageList[$v['order_no']] ?? [];
            $trackingOrderList[$k]['material_list'] = $materialList[$v['order_no']] ?? [];
        }
        $this->trackingOrderList = collect($trackingOrderList)->groupBy('merchant_id')->toArray();
        unset($packageList, $materialList, $orderList);
    }
}
