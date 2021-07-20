<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\HomeService;

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
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function periodCount()
    {
        ini_set('memory_limit', '1280M');
        return $this->service->periodCount($this->data);
    }

    public function merchantCount()
    {
        return $this->service->merchantCount();
    }

    /**
     * 货主总计
     * @return array
     */
    public function merchantTotalCount()
    {
        return $this->service->merchantTotalCount();
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
     * 获取快捷方式列表
     * @return array
     */
    public function getShortCut()
    {
        return $this->service->getShortCut();
    }

    /**
     * 任务结果概览
     * @return array
     */
    public function resultOverview()
    {
        return $this->service->resultOverview($this->data['execution_date'] ?? null);
    }

    public function orderAnalysis()
    {
        return $this->service->orderAnalysis();
    }

    /**
     * 预约任务
     * @return array
     */
    public function reservation()
    {
        return $this->service->reservation();
    }

    /**
     * 流程图
     */
    public function flow()
    {
        return $this->service->flow();
    }
}
