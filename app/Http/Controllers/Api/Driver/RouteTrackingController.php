<?php

namespace App\Http\Controllers\Api\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Driver\RouteTrackingService;
use Illuminate\Support\Facades\Redis;

/**
 * Class RouteTrackingController
 * @package App\Http\Controllers\Api\Driver
 * @property RouteTrackingService $service
 */
class RouteTrackingController extends BaseController
{

    public function __construct(RouteTrackingService $service)
    {
        parent::__construct($service);
    }

    /**
     * 单条采集位置
     * @throws BusinessLogicException
     */
    public function store()
    {
       return $this->service->store($this->data);
    }

    /**
     * 批量采集位置
     * @return mixed
     * @throws BusinessLogicException
     */
    public function storeByList(){
        return $this->service->createBylist($this->data);
    }
}
