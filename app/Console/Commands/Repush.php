<?php

namespace App\Console\Commands;

use App\Models\Batch;
use App\Models\Order;
use App\Models\Tour;
use Illuminate\Console\Command;

class Repush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repush {--order_no= : order_no}{--tour_no= : tour_no}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试 guzzle';

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
        if (!empty($this->option('order_no'))) {
            $order = Order::query()->where('order_no', $this->option('order_no'))->first()->toArray();
            $orderList = Order::query()->where('order_no', $order['order_no'])->get()->toArray();
        } elseif (!empty($this->option('tour_no'))) {
            $order = Order::query()->where('tour_no', $this->option('tour_no'))->first()->toArray();
            $orderList = Order::query()->where('tour_no', $order['tour_no'])->get()->toArray();
        }else{
            return;
        }
        $tour = Tour::query()->where('tour_no', $order['tour_no'])->first()->toArray();
        $batch = Batch::query()->where('batch_no', $order['batch_no'])->first()->toArray();
        //签收通知
        if($tour['company_id'] == config('tms.old_company_id')){
            event(new \App\Events\TourNotify\AssignBatch($tour, $batch, $orderList));
        }else{
            event(new \App\Events\TourNotify2\AssignBatch($tour, $batch, $orderList));
        }
    }
}
