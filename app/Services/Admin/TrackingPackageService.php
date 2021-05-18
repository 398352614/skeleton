<?php


namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\BagResource;
use App\Models\Bag;
use App\Models\TrackingPackage;
use App\Services\Admin\BaseService;
use App\Services\BaseConstService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TrackingPackageService extends BaseService
{
    public $filterRules = [
        'status' => ['=', 'status'],
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(TrackingPackage $model)
    {
        parent::__construct($model);
    }

    /**
     * 通过订单批量新增转运单
     * @param $order
     * @param $warehouseId
     * @throws BusinessLogicException
     */
    public function storeByOrder($order, $warehouseId)
    {
        $packageList = $this->getPackageService()->getList(['order_no' => $order['order_no']], ['*'], false);
        foreach ($packageList as $k => $v) {
            $this->allocate($v['express_first_no'], $warehouseId, false);
        }
    }

    /**
     * 分拨
     * @param $packageNo
     * @param $warehouseId
     * @param bool $check
     * @return array
     * @throws BusinessLogicException
     */
    public function allocate($packageNo, $warehouseId, $check = true)
    {
        //存在验证
        $package = $this->getPackageService()->getInfo(['express_first_no' => $packageNo], ['*'], false, ['created_at' => 'desc']);
        if (empty($package)) {
            throw new BusinessLogicException('当前包裹不存在系统中');
        }
        $order = $this->getOrderService()->getInfo(['order_no' => $package->order_no], ['*'], false)->toArray();
        $type = $this->getOrderService()->getTrackingOrderType($order);
        if ($check == true) {
            $this->getStockService()->check($package, $order, $type);
        }
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $warehouseId], ['*'], false)->toArray();
        $pieWarehouse = $this->getBaseWarehouseService()->getPickupWarehouseByOrder($order);//派件运单特殊处理
        $pieCenter = $this->getBaseWarehouseService()->getCenter($pieWarehouse);
        if ($warehouseId == $pieCenter['id']) {
            //如果本网点为该包裹的派件网点所属的分拨中心，则生成分拨转运单
            return $this->getStockService()->createTrackingPackage($package, $warehouse, $pieWarehouse, BaseConstService::TRACKING_PACKAGE_TYPE_1);
        } elseif ($warehouse['is_center'] == BaseConstService::YES) {
            //如果本网点为其他分拨中心，则生成中转转运单
            return $this->getStockService()->createTrackingPackage($package, $warehouse, $pieCenter, BaseConstService::TRACKING_PACKAGE_TYPE_2);
        } elseif ($warehouse['parent'] !==$pieWarehouse['parent']) {
            //如果本网点为其他分拨中心的网点，则生成长途中国转转运单
            return $this->getStockService()->createTrackingPackage($package, $warehouse, $pieCenter, BaseConstService::TRACKING_PACKAGE_TYPE_2);
        } else {
            throw new BusinessLogicException('生成转运单失败');
        }
    }
}
