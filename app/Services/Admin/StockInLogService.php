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
    public function __construct(StockInLog $stockInLog)
    {
        parent::__construct($stockInLog, StockInResource::class);
    }
}
