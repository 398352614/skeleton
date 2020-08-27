<?php

namespace App\Jobs;

use App\Events\TourNotify\NextBatch;
use App\Exceptions\BusinessLogicException;
use App\Models\Batch;
use App\Models\Material;
use App\Models\MerchantApi;
use App\Models\Package;
use App\Models\Tour;
use App\Services\Admin\TourService;
use App\Services\BaseConstService;
use App\Services\CurlClient;
use App\Traits\CompanyTrait;
use App\Traits\FactoryInstanceTrait;
use App\Traits\TourTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use WebSocket\Client;

class SyncOrderStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * 任务连接名称。
     *
     * @var string|null
     */
    public $connection = 'redis';

    /**
     * 任务发送到的队列的名称.
     *
     * @var string|null
     */
    public $queue = 'sync-order';

    /**
     * 任务可以执行的最大秒数 (超时时间)。
     *
     * @var int
     */
    public $timeout = 30;


    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 3;

    /**
     * @var CurlClient $curl
     */
    public $curl;

    public $orderList;

    public static $orderFields = [
        'merchant_id', 'tour_no', 'batch_no', 'order_no', 'out_order_no', 'status', 'status_name', 'package_list', 'material_list'
    ];


    /**
     * UpdateLineCountTime constructor.
     * @param $tourNo
     * @param $orderList ;
     */
    public function __construct(array $orderList)
    {
        $this->orderList = collect($orderList)->map(function ($order) {
            return Arr::only($order, self::$orderFields);
        })->toArray();
    }


    /**
     * Execute the job.
     * @return bool
     * @throws BusinessLogicException
     */
    public function handle()
    {
        $merchantList = $this->getMerchantList(array_column($this->orderList, 'merchant_id'));
        $notifyType = $this->notifyType();
        if (empty($merchantList)) return true;
        $this->curl = new CurlClient();
        $merchantOrderList = collect($this->orderList)->groupBy('merchant_id')->toArray();
        foreach ($merchantOrderList as $merchantId => $orderList) {
            $postData = ['type' => $notifyType, 'data' => ['order_list' => $orderList]];
            $this->postData($merchantList[$merchantId]['url'], $postData);
        }
        return true;
    }

    /**
     * 获取商户信息
     * @param $merchantIdList
     * @return array
     */
    private function getMerchantList($merchantIdList)
    {
        $merchantList = MerchantApi::query()
            ->whereIn('merchant_id', $merchantIdList)
            ->where('status', BaseConstService::ON)
            ->where('url', '<>', null)
            ->get();
        return collect($merchantList)->keyBy('merchant_id')->toArray();
    }

    /**
     * 发送通知-1
     * @param string $url
     * @param array $postData
     * @throws BusinessLogicException
     */
    public function postData(string $url, array $postData)
    {
        try {
            $res = $this->curl->post($url, $postData);
            if (empty($res) || empty($res['ret']) || (intval($res['ret']) != 1)) {
                app('log')->info('send notify failure');
                Log::info('商户通知失败:' . json_encode($res, JSON_UNESCAPED_UNICODE));
            }
        } catch (\Exception $ex) {
            Log::info(json_encode($postData, JSON_UNESCAPED_UNICODE));
            Log::info('推送失败');
        }
    }

    /**
     * 发送通知-2
     * @param array $merchant
     * @param array $postData
     * @throws BusinessLogicException
     */
    public function merchantPostData(array $merchant, array $postData)
    {
        $res = $this->curl->merchantPostJson($merchant, $postData);
        if (empty($res) || empty($res['ret']) || (intval($res['ret']) != 1)) {
            app('log')->info('send notify failure');
            Log::info('商户通知失败:' . json_encode($res, JSON_UNESCAPED_UNICODE));
            throw new BusinessLogicException('发送失败');
        }
    }

    public function notifyType()
    {
        return BaseConstService::NOTIFY_SYNC_ORDER_STATUS;
    }
}
