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

    public $orderList;

    public $toId;

    public $token;

    static $type = 'add_order';

    public $tries = 3;

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
            'data' => [
                'type' => self::$type,
                'order_list' => $this->getOrderPackageList($this->orderList),
                'material_list' => $this->getMaterialList($this->orderList)],
        ];
        try {
            $client = new Client('wss://' . config('tms.push_url') . '/?token=' . $this->token);
            $client->send(json_encode($message, JSON_UNESCAPED_UNICODE));
            $client->close();
        } catch (\Exception $ex) {
            Log::error('加单错误:' . $ex->getMessage());
            return false;
        }
        return true;
    }

    /**
     * 获取订单包裹列表
     * @param $orderList
     * @return mixed
     */
    public function getOrderPackageList($orderList)
    {
        $packageList = Package::query()->whereIn('order_no', array_column($orderList, 'order_no'))->get(['order_no', 'type', 'name', 'express_first_no', 'expect_quantity'])->toArray();
        $packageList = collect($packageList)->groupBy('order_no')->toArray();
        foreach ($orderList as &$order) {
            $order['package_list'] = $packageList[$order['order_no']] ?? '';
            $order = Arr::only($order, ['order_no']);
        }
        unset($packageList);
        return $orderList;
    }

    /**
     * 获取材料列表
     * @param $orderList
     * @return array
     */
    public function getMaterialList($orderList)
    {
        return Material::query()
            ->whereIn('order_no', array_column($orderList, 'order_no'))
            ->get(['name', 'code', DB::raw('SUM(expect_quantity) as expect_quantity'), DB::raw('0 as actual_quantity')])
            ->toArray();
    }
}
