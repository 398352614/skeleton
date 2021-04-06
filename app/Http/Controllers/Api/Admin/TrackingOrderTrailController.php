<?php
/**
 * 运单轨迹
 */

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\TrackingOrderTrailService;

/**
 * Class TrackingOrderTrailController
 * @package App\Http\Controllers\Api\Admin
 * @property TrackingOrderTrailService $service
 */
class TrackingOrderTrailController extends BaseController
{
    public function __construct(TrackingOrderTrailService $service)
    {
        parent::__construct($service);
    }


    /**
     * 运单轨迹
     * @param $trackingOrderNo
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function index($trackingOrderNo)
    {
        return $this->service->index($trackingOrderNo);
    }

    /**
     * 手动新增
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * 手动删除
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
