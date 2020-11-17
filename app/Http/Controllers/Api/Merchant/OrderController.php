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
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function updateByApi($id)
    {
        return $this->service->updatePhoneDateByApi($id, $this->data);
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
     * 批量检查
     * @return array
     * @throws BusinessLogicException
     */
    public function importCheckByList()
    {
        return $this->service->importCheckByList($this->data);
    }

    /**
     * 检查
     * @throws BusinessLogicException
     */
    public function importCheck()
    {
        return $this->service->importCheck($this->data);
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
}
