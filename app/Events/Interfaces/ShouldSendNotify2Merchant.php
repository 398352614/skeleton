<?php

namespace App\Events\Interfaces;

use App\Models\Batch;
use App\Models\Tour;
use App\Services\BaseConstService;

interface ShouldSendNotify2Merchant
{
    /**
     * 需要发送给商家的线路
     */
    public function getTour(): Tour;

    /**
     * 需要发送给商家的批次 -- 可选
     */
    public function getBatch(): ?Batch;

    /**
     * 发送的动作类型
     * @return BaseConstService 订阅及通知常量
     */
    public function notifyType(): int;
}
