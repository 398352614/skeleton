<?php
/**
 * 订单 接口
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:37
 */

namespace App\Http\Controllers\Api\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Jobs\OrderStore;
use App\Services\BaseConstService;
use App\Services\Merchant\OrderService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderController
 * @package App\Http\Controllers\Api\Admin
 * @property OrderService $service
 */
class OrderController extends OrderBaseController
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
        if (auth()->user()->getAttribute('is_api') == true) {
            $orderSource = BaseConstService::ORDER_SOURCE_3;
            $this->data['out_status'] = !empty($this->data['out_status']) ? $this->data['out_status'] : BaseConstService::OUT_STATUS_2;
            unset($this->data['place_address']);
        } else {
            $orderSource = BaseConstService::ORDER_SOURCE_1;
            $this->data['out_status'] = BaseConstService::OUT_STATUS_1;
        }
        return $this->service->store($this->data, $orderSource);
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
     * 修改地址
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function updateAddressDate($id)
    {
        return $this->service->updateAddressDate($id, $this->data);
    }

    /**
     * 修改派件日期
     * @param $id
     * @throws BusinessLogicException
     */
    public function updateSecondDate($id)
    {
        return $this->service->updateSecondDate($id, $this->data['second_execution_date']);
    }

    /**
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function updateByApi($id)
    {
        return $this->service->updatePhoneDateByApi($id, $this->data);
    }

    /**
     * @return mixed
     * @throws BusinessLogicException
     */
    public function updateByApiList()
    {
        return $this->service->updatePhoneDateByApiList($this->data);
    }

    /**
     * 修改订单清单
     * @param $id
     * @throws BusinessLogicException
     */
    public function updateItemList($id)
    {
        return $this->service->updateItemList($id, $this->data);
    }


    /**
     * 删除订单
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id, $this->data);
    }

    /**
     * 批量删除
     * @return string
     * @throws BusinessLogicException
     */
    public function destroyAll()
    {
        return $this->service->destroyAll($this->data['order_no_list']);
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
     * 修改订单出库状态
     * @param $id
     * @throws BusinessLogicException
     */
    public function updateOutStatus($id)
    {
        return $this->service->updateOutStatus($id, $this->data);
    }

    /**
     * 获取订单派送信息
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function getOrderDispatchInfo($id)
    {
        return $this->service->getOrderDispatchInfo($id);
    }

    /**
     * API订单查询
     * @return array
     * @throws BusinessLogicException
     */
    public function showByApi()
    {
        return $this->service->showByApi($this->data);
    }

    /**
     * 物流追踪
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function track($id)
    {
        return $this->service->track($id);
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
     * 取消预约
     * @param $id
     * @return void
     * @throws BusinessLogicException
     */
    public function removeFromBatch($id)
    {
        return $this->service->removeFromBatch($id);
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

    /**
     * 通过订单ID获取可选日期
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getAbleDateList($id)
    {
        return $this->service->getAbleDateList($id);
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
     * 重新预约
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function assignToBatch($id)
    {
        return $this->service->assignToBatch($id, $this->data);
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
     * 订单打印
     * @return mixed
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function orderBillPrint()
    {
        return $this->service->orderBillPrint($this->data);
    }

}
