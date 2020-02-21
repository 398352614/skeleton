<?php

namespace App\Events;

use App\Events\Interfaces\ShouldSendNotify2Merchant;
use App\Models\Batch;
use App\Models\Tour;
use App\Services\BaseConstService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BatchAssignFail implements ShouldSendNotify2Merchant
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $batch;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Batch $batch)
    {
        $this->batch = $batch;
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

    public function getTour(): Tour
    {
        return $this->batch->tour;
    }

    public function getBatch(): ?Batch
    {
        return $this->batch;
    }

    public function notifyType(): int
    {
        return BaseConstService::PICKUP_FAILED;
    }
}
