<?php
/**
 * 运单 服务
 * User: long
 * Date: 2020/10/27
 * Time: 11:17
 */

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Models\TrackingOrder;
use App\Services\BaseConstService;
use App\Services\OrderTrailService;
use App\Services\TrackingOrderTrailService;

/**
 * Class TrackingOrderService
 * @package App\Services\Driver
 * @property TrackingOrder $model
 */
class TrackingOrderService extends BaseService
{
    public function __construct(TrackingOrder $trackingOrder, $resource = null, $infoResource = null)
    {
        parent::__construct($trackingOrder, $resource, $infoResource);
    }

    /**
     * 新增
     * @param $params
     * @param $orderNo
     * @param $line
     * @param bool $again
     * @return bool
     * @throws BusinessLogicException
     * @params $line
     */
    public function store($params, $orderNo, $line, $again = false)
    {
        //填充发件人信息
        $line = $this->fillWarehouseInfo($params, BaseConstService::YES, $line);
        /**********************************************生成运单********************************************************/
        $trackingOrder = parent::create($params);
        if ($trackingOrder == false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $trackingOrder = $trackingOrder->getAttributes();
        /*****************************************运单加入站点*********************************************************/
        list($batch, $tour) = $this->getBatchService()->join($trackingOrder, $line);
        $trackingOrder = $this->fillBatchTourInfo($trackingOrder, $batch, $tour, true);
        /*******************************************填充运单信息至订单***************************************************/
        $this->fillToOrder($orderNo, $trackingOrder, false);
        /*******************************************生成运单包裹和材料***************************************************/
        $this->addAllItemList($orderNo, $trackingOrder);
        //重新统计站点金额
        $this->getBatchService()->reCountAmountByNo($batch['batch_no']);
        //重新统计线路任务金额
        $this->getTourService()->reCountAmountByNo($tour['tour_no']);
        //运单轨迹-运单创建
        TrackingOrderTrailService::trackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_CREATED);
        //运单轨迹-运单加入站点
        TrackingOrderTrailService::trackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_BATCH, $batch);
        //运单轨迹-运单加入线路任务
        TrackingOrderTrailService::trackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_TOUR, $tour);
        //订单轨迹-运单中途创建
        if ($again == true) {
            OrderTrailService::orderStatusChangeCreateTrail($trackingOrder, BaseConstService::ORDER_TRAIL_RESTART);
        } else {
            OrderTrailService::orderStatusChangeCreateTrail($trackingOrder, BaseConstService::ORDER_TRAIL_CREATED);
        }
        return $tour;
    }


    /**
     * 新增运单获取列表
     * @param $orderNo
     * @param $trackingOrder
     * @throws BusinessLogicException
     */
    private function addAllItemList($orderNo, $trackingOrder)
    {
        $packageList = $this->getPackageService()->getList(['order_no' => $orderNo, 'status' => ['in', [BaseConstService::PACKAGE_TYPE_1, BaseConstService::PACKAGE_TYPE_2]]], ['*'], false)->toArray();
        if (!empty($packageList)) {
            data_set($packageList, '*.tour_no', $trackingOrder['tour_no']);
            data_set($packageList, '*.batch_no', $trackingOrder['batch_no']);
            data_set($packageList, '*.tracking_order_no', $trackingOrder['tracking_order_no']);
            data_set($packageList, '*.type', $trackingOrder['type']);
            data_set($packageList, '*.status', $trackingOrder['status']);
            data_set($packageList, '*.execution_date', $trackingOrder['execution_date']);
            $rowCount = $this->getTrackingOrderPackageService()->insertAll($packageList);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败，请重新操作');
            }
        }
    }


    /**
     * 填充网点信息
     * @param $params
     * @param $merchantAlone
     * @params $line
     * @return array
     * @throws BusinessLogicException
     */
    private function fillWarehouseInfo(&$params, $merchantAlone = BaseConstService::NO, $line = [])
    {
        //获取线路
        empty($line) && $line = $this->getLineService()->getInfoByRule($params, BaseConstService::TRACKING_ORDER_OR_BATCH_1, $merchantAlone);
        //获取网点
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('网点不存在');
        }
        //填充发件人信息
        $params = array_merge($params, [
            'warehouse_fullname' => $warehouse['name'],
            'warehouse_phone' => $warehouse['phone'],
            'warehouse_country' => $warehouse['country'],
            'warehouse_post_code' => $warehouse['post_code'],
            'warehouse_house_number' => $warehouse['house_number'],
            'warehouse_city' => $warehouse['city'],
            'warehouse_street' => $warehouse['street'],
            'warehouse_address' => $warehouse['address'],
            'warehouse_lon' => $warehouse['warehouse_lon'],
            'warehouse_lat' => $warehouse['warehouse_lat']
        ]);
        return $line;
    }


    /**
     * 获取运单
     * @param $orderNo
     * @return array
     */
    public function getTrackingOrderByOrderNo($orderNo)
    {
        $trackingOrder = parent::getInfo(['order_no' => $orderNo], ['*'], false, ['created_at' => 'desc']);
        return !empty($trackingOrder) ? $trackingOrder->toArray() : [];
    }


    /**
     * 填充运单信息至订单
     * @param $orderNo
     * @param $trackingOrder
     * @params $fillMaterial
     * @throws BusinessLogicException
     */
    public function fillToOrder($orderNo, $trackingOrder, $fillMaterial = true)
    {
        if ($fillMaterial == true) {
            $rowCount = $this->getMaterialService()->update(['order_no' => $orderNo], ['tracking_order_no' => $trackingOrder['tracking_order_no']]);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败，请重新操作');
            }
        }
        $rowCount = $this->getPackageService()->update(['order_no' => $orderNo], ['tracking_order_no' => $trackingOrder['tracking_order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $rowCount = $this->getOrderService()->update(['order_no' => $orderNo], ['tracking_order_no' => $trackingOrder['tracking_order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }


    /**
     * 填充运单数据
     * @param $trackingOrder
     * @param $batch
     * @param $tour
     * @param boolean $isUpdateOrder
     * @return array
     * @throws BusinessLogicException
     */
    public function fillBatchTourInfo($trackingOrder, $batch, $tour, $isUpdateOrder = false)
    {
        $data = self::getBatchTourFillData($batch, $tour);
        $rowCount = parent::update(['id' => $trackingOrder['id'], 'driver_id' => ['all', null]], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $trackingOrder = array_merge($trackingOrder, $data);
        ($isUpdateOrder == true) && $this->getOrderService()->updateByTrackingOrder($trackingOrder);
        return $trackingOrder;
    }

    /**
     * 获取运单填充数据
     * @param $batch
     * @param $tour
     * @return array
     */
    private static function getBatchTourFillData($batch, $tour)
    {
        return [
            'execution_date' => $batch['execution_date'],
            'batch_no' => $batch['batch_no'],
            'tour_no' => $tour['tour_no'],
            'line_id' => $tour['line_id'],
            'line_name' => $tour['line_name'],
            'driver_id' => $tour['driver_id'] ?? null,
            'driver_name' => $tour['driver_name'] ?? '',
            'driver_phone' => $tour['driver_phone'] ?? '',
            'car_id' => $tour['car_id'] ?? null,
            'car_no' => $tour['car_no'] ?? '',
            'status' => $tour['status'] ?? BaseConstService::TRACKING_ORDER_STATUS_1
        ];
    }

    /**
     * 获取运单类型
     * @param $orderType
     * @return int
     */
    public function getTypeByOrderType($orderType)
    {
        return (intval($orderType) == BaseConstService::ORDER_TYPE_2) ? BaseConstService::TRACKING_ORDER_TYPE_2 : BaseConstService::TRACKING_ORDER_TYPE_1;
    }


    public function getOrder($where = [], $orderWhere = [], $orderSelectFields = ['*'])
    {
        return $this->model->getOrder($where, $orderWhere, $orderSelectFields);
    }

    public function getOrderList($where = [], $orderWhere = [], $orderSelectFields = ['*'])
    {
        return $this->model->getOrderList($where, $orderWhere, $orderSelectFields);
    }

    public function getPackageList($where, $packageWhere = [], $packageSelectFields = ['*'], $packageGroupFields = [])
    {
        $orderList = $this->model->getOrderList($where, [], ['order_no']);
        if (empty($orderList)) return [];
        $packageWhere['order_no'] = ['in', array_column($orderList, 'order_no')];
        return $this->getPackageService()->getList($packageWhere, $packageSelectFields, false, $packageGroupFields)->toArray();
    }

    public function getPackageFieldSum($PackageField, $where, $packageWhere = [])
    {
        $orderList = $this->model->getOrderList($where, [], ['order_no']);
        if (empty($orderList)) return 0;
        $packageWhere['order_no'] = ['in', array_column($orderList, 'order_no')];
        return $this->getPackageService()->sum($PackageField, $packageWhere);
    }

}
