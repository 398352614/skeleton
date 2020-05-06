<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\HomeService;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers\Api\Admin
 * @property HomeService $service
 */
class HomeController extends BaseController
{
    public function __construct(HomeService $service)
    {
        parent::__construct($service);
    }

    /**
     * 主页
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function home(){
        return $this->service->home();
    }

    /**
     * 此周数据
     * @return array
     */
    public function thisWeekCount(){
        return $this->service->thisWeekCount();
    }

    /**
     * 上周数据
     * @return array
     */
    public function lastWeekCount(){
        return $this->service->lastWeekCount();
    }

    /**
     * 此月数据
     * @return array
     */
    public function thisMonthCount(){
        return $this->service->thisMonthCount();
    }

    /**
     * 上月数据
     * @return array
     */
    public function lastMonthCount(){
        return $this->service->lastMonthCount();
    }

    /**
     * 时间段订单统计
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function periodCount(){
        return $this->service->periodCount($this->data);
    }
}
