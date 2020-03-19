<?php

namespace App\Events;

use App\Models\Batch;
use App\Services\BaseConstService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverArriveBatch
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $batch;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $batchNo)
    {
        $batch = Batch::where('batch_no', $batchNo)->first();
        $this->batch = $batch;
    }

    /**
     * 获取线路司机事件文本
     */
    public function getContent(): string
    {
        return '司机到达客户家';
    }

    /**
     * 获取当前司机事件的司机位置
     */
    public function getLocation(): array
    {
        return [
            'lon'       => $this->batch->receiver_lon,
            'lat'       => $this->batch->receiver_lat,
        ];
    }

    /**
     * 获取当前司机事件的 tour_no
     */
    public function getTourNo(): string
    {
        return $this->batch->tour_no;
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
