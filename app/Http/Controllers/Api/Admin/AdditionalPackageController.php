<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\AdditionalPackageService;

/**
 * Class AdditionalPackageController
 * @package App\Http\Controllers\Api\admin
 * @property AdditionalPackageService $service
 */
class AdditionalPackageController extends BaseController
{
    public function __construct(AdditionalPackageService $service)
    {
        parent::__construct($service);
    }

    /**
     * 列表查询
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 获取详情
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }

}
