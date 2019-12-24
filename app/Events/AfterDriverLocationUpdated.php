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

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Tour $tour, $nextBatchNo,$location=null)
    {
        $this->tour = $tour;
        $this->location = $location;
        $this->nextBatchNo = $nextBatchNo;
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
