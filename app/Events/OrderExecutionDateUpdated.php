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

    public $out_order_no;

    public $execution_date;

    public $batch_no;

    public $tour;

    /**
     * Create a new event instance.
     *
     * @param $orderNo
     * @param $outOrderNo
     * @param $executionDate
     * @param $batchNo
     * @param $tour
     * @return void
     */
    public function __construct($orderNo, $outOrderNo, $executionDate, $batchNo, $tour)
    {
        $this->order_no = $orderNo;
        $this->out_order_no = $outOrderNo;
        $this->execution_date = $executionDate;
        $this->batch_no = $batchNo;
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
