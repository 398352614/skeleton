<?php
/**
 * 运单 接口
 * Created by PhpStorm
 * User: long
 * Date: 2020/10/20
 * Time: 16:37
 */

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\TrackingOrderService;
use Illuminate\Database\Eloquent\Builder;

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
     * 查询初始化
     * @return array
     */
    public function initIndex()
    {
        return $this->service->initIndex();
    }

    /**
     * 运单统计
     * @return array
     * @throws BusinessLogicException
     */
    public function trackingOrderCount()
    {
        return $this->service->trackingOrderCount($this->data);
    }

    /**
     * 线路列表
     * @return array|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getLineList()
    {
        return $this->service->getLineList();
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    public function show($id)
    {
        return $this->service->show($id);
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
     * 批量运单从站点移除
     * @throws BusinessLogicException
     */
    public function removeListFromBatch()
    {
        return $this->service->removeListFromBatch($this->data);
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

    /**
     * 获取可分配的站点列表
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getAbleBatchList($id)
    {
        return $this->service->getAbleBatchList($id, $this->data);
    }


    /**
     * 运单分配至站点
     * @param $id
     * @return string
     * @throws BusinessLogicException
     */
    public function assignToBatch($id)
    {
        return $this->service->assignToBatch($id, $this->data);
    }

    /**
     * 批量运单分配至线路任务
     * @throws BusinessLogicException
     * @throws \WebSocket\BadOpcodeException
     */
    public function assignListTour()
    {
        return $this->service->assignListTour($this->data);
    }

    /**
     * 导出运单
     * @return array
     * @throws BusinessLogicException
     */
    public function trackingOrderExport()
    {
        return $this->service->trackingOrderExport();
    }

    /**
     *
     * @return mixed
     * @throws BusinessLogicException
     */
    public function changeOutStatus()
    {
        return $this->service->changeOutStatus($this->data);
    }

}
