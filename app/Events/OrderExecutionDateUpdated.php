<?php

namespace App\Events;

use App\Services\BaseConstService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderExecutionDateUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order_no;

    public $execution_date;

    public $tour;

    /**
     * Create a new event instance.
     *
     * @param $orderNo
     * @param $executionDate
     * @param $tour
     * @return void
     */
    public function __construct($orderNo, $executionDate, $tour)
    {
        $this->order_no = $orderNo;
        $this->execution_date = $executionDate;
        $this->tour = $tour;
    }

    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_ORDER_EXECUTION_DATE_UPDATE;
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
