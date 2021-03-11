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
use App\Traits\CompanyTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class StockService extends BaseService
{
    public function __construct(Stock $stock, $resource = null, $infoResource = null)
    {
        parent::__construct($stock, $resource, $infoResource);
    }

    /**
     * 包裹出库
     * @param $packageList
     * @param $tour
     * @throws BusinessLogicException
     */
    public function outWarehouse($packageList, $tour)
    {
        $packageList = array_create_index($packageList, 'express_first_no');
        $dbPackageList = parent::getList(['express_first_no' => ['in', array_column($packageList, 'express_first_no')]], ['express_first_no'], false)->toArray();
        $rowCount = parent::delete(['express_first_no' => ['in', array_column($packageList, 'express_first_no')]]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        if (empty($dbPackageList)) return;
        $stockDataList = [];
        foreach ($dbPackageList as $dbPackage) {
            $no = $dbPackage['express_first_no'];
            $stockDataList[] = [
                'line_id' => $tour['line_id'],
                'line_name' => $tour['line_name'],
                'tracking_order_no' => $packageList[$no]['tracking_order_no'],
                'execution_date' => $tour['execution_date'],
                'operator' => auth()->user()->fullname,
                'operator_id' => auth()->user()->id,
                'order_no' => $packageList[$no]['order_no'],
                'express_first_no' => $no
            ];
        }
        $rowCount = $this->getStockOutLogService()->insertAll($stockDataList);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }


    /**
     * 包裹分拣入库
     * @param $packageNo
     * @return array
     * @throws BusinessLogicException
     */
    public function packagePickOut($packageNo)
    {
        $line = [];
        //存在验证
        $package = $this->getPackageService()->getInfo(['express_first_no' => $packageNo], ['*'], false, ['created_at' => 'desc']);
        if (empty($package)) {
            throw new BusinessLogicException('当前包裹不存在系统中');
        }
        //在库验证
        $this->stockExistCheck($package);
        $order = $this->getOrderService()->getInfo(['order_no' => $package->order_no], ['*'], false)->toArray();
        $type = $this->getOrderService()->getTrackingOrderType($order);
        //异常验证
        $this->stockExceptionCheck($order);
        if (!in_array($package->status, [BaseConstService::PACKAGE_STATUS_1, BaseConstService::PACKAGE_STATUS_2])) {
            throw new BusinessLogicException('当前包裹状态为[:status_name],不能分拣入库', 1000, ['status_name' => $package->status_name]);
        }
        if (empty($type) || ($type != BaseConstService::TRACKING_ORDER_TYPE_2)) {
            throw new BusinessLogicException('当前包裹不能生成对应派件运单或已生成派件运单');
        }
        //获取最近可选日期
        $executionDate = ($order['type'] == BaseConstService::ORDER_TYPE_2) ? $order['execution_date'] : $order['second_execution_date'];
        if (empty($executionDate) || Carbon::today()->gte($executionDate . ' 00:00:00')) {
            $placeCode = ($order['type'] == BaseConstService::ORDER_TYPE_2) ? $order['place_post_code'] : $order['second_place_post_code'];
            list($executionDate, $line) = $this->getLineService()->getCurrentDate(['place_post_code' => $placeCode, 'type' => $type], $order['merchant_id']);
        }
        //有效期验证
        if ($executionDate > $package['expiration_date']) {
            throw new BusinessLogicException('包裹派送已超期，请联系管理员进行处理');
        }
        //格式处理
        $trackingOrder= $this->form($order, $executionDate, $type);
        //生成运单号
        $trackingOrder['tracking_order_no'] = $this->getOrderNoRuleService()->createTrackingOrderNo();
        $tour = $this->getTrackingOrderService()->store($trackingOrder, $order['order_no'], $line, true);
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
            'operator_id' => auth()->user()->id,
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
        //推送入库分拣信息
        dispatch(new \App\Jobs\PackagePickOut([$package]));
    }

    /**
     * 库存已在库验证
     * @param $package
     * @throws BusinessLogicException
     */
    public function stockExistCheck($package)
    {
        $stock = $this->getStockService()->getInfo(['express_first_no' => $package['express_first_no']], ['*'], false);
        if (!empty($stock)) {
            $trackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $package['order_no'], 'driver_id' => ['all', null]], ['*'], false, ['id' => 'desc']);
            if (!empty($trackingOrder)) {
                $lineName = $trackingOrder['line_name'] ?? '';
                $date = $trackingOrder['execution_date'] ?? null;
            } else {
                $lineName = $stock['line_name'];
                $date = $stock['execution_date'];
            }
            throw new BusinessLogicException('包裹已入库，当前线路[:line_name]，派送日期[:execution_date]', 1000, ['line_name' => $lineName, 'execution_date' => $date]);
        }
    }

    /**
     * 异常验证
     * @param $order
     * @throws BusinessLogicException
     */
    public function stockExceptionCheck($order)
    {
        $trackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $order['order_no']], ['*'], false, ['id' => 'desc']);
        //取派订单的取件失败，但是包裹拿到了（司机忘签了）
        if (in_array($order['status'], [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2, BaseConstService::ORDER_STATUS_4])
            && $order['type'] == BaseConstService::ORDER_TYPE_3
            && !empty($trackingOrder)
            && $trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_1
            && $trackingOrder['status'] == BaseConstService::TRACKING_ORDER_STATUS_6
            && !empty(CompanyTrait::getCompany()['stock_exception_verify'])
        ) {
            if (CompanyTrait::getCompany()['stock_exception_verify'] == BaseConstService::STOCK_EXCEPTION_VERIFY_2) {
                //未开启审核，自动入库，返回线路日期
                throw new BusinessLogicException('当前包裹不能生成对应派件运单，请进行异常入库处理', 5005);
            } else {
                //开启审核，不返回值
                throw new BusinessLogicException('当前包裹不能生成对应派件运单，请进行异常入库处理', 5004);
            }
        }
    }

    /**
     * 格式化
     * @param $order
     * @param $executionDate
     * @param $type
     * @return array
     */
    public function form($order, $executionDate, $type)
    {
        if ($order['type'] == BaseConstService::ORDER_TYPE_2) {
            $trackingOrder = Arr::only($order, ['place_fullname', 'place_phone', 'place_country', 'place_post_code', 'place_house_number', 'place_city', 'place_street', 'place_address', 'place_lat', 'place_lon']);
        } else {
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
                'place_lon' => $order['second_place_lon']
            ];
        }
        $trackingOrder['execution_date'] = $executionDate;
        $trackingOrder['type'] = $type;
        return array_merge($trackingOrder, Arr::only($order, ['merchant_id', 'order_no', 'out_user_id', 'out_order_no', 'mask_code', 'special_remark']));
    }

}
