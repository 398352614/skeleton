<?php

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\ConstTranslateTrait;

class CarService extends BaseService
{
    public function __construct(Car $car)
    {
        parent::__construct($car);
    }
}
