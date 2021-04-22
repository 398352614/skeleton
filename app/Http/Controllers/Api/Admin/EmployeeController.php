<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\EmployeeService;
use App\Services\BaseService;
use Illuminate\Support\Arr;

/**
 * Class EmployeeController
 * @package App\Http\Controllers\Api\Admin
 * @property EmployeeService $service
 */
class EmployeeController extends BaseController
{
    public $service;

    public function __construct(EmployeeService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * 新建
     *
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * 更新
     * @param int $id
     * @return bool|int|void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }

    /**
     * 删除
     *
     * @param int $id
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function destroy(int $id)
    {
        return $this->service->destroy($id);
    }

    /**
     * 设置登录权限
     *
     * @param int $id
     * @param int $enabled
     * @return array
     */
    public function setLogin(int $id, int $enabled)
    {
        if ($this->service->forbidLogin(Arr::wrap($id), (bool)$enabled)) {
            return success();
        }

        return failed();
    }

    /**
     * @param int $id
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function resetPassword(int $id)
    {
        if ($this->service->resetPassword($id, \request()->all())) {
            return success();
        }

        return failed();
    }

    /**
     * 批量修改状态
     */
    public function setLoginByList()
    {
        return $this->service->setLoginByList($this->data);
    }
}
