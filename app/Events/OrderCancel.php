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

class OrderCancel
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order_no;

    public $out_order_no;

    /**
     * Create a new event instance.
     *
     * @param $orderNo
     * @param $outOrderNo
     * @return void
     */
    public function __construct($orderNo, $outOrderNo)
    {
        $this->order_no = $orderNo;
        $this->out_order_no = $outOrderNo;
    }

    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_ORDER_CANCEL;
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
