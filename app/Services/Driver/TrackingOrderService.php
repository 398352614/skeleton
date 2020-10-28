<?php
/**
 * 运单 服务
 * User: long
 * Date: 2020/10/27
 * Time: 11:17
 */

namespace App\Services\Driver;

use App\Models\TrackingOrder;
use App\Services\BaseService;

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

    public function getMaterialList($where, $materialWhere = [], $selectFields = ['*'], $materialGroupFields = [])
    {
        $orderList = $this->model->getOrderList($where, [], ['order_no']);
        if (empty($orderList)) return [];
        $materialWhere['order_no'] = ['in', array_column($orderList, 'order_no')];
        return $this->getMaterialService()->getList($materialWhere, $selectFields, false, $materialGroupFields)->toArray();
    }

}