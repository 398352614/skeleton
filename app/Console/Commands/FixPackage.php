<?php


namespace App\Console\Commands;


use App\Services\BaseConstService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:package {--full= : full}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix package table';

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
        $this->info('begin:' . now());
//        try {
//            foreach ($trackingPackageList as $k=>$v) {
//                DB::table('tracking_package')->where('express_first_no',$v->express_first_no)
//                    ->update(['order_no'=>
//                        $packageList->where('express_first_no',$v->express_first_no)->first()->order_no]);
//            }
        $packageList = DB::table('package')->get()->toArray();
        $trackingOrderList = DB::table('tracking_order')->get();
        foreach ($packageList as $k => $v) {
            if (($v->stage == null && $v->status !== BaseConstService::PACKAGE_STATUS_5)|| $this->option('full') == 1) {
                $trackingOrder = $trackingOrderList->where('order_no', $v->order_no)->sortByDesc('id')->first();
                if(!empty($trackingOrder)){
                    //大条件：只有运单
                    if ($trackingOrder->type == BaseConstService::TRACKING_PACKAGE_TYPE_1) {
                        //小条件：运单为取件，则包裹阶段为取件
                        DB::table('package')->where('express_first_no', $v->express_first_no)->update(['stage' => BaseConstService::PACKAGE_STAGE_1]);
                    } elseif ($trackingOrder->type == BaseConstService::TRACKING_PACKAGE_TYPE_2) {
                        //小条件：运单为派件，则包裹阶段为取件
                        DB::table('package')->where('express_first_no', $v->express_first_no)->update(['stage' => BaseConstService::PACKAGE_STAGE_3]);
                    }
                }
                $this->info('fix:' . ($k + 1));
            }
        }

//        $packageList = DB::table('package')->get()->toArray();
//        $trackingOrderList = DB::table('tracking_order')->get();
//        $trackingPackageList = DB::table('tracking_package')->get();
//        $orderList = DB::table('order')->get();
//        $count = count($packageList);
//        $this->info($count);
//        foreach ($packageList as $k => $v) {
//            if ($v->stage == null || $this->option('full') == 1) {
//                $order = $orderList->where('order_no', $v->order_no)->sortByDesc('id')->first();
//                $trackingOrder = $trackingOrderList->where('order_no', $v->order_no)->sortByDesc('id')->first();
//                $trackingPackage = $trackingPackageList->where('order_no', $v->order_no)->sortByDesc('id')->first();
//                if ($order->status !== BaseConstService::ORDER_STATUS_5) {
//                    if (empty($trackingPackage) && !empty($trackingOrder)) {
//                        //大条件：只有运单
//                        if ($trackingOrder->type == BaseConstService::TRACKING_PACKAGE_TYPE_1) {
//                            //小条件：运单为取件，则包裹阶段为取件
//                            DB::table('package')->where('express_first_no', $v->express_first_no)->update(['stage' => BaseConstService::PACKAGE_STAGE_1]);
//                        } elseif ($trackingOrder->type == BaseConstService::TRACKING_PACKAGE_TYPE_2) {
//                            //小条件：运单为派件，则包裹阶段为取件
//                            DB::table('package')->where('express_first_no', $v->express_first_no)->update(['stage' => BaseConstService::PACKAGE_STAGE_3]);
//                        }
//                        $this->info('fix:' . ($k + 1) . '/' . $count);
//                    } elseif (!empty($trackingPackage) && empty($trackingOrder)) {
//                        //大条件：只有转运单，则为仓配一体的派件，包裹阶段为中转
//                        DB::table('package')->where('express_first_no', $v->express_first_no)->update(['stage' => BaseConstService::PACKAGE_STAGE_2]);
//                        $this->info('fix:' . ($k + 1) . '/' . $count);
//                    } elseif (!empty($trackingPackage) && !empty($trackingOrder)) {
//                        //大条件：既有运单，也有转运单
//                        //装运单的创建时间早于运单的创建时间则，最新创建的是运单
//                        if ($trackingPackage->created_at < $trackingOrder->created_at) {
//                            //小条件：最新创建的是运单，则包裹阶段为派件
//                            DB::table('package')->where('express_first_no', $v->express_first_no)->update(['stage' => BaseConstService::PACKAGE_STAGE_3]);
//                            $this->info('fix:' . ($k + 1) . '/' . $count);
//                        } else {
//                            //小条件：最新创建的是转运单，则包裹阶段为中转
//                            DB::table('package')->where('express_first_no', $v->express_first_no)->update(['stage' => BaseConstService::PACKAGE_STAGE_2]);
//                            $this->info('fix:' . ($k + 1) . '/' . $count);
//                        }
//                    }
//                }
//            }
//        }

//        } catch (\Exception $e) {
//            $this->info('fix fail:' . $e);
//        }
        $this->info('fix end:' . now());
    }
}
