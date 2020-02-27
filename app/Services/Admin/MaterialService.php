<?php


namespace App\Services\Admin;


use App\Models\Material;
use App\Models\Package;
use App\Services\BaseService;

class MaterialService extends BaseService
{
    public function __construct(Material $materia)
    {
        $this->model = $materia;
        $this->query = $this->model::query();
    }


}
