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
use App\Services\BaseService;
use App\Services\OrderNoRuleService;
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
     * 线路 服务
     * @return LineService
     */
    private function getLineService()
    {
        return self::getInstance(LineService::class);
    }

    /**
     * 仓库 服务
     * @return WareHouseService
     */
    private function getWareHouseService()
    {
        return self::getInstance(WareHouseService::class);
    }

    /**
     * 取件线路 服务
     * @return TourService
     */
    private function getTourService()
    {
        return self::getInstance(TourService::class);
    }

    /**
     * 站点 服务
     * @return BatchService
     */
    private function getBatchService()
    {
        return self::getInstance(BatchService::class);
    }

    /**
     * 包裹 服务
     * @return PackageService
     */
    private function getPackageService()
    {
        return self::getInstance(PackageService::class);
    }

    /**
     * 材料 服务
     * @return MaterialService
     */
    private function getMaterialService()
    {
        return self::getInstance(MaterialService::class);
    }

    /**
     * 单号规则 服务
     * @return OrderNoRuleService
     */
    private function getOrderNoRuleService()
    {
        return self::getInstance(OrderNoRuleService::class);
    }

    /**
     * 新增
     * @param $params
     * @param $orderNo
     * @return bool
     * @throws BusinessLogicException
     */
    private function store($params, $orderNo)
    {
        //填充发件人信息
        $line = $this->fillWarehouseInfo($params, BaseConstService::YES);
        //生成运单号
        $params['tracking_order_no'] = $this->getOrderNoRuleService()->createTrackingOrderNo();
        /**********************************************生成运单********************************************************/
        $trackingOrder = parent::create($params);
        if ($trackingOrder == false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $trackingOrder = $trackingOrder->getAttributes();
        /*****************************************运单加入站点*********************************************************/
        list($batch, $tour) = $this->getBatchService()->join($trackingOrder, $line);
        $this->fillBatchTourInfo($trackingOrder, $batch, $tour);
        /*******************************************材料填充取派信息***************************************************/
        $rowCount = $this->getMaterialService()->update(['order_no' => $orderNo], ['batch_no' => $batch['batch_no'], 'tour_no' => $tour['tour_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //重新统计站点金额
        $this->getBatchService()->reCountAmountByNo($batch['batch_no']);
        //重新统计取件线路金额
        $this->getTourService()->reCountAmountByNo($tour['tour_no']);
        //运单轨迹-运单创建
        TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_CREATED);
        //运单轨迹-运单加入站点
        TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_BATCH, $batch);
        //运单轨迹-运单加入取件线路
        TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_TOUR, $tour);
        return $tour;
    }


    /**
     * 填充仓库信息
     * @param $params
     * @param $merchantAlone
     * @return array
     * @throws BusinessLogicException
     */
    private function fillWarehouseInfo(&$params, $merchantAlone = BaseConstService::NO)
    {
        //获取线路
        $line = $this->getLineService()->getInfoByRule($params, BaseConstService::TRACKING_ORDER_OR_BATCH_1, $merchantAlone);
        //获取仓库
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('仓库不存在');
        }
        //填充发件人信息
        $params = array_merge($params, [
            'warehouse_fullname' => $warehouse['fullname'],
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
     * 填充运单数据
     * @param $trackingOrder
     * @param $batch
     * @param $tour
     * @throws BusinessLogicException
     */
    public function fillBatchTourInfo($trackingOrder, $batch, $tour)
    {
        $rowCount = parent::updateById($trackingOrder['id'], self::getBatchTourFillData($batch, $tour));
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
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
            'driver_id' => $tour['driver_id'] ?? null,
            'driver_name' => $tour['driver_name'] ?? '',
            'driver_phone' => $tour['driver_phone'] ?? '',
            'car_id' => $tour['car_id'] ?? null,
            'car_no' => $tour['car_no'] ?? '',
            'status' => $tour['status'] ?? BaseConstService::TRACKING_ORDER_STATUS_1
        ];
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