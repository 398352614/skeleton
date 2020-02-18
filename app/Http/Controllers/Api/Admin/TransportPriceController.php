<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\TransportPriceService;

/**
 * 运价管理
 * Class OrderController
 * @package App\Http\Controllers\Api\Admin
 * @property TransportPriceService $service
 */
class TransportPriceController extends BaseController
{
    public function __construct(TransportPriceService $service)
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
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
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
     * 状态-启用/禁用
     * @param $id
     * @throws BusinessLogicException
     */
    public function status($id)
    {
        return $this->service->status($id, $this->data);
    }

    /**
     * 价格测试
     * @param $id
     * @return
     * @throws BusinessLogicException
     */
    public function getPriceResult($id)
    {
        return $this->service->getPriceResult($id, $this->data);
    }


}
