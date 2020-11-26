<?php

namespace App\Services\Admin;

use App\Http\Resources\Api\Admin\CarBrandResource;
use App\Models\CarBrand;

class CarBrandService extends BaseService
{
    public $filterRules = [
    ];

    public function __construct(CarBrand $carBrand)
    {
        parent::__construct($carBrand, CarBrandResource::class);
    }


    public function index()
    {
        return parent::getList([], ['id', 'cn_name', 'en_name'], false)->toArray();
    }
}
