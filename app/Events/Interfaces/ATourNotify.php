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

abstract class ATourNotify
{
    public $tour;

    public $batch;

    public $batchList;

    public $trackingOrderList;

    public $type;

    public static $tourFields = ['line_name', 'tour_no', 'execution_date', 'expect_distance', 'expect_time', 'driver_id', 'driver_name', 'driver_phone', 'car_id', 'car_no'];

    public static $batchFields = [
        'tour_no', 'batch_no', 'place_fullname', 'place_phone', 'place_country', 'place_province', 'place_post_code', 'place_house_number',
        'place_city', 'place_district', 'place_street', 'place_address', 'expect_arrive_time', 'expect_time', 'expect_distance', 'signature', 'cancel_remark',
        'pay_type', 'pay_picture', 'status', 'auth_fullname', 'auth_birth_date'
    ];

    public static $trackingOrderFields = [
        'merchant_id', 'tour_no', 'batch_no', 'tracking_order_no', 'order_no', 'out_order_no', 'status', 'type'
    ];

    public function __construct($tour, $batch, $batchList, $trackingOrderList)
    {
        //取件线路
        $this->tour = Arr::only($tour, self::$tourFields);
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
        $notifyType = $this->notifyType();
        Log::channel('job')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'notifyType', [$notifyType]);
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
     * @param bool $packageFill
     * @param bool $materialFill
     */
    public function fillTrackingOrderList($packageFill = false, $materialFill = false)
    {
        $orderNoList = array_column($this->trackingOrderList, 'order_no');
        $trackingOrderNoList = array_column($this->trackingOrderList, 'tracking_order_no');
        //获取订单列表
        $orderList = Order::query()->whereIn('order_no', $orderNoList)->get(['order_no', 'out_order_no', DB::raw('type as order_type'), DB::raw('status as order_status')])->toArray();
        $orderList = array_create_index($orderList, 'order_no');
        //获取包裹
        $packageList = [];
        if ($packageFill === true) {
            $packageList = TrackingOrderPackage::query()->whereIn('tracking_order_no', $trackingOrderNoList)->get(['name', 'order_no', 'express_first_no', 'express_second_no', 'out_order_no', 'expect_quantity', 'actual_quantity', DB::raw('type as tracking_type'), DB::raw('status as tracking_status'), 'sticker_no', 'sticker_amount', DB::raw('IFNULL(delivery_amount,0.00) as delivery_amount'), DB::raw('IF(IFNULL(delivery_amount,0.00)=0.00,0,1) as delivery_count'), 'is_auth', 'auth_fullname', 'auth_birth_date'])->toArray();
            $dbPackageList = Package::query()->whereIn('express_first_no', array_column($packageList, 'express_first_no'))->get(['status', 'type', 'express_first_no'])->toArray();
            $dbPackageList = array_create_index($dbPackageList, 'express_first_no');
            foreach ($packageList as &$package) {
                $package = array_merge($package, $dbPackageList[$package['express_first_no']]);
            }
            $packageList = array_create_group_index($packageList, 'order_no');
        }
        //获取材料
        $materialList = [];
        if ($materialFill === true) {
            $materialList = TrackingOrderMaterial::query()->whereIn('tracking_order_no', $trackingOrderNoList)->get(['order_no', 'name', 'code', 'out_order_no', 'expect_quantity', 'actual_quantity'])->toArray();
            $materialList = array_create_group_index($materialList, 'order_no');
        }
        //将包裹材料组装至运单下
        $this->trackingOrderList = collect($this->trackingOrderList)->map(function ($trackingOrder) use ($packageFill, $materialFill, $packageList, $materialList, $orderList) {
            ($packageFill == true) && $trackingOrder['package_list'] = $packageList[$trackingOrder['order_no']] ?? [];
            ($materialFill == true) && $trackingOrder['material_list'] = $materialList[$trackingOrder['order_no']] ?? [];
            $trackingOrder = array_merge($trackingOrder, !empty($orderList[$trackingOrder['order_no']]) ? Arr::only($orderList[$trackingOrder['order_no']], ['order_no', 'out_order_no', 'order_type', 'order_status']) : []);
            $trackingOrder['delivery_count'] = !empty($packageList[$trackingOrder['order_no']]) ? array_sum(array_column($packageList[$trackingOrder['order_no']], 'delivery_count')) : 0;
            return collect($trackingOrder);
        })->toArray();
        unset($packageList, $materialList, $orderList);
    }
}
