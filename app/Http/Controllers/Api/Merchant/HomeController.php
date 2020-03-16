<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\BaseController;
use App\Services\Merchant\HomeService;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers\Api\Merchant
 * @property HomeService $service
 */
class HomeController extends BaseController
{
    public function __construct(HomeService $service)
    {
        parent::__construct($service);
    }

    public function home()
    {
        return $this->service->home();
    }

    public function thisWeekCount()
    {
        return $this->service->thisWeekCount();
    }

    public function lastWeekCount()
    {
        return $this->service->lastWeekCount();
    }

    public function thisMonthCount()
    {
        return $this->service->thisMonthCount();
    }

    public function lastMonthCount()
    {
        return $this->service->lastMonthCount();
    }

    /**
     * 时间段订单统计
     *
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function periodCount()
    {
        return $this->service->periodCount($this->data);
    }
}
