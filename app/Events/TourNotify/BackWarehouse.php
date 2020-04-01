<?php

namespace App\Events\TourNotify;

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

/**
 * 司机回仓事件
 */
class BackWarehouse implements ShouldSendNotify2Merchant
{
    public $tour;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Tour $tour)
    {
        $this->tour = $tour;
    }

    public function getTour(): Tour
    {
        return $this->tour;
    }

    public function getBatch(): ?Batch
    {
        return null;
    }

    public function notifyType(): int
    {
        return BaseConstService::BACK_WAREHOUSE;
    }

    public function getMerchantList(): array
    {
        return [];
    }


    public function getData(): array
    {
        return [];
    }
}
