<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/11/29
 * Time: 16:06
 */

namespace App\Services\Driver;


use App\Models\StockLog;
use Illuminate\Database\Eloquent\Model;

class StockLogService extends BaseService
{
    public function __construct(StockLog $stockLog, $resource = null, $infoResource = null)
    {
        parent::__construct($stockLog, $resource, $infoResource);
    }

}