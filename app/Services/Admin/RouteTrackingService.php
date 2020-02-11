<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\CarResource;
use App\Http\Resources\RouteTrackingResource;
use App\Models\Car;
use App\Models\RouteTracking;
use App\Services\BaseConstService;
use App\Services\BaseService;

class RouteTrackingService extends BaseService
{
    public $filterRules = [
        'driver_id' => ['=', 'driver_id'],
        'tour_no' => ['=', 'tour_no'],
    ];

    public function __construct(RouteTracking $tracking)
    {
        $this->model = $tracking;
        $this->query = $this->model::query();
        $this->resource = RouteTrackingResource::class;
        $this->infoResource = RouteTrackingResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }
}
