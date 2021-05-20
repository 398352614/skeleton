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
    protected $signature = 'fix:package';

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
        try {
            $packageList = DB::table('package')->get()->toArray();
            $count = count($packageList);
            foreach ($packageList as $k => $v) {
                $trackingOrder = DB::table('tracking_order')->where('order_no', $v->order_no)->orderByDesc('id')->first();
                $trackingPackage = DB::table('tracking_package')->where('order_no', $v->order_no)->orderByDesc('id')->first();
                if (empty($trackingPackage) && !empty($trackingOrder)) {
                    //大条件：只有运单
                    if ($trackingOrder->type == BaseConstService::TRACKING_PACKAGE_TYPE_1) {
                        //小条件：运单为取件，则包裹阶段为取件
                        DB::table('package')->where('express_first_no', $v->express_first_no)->update(['stage' => BaseConstService::PACKAGE_STAGE_1]);
                    } elseif ($trackingOrder->type == BaseConstService::TRACKING_PACKAGE_TYPE_2) {
                        //小条件：运单为派件，则包裹阶段为取件
                        DB::table('package')->where('express_first_no', $v->express_first_no)->update(['stage' => BaseConstService::PACKAGE_STAGE_3]);
                    }
                    $this->info('fix:' . ($k + 1) . '/' . $count);
                } elseif (!empty($trackingPackage) && empty($trackingOrder)) {
                    //大条件：只有转运单，则为仓配一体的派件，包裹阶段为中转
                    DB::table('package')->where('express_first_no', $v->express_first_no)->update(['stage' => BaseConstService::PACKAGE_STAGE_2]);
                } elseif (!empty($trackingPackage) && !empty($trackingOrder)) {
                    //大条件：既有运单，也有转运单
                    if ($trackingPackage->created_at > $trackingOrder->created_at) {
                        //小条件：最新创建的是运单，则包裹阶段为派件
                        DB::table('package')->where('express_first_no', $v->express_first_no)->update(['stage' => BaseConstService::PACKAGE_STAGE_3]);
                    } else {
                        //小条件：最新创建的是转运单，则包裹阶段为中转
                        DB::table('package')->where('express_first_no', $v->express_first_no)->update(['stage' => BaseConstService::PACKAGE_STAGE_2]);
                    }
                } else {
                    $this->info('fix fail:' . $v->express_first_no . '包裹阶段空');
                }
            }
        } catch (\Exception $e) {
            $this->info('fix fail:' . $e);
        }
        $this->info('fix end:' . now());
    }
}
