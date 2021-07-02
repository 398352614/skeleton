<?php

namespace App\Console\Commands;

use App\Models\Material;
use App\Models\Order;
use App\Models\Package;
use App\Models\Tour;
use App\Models\TrackingOrder;
use App\Models\TrackingOrderMaterial;
use App\Models\TrackingOrderPackage;
use App\Services\BaseConstService;
use App\Services\OrderNoRuleService;
use App\Traits\FactoryInstanceTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InitTrackingOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:tracking-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tracking order init';

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
        try {
//            $count = Order::query()->where('status', '<>', BaseConstService::TRACKING_ORDER_STATUS_7)->count();
//            $pages = ceil($count / 500);
//            $trackingOrderFields = (new TrackingOrder())->getFillable();
//            $tourFillFields = [
//                'line_id',
//                'line_name',
//                'warehouse_name',
//                'warehouse_phone',
//                'warehouse_country',
//                'warehouse_post_code',
//                'warehouse_house_number',
//                'warehouse_city',
//                'warehouse_street',
//                'warehouse_address',
//                'warehouse_lon',
//                'warehouse_lat'
//            ];
//            $trackingOrderModel = new TrackingOrder();
//            $startIndex = 1;
//            /****************************************************新增运单**************************************************/
//            for ($i = 1; $i <= $pages; $i++) {
//                $orderList = Order::query()->where('status', '<>', BaseConstService::TRACKING_ORDER_STATUS_7)->forPage($i, 500)->get()->toArray();
//                $trackingOrderList = [];
//                foreach ($orderList as $order) {
//                    $trackingOrder = Arr::only($order, $trackingOrderFields);
//                    $trackingOrder['place_fullname'] = $order['receiver_fullname'];
//                    $trackingOrder['place_phone'] = $order['receiver_phone'];
//                    $trackingOrder['place_country'] = $order['receiver_country'];
//                    $trackingOrder['place_post_code'] = $order['receiver_post_code'];
//                    $trackingOrder['place_house_number'] = $order['receiver_house_number'];
//                    $trackingOrder['place_city'] = $order['receiver_city'];
//                    $trackingOrder['place_street'] = $order['receiver_street'];
//                    $trackingOrder['place_address'] = $order['receiver_address'];
//                    $trackingOrder['place_lon'] = $order['lon'];
//                    $trackingOrder['place_lat'] = $order['lat'];
//                    //$trackingOrderNo = $orderNoRuleService->createTrackingOrderNo($order['company_id']);
//                    $trackingOrderNo = 'YD0' . Str::substr($order['order_no'], 3);
//                    $startIndex++;
//                    $trackingOrder = Arr::add($trackingOrder, 'tracking_order_no', $trackingOrderNo);
//                    $trackingOrder = array_merge(array_fill_keys($tourFillFields, ''), $trackingOrder);
//                    $trackingOrder['line_id'] = null;
//                    if (!empty($order['tour_no'])) {
//                        $tour = Tour::query()->where('tour_no', $order['tour_no'])->first();
//                        if (!empty($tour)) {
//                            $trackingOrder = array_merge($trackingOrder, Arr::only($tour->toArray(), $tourFillFields));
//                            $trackingOrder['warehouse_fullname'] = $tour['warehouse_name'];
//                            unset($trackingOrder['warehouse_name']);
//                        }
//                    }
//                    $trackingOrderList[] = $trackingOrder;
//                }
//                $trackingOrderModel::query()->insert($trackingOrderList);
//            }
            /************************************************新增运单包裹**********************************************/
            $count = Package::query()->where('status', '<>', BaseConstService::TRACKING_ORDER_STATUS_7)->count();
            $pages = ceil($count / 500);
            $trackingOrderPackageModel = new TrackingOrderPackage();
            $fields = $trackingOrderPackageModel->getFillable();
            for ($i = 1; $i <= $pages; $i++) {
                $packageList = Package::query()->where('status', '<>', BaseConstService::TRACKING_ORDER_STATUS_7)->forPage($i, 500)->get()->toArray();
                $trackingOrderPackageList = collect($packageList)->map(function ($package, $key) use ($fields) {
                    $package = Arr::only($package, $fields);
                    return collect($package);
                })->toArray();
                $trackingOrderPackageModel::query()->insert($trackingOrderPackageList);
            }
            /************************************************新增运单材料**********************************************/
            $count = Material::query()->count();
            $pages = ceil($count / 500);
            $TrackingOrderMaterialModel = new TrackingOrderMaterial();
            $fields = $TrackingOrderMaterialModel->getFillable();
            for ($i = 1; $i <= $pages; $i++) {
                $materialList = Material::query()->forPage($i, 500)->get()->toArray();
                $trackingOrderMaterialList = collect($materialList)->map(function ($material, $key) use ($fields) {
                    $material = Arr::only($material, $fields);
                    return collect($material);
                })->toArray();
                $TrackingOrderMaterialModel::query()->insert($trackingOrderMaterialList);
            }
        } catch (\Exception $e) {
            //dd($e->getTrace());
            $this->error($e->getLine());
            $this->error($e->getMessage());
            return;
        }
        $this->info('successful');
        return;
    }

}
