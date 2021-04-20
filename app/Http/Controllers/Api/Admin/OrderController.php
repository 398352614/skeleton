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
     * 获取订单的运单轨迹列表
     * @param $id
     * @return array
     */
    public function getTrackingOrderTrailList($id)
    {
        return $this->service->getTrackingOrderTrailList($id);
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
     * 通过地址获取可选日期
     * @return array
     * @throws BusinessLogicException
     */
    public function getAbleDateListByAddress()
    {
        return $this->service->getAbleDateListByAddress($this->data);
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
     * 获取继续派送(再次取派)信息
     * @param $id
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function getAgainInfo($id)
    {
        return $this->service->getAgainInfo($id);
    }

    /**
     * 继续派送(再次取派)
     * @param $id
     * @return bool
     * @throws BusinessLogicException
     */
    public function again($id)
    {
        return $this->service->again($id, $this->data);
    }

    /**
     * 终止派送
     * @param $id
     * @throws BusinessLogicException
     */
    public function end($id)
    {
        return $this->service->end($id);
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
     * 批量打印2
     * @return mixed
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function orderBillPrint()
    {
        return $this->service->orderBillPrint($this->data['id_list']);
    }

    /**
     * 同步订单状态列表
     */
    public function synchronizeStatusList()
    {
        return $this->service->synchronizeStatusList($this->data['id_list']);
    }

    /**
     * 订单导出
     * @return array
     * @throws BusinessLogicException
     */
    public function orderExport()
    {
        return $this->service->orderExport();
    }

    /**
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function neutralize($id)
    {
        return $this->service->neutralize($id);
    }

    /**
     * 运价估算
     * @return array|void
     * @throws BusinessLogicException
     */
    public function priceCount()
    {
        return $this->service->priceCount($this->data);
    }

    /**
     * 获取网点
     * @return array
     * @throws BusinessLogicException
     */
    public function getWarehouse()
    {
        return $this->service->getWareHouse($this->data);
    }
}
