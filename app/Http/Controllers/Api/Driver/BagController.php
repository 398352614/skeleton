<?php

namespace App\Http\Controllers\Api\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Driver\BagService;

/**
 * Class BagController
 * @package App\Http\Controllers\Api\Drvier
 * @property BagService $service
 */
class BagController extends BaseController
{
    public $service;

    public function __construct(BagService $service)
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
     * 扫描包裹
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     * @throws BusinessLogicException
     */
    public function packPackage($id)
    {
        return $this->service->packPackage($id, $this->data);
    }

    /**
     * 移除包裹
     * @param $id
     * @return void
     * @throws BusinessLogicException
     */
    public function removePackage($id)
    {
        return $this->service->removePackage($id, $this->data);
    }

    /**
     * 删除袋号
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }


    /**
     * 拆袋
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|mixed
     * @throws BusinessLogicException
     */
    public function unpackPackage($id)
    {
        return $this->service->unpackPackageList($id, $this->data);
    }
}
