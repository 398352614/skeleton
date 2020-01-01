<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/30
 * Time: 13:59
 */

namespace App\Http\Controllers\Api\Driver;


use App\Http\Controllers\BaseController;
use App\Services\Driver\CarService;

class CarController extends BaseController
{
    public function __construct(CarService $service)
    {
        parent::__construct($service);
    }

    public function index()
    {
        return $this->service->getPageList();
    }
}