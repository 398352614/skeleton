<?php

namespace App\Events;

use App\Models\Tour;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
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


    /**
     * AfterDriverLocationUpdated constructor.
     * @param Tour $tour
     * @param $nextBatchNo
     * @param null $location
     * @param bool $queue 是否加入队列
     */
    public function __construct(Tour $tour, $nextBatchNo, $location = null, $queue = false)
    {
        $this->tour = $tour;
        $this->location = $location;
        $this->nextBatchNo = $nextBatchNo;
        $this->queue = $queue;
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
