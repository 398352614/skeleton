<?php
/**
 * 订单 接口
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:37
 */

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\OrderService;

/**
 * Class OrderController
 * @package App\Http\Controllers\Api\Admin
 * @property OrderService $service
 */
class OrderController extends BaseController
{
    public function __construct(OrderService $service)
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
     * 取件列表初始化
     * @return array
     */
    public function initPickupIndex()
    {
        return $this->service->initPickupIndex();
    }

    /**
     * 派件列表初始化
     * @return array
     */
    public function initPieIndex()
    {
        return $this->service->initPieIndex();
    }

    public function initStore()
    {
        return $this->service->initStore();
    }

    /**
     * 新增
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * 订单批量导入
     * @return array
     * @throws BusinessLogicException
     */
    public function import()
    {
        return $this->service->import($this->data);
    }

    /**
     * 批量新增
     * @return mixed
     * @throws BusinessLogicException
     */
    public function storeByList()
    {
        return $this->service->createByList($this->data);
    }

    /**
     * 修改
     * @param $id
     * @return bool|int|void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }


    /**
     * 通过订单，获取可分配的线路的取派日期
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getTourDate($id)
    {
        return $this->service->getTourDate($id);
    }

    /**
     * 通过国家邮编，获取可分配的取派日期
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getDate()
    {
        return $this->service->getDate($this->data);
    }

    /**
     * 通过订单,获取可分配的站点列表
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getBatchPageListByOrder($id)
    {
        return $this->service->getBatchPageListByOrder($id, $this->data);
    }


    /**
     * 分配至站点
     * 参数存在站点编号(batchNo),为指定站点;否则为新建站点
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function assignToBatch($id)
    {
        return $this->service->assignToBatch($id, $this->data);
    }

    /**
     * 从站点中移除订单
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function removeFromBatch($id)
    {
        return $this->service->removeFromBatch($id);
    }

    /**
     * 批量订单从站点移除
     * @throws BusinessLogicException
     */
    public function removeListFromBatch()
    {
        return $this->service->removeListFromBatch($this->data['id_list']);
    }

    /**
     * 删除订单
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id, $this->data);
    }

    /**
     * 恢复
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function recovery($id)
    {
        return $this->service->recovery($id, $this->data);
    }

    /**
     * 彻底删除
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function actualDestroy($id)
    {
        return $this->service->actualDestroy($id);
    }

    /**
     * 批量分配订单至取件线路
     * @throws BusinessLogicException
     * @throws \WebSocket\BadOpcodeException
     */
    public function assignListTour()
    {
        return $this->service->assignListTour($this->data);
    }

    /**
     * 批量打印
     * @return array
     * @throws BusinessLogicException
     */
    public function orderPrintAll()
    {
        return $this->service->orderPrintAll($this->data['id_list']);
    }
}
