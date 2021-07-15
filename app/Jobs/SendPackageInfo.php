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
use Illuminate\Support\Arr;
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
     * 推送类型-发送包裹信息
     * @return string
     */
    public function notifyType()
    {
        return BaseConstService::NOTIFY_PACKAGE_INFO;
    }

    /**
     * Execute the job.
     * @return bool
     * @throws BusinessLogicException
     */
    public function handle()
    {
        Log::channel('job')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '包裹重量转发开始');
        $columns = [
            'weight',
            'express_first_no',
            'out_order_no',
            'order_no'
        ];
        $this->curl = new CurlClient();
        $notifyType = $this->notifyType();
        //取数据
        foreach ($this->packageList as $k => $v) {
            $packageList[$k] = Package::query()->where('express_first_no', $v['express_first_no'])->orderBy('id', 'desc')->first();
            $packageList[$k]['weight'] = $v['weight'];
        }
        if (empty($packageList)) return true;
        //取商家url
        $merchantList = $this->getMerchantList(collect($packageList)->pluck('merchant_id')->toArray());
        if (empty($merchantList)) return true;
        //分商家推送
        foreach ($merchantList as $merchantId => $merchant) {
            $packageList = collect($packageList)->where('merchant_id', $merchantId)->toArray();
            foreach ($packageList as $k => $v) {
                $packageList[$k] = Arr::only($v, $columns);
            }
            if (!empty($packageList)) {
                $postData = ['type' => $notifyType, 'data' => ['package_list' => $packageList]];
                $this->postData($merchantList[$merchantId]['url'], $postData);
            }
        }
        Log::channel('job')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '包裹重量转发成功');
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
     * @throws BusinessLogicException
     */
    public function postData(string $url, array $postData)
    {
        try {
            $res = $this->curl->post($url, $postData);
            if (empty($res) || empty($res['ret']) || (intval($res['ret']) != 1)) {
                Log::channel('api')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '请求失败');
                Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'res', [$res]);
            }
        } catch (\Exception $e) {
            Log::channel('job')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
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
            Log::channel('api')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '请求失败');
            Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'res', [$res]);
            throw new BusinessLogicException('发送失败');
        }
    }
}
