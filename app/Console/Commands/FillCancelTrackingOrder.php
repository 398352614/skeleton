<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\TrackingOrder;
use App\Services\BaseConstService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FillCancelTrackingOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel-tracking-order:fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fill cancel tracking order';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $count = Order::query()->where('status', BaseConstService::ORDER_STATUS_4)->count();
        $pages = ceil($count / 500);
        $trackingOrderFields = (new TrackingOrder())->getFillable();
        $trackingOrderModel = new TrackingOrder();
        $tourFillFields = [
            'line_id',
            'line_name',
            'warehouse_name',
            'warehouse_phone',
            'warehouse_country',
            'warehouse_post_code',
            'warehouse_house_number',
            'warehouse_city',
            'warehouse_street',
            'warehouse_address',
            'warehouse_lon',
            'warehouse_lat'
        ];
        /****************************************************新增运单**************************************************/
        for ($i = 1; $i <= $pages; $i++) {
            $orderList = Order::query()->where('status', BaseConstService::ORDER_STATUS_4)->forPage($i, 500)->get()->toArray();
            $trackingOrderList = [];
            foreach ($orderList as $order) {
                $trackingOrder = Arr::only($order, $trackingOrderFields);
                $trackingOrderNo = 'YD0' . Str::substr($order['order_no'], 3);
                $trackingOrder = Arr::add($trackingOrder, 'tracking_order_no', $trackingOrderNo);
                $trackingOrder = array_merge(array_fill_keys($tourFillFields, ''), $trackingOrder);
                $trackingOrder['line_id'] = null;
                $trackingOrderList[] = $trackingOrder;
            }
            $trackingOrderModel::query()->insert($trackingOrderList);
        }
    }
}
