<?php

namespace App\Services\Merchant;

use App\Models\Car;

class CarService extends BaseService
{
    public function __construct(Car $car)
    {
        parent::__construct($car);
    }
}
