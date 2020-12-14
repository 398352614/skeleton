<?php

namespace App\Events;

use App\Services\BaseConstService;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderDelete
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
        return BaseConstService::NOTIFY_ORDER_DELETE;
    }

    /**
     * 获取第三方对接内容
     * @param bool $status
     * @param string $msg
     * @return string
     */
    public function getThirdPartyContent(bool $status, string $msg = ''): string
    {
        if ($status == true) {
            $content = '删除订单推送成功';
        } else {
            $content = '删除订单推送失败,失败原因:' . $msg;
        }
        return $content;
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
