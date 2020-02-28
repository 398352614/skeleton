<?php


namespace App\Services\Merchant;


use App\Models\Package;
use App\Services\BaseService;

class PackageService extends BaseService
{
    public function __construct(Package $package)
    {
        $this->model = $package;
        $this->query = $this->model::query();
    }


}
