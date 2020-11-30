<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/11/30
 * Time: 13:51
 */

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\BaseService;

class StockInLogController extends BaseController
{
    public function __construct(BaseService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

}