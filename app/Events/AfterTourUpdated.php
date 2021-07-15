<?php

namespace App\Events;

use App\Models\Tour;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
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

    public $queue;

    /**
     * Create a new event instance.
     *
     * @param Tour $tour
     * @param $nextBatch
     * @param $queue
     */
    public function __construct(Tour $tour, $nextBatch, $queue = false)
    {
        $this->tour = $tour;
        $this->nextBatch = $nextBatch;
        $this->queue = $queue;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
