<?php

namespace App\Listeners;

use App\Events\Interfaces\ATourNotify;
use App\Events\Interfaces\ATourNotify2;
use App\Exceptions\BusinessLogicException;
use App\Http\Resources\api\MerchantApi\OrderResource;
use App\Http\Resources\api\MerchantApi\OrderInfoResource;
use App\Models\MerchantApi;
use App\Services\BaseConstService;
use App\Services\CurlClient;
use App\Services\ThirdPartyLogService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNotify2Merchant2 implements ShouldQueue
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
    public $timeout = 90;

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
     * @param ATourNotify2 $event
     * @return bool
     */
    public function handle(ATourNotify2 $event)
    {
        try {
            $dataList = $event->getDataList();
            $notifyType = $event->notifyType();
            Log::info('notify-type:' . $notifyType);
            Log::info('data-list:' . json_encode($dataList, JSON_UNESCAPED_UNICODE));
            if (empty($dataList)) return true;
            $merchantList = $this->getMerchantList(array_keys($dataList));
            if (empty($merchantList)) return true;
            foreach ($dataList as $merchantId => $data) {
                //根据推送模式组合数据,默认详细模式
                if (!empty($merchantList[$merchantId]['push_mode']) && $merchantList[$merchantId]['push_mode'] == BaseConstService::SIMPLE_PUSH_MODE) {
                    $data = OrderResource::make($data)->toArray(request());
                } else {
                    $data = OrderInfoResource::make($data)->toArray(request());
                }
                $postData = ['type' => $notifyType, 'data' => $data];
                if (empty($merchantList[$merchantId]['url'])) continue;
                list($pushStatus, $msg) = $this->postData($merchantList[$merchantId]['url'], $postData);
                ThirdPartyLogService::storeAll($merchantId, $data, $notifyType, $event->getThirdPartyContent($pushStatus, $msg));
            }
        } catch (\ErrorException $ex) {
            Log::channel('job-daily')->error($ex->getMessage());
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
     * @return array $res
     * @throws BusinessLogicException
     */
    public function postData(string $url, array $postData)
    {
        $res = $this->curl->post($url, $postData);
        if (empty($res) || empty($res['ret']) || (intval($res['ret']) != 1)) {
            app('log')->info('send notify failure');
            Log::info('商户通知失败:' . json_encode($res, JSON_UNESCAPED_UNICODE));
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
            app('log')->info('send notify failure');
            Log::info('商户通知失败:' . json_encode($res, JSON_UNESCAPED_UNICODE));
            throw new BusinessLogicException('发送失败');
        }
    }
}
