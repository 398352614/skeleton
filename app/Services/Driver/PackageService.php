<?php


namespace App\Services\Driver;


use App\Models\Package;


class PackageService extends BaseService
{
    public function __construct(Package $package)
    {
        parent::__construct($package);

    }


}
