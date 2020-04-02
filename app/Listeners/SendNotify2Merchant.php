<?php

namespace App\Listeners;

use App\Events\Interfaces\ShouldSendNotify2Merchant;
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

class SendNotify2Merchant implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     * 处理任务的延迟时间.
     *
     * @var int
     */
    public $delay = 2;

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
     * @var BaseConstService
     */
    // const OUT_WAREHOUSE             = 1; // 出库
    // const PICKUP_FAILED             = 2; // 取件失败
    // const PICKUP_SUCCESS            = 3; // 取件成功
    // const EXPECTED_ARRIVE_TIME      = 4; // 预计到达时间
    // const BACK_WAREHOUSE            = 5; // 回到仓库

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
     * @param ShouldSendNotify2Merchant $event
     * @return bool
     * @throws BusinessLogicException
     */
    public function handle(ShouldSendNotify2Merchant $event)
    {
        $dataList = $event->getDataList();
        $notifyType = $event->notifyType();
        if (empty($dataList)) return true;
        $merchantList = $this->getMerchantList(array_column($dataList, 'merchant_id'));
        if (empty($merchantList)) return true;
        foreach ($dataList as $merchantId => $data) {
            $postData = ['type' => $notifyType, 'data' => $dataList];
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
     * 发送通知
     * @param string $url
     * @param array $postData
     * @throws BusinessLogicException
     */
    public function postData(string $url, array $postData)
    {
        $res = $this->curl->post($url, $postData);
        if (empty($res) || empty($res['code']) || ($res['code'] != 200)) {
            app('log')->info('send notify failure');
            throw new BusinessLogicException('发送失败');
        }
    }
}
