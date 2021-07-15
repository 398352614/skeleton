<?php

namespace App\Jobs;

use App\Exceptions\BusinessLogicException;
use App\Models\MerchantApi;
use App\Services\BaseConstService;
use App\Services\CurlClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

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
    public $queue = 'sync-order-status';

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
        'merchant_id', 'tour_no', 'batch_no', 'order_no', 'out_order_no', 'status', 'status_name',
        'delivery_count', 'cancel_remark', 'signature', 'pay_type',
        'line_id', 'line_name', 'driver_id', 'driver_name', 'driver_phone', 'car_id', 'car_no',
        'package_list', 'material_list', 'tracking_order_type', 'tracking_order_type_name', 'type', 'type_name', 'tracking_order_status', 'tracking_order_status_name','sticker_amount','tracking_type'
    ];


    /**
     * UpdateLineCountTime constructor.
     * @param array $orderList ;
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
            $orderList = collect($orderList)->where('out_order_no', '<>', '')->toArray();
            if (empty($orderList)) {
                continue;
            }
            $postData = ['type' => $notifyType, 'data' => ['order_list' => $orderList]];
            $this->postData($merchantList[$merchantId]['url'], $postData);
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

    public function notifyType()
    {
        return BaseConstService::NOTIFY_SYNC_ORDER_STATUS;
    }
}
