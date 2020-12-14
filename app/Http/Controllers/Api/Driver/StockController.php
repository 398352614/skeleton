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
     * 包裹分拣入库
     * @throws BusinessLogicException
     */
    public function packagePickOut()
    {
        return $this->service->packagePickOut($this->data['express_first_no']);
    }
}