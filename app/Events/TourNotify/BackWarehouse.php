<?php

namespace App\Events\TourNotify;

use App\Events\Interfaces\ATourNotify;
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

/**
 * 司机回仓事件
 */
class BackWarehouse extends ATourNotify
{
    public $tour;

    /**
     * Create a new event instance.
     *
     * @param $tour
     * @return void
     */
    public function __construct($tour)
    {
        parent::__construct($tour, [], [], []);
    }


    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_BACK_WAREHOUSE;
    }


    public function getDataList(): array
    {
        return [];
    }
}
