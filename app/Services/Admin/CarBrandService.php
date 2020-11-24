<?php

namespace App\Services\Admin;

use App\Http\Resources\CarBrandResource;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Models\CarBrand;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

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
