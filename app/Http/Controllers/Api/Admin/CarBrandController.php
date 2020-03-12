<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/3/12
 * Time: 18:19
 */

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\CarBrandService;

/**
 * Class CarBrandController
 * @package App\Http\Controllers\Api\Admin
 * @property CarBrandService $service
 */
class CarBrandController extends BaseController
{
    public function __construct(CarBrandService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function index()
    {
        return $this->service->index();
    }


    public function store()
    {
        return $this->service->create($this->data);
    }
}