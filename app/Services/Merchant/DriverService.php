<?php

namespace App\Services\Merchant;

use App\Models\Driver;

class DriverService extends BaseService
{
    public function __construct(Driver $driver)
    {
        parent::__construct($driver);
    }

}
