<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/11/29
 * Time: 16:06
 */

namespace App\Services\Driver;

use App\Http\Resources\Api\Admin\StockInResource;
use App\Models\StockOutLog;

class StockOutLogService extends BaseService
{
    public function __construct(StockOutLog $stockOutLog)
    {
        parent::__construct($stockOutLog, null, null);
    }

}