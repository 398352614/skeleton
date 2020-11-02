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
     * @return string
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        //填充发件人信息
        $line = $this->fillSender($params, BaseConstService::YES);
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
        return 'true';
    }


    /**
     * 填充发件人信息
     * @param $params
     * @param $merchantAlone
     * @return array
     * @throws BusinessLogicException
     */
    private function fillSender(&$params, $merchantAlone = BaseConstService::NO)
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
            'sender_fullname' => $warehouse['fullname'],
            'sender_phone' => $warehouse['phone'],
            'sender_country' => $warehouse['country'],
            'sender_post_code' => $warehouse['post_code'],
            'sender_house_number' => $warehouse['house_number'],
            'sender_city' => $warehouse['city'],
            'sender_street' => $warehouse['street'],
            'sender_address' => $warehouse['address'],
        ]);
        return $line;
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