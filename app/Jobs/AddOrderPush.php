<?php

namespace App\Jobs;

use App\Models\Material;
use App\Models\Package;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use WebSocket\Client;

class AddOrderPush implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * 任务发送到的队列的名称.
     *
     * @var string|null
     */
    public $queue = 'add-order-push';

    /**
     * 处理任务的延迟时间.
     *
     * @var int
     */
    public $delay = 60;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 3;

    public $orderList;

    public $toId;

    public $token;

    static $type = 'add_order';

    /**
     * AddOrderPush constructor.
     * @param array $orderList
     * @param null $toId
     * @param $packageList
     */
    public function __construct(array $orderList, $toId = null)
    {
        $this->orderList = $orderList;
        $this->toId = $toId;
        $this->token = JWTAuth::getToken();
    }


    /**
     * Execute the job.
     * @throws \WebSocket\BadOpcodeException
     */
    public function handle()
    {
        $message = [
            'type' => 'pushOneDriver',
            'to_id' => $this->toId,
            'data' => $this->getData()
        ];
        try {
            $client = new Client('wss://' . config('tms.push_url') . '/?token=' . $this->token);
            $client->send(json_encode($message, JSON_UNESCAPED_UNICODE));
            $client->close();
        } catch (\Exception $ex) {
            Log::channel('job-daily')->error('加单错误:' . $ex->getMessage());
            return false;
        }
        return true;
    }

    /**
     * 获取推送数据
     * @return array
     */
    private function getData()
    {
        $data = [
            'type' => self::$type,
            'is_exist_special_remark' => !empty(array_filter(array_column($this->orderList, 'special_remark'))) ? true : false,
            'order_list' => $this->getOrderPackageList($this->orderList),
            'material_list' => $this->getMaterialList($this->orderList),
        ];
        return $data;
    }


    /**
     * 获取订单包裹列表
     * @param $orderList
     * @return mixed
     */
    private function getOrderPackageList($orderList)
    {
        $packageList = Package::query()->whereIn('order_no', array_column($orderList, 'order_no'))->get(['order_no', 'type', 'name', 'express_first_no', 'expect_quantity'])->toArray();
        $packageList = collect($packageList)->groupBy('order_no')->toArray();
        foreach ($orderList as &$order) {
            $order['package_list'] = $packageList[$order['order_no']] ?? '';
            $order = Arr::only($order, ['order_no', 'special_remark']);
        }
        unset($packageList);
        return $orderList;
    }

    /**
     * 获取材料列表
     * @param $orderList
     * @return array
     */
    private function getMaterialList($orderList)
    {
        return Material::query()
            ->whereIn('order_no', array_column($orderList, 'order_no'))
            ->get(['name', 'code', DB::raw('SUM(expect_quantity) as expect_quantity'), DB::raw('0 as actual_quantity')])
            ->toArray();
    }
}
