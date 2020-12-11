<?php
/**
 * 入库日志 服务
 * User: long
 * Date: 2020/6/22
 * Time: 13:46
 */

namespace App\Services\Admin;

use App\Http\Resources\Api\Admin\StockInResource;
use App\Models\StockInLog;

class StockInLogService extends BaseService
{
    public $filterRules = [
        'tracking_order_no,order_no,out_order_no' => ['like', 'keyword'],
    ];

    public function __construct(StockInLog $stockInLog)
    {
        parent::__construct($stockInLog, StockInResource::class);
    }
}
