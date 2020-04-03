<?php

namespace App\Events\Interfaces;

use App\Models\Batch;
use App\Models\Tour;
use App\Services\BaseConstService;

interface ShouldSendNotify2Merchant
{
    /**
     * 发送的动作类型
     * @return BaseConstService 订阅及通知常量
     */
    public function notifyType(): string ;

    /**
     * 推送数据
     * @return array
     */
    public function getDataList(): array;
}
