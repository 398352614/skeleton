<?php

/**
 * 线路任务 操作
 * User: long
 * Date: 2019/12/30
 * Time: 11:54
 */

namespace App\Http\Controllers\Api\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\BaseConstService;
use App\Services\Driver\TourService;
use App\Traits\TourTrait;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class TourController
 * @package App\Http\Controllers\Api\Driver
 * @property TourService $service
 */
class TourController extends BaseController
{
    public function __construct(TourService $service)
    {
        parent::__construct($service, ['updateBatchIndex']);
    }

    /**
     * 锁定-开始装货
     * @param $id
     * @throws BusinessLogicException
     */
    public function lock($id)
    {
        return $this->service->lock($id);
    }

    /**
     * 解锁
     * @param $id
     * @throws BusinessLogicException
     */
    public function unlock($id)
    {
        return $this->service->unlock($id);
    }

    /**
     * 备注
     * @param $id
     * @throws BusinessLogicException
     */
    public function remark($id)
    {
        return $this->service->remark($id, $this->data);
    }

    /**
     * 更换车辆
     * @param $id
     * @throws BusinessLogicException
     */
    public function changeCar($id)
    {
        return $this->service->changeCar($id, $this->data);
    }

    /**
     * 出库前验证
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function checkOutWarehouse($id)
    {
        $this->service->checkOutWarehouse($id, $this->data);
        return 'true';
    }

    /**
     * 司机出库
     * @param $id
     * @throws BusinessLogicException
     */
    public function outWarehouse($id)
    {
        list($tour, $cancelTrackingOrderList) = $this->service->outWarehouse($id, $this->data);
        TourTrait::afterOutWarehouse($tour, $cancelTrackingOrderList);
    }

    /**
     * 司机实际出库
     * @param $id
     * @return void
     * @throws BusinessLogicException
     */
    public function actualOutWarehouse($id)
    {
        $tour = $this->service->getInfo(['id' => $id], ['*'], false)->toArray();
        $this->service->actualOutWarehouse($id, $this->data);
        TourTrait::actualOutWarehouse($tour);
        return;
    }

    /**
     * 获取站点列表
     * @param $id
     * @return array|Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getBatchList($id)
    {
        return $this->service->getBatchList($id);
    }

    /**
     * 获取站点的运单列表
     * @param $id
     * @return array|Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getBatchTrackingOrderList($id)
    {
        return $this->service->getBatchTrackingOrderList($id, $this->data);
    }


    /**
     * 到达站点
     * @param $id
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws BusinessLogicException
     */
    public function batchArrive($id)
    {
        return $this->service->batchArrive($id, $this->data);
    }

    /**
     * 获取站点详情
     * @param $id
     * @return array|Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getBatchInfo($id)
    {
        return $this->service->getBatchInfo($id, $this->data);
    }

    /**
     * 站点 异常上报
     * @param $id
     * @return array|Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function batchException($id)
    {
        return $this->service->batchException($id, $this->data);
    }

    /**
     * 站点 取消取派
     * @param $id
     * @throws BusinessLogicException
     */
    public function batchCancel($id)
    {
        list($tour, $batch, $cancelTrackingOrderList) = $this->service->batchCancel($id, $this->data);
        TourTrait::afterBatchCancel($tour, $batch, $cancelTrackingOrderList);
    }

    /**
     * 签收验证
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function checkBatchSign($id)
    {
        return $this->service->checkBatchSign($id, $this->data);
    }

    /**
     * 站点 签收
     * @param $id
     * @throws BusinessLogicException
     */
    public function batchSign($id)
    {
        list($tour, $batch) = $this->service->batchSign($id, $this->data);
        TourTrait::afterBatchSign($tour, $batch);
    }

    /**
     * 获取线路任务统计数据
     * @param $id
     * @return array|Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getTotalInfo($id)
    {
        return $this->service->getTotalInfo($id);
    }

    /**
     * 司机入库
     * @param $id
     * @throws BusinessLogicException
     */
    public function inWarehouse($id)
    {
        $tour = $this->service->inWarehouse($id, $this->data);
        TourTrait::afterBackWarehouse($tour);
    }

    /**
     * @api {POST}  api/driver/tour/update-batch-index 管理员端:更新批次的派送顺序
     * @apiName update-batch-index
     * @apiGroup driver
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 更新批次的派送顺序
     * @apiParam {String}   batch_ids                  有序的批次数组
     * @apiParam {String}   tour_no                    在途编号
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"添加线路",
     *  "data":{}
     * }
     */
    public function updateBatchIndex()
    {
        return $this->service->updateBatchIndex($this->data,BaseConstService::YES);
    }

    /**
     * 获取线路及线路任务列表
     * @return array
     */
    public function getTourList()
    {
        return $this->service->getTourList();
    }

    /**
     * 站点跳过
     * @param $id
     * @return void
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function batchSkip($id)
    {
        return $this->service->batchSkip($id, $this->data);
    }

    /**
     * 站点恢复
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function batchRecovery($id)
    {
        return $this->service->batchRecovery($id, $this->data);
    }

    /**
     * 延迟
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function delay($id)
    {
        return $this->service->delay($id, $this->data);
    }
}
