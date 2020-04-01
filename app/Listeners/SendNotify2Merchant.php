<?php

namespace App\Listeners;

use App\Events\Interfaces\ShouldSendNotify2Merchant;
use App\Models\Batch;
use App\Models\Merchant;
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
    use Dispatchable,InteractsWithQueue, Queueable, SerializesModels;

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
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ShouldSendNotify2Merchant $event)
    {
        $tour = $event->getTour();      // 必定存在
        $batch = $event->getBatch();    // 可能不存在

        // 获取当前批次下所有商家
        $merchants = Merchant::where('company_id', $tour->company_id)->whereIn('id', function ($query) use ($tour) {
            $query->select('merchant_id')->from('order')->where('tour_no', $tour->tour_no);
        })->get();

        //遍历当前存在任务的所有商家
        foreach ($merchants as $key => $merchant) {
            $data = [];
            $data['action'] = $event->notifyType();
            switch ($event->notifyType()) {
                    //出库消息
                case BaseConstService::OUT_WAREHOUSE:
                    $data['msg'] = '出库通知';
                    $data['data'] = $this->getOutWarehouseData($tour, $merchant);
                    break;
                case BaseConstService::PICKUP_FAILED:
                    $data['msg'] = '取件失败通知';
                    $data['data'] = $this->getPickUpFailData($batch, $merchant); // 默认取件失败为 batch 的操作
                    break;
                case BaseConstService::PICKUP_SUCCESS:
                    $data['msg'] = '取件成功通知';
                    $data['data'] = $this->getPickUpSuccessData($batch, $merchant); // 取件成功也为 batch 的操作
                    break;
                case BaseConstService::EXPECTED_ARRIVE_TIME:
                    $data['msg'] = '预计到达时间通知';
                    $data['data'] = $this->getExpectedArriveTimeData($tour, $merchant);
                    break;
                case BaseConstService::BACK_WAREHOUSE:
                    $data['msg'] = '回仓通知';
                    $data['data'] = $this->getInWarehouseData($tour, $merchant);
                    break;

                default:
                    # code...
                    break;
            }

            $this->postData($merchant, $data);
        }
    }


    /**
     * 获取 tour 司机回仓的数据
     */
    public function getInWarehouseData(Tour $tour, Merchant $merchant): array
    {
        return [];
    }

    /**
     * 获取 tour 所有批次预计到达时间的数据
     */
    public function getExpectedArriveTimeData(Tour $tour, Merchant $merchant): array
    {
        $batchs = Batch::with(['orders' => function ($order) use ($merchant) {
            $order->where('mercahnt_id', $merchant->id);
        }])->where('tour_no', $tour->tour_no)->where('status', BaseConstService::BATCH_DELIVERING)->get();

        $data = [];

        foreach ($batchs as $key => $batch) {
            if ($batch->orders->count() != 0) {
                $data[] = [
                    'batch_no'  => $batch->batch_no,
                    'order_nos' => $batch->orders->pluck('outer_order_no'),
                    'expect_arrive_time'    => $batch->expect_arrive_time,
                ];
            }
        }

        return $data;
    }

    /**
     * 获取取件成功的数据
     */
    public function getPickUpSuccessData(Batch $batch, Merchant $merchant): array
    {

        $data = [];

        foreach ($batch->orders as $key => $order) {
            if ($order->merchant_id == $merchant->id) {
                $data[] = [
                    'order_no'      => $order->outer_order_no,
                    'status'        => $order->status,
                ];
            }
        }

        return $data;
    }

    /**
     * 获取取件失败的数据
     */
    public function getPickUpFailData(Batch $batch, Merchant $merchant): array
    {
        $data = [];

        foreach ($batch->orders as $key => $order) {
            if ($order->merchant_id == $merchant->id) {
                $data[] = [
                    'order_no'      => $order->outer_order_no,
                    'status'        => $order->status,
                ];
            }
        }

        return $data;
    }

    /**
     * 获取出库需要传送的数据
     */
    public function getOutWarehouseData(Tour $tour, Merchant $merchant): array
    {

        $data = [];
        $orderNos = Order::where('tour_no', $tour->tour_no)->where('merchant_id', $merchant->id)->pluck('out_order_no');

        $data = $orderNos;

        return $data;
    }

    /**
     * 发送通知
     */
    public function postData(Merchant $merchant, array $data, int $next = 0)
    {
        $url = $merchant->url; // 此处需要修改
        $res = $this->curl->post($url, $data);

        //重试
        if ((!$res || (isset($res['ret']) && $res['ret'] != 1) || (isset($res['status']) && $res['status'] != 0)) && $next <= 3) {
            $this->postData($merchant, $data, $next++);
        }
    }
}
