<?php


namespace App\Services\Driver;


use App\Http\Resources\Api\Driver\TourDelayResource;
use App\Models\TourDelay;
use App\Services\Driver\BaseService;
use App\Traits\ConstTranslateTrait;

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
        'delay_type' => ['=', 'delay_type'],
        'tour_no' => ['like', 'tour_no']
    ];
}
