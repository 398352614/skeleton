<?php

namespace App\Console\Commands\Admin;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DestroyOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'destroy:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'destroy order';

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
        $array=['order','package','material','batch','tour','batch_exception','order_amount','order_customer_record','order_receipt','order_trail','package_trail',
            'stock','stock_exception','stock_in_log','stock_out_log','route_tracking','tour_delay','tour_driver_event','tour_material','tracking_order','tracking_order_material',
            'tracking_order_package','tracking_order_trail'];
        foreach ($array as $v){
            DB::table($v)->truncate();
            $this->info($v.'truncate successful');
        }
    }
}
