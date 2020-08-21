<?php

namespace App\Listeners;

use App\Events\AfterTourUpdated;
use App\Exceptions\BusinessLogicException;
use App\Models\Batch;
use App\Models\Tour;
use App\Models\TourLog;
use App\Services\BaseConstService;
use App\Services\GoogleApiService;
use App\Traits\UpdateTourTimeAndDistanceTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateLineCountTime
{
    use UpdateTourTimeAndDistanceTrait;

    /**
     * @var GoogleApiService
     */
    public $apiClient;

    public $times = 5;

    public $delay = 1;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(GoogleApiService $client)
    {
        $this->apiClient = $client;
    }

    /**
     * 更新取件线路
     *
     * @param AfterTourUpdated $event
     * @throws BusinessLogicException
     */
    public function handle(AfterTourUpdated $event)
    {
        app('log')->info('更新线路出发事件进入此处');
        $tour = $event->tour; // 获取事件对应的线路
        $res = $this->apiClient->UpdateTour($tour, $event->nextBatch);
        app('log')->info('更新线路的返回结果为:' . json_encode($res, JSON_UNESCAPED_UNICODE));
        sleep(1);
        $bool = false;
        for ($i = 1; $i <= $this->times; $i++) {
            $bool = $this->updateTourTimeAndDistance($tour);
            if ($bool) break;
            sleep($this->delay);
        }
        if (!$bool) {
            throw new BusinessLogicException('更新线路失败，请稍后重试');
        }
        Log::info('取件线路预计耗时和里程更新成功');
    }
}
