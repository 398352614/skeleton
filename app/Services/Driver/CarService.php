<?php
/**
 * 车辆管理 服务
 * User: long
 * Date: 2019/12/30
 * Time: 14:00
 */

namespace App\Services\Driver;


use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Services\BaseService;

class CarService extends BaseService
{
    public function __construct(Car $car)
    {
        $this->request = request();
        $this->model = $car;
        $this->query = $this->model::query();
        $this->resource = CarResource::class;
    }
}