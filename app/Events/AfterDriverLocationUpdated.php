<?php

namespace App\Events;

use App\Models\Tour;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AfterDriverLocationUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Tour $tour
     */
    public $tour;

    public $location;

    public $nextBatchNo;

    public $queue;

    public $notifyNextBatch;


    /**
     * AfterDriverLocationUpdated constructor.
     * @param Tour $tour
     * @param $nextBatchNo
     * @param null $location
     * @param bool $queue 是否加入队列
     * @param bool $notifyNextBatch 是否通知下一家客户
     */
    public function __construct(Tour $tour, $nextBatchNo, $location = null, $queue = false, $notifyNextBatch = false)
    {
        $this->tour = $tour;
        $this->location = $location;
        $this->nextBatchNo = $nextBatchNo;
        $this->queue = $queue;
        $this->notifyNextBatch = $notifyNextBatch;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
