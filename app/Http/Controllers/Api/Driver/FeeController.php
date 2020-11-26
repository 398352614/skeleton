<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/6/28
 * Time: 15:49
 */

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\BaseController;
use App\Services\BaseService;
use App\Services\Driver\FeeService;

/**
 * Class FeeController
 * @package App\Http\Controllers\Api\Driver
 * @property FeeService $service
 */
class FeeController extends BaseController
{
    public function __construct(FeeService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function getAllFeeList(){
        return $this->service->getAllFeeList();
    }
}