<?php


namespace App\Services\Admin;


use App\Http\Resources\SourceResource;
use App\Models\Source;
use App\Services\BaseService;

class SourceService extends BaseService
{
    public function __construct(Source $source)
    {
        $this->model = $source;
        $this->query = $this->model::query();
        $this->request = request();
        $this->formData = $this->request->all();
        $this->resource = SourceResource::class;

    }

    public function create($data)
    {
        return parent::create($data);
    }

    public function getInfo($where, $selectFields = ['*'], $isResource = true, $orderFields = [])
    {
        return parent::getInfo($where, $selectFields, $isResource, $orderFields);
    }
}
