<?php

namespace App\Console\Commands\Data;

use App\Models\Material;
use App\Models\Order;
use App\Models\Package;
use App\Models\Tour;
use App\Models\TrackingOrder;
use App\Models\TrackingOrderMaterial;
use App\Models\TrackingOrderPackage;
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
    protected $signature = 'fill:cancel-tracking-order';

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
        $idList = DB::select("SELECT GROUP_CONCAT(`id`) as id_list FROM `order` WHERE `company_id`=6 AND `status`=4 AND `tracking_order_no` is null");
        $idList = $idList[0]->id_list;
        $oldOrderList = DB::select("SELECT * FROM `old_order` WHERE id in ({$idList})");
        $oldOrderList = collect($oldOrderList)->map(function ($oldOrder, $key) {
            return collect($oldOrder);
        })->toArray();
        $trackingOrderFields = (new TrackingOrder())->getFillable();
        $trackingOrderPackageFields = (new TrackingOrderPackage())->getFillable();
        $trackingOrderModel = new TrackingOrder();
        $trackingOrderPackageModel = new TrackingOrderPackage();
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
            if (!empty($oldOrder['tour_no'])) {
                $tour = Tour::query()->where('tour_no', $oldOrder['tour_no'])->first();
                if (!empty($tour)) {
                    $trackingOrder = array_merge($trackingOrder, Arr::only($tour->toArray(), $tourFillFields));
                    $trackingOrder['warehouse_fullname'] = $tour['warehouse_name'];
                    unset($trackingOrder['warehouse_name']);
                }
            }
            $trackingOrderList[] = $trackingOrder;
            //订单更新
            Order::query()->where('order_no', $oldOrder['order_no'])->update(['tracking_order_no' => $trackingOrderNo]);
            //包裹更新
            Package::query()->where('order_no', $oldOrder['order_no'])->update(['tracking_order_no' => $trackingOrderNo]);
            //材料更新
            Material::query()->where('order_no', $oldOrder['order_no'])->update(['tracking_order_no' => $trackingOrderNo]);
            //运单材料更新
            TrackingOrderMaterial::query()->where('order_no', $oldOrder['order_no'])->update(['tracking_order_no' => $trackingOrderNo]);
            //运单包裹新增
            $packageList = Package::query()->where('order_no', $oldOrder['order_no'])->get()->toArray();
            if (empty($packageList)) continue;
            $packageList = collect($packageList)->map(function ($package, $key) use ($trackingOrderPackageFields) {
                return collect(Arr::only($package, $trackingOrderPackageFields));
            })->toArray();
            data_set($packageList, '*.tour_no', $trackingOrder['tour_no']);
            data_set($packageList, '*.batch_no', $trackingOrder['batch_no']);
            data_set($packageList, '*.tracking_order_no', $trackingOrder['tracking_order_no']);
            data_set($packageList, '*.type', $trackingOrder['type']);
            data_set($packageList, '*.status', $trackingOrder['status']);
            data_set($packageList, '*.execution_date', $trackingOrder['execution_date']);
            $trackingOrderPackageModel::query()->insert($packageList);
        }
        $trackingOrderModel::query()->insert($trackingOrderList);
        $this->info('successful\n');
        return;
    }
}
