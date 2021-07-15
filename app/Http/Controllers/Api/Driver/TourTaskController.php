<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/30
 * Time: 10:41
 */

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\BaseController;
use App\Services\Driver\TourTaskService;

/**
 * Class TourTaskController
 * @package App\Http\Controllers\Api\Driver
 * @property TourTaskService $service
 */
class TourTaskController extends BaseController
{
    public function __construct(TourTaskService $service)
    {
        parent::__construct($service);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * 获取所有的订单特殊事项列表
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getSpecialRemarkList($id)
    {
        return $this->service->getSpecialRemarkList($id);
    }

    /**
     * 获取站点特殊事项列表
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getBatchSpecialRemarkList($id)
    {
        return $this->service->getBatchSpecialRemarkList($this->data['batch_id']);
    }

    /**
     * 获取特殊事项
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getSpecialRemark($id)
    {
        return $this->service->getSpecialRemark($this->data['tracking_order_id']);
    }


    /**
     * 获取运单及包裹列表
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getTrackingOrderList()
    {
        return $this->service->getTrackingOrderList($this->data);
    }

    /**
     * 获取所有信息
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getAllInfo()
    {
        return $this->service->getAllInfo();
    }
}
