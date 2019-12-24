<?php

namespace App\Services\Admin;

use App\Http\Resources\BatchResource;
use App\Http\Resources\TourResource;
use App\Models\Batch;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\BaseService;

class BatchService extends BaseService
{

    public $filterRules = [
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'order_no,out_order_no' => ['like', 'keyword']
    ];

    public function __construct(Batch $batch)
    {
        $this->model = $batch;
        $this->query = $this->model::query();
        $this->resource = BatchResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

    //新增
    public function store($params)
    {

    }


}