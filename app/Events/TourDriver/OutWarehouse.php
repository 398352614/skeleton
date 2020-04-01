<?php
/**
 * 司机出库
 * User: long
 * Date: 2020/3/31
 * Time: 15:00
 */

namespace App\Events\TourDriver;

use App\Events\Interfaces\ITourDriver;
use App\Services\BaseConstService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Queue\SerializesModels;

class OutWarehouse implements ITourDriver
{
    use SerializesModels;
    public $tour;

    public function __construct($tour)
    {
        $this->tour = $tour;
    }

    public function getTourNo(): string
    {
        return $this->tour['tour_no'];
    }

    public function getLocation(): array
    {
        return [
            'lon' => $this->tour['warehouse_lon'],
            'lat' => $this->tour['warehouse_lat']
        ];
    }

    public function getContent(): string
    {
        return ConstTranslateTrait::$driverEventList[BaseConstService::DRIVER_EVENT_OUT_WAREHOUSE];
    }

}