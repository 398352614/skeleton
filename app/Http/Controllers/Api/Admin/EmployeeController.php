<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EmployeeController extends Controller
{
    public $service;

    public function __construct(EmployeeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    public function show($id)
    {
        return $this->service->getInfo(['id' => $id], ['*'], true);
    }

    /**
     * 更新
     *
     * @param  int  $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function update(int $id)
    {

        return $this->service->updateEmployee($id, \request()->all());
    }

    /**
     * 新建
     *
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->service->createEmployee(\request()->all());
    }

    /**
     * 删除
     *
     * @param  int  $id
     * @return array
     */
    public function destroy(int $id)
    {
        if ($this->service->delete($id)) {
            return success();
        }

        return failed();
    }

    /**
     * 设置登录权限
     *
     * @param  int  $id
     * @param  int  $enabled
     * @return array
     */
    public function setLogin(int $id, int $enabled)
    {
        if ($this->service->forbidLogin(Arr::wrap($id), (bool) $enabled)) {
            return success();
        }

        return failed();
    }
}
