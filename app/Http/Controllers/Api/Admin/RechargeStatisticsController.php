<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\RechargeStatisticsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BatchExceptionController
 * @package App\Http\Controllers\Api\Driver
 * @property RechargeStatisticsService $service
 */
class RechargeStatisticsController extends BaseController
{
    /**
     * MerchantController constructor.
     * @param RechargeStatisticsService $service
     */
    public function __construct(RechargeStatisticsService $service)
    {
        parent::__construct($service);
    }

    /**
     * 充值统计查询
     * @return Collection
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 充值统计详情
     * @param $id
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * 审核
     * @param $id
     * @return void
     * @throws BusinessLogicException
     */
    public function verify($id)
    {
        return $this->service->verify($id, $this->data);
    }
}
