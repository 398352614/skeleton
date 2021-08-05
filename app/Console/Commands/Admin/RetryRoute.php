<?php

namespace App\Console\Commands\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Tour;
use App\Services\Admin\TourService;
use App\Services\ApiServices\TourOptimizationService;
use App\Services\BaseConstService;
use App\Traits\FactoryInstanceTrait;
use Doctrine\Common\Cache\Cache;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class RetryRoute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retry:route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '失败再尝试：线路优化及更新';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $totalRouteRetryList = \App\Models\RouteRetry::query()->get();
        if (empty($totalRouteRetryList)) {
            return;
        }
        $totalRouteRetryList = collect($totalRouteRetryList)->groupBy('tour_no')->toArray();
        if(!empty($totalRouteRetryList)){
            Log::channel('roll')->info(__CLASS__ .'.'. __FUNCTION__ .'.'. 'totalRouteRetryList', $totalRouteRetryList);
        }
        foreach ($totalRouteRetryList as $tourNo => $routeRetryList) {
            $latestRouteRetry = collect(collect($routeRetryList)->sortByDesc('updated_at')->first())->toArray();
            if (empty($latestRouteRetry)) {
                return;
            }
            try {
                $tourService = FactoryInstanceTrait::getInstance(TourService::class);
                $tourService->routeRefresh($latestRouteRetry['tour_no']);
                //成功则清空路线重试任务
                \App\Models\RouteRetry::query()->where('tour_no', $latestRouteRetry['tour_no'])->delete();
            } catch (BusinessLogicException $e) {
                //失败则路线重试次数+1
                \App\Models\RouteRetry::query()->where('id', $latestRouteRetry['id'])->update(['retry_times' => $latestRouteRetry['retry_times'] + 1]);
            }
            //超过最大重试次数则删除该条任务
            if ($latestRouteRetry['retry_times'] + 1 >= BaseConstService::ROUTE_RETRY_MAX_TIMES) {
                \App\Models\RouteRetry::query()->where('id', $latestRouteRetry['id'])->delete();
            }
        }
    }
}
