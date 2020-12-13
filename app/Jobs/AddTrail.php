<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\TrackingOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddTrail implements ShouldQueue
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
    public $queue = 'add-trail';

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
        $query = ($this->type == 'order') ? Order::query() : TrackingOrder::query();
        $query->insert($this->data);
    }

}
