<?php

namespace App\Jobs;

use App\Models\Batch;
use App\Models\Tour;
use App\Services\BaseConstService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OutWarehouse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * 任务连接名称。
     *
     * @var string|null
     */
    public $connection = 'redis';

    /**
     * 任务发送到的队列的名称.
     *
     * @var string|null
     */
    public $queue = 'update-line-time';

    /**
     * 任务可以执行的最大秒数 (超时时间)。
     *
     * @var int
     */
    public $timeout = 30;


    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 3;

    public $tour_no;

    public $trackingOrderList;


    /**
     * UpdateLineCountTime constructor.
     * @param $tourNo
     * @param $trackingOrderList ;
     */
    public function __construct($tourNo, $trackingOrderList)
    {
        $this->tour_no = $tourNo;
        $this->trackingOrderList = $trackingOrderList;
    }


    /**
     * Execute the job.
     * @throws \WebSocket\BadOpcodeException
     */
    public function handle()
    {
        /****************************************2.触发司机出库****************************************************/
        $tour = Tour::query()->where('tour_no', $this->tour_no)->first()->toArray();
        $batchList = Batch::query()->where('tour_no', $this->tour_no)->where('status', BaseConstService::BATCH_DELIVERING)->get()->toArray();
        event(new \App\Events\TourNotify\OutWarehouse($tour, $batchList, $this->trackingOrderList));
        Log::channel('job')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '出库成功');
        return true;
    }
}
