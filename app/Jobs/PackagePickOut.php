<?php

namespace App\Jobs;


use App\Models\MerchantApi;
use App\Services\BaseConstService;
use App\Services\CurlClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class PackagePickOut implements ShouldQueue
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
     * @var CurlClient
     */
    private $curl;

    private $packageList;

    /**
     * 推送类型-包裹入库分拣
     * @return string
     */
    public function notifyType()
    {
        return BaseConstService::NOTIFY_PACKAGE_PICK_OUT;
    }

    /**
     * UpdateLineCountTime constructor.
     * @param $packageList
     */
    public function __construct($packageList)
    {
        $this->packageList = $packageList;
    }


    /**
     * 触发入库分拣队列
     * Execute the job.
     */
    public function handle()
    {
        Log::channel('job')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '入库分拣开始');
        $columns = [
            'express_first_no',
            'order_no',
            'out_order_no',
        ];
        $this->curl = new CurlClient();
        $notifyType = $this->notifyType();
        $merchantList = $this->getMerchantList(collect($this->packageList)->pluck('merchant_id')->toArray());
        if (empty($merchantList)) return true;
        foreach ($merchantList as $merchantId => $merchant) {
            $packageList = collect($this->packageList)->where('merchant_id', $merchantId)->toArray();
            foreach ($packageList as $k => $v) {
                $packageList[$k] = Arr::only($v, $columns);
            }
            if (!empty($packageList)) {
                $postData = ['type' => $notifyType, 'data' => ['package_list' => $packageList]];
                $this->postData($merchant['url'], $postData);
            }
        }
        Log::channel('job')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '入库分拣成功');
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
     * 发送通知
     * @param string $url
     * @param array $postData
     */
    public function postData(string $url, array $postData)
    {
        try {
            $res = $this->curl->post($url, $postData);
            if (empty($res) || empty($res['ret']) || (intval($res['ret']) != 1)) {
                Log::channel('api')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '请求失败');
            }
            Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'res', [$res]);
        } catch (\Exception $e) {
            Log::channel('job')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
        }
    }
}
