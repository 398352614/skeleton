<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Admin\EmployeeResource;
use App\Services\Admin\InstitutionService;

class InstitutionController extends BaseController
{
    /**
     * @var InstitutionService
     */
    public $service;

    public function __construct(InstitutionService $service)
    {
        parent::__construct($service);
    }

    /**
     * 获取树
     *
     * @return array
     */
    public function index()
    {
        return $this->service->getTree();
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function indexOfEmployees(int $id)
    {
        return EmployeeResource::collection($this->service->indexOfEmployees($id));
    }

    /**
     *
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        return $this->service->show($id);
    }

    /**
     * 子树
     *
     * @param int $id
     * @return array
     */
    public function child(int $id)
    {
        return $this->service->getChildren($id);
    }

    /**
     * 新建
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        if ($this->service->createNode($this->data['parent_id'], $this->data)) {
            return success();
        }

        return failed();
    }

    /**
     * 移动
     *
     * @param int $id
     * @param int $parentId
     * @return array
     */
    public function move(int $id, int $parentId)
    {
        if ($this->service->move($id, $parentId)) {
            return success();
        }

        return failed();
    }

    /**
     * 更新信息
     *
     * @param int $id
     * @return array
     */
    public function update(int $id)
    {
        if ($this->service->updateNode($id, $this->data)) {
            return success();
        }

        return failed();
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
        if ($this->service->deleteNode($id)) {
            return success();
        }

        return failed();
    }


    /**
     * 删除它和子树
     * @param int $id
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function destroyWithChildren(int $id)
    {
        if ($this->service->deleteNodeWithChildren($id)) {
            return success();
        }

        return failed();
    }
}
