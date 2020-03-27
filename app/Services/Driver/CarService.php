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
        parent::__construct($car, CarResource::class);
    }
}
