<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/3/12
 * Time: 18:19
 */

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\StockService;

/**
 * Class CarBrandController
 * @package App\Http\Controllers\Api\Admin
 * @property StockService $service
 */
class StockController extends BaseController
{
    public function __construct(StockService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function index()
    {
        return $this->service->getPageList();
    }
}
