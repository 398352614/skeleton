<?php

namespace App\Jobs;

use App\Exceptions\BusinessLogicException;
use App\Models\MerchantApi;
use App\Models\Package;
use App\Services\BaseConstService;
use App\Services\CurlClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendPackageInfo implements ShouldQueue
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
    public $queue = 'package-info-notify';

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

    public $packageList;


    /**
     * UpdateLineCountTime constructor.
     * @param $packageList ;
     */
    public function __construct(array $packageList)
    {
        $this->packageList = $packageList;
    }


    /**
     * Execute the job.
     * @return bool
     * @throws BusinessLogicException
     */
    public function handle()
    {
        $merchantId = Package::query()->where('express_first_no', $this->packageList[0]['express_first_no'])->orderBy('id', 'desc')->first();
        $merchantList = $this->getMerchantList([$merchantId]);
        $notifyType = $this->notifyType();
        if (empty($merchantList)) return true;
        $this->curl = new CurlClient();
        $postData = ['type' => $notifyType, 'data' => ['package_list' => $this->packageList]];
        $this->postData($merchantList[$merchantId]['url'], $postData);
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
        $res = $this->curl->merchantPost($merchant, $postData);
        if (empty($res) || empty($res['ret']) || (intval($res['ret']) != 1)) {
            app('log')->info('send notify failure');
            Log::info('商户通知失败:' . json_encode($res, JSON_UNESCAPED_UNICODE));
            throw new BusinessLogicException('发送失败');
        }
    }

    public function notifyType()
    {
        return BaseConstService::NOTIFY_PACKAGE_INFO;
    }
}
