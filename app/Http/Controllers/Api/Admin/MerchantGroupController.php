<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\MerchantGroupService;

/**
 * 货主组 列表
 * Class OrderController
 * @package App\Http\Controllers\Api\Admin
 * @property MerchantGroupService $service
 */
class MerchantGroupController extends BaseController
{
    public function __construct(MerchantGroupService $service)
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
     * @return array
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
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }

    public function getFeeList()
    {
        return $this->service->getFeeList($this->data['merchant_group_id'] ?? null);
    }

    /**
     * 配置
     * @param $id
     * @throws BusinessLogicException
     */
    public function config($id)
    {
        return $this->service->config($id, $this->data);
    }

    /**
     * 成员信息
     * @param $id
     * @return mixed
     */
    public function indexOfMerchant($id)
    {
        return $this->service->indexOfMerchant($id);
    }


    /**
     * 批量设置运价
     * @throws BusinessLogicException
     */
    public function updatePrice()
    {
        return $this->service->updatePrice($this->data);
    }

    /**
     * 状态-启用/禁用
     * @param $id
     * @return
     * @throws BusinessLogicException
     */
    public function status($id)
    {
        return $this->service->status($id, $this->data);
    }
}
