<?php

namespace App\Services\Admin;

use App\Http\Resources\CarBrandResource;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Models\CarBrand;
use App\Services\BaseConstService;
use App\Services\BaseService;

class CarBrandService extends BaseService
{
    public $filterRules = [
    ];

    public function __construct(CarBrand $carBrand)
    {
        $this->model = $carBrand;
        $this->query = $this->model::query();
        $this->resource = CarBrandResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

}