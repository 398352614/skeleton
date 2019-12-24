<?php

namespace App\Services\Admin;

use App\Http\Resources\DriverResource;
use App\Http\Resources\TourResource;
use App\Models\Driver;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\BaseService;

class DriverService extends BaseService
{

    public $filterRules = [
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'order_no,out_order_no' => ['like', 'keyword']
    ];

    public function __construct(Driver $driver)
    {
        $this->model = $driver;
        $this->query = $this->model::query();
        $this->resource = DriverResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

    //新增
    public function store($params)
    {

    }


}