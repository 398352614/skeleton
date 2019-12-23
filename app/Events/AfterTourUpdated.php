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

class AfterTourUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Tour
     */
    public $tour;

    public $nextBatch;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Tour $tour, $nextBatch)
    {
        $this->tour = $tour;
        $this->nextBatch = $nextBatch;
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
