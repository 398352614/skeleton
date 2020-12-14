<?php
/**
 * 运单 接口
 * Created by PhpStorm
 * User: long
 * Date: 2020/10/20
 * Time: 16:37
 */

namespace App\Http\Controllers\Api\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Merchant\TrackingOrderService;

/**
 * Class OrderController
 * @package App\Http\Controllers\Api\Admin
 * @property TrackingOrderService $service
 */
class TrackingOrderController extends BaseController
{
    public function __construct(TrackingOrderService $service)
    {
        parent::__construct($service);
    }

    /**
     * 运单从站点移除
     * @param $id
     * @throws BusinessLogicException
     */
    public function removeFromBatch($id)
    {
        return $this->service->removeFromBatch($id);
    }

    /**
     * 通过地址获取日期列表
     * @return array
     * @throws BusinessLogicException
     */
    public function getAbleDateListByAddress()
    {
        return $this->service->getAbleDateListByAddress($this->data);
    }


    /**
     * 通过订单，获取可分配的线路的取派日期
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getAbleDateList($id)
    {
        return $this->service->getAbleDateList($id);
    }


}
