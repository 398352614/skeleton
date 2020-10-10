<?php


namespace App\Services\Admin;


use App\Http\Resources\TourDelayResource;
use App\Models\TourDelay;
use App\Services\BaseService;

class TourDelayService extends BaseService
{
    public function __construct(TourDelay $tourDelay)
    {
        parent::__construct($tourDelay,TourDelayResource::class);
    }

    public $filterRules = [
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'driver_name' => ['like', 'driver_name'],
        'line_name' => ['like', 'line_name'],
        'delay_type' => ['=', 'type'],
        'tour_no' => ['like', 'tour_no']
    ];
}
