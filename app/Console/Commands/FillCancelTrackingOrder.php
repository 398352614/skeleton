<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Package;
use App\Models\Tour;
use App\Models\TrackingOrder;
use App\Services\BaseConstService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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
        $idList = DB::select("SELECT `id` FROM `order` WHERE `company_id`=6 AND `status`=4 AND `tracking_order_no` is null");
        $oldOrderList = DB::select("SELECT * FROM `old_order` WHERE id in ({$idList})");
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
        $trackingOrderList = [];
        foreach ($oldOrderList as $oldOrder) {
            $trackingOrder = Arr::only($oldOrder, $trackingOrderFields);
            $trackingOrderNo = 'YD0' . Str::substr($oldOrder['order_no'], 3);
            $trackingOrder = Arr::add($trackingOrder, 'tracking_order_no', $trackingOrderNo);
            $trackingOrder['place_fullname'] = $oldOrder['receiver_fullname'];
            $trackingOrder['place_phone'] = $oldOrder['receiver_phone'];
            $trackingOrder['place_country'] = $oldOrder['receiver_country'];
            $trackingOrder['place_post_code'] = $oldOrder['receiver_post_code'];
            $trackingOrder['place_house_number'] = $oldOrder['receiver_house_number'];
            $trackingOrder['place_city'] = $oldOrder['receiver_city'];
            $trackingOrder['place_street'] = $oldOrder['receiver_street'];
            $trackingOrder['place_address'] = $oldOrder['receiver_address'];
            $trackingOrder['place_lon'] = $oldOrder['lon'];
            $trackingOrder['place_lat'] = $oldOrder['lat'];
            $trackingOrder = array_merge(array_fill_keys($tourFillFields, ''), $trackingOrder);
            $trackingOrder['line_id'] = null;
            if (!empty($order['tour_no'])) {
                $tour = Tour::query()->where('tour_no', $order['tour_no'])->first();
                if (!empty($tour)) {
                    $trackingOrder = array_merge($trackingOrder, Arr::only($tour->toArray(), $tourFillFields));
                    $trackingOrder['warehouse_fullname'] = $tour['warehouse_name'];
                    unset($trackingOrder['warehouse_name']);
                }
            }
            $trackingOrderList[] = $trackingOrder;
        }
        $trackingOrderModel::query()->insert($trackingOrderList);
    }
}
