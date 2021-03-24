<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/11/29
 * Time: 16:06
 */

namespace App\Services\Driver;

use App\Models\StockInLog;

class StockInLogService extends BaseService
{
    public function __construct(StockInLog $stockInLog)
    {
        parent::__construct($stockInLog, null, null);
    }


}
