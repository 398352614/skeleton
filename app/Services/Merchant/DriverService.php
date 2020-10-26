<?php

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Merchant\DriverResource;
use App\Http\Resources\Api\Merchant\TourResource;
use App\Models\Driver;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\Merchant\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class DriverService extends BaseService
{
    public function __construct(Driver $driver)
    {
        parent::__construct($driver);
    }

}
