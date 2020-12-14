<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\BaseController;
use App\Services\Driver\RechargeService;

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

    /**
     * 充值
     * @return void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function recharge()
    {
        return $this->service->recharge($this->data);
    }

    /**
     * 确认充值
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function verify()
    {
        return $this->service->verify($this->data);
    }

    /**
     * 获取外部用户
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getOutUser()
    {
        return $this->service->getOutUser($this->data);
    }
}
