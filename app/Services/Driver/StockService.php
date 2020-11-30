<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/11/29
 * Time: 16:06
 */

namespace App\Services\Driver;


use App\Exceptions\BusinessLogicException;
use App\Models\Stock;
use App\Services\BaseConstService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class StockService extends BaseService
{
    public function __construct(Stock $stock, $resource = null, $infoResource = null)
    {
        parent::__construct($stock, $resource, $infoResource);
    }


    /**
     * 分拣入库
     * @param $packageNo
     * @throws BusinessLogicException
     */
    public function packagePickOut($packageNo)
    {
        $package = $this->getPackageService()->getInfo(['express_first_no' => $packageNo], ['*'], false, ['created_at' => 'desc']);
        if (empty($package)) {
            throw new BusinessLogicException('当前包裹不存在系统中');
        }
        if (in_array($package->status, [BaseConstService::PACKAGE_STATUS_3, BaseConstService::PACKAGE_STATUS_4])) {
            throw new BusinessLogicException('当前包裹已取消取派或删除');
        }
        $order = $this->getOrderService()->getInfo(['order_no' => $package->order_no], ['*'], false)->toArray();
        $type = $this->getOrderService()->getTrackingOrderType($order);
        if (empty($type) || ($type != BaseConstService::TRACKING_ORDER_TYPE_2)) {
            throw new BusinessLogicException('当前包裹不能生成对应派件运单');
        }
        if (!empty($order['second_execution_date'])) {
            $executionDate = $order['second_execution_date'];
            $line = [];
        } else {
            list($executionDate, $line) = $this->getLineService()->getCurrentDate(['place_post_code' => $order['second_place_post_code'], 'type' => $type], $order['merchant_id']);
        }
        $trackingOrder = [
            'place_fullname' => $order['second_place_fullname'],
            'place_phone' => $order['second_place_phone'],
            'place_country' => $order['second_place_country'],
            'place_post_code' => $order['second_place_post_code'],
            'place_house_number' => $order['second_place_house_number'],
            'place_city' => $order['second_place_city'],
            'place_street' => $order['second_place_street'],
            'place_address' => $order['second_place_address'],
            'place_lat' => $order['second_place_lat'],
            'place_lon' => $order['second_place_lon'],
            'execution_date' => $executionDate,
            'type' => $type,
        ];
        $trackingOrder = array_merge($trackingOrder, Arr::only($order, ['merchant_id', 'order_no', 'out_user_id', 'out_order_no', 'mask_code', 'special_remark']));
        //生成运单号
        $trackingOrder['tracking_order_no'] = $this->getOrderNoRuleService()->createTrackingOrderNo();
        $tour = $this->getTrackingOrderService()->store($trackingOrder, $order['order_no'], $line);
        //包裹分拣
        $this->pickOut($package, $tour, $trackingOrder);
        return [
            'line_id' => $tour['line_id'],
            'line_name' => $tour['line_name'],
            'execution_date' => $executionDate
        ];
    }


    /**
     * 分拣入库
     * @param $package
     * @param $tour
     * @param $trackingOrder
     * @throws BusinessLogicException
     */
    public function pickOut($package, $tour, $trackingOrder)
    {
        $dbPackage = parent::getInfoLock(['express_first_no' => $package['express_first_no']], ['*'], false);
        if (!empty($dbPackage)) {
            throw  new BusinessLogicException('当前包裹已入库');
        }
        //加入库存
        $stockData = [
            'line_id' => $tour['line_id'],
            'line_name' => $tour['line_name'],
            'tracking_order_no' => $trackingOrder['tracking_order_no'],
            'execution_date' => $tour['execution_date'],
            'operator' => auth()->user()->fullname,
            'order_no' => $package['order_no'],
            'express_first_no' => $package['express_first_no']
        ];
        $rowCount = parent::create($stockData);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //生成入库日志
        $rowCount = $this->getStockInLogService()->create($stockData);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }


}