<?php

namespace App\Listeners;

use App\Events\OrderCancel;
use App\Events\OrderDelete;
use App\Events\OrderExecutionDateUpdated;
use App\Exceptions\BusinessLogicException;
use App\Models\MerchantApi;
use App\Models\Order;
use App\Services\BaseConstService;
use App\Services\CurlClient;
use App\Services\ThirdPartyLogService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOrderDelete implements ShouldQueue
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
    public $queue = 'order-delete-notify';


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
    public function handle(OrderDelete $event)
    {
        try {
            //获取货主ID
            $merchantId = $this->getMerchantIdByOrderNo($event->order_no);
            if (empty($merchantId)) return true;
            //获取推送url
            $url = $this->getUrlByMerchantId($merchantId);
            if (empty($url)) return true;
            //推送
            $postData = ['order_no' => $event->order_no, 'out_order_no' => $event->out_order_no];
            list($pushStatus, $msg) = $this->postData($url, [
                'type' => $event->notifyType(),
                'data' => $postData
            ]);
            ThirdPartyLogService::storeAll($merchantId, $postData, $event->notifyType(), $event->getThirdPartyContent($pushStatus, $msg));
            Log::channel('worker')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '订单删除');
        } catch (\Exception $e) {
            Log::channel('job')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
        }
        return true;
    }

    /**
     * 通过单号 获取货主ID
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
     * 通过货主ID 获取url
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
     * @return array $res
     * @throws BusinessLogicException
     */
    public function postData(string $url, array $postData)
    {
        $res = $this->curl->post($url, $postData);
        if (empty($res) || empty($res['ret']) || (intval($res['ret']) != 1)) {
            Log::channel('api')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '请求失败');
            Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'res', [$res]);
            return [false, $res['msg'] ?? '服务器内部错误'];
        }
        return [true, ''];
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
            Log::channel('api')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '请求失败');
            Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'res', [$res]);
            throw new BusinessLogicException('发送失败');
        }
    }


}
