<?php

namespace App\Console\Commands;

use App\Exceptions\BusinessLogicException;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Tour;
use App\Services\ApiServices\TourOptimizationService;
use App\Services\BaseConstService;
use Doctrine\Common\Cache\Cache;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RouteRetry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:retry';

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
        $totalRouteRetryList = \App\Models\RouteRetry::query()->groupBy('tour_no')->get();
        foreach ($totalRouteRetryList as $tourNo => $routeRetryList) {
            $latestRouteRetry = collect($routeRetryList)->sortByDesc('id')->first();
            $data = json_decode($latestRouteRetry['data']);
            try {
                TourOptimizationService::getOpInstance($latestRouteRetry['company_id'])->updateDriverLocation($data['tour'], $data['driver_location'], $data['next_batch_no'], $data['queue']);
                //成功则清空路线重试任务
                \App\Models\RouteRetry::query()->where('tour_no', $latestRouteRetry['tour_no'])->delete();
            } catch (BusinessLogicException $e) {
                //失败则路线重试次数+1
                \App\Models\RouteRetry::query()->where('tour_no', $latestRouteRetry['tour_no'])->update(['retry_times' => $latestRouteRetry['retry_times'] + 1]);
            }
            //超过最大重试次数则删除该条任务
            if($latestRouteRetry['retry_times'] + 1 >= BaseConstService::ROUTE_RETRY_MAX_TIMES){
                \App\Models\RouteRetry::query()->where('id', $latestRouteRetry['id'])->delete();
            }
        }
    }
}
