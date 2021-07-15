<?php

namespace App\Listeners;

use App\Events\Interfaces\ATourNotify;
use App\Exceptions\BusinessLogicException;
use App\Models\MerchantApi;
use App\Services\BaseConstService;
use App\Services\CurlClient;
use App\Services\ThirdPartyLogService;
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
     * @param ATourNotify $event
     * @return bool
     */
    public function handle(ATourNotify $event)
    {
        try {
            $dataList = $event->getDataList();
            $dataList2 = $event->getDataList2();
            $dataList3 = $event->getDataList3();
            $notifyType = $event->notifyType();
            Log::channel('worker')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'notifyType', [$notifyType]);
            Log::channel('worker')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'datalist', $dataList);
            if (empty($dataList)) return true;
            $merchantList = $this->getMerchantList(array_keys($dataList));
            if (empty($merchantList)) return true;
            foreach ($merchantList as $merchantId => $merchant) {
                if ($merchant['push_mode'] == BaseConstService::DETAIL_PUSH_MODE) {
                    $data = $dataList2[$merchantId];
                } elseif ($merchant['push_mode'] == BaseConstService::SIMPLE_PUSH_MODE) {
                    $data = $dataList3[$merchantId];
                } else {
                    $data = $dataList[$merchantId];
                }
                $postData = ['type' => $notifyType, 'data' => $data];
                if (empty($merchantList[$merchantId]['url'])) continue;
                list($pushStatus, $msg) = $this->postData($merchantList[$merchantId]['url'], $postData);
                ThirdPartyLogService::storeAll($merchantId, $data, $notifyType, $event->getThirdPartyContent($pushStatus, $msg));
            }
        } catch (BusinessLogicException $e) {
            Log::channel('job')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'BusinessLogicException', ['message' => $e->getMessage()]);
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
     * 获取货主信息
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
            app('log')->info('send notify failure');
            Log::info('商户通知失败:' . json_encode($res, JSON_UNESCAPED_UNICODE));
            throw new BusinessLogicException('发送失败');
        }
    }
}
