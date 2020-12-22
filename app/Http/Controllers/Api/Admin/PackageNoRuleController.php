<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\PackageNoRuleService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * 单号规则管理
 * Class OrderController
 * @package App\Http\Controllers\Api\Admin
 * @property PackageNoRuleService $service
 */
class PackageNoRuleController extends BaseController
{
    public function __construct(PackageNoRuleService $service)
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
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = $this->service->getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
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
