<?php

namespace App\Listeners;

use App\Events\Interfaces\ATourNotify;
use App\Exceptions\BusinessLogicException;
use App\Models\Batch;
use App\Models\Merchant;
use App\Models\MerchantApi;
use App\Models\Order;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\CurlClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNotify2Merchant implements ShouldQueue
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
    public $queue = 'tour-notify';


    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 3;

    /**
     * 任务可以执行的最大秒数 (超时时间)。
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * @var CurlClient
     */
    public $curl;

    /**
     * Create the event listener.
     * @param $curlClient
     * @return void
     */
    public function __construct(CurlClient $curlClient)
    {
        $this->curl = $curlClient;
    }


    /**
     * Handle the event
     *
     * @param ATourNotify $event
     * @return bool
     * @throws BusinessLogicException
     */
    public function handle(ATourNotify $event)
    {
        try {
            $dataList = $event->getDataList();
            $notifyType = $event->notifyType();
            Log::info('notify-type:' . $notifyType);
            Log::info('dataList:' . json_encode($dataList, JSON_UNESCAPED_UNICODE));
            if (empty($dataList)) return true;
            $merchantList = $this->getMerchantList(array_column($dataList, 'merchant_id'));
            if (empty($merchantList)) return true;
            foreach ($dataList as $merchantId => $data) {
                $postData = ['type' => $notifyType, 'data' => $data];
                $res = $this->postData($merchantList[$merchantId]['url'], $postData);
                Log::info($merchantId . '-商户通知成功返回:' . json_encode($res, JSON_UNESCAPED_UNICODE));
            }
            Log::info('取件线路通知成功:' . $notifyType);
        } catch (\Exception $ex) {
            Log::channel('job-daily')->error($ex->getMessage());
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
        $res = $this->curl->post($url, $postData);
        if (empty($res) || empty($res['ret']) || (intval($res['ret']) != 1)) {
            app('log')->info('send notify failure');
            Log::info('商户通知失败:' . json_encode($res, JSON_UNESCAPED_UNICODE));
            throw new BusinessLogicException('发送失败');
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
}
