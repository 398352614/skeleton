<?php

/**
 * 取件线路 操作
 * User: long
 * Date: 2019/12/30
 * Time: 11:54
 */

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\BaseController;
use App\Services\Driver\TourService;
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
        //, 'outWarehouse', 'batchCancel', 'batchSign'
        parent::__construct($service, ['updateBatchIndex']);
    }

    /**
     * 锁定-开始装货
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function lock($id)
    {
        return $this->service->lock($id);
    }

    /**
     * 解锁
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function unlock($id)
    {
        return $this->service->unlock($id);
    }

    /**
     * 备注
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function remark($id)
    {
        return $this->service->remark($id, $this->data);
    }

    /**
     * 更换车辆
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function changeCar($id)
    {
        return $this->service->changeCar($id, $this->data);
    }

    /**
     * 出库前验证
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function checkOutWarehouse($id)
    {
        $this->service->checkOutWarehouse($id, $this->data);
        return 'true';
    }

    /**
     * 司机出库
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function outWarehouse($id)
    {
        return $this->service->outWarehouse($id, $this->data);
    }

    /**
     * 获取站点列表
     * @param $id
     * @return array|Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getBatchList($id)
    {
        return $this->service->getBatchList($id);
    }

    /**
     * 获取站点的订单列表
     * @param $id
     * @return array|Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getBatchOrderList($id)
    {
        return $this->service->getBatchOrderList($id, $this->data);
    }


    /**
     * 到达站点
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function batchArrive($id)
    {
        return $this->service->batchArrive($id, $this->data);
    }

    /**
     * 获取站点详情
     * @param $id
     * @return array|Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getBatchInfo($id)
    {
        return $this->service->getBatchInfo($id, $this->data);
    }

    /**
     * 站点 异常上报
     * @param $id
     * @return array|Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function batchException($id)
    {
        return $this->service->batchException($id, $this->data);
    }

    /**
     * 站点 取消取派
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function batchCancel($id)
    {
        return $this->service->batchCancel($id, $this->data);
    }

    /**
     * 签收验证
     * @param $id
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function checkBatchSign($id)
    {
        return $this->service->checkBatchSign($id, $this->data);
    }

    /**
     * 站点 签收
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function batchSign($id)
    {
        return $this->service->batchSign($id, $this->data);
    }

    /**
     * 获取取件线路统计数据
     * @param $id
     * @return array|Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getTotalInfo($id)
    {
        return $this->service->getTotalInfo($id);
    }

    /**
     * 司机入库
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function inWarehouse($id)
    {
        return $this->service->inWarehouse($id, $this->data);
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
        return $this->service->updateBatchIndex();
    }

    /**
     * 获取线路及取件线路列表
     * @return array
     */
    public function getTourList()
    {
        return $this->service->getTourList();
    }
}
