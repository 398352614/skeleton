<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\BatchService;

/**
 * Class BagController
 * @package App\Http\Controllers\Api\Admin
 * @property BagService $service
 */
class BagController extends BaseController
{
    public $service;

    public function __construct(\App\Services\Driver\BagService $service)
    {
        parent::__construct($service);
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
     * 详情
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
     * @throws BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     *
     */
    public function addPackage()
    {
        return $this->service->addPackage($this->data);
    }
}
