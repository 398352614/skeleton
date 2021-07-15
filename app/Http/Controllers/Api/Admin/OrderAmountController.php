<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\OrderAmountService;
use App\Services\BaseConstService;

/**
 * Class OrderImportController
 * @package App\Http\Controllers\Api\Admin
 * @property OrderAmountService $service
 */
class OrderAmountController extends BaseController
{
    /**
     * OrderAmountController constructor.
     * @param OrderAmountService $service
     */
    public function __construct(OrderAmountService $service)
    {
        $this->service = $service;
    }

    /**
     * 查询
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 新增
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * 新增
     * @return void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * 修改
     * @param $id
     * @return bool|int
     * @throws BusinessLogicException
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }


    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
