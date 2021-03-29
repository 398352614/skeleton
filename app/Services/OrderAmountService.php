<?php
/**
 * Created by PhpStorm
 * User: Yomi
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\OrderImportInfoResource;
use App\Http\Resources\Api\Admin\OrderImportResource;
use App\Models\OrderAmount;
use App\Models\OrderImportLog;
use App\Traits\ExportTrait;
use Illuminate\Support\Facades\Storage;

class OrderAmountService extends BaseService
{
    public function __construct(OrderAmount $orderAmount)
    {
        parent::__construct($orderAmount);
    }
}
