<?php
/**
 * 司机到达站点
 * User: long
 * Date: 2020/3/31
 * Time: 15:00
 */

namespace App\Events\TourDriver;

use App\Events\Interfaces\ITourDriver;
use Illuminate\Queue\SerializesModels;

class BatchArrived implements ITourDriver
{
    use SerializesModels;

    public $batch;

    /**
     * Create a new event instance.
     * @param $batch
     * @return void
     */
    public function __construct($batch)
    {
        $this->batch = $batch;
    }


    /**
     * 获取当前司机事件的 tour_no
     */
    public function getTourNo(): string
    {
        return $this->batch['tour_no'];
    }

    /**
     * 获取当前司机事件的司机位置
     */
    public function getLocation(): array
    {
        return [
            'lon' => $this->batch['place_lon'],
            'lat' => $this->batch['place_lat'],
        ];
    }

    /**
     * 获取当前司机事件的 tour_no
     */
    public function getBatchNo(): string
    {
        return $this->batch['batch_no'];
    }

    /**
     * 获取线路司机事件文本
     */
    public function getContent(): string
    {
        return __('到达[:params]客户家', ['params' => $this->batch['place_fullname']]);
    }

    public function getAddress(): string
    {
        $address = [
            'place_street' => $this->batch['place_street'],
            'place_house_number' => $this->batch['place_house_number'],
            'place_district' => $this->batch['place_district'],
            'place_city' => $this->batch['place_city'],
            'place_post_code' => $this->batch['place_post_code'],
            'place_province' => $this->batch['place_province'],
            'place_country' => $this->batch['place_country']
        ];
        $address = implode(' ', $address);
        return $address;
    }

    public function getDriverId(): string
    {
        return $this->batch['driver_id'];
    }
}
