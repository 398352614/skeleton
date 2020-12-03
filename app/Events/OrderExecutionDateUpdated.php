<?php

namespace App\Events;

use App\Services\BaseConstService;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderExecutionDateUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order_no;

    public $out_order_no;

    public $execution_date;

    public $second_execution_date;

    public $status;

    public $batch_no;

    public $tour;

    /**
     * Create a new event instance.
     *
     * @param $orderNo
     * @param $outOrderNo
     * @param $executionDate
     * @params $secondExecutionDate
     * @param $batchNo
     * @param $tour
     * @params $status
     * @return void
     */
    public function __construct($orderNo, $outOrderNo, $executionDate, $secondExecutionDate, $status, $batchNo, $tour)
    {
        $this->order_no = $orderNo;
        $this->out_order_no = $outOrderNo;
        $this->execution_date = $executionDate;
        $this->second_execution_date = $secondExecutionDate;
        $this->status = $status;
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
