<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Models\Merchant;
use App\Services\Admin\MerchantGroupService;

/**
 * 商户组 列表
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
        $info = $this->service->getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info->toArray();
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

}
