<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/11/30
 * Time: 13:51
 */

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\StockOutLogService;

class StockOutLogController extends BaseController
{
    public function __construct(StockOutLogService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

}