<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/11/30
 * Time: 13:51
 */

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\StockInLogService;

/**
 * Class StockInLogController
 * @package App\Http\Controllers\Api\Admin
 * @property StockInLogService $service
 */
class StockInLogController extends BaseController
{
    public function __construct(StockInLogService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

}