<?php
/**
 * 司机从站点出发
 * User: long
 * Date: 2020/3/31
 * Time: 15:00
 */

namespace App\Events\TourDriver;

use App\Events\Interfaces\ITourDriver;
use App\Services\BaseConstService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class BatchDepart implements ITourDriver
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
            'lon' => $this->batch['receiver_lon'],
            'lat' => $this->batch['receiver_lat'],
        ];
    }

    /**
     * 获取线路司机事件文本
     */
    public function getContent(): string
    {
        return '从' . $this->batch['receiver'] . '客户家离开';
    }

    public function getAddress(): string
    {
        $address = implode(' ', Arr::only($this->batch, ['receiver_street', 'receiver_house_number', 'receiver_city', 'receiver_post_code', 'receiver_country']));
        return $address;
    }
}
