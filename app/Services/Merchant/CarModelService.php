<?php

namespace App\Services\Merchant;

use App\Http\Resources\CarBrandResource;
use App\Http\Resources\CarModelResource;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Services\BaseConstService;
use App\Services\BaseService;

class CarModelService extends BaseService
{
    public $filterRules = [
        'brand_id' => ['=', 'brand_id'],
    ];

    public function __construct(CarModel $carBrand)
    {
        $this->model = $carBrand;
        $this->query = $this->model::query();
        $this->resource = CarModelResource::class;
        $this->infoResource = CarModelResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

}
