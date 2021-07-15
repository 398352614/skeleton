<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/11/29
 * Time: 16:57
 */

namespace App\Http\Controllers\Api\Driver;


use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Driver\StockService;

/**
 * Class StockController
 * @package App\Http\Controllers\Api\Driver
 * @property StockService $service
 */
class StockController extends BaseController
{
    public function __construct(StockService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * 包裹分拨
     * @throws BusinessLogicException
     */
    public function allocate()
    {
        return $this->service->allocate($this->data['express_first_no'], $this->data['ignore_rule']);
    }
}
