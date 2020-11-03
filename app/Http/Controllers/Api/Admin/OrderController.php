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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * 获取订单的运单列表
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function getTrackingOrderList($id)
    {
        return $this->service->getTrackingOrderList($id);
    }

    /**
     * 订单统计
     * @return array
     * @throws BusinessLogicException
     */
    public function orderCount()
    {
        return $this->service->orderCount($this->data);
    }

    public function initStore()
    {
        return $this->service->initStore();
    }

    /**
     * 新增
     * @throws BusinessLogicException
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
     * @throws BusinessLogicException
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }


    /**
     * 删除订单
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }


    /**
     * 批量删除订单
     * @throws BusinessLogicException
     */
    public function destroyAll()
    {
        return $this->service->destroyAll($this->data['id_list']);
    }


    /**
     * 批量打印
     * @return array
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function orderPrintAll()
    {
        return $this->service->orderPrintAll($this->data['id_list']);
    }

    /**
     * 同步订单状态列表
     */
    public function synchronizeStatusList()
    {
        return $this->service->synchronizeStatusList($this->data['id_list']);
    }
}
