<?php
/**
 * 司机回网点
 * User: long
 * Date: 2020/3/31
 * Time: 15:00
 */

namespace App\Events\TourDriver;

use App\Events\Interfaces\ITourDriver;
use App\Services\BaseConstService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Queue\SerializesModels;

class BackWarehouse implements ITourDriver
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

    /**
     * 获取当前司机事件的 tour_no
     */
    public function getBatchNo(): string
    {
        return '';
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
        return ConstTranslateTrait::$driverEventList[BaseConstService::DRIVER_EVENT_BACK_WAREHOUSE];
    }

    public function getAddress(): string
    {
        $address = [
            'warehouse_street' => $this->tour['warehouse_street'],
            'warehouse_house_number' => $this->tour['warehouse_house_number'],
            'warehouse_city' => $this->tour['warehouse_city'],
            'warehouse_post_code' => $this->tour['warehouse_post_code'],
            'warehouse_country' => $this->tour['warehouse_country']
        ];
        $address = implode(' ', $address);
        return $address;
    }

    public function getDriverId(): string
    {
        return $this->tour['driver_id'];
    }
}
