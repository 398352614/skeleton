<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\BaseController;
use App\Services\Merchant\HomeService;

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

    /**
     * 今日概览
     * @return array
     */
    public function todayOverview()
    {
        return $this->service->todayOverview();
    }

    /**
     * 此周数据
     * @return array
     * @throws \Exception
     */
    public function thisWeekCount()
    {
        return $this->service->thisWeekCount();
    }

    /**
     * 上周数据
     * @return array
     * @throws \Exception
     */
    public function lastWeekCount()
    {
        return $this->service->lastWeekCount();
    }

    /**
     * 此月数据
     * @return array
     * @throws \Exception
     */
    public function thisMonthCount()
    {
        return $this->service->thisMonthCount();
    }

    /**
     * 上月数据
     * @return array
     * @throws \Exception
     */
    public function lastMonthCount()
    {
        return $this->service->lastMonthCount();
    }

    /**
     * 时间段订单统计
     * @return array
     */
    public function periodCount()
    {
        dd(1);
        return $this->service->periodCount($this->data);
    }

    /**
     * 总计统计
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function trail()
    {
        return $this->service->trail();
    }
}
