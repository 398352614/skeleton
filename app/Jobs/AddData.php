<?php

namespace App\Jobs;

use App\Models\OrderTrail;
use App\Models\TourDriverEvent;
use App\Models\TrackingOrderTrail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddData implements ShouldQueue
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
    public $queue = 'add-data';

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

    public $type;

    public $data;


    /**
     * addTrail constructor.
     * @param $type
     * @param $data
     */
    public function __construct($type, $data)
    {
        $this->type = $type;
        $this->data = $data;
    }


    /**
     * 触发入库分拣队列
     * Execute the job.
     */
    public function handle()
    {
        if ($this->type == 'order-trail') {
            $query = OrderTrail::query();
        } elseif ($this->type == 'tracking-order-trail') {
            $query = TrackingOrderTrail::query();
        } elseif ($this->type == 'package-trail') {
            $query = OrderTrail::query();
        } elseif ($this->type == 'tour-event') {
            $query = TourDriverEvent::query();
        } else {
            return;
        }
        $query->insert($this->data);
    }

}
