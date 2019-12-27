<?php
/**
 * 订单 接口
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:37
 */

namespace App\Http\Controllers\Api\Admin;

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
}