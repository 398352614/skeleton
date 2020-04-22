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

    /**
     * 主页（默认显示今天总数和这周每日完成订单数）
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function home()
    {
        return $this->service->home();
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
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function lastWeekCount()
    {
        return $this->service->lastWeekCount();
    }

    /**
     * 此月数据
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function thisMonthCount()
    {
        return $this->service->thisMonthCount();
    }

    /**
     * 上月数据
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
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

    public function all(){
        return $this->service->all();
    }
}
