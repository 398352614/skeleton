<?php

namespace App\Listeners;

use App\Events\OrderExecutionDateUpdated;
use App\Exceptions\BusinessLogicException;
use App\Models\MerchantApi;
use App\Models\Order;
use App\Services\BaseConstService;
use App\Services\CurlClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class SendOrderExecutionDate implements ShouldQueue
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
    public $queue = 'execution-date-notify';


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
     * @param OrderExecutionDateUpdated $event
     * @return bool
     * @throws BusinessLogicException
     */
    public function handle(OrderExecutionDateUpdated $event)
    {
        try {
            //获取商户ID
            $merchantId = $this->getMerchantIdByOrderNo($event->order_no);
            if (empty($merchantId)) return true;
            //获取推送url
            $url = $this->getUrlByMerchantId($merchantId);
            if (empty($url)) return true;
            //推送
            $res = $this->postData($url, [
                'type' => $event->notifyType(),
                'data' => [
                    'order_no' => $event->order_no,
                    'out_order_no' => $event->out_order_no,
                    'execution_date' => $event->execution_date,
                    'second_execution_date' => $event->second_execution_date,
                    'status' => $event->status,
                    'batch_no' => $event->batch_no,
                    'tour_no' => $event->tour['tour_no'],
                    'line' => Arr::except($event->tour, ['tour_no'])
                ]
            ]);
            Log::info('订单取派日期修改通知成功:' . json_encode($res, JSON_UNESCAPED_UNICODE));
        } catch (\Exception $ex) {
            Log::channel('job-daily')->error($ex->getMessage());
        }
        return true;
    }

    /**
     * 通过单号 获取商户ID
     * @param $orderNo
     * @return mixed|null
     */
    private function getMerchantIdByOrderNo($orderNo)
    {
        $order = Order::query()->where('order_no', $orderNo)->first(['merchant_id']);
        if (empty($order)) return null;
        return $order->merchant_id;
    }

    /**
     * 通过商户ID 获取url
     * @param $merchantId
     * @return mixed|null
     */
    private function getUrlByMerchantId($merchantId)
    {
        $merchantApi = MerchantApi::query()
            ->where('merchant_id', $merchantId)
            ->where('status', BaseConstService::ON)
            ->where('url', '<>', null)
            ->first(['url']);
        if (empty($merchantApi)) return null;
        return $merchantApi->url;
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
        $res = $this->curl->merchantPost($merchant, $postData);
        if (empty($res) || empty($res['ret']) || (intval($res['ret']) != 1)) {
            app('log')->info('send notify failure');
            Log::info('商户通知失败:' . json_encode($res, JSON_UNESCAPED_UNICODE));
            throw new BusinessLogicException('发送失败');
        }
    }
}
