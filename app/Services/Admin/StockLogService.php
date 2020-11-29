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
    public $filterRules = [
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'line_id' => ['=', 'line_id'],
        'express_first_no,order_no' => ['like', 'keyword'],
    ];

    public function __construct(StockLog $stockLog)
    {
        parent::__construct($stockLog, StockResource::class);
    }

}
