<?php

namespace App\Events;

use App\Models\Tour;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AfterTourInit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Tour
     */

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Tour $tour)
    {
        $this->tour = $tour;
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
