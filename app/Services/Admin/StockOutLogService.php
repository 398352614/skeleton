<?php
/**
 * 出库日志 服务
 * User: long
 * Date: 2020/6/22
 * Time: 13:46
 */

namespace App\Services\Admin;

use App\Http\Resources\Api\Admin\StockOutResource;
use App\Models\StockOutLog;

class StockOutLogService extends BaseService
{
    public $filterRules = [
        'tracking_order_no,order_no,express_first_no' => ['like', 'keyword'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
    ];

    public function __construct(StockOutLog $stockOutLog)
    {
        parent::__construct($stockOutLog, StockOutResource::class);
    }
}
