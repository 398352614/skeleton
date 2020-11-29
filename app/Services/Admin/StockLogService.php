<?php
/**
 * 库存日志 服务
 * User: long
 * Date: 2020/6/22
 * Time: 13:46
 */

namespace App\Services\Admin;

use App\Http\Resources\Api\Admin\StockResource;
use App\Models\Stock;
use App\Models\StockLog;

class StockLogService extends BaseService
{

    public function __construct(StockLog $stockLog)
    {
        parent::__construct($stockLog, StockResource::class);
    }

}
