<?php
/**
 * 订单轨迹
 */

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\OrderTrailService;
use App\Services\PackageTrailService;

/**
 * Class OrderTrailController
 * @package App\Http\Controllers\api\admin
 * @property PackageTrailService $service
 */
class PackageTrailController extends BaseController
{
    public function __construct(PackageTrailService $service)
    {
        parent::__construct($service);
    }

    /**
     * @param $orderNo
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function index($orderNo)
    {
        return $this->service->index($orderNo);
    }
}
