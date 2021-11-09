<?php

namespace App\Console\Commands\Data;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixPhone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:phone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fix:phone';

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
        $orderNoList = [
//            "TMS0006149856",
            "TMS0006149855", "TMS0006149854", "TMS0006149853",
            "TMS0006149851", "TMS0006149850", "TMS0006149849", "TMS0006149847",
            "TMS0006149846", "TMS0006149845", "TMS0006149844", "TMS0006149843", "TMS0006149842", "TMS0006149841"
        ];
        $orderList = DB::table('order')->whereIn('order_no', $orderNoList)->get();
        $trackingOrderList = DB::table('tracking_order')->whereIn('order_no', $orderNoList)->get();

        foreach ($trackingOrderList as $k => $v) {
            $order=$orderList->where('order_no',$v->order_no)->first();
            DB::table('tracking_order')->where('order_no', $v->order_no)->update([
                'place_phone' => $order->place_phone,
                'place_fullname' => $order->place_fullname,
                'place_address' => $order->place_address,
            ]);
            DB::table('batch')->where('batch_no', $v->batch_no)->update([
                'place_phone' => $order->place_phone,
                'place_fullname' => $order->place_fullname,
                'place_address' => $order->place_address,
            ]);
            $this->info('success');
        }
        return;
    }
}
