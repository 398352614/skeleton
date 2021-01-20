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
        'express_first_no,order_no' => ['like', 'keyword'],
        'express_first_no'=>['like','express_first_no'],
        'order_no'=>['like','order_no'],
        'line_id' => ['=', 'line_id'],
        'line_name' => ['like', 'line_name'],
    ];

    public function __construct(Stock $stock)
    {
        parent::__construct($stock, StockResource::class);
    }

}
