<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\RechargeService;

/**
 * Class BatchExceptionController
 * @package App\Http\Controllers\Api\Driver
 * @property RechargeService $service
 */
class RechargeController extends BaseController
{
    /**
     * MerchantController constructor.
     * @param RechargeService $service
     */
    public function __construct(RechargeService $service)
    {
        parent::__construct($service);
    }

    /**
     * 充值查询
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 充值详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }
}
