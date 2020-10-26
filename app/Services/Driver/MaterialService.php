<?php


namespace App\Services\Driver;


use App\Models\Material;
use App\Models\Package;


class MaterialService extends BaseService
{
    public function __construct(Material $material)
    {
        parent::__construct($material);
    }


}
