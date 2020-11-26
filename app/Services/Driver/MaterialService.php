<?php

namespace App\Services\Driver;

use App\Models\Material;

class MaterialService extends BaseService
{
    public function __construct(Material $material)
    {
        parent::__construct($material);
    }


}
