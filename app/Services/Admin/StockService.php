<?php
/**
 * 库存 服务
 * User: long
 * Date: 2020/6/22
 * Time: 13:46
 */

namespace App\Services\Admin;

use App\Http\Resources\Api\Admin\StockResource;
use App\Models\Stock;

class StockService extends BaseService
{
    public $filterRules = [
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'line_id' => ['=', 'line_id'],
        'express_first_no,order_no' => ['like', 'keyword'],
    ];

    public function __construct(Stock $stock)
    {
        parent::__construct($stock, StockResource::class);
    }

    public function getStockLogList($packageNo)
    {
        return $this->getStockLogService()->getList(['express_first_no' => $packageNo], ['*'], false)->toArray();
    }

}
