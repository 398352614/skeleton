<?php

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\DriverResource;
use App\Http\Resources\TourResource;
use App\Models\Driver;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class DriverService extends BaseService
{
    public function __construct(Driver $driver)
    {
        parent::__construct($driver);
    }

}
