<?php

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\RouteTrackingResource;
use App\Models\RouteTracking;
use App\Models\Tour;
use App\Services\BaseConstService;

class RouteTrackingService extends BaseService
{
    public $filterRules = [
        'driver_id' => ['=', 'driver_id'],
        'tour_no' => ['=', 'tour_no'],
    ];

    public function __construct(RouteTracking $tracking)
    {
        parent::__construct($tracking, RouteTrackingResource::class, RouteTrackingResource::class);
    }

    /**
     * @param $params
     * @return string
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $params['time'] = strtotime(date("Y-m-d H:i:s"), $params['time']);
        $params['driver_id'] = auth()->user()->id;
        $tour = Tour::query()->where('driver_id', $params['driver_id'])->where('status', BaseConstService::TOUR_STATUS_4)->first();
        if ($tour === false) {
            return 'true';
        }
        $params['tour_no'] = $tour->tour_no;
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('采集位置失败');
        }
    }

    /**
     * 采集位置
     * @param $params
     * @return string|void
     * @throws BusinessLogicException
     */
    public function createByList($params)
    {
        if(empty($params['device_number'])){
            return '';
        }
        //验证当前账号是否绑定指定设备
        $device = $this->getDeviceService()->getInfo(['number' => $params['device_number'], 'driver_id' => auth()->user()->id], ['*'], false);
        if (empty($device)) return '';

        $tour = Tour::query()->where('driver_id', auth()->user()->id)->where('status', BaseConstService::TOUR_STATUS_4)->first();
        if (empty($tour)) {
            return '';
        }
        $tracking = collect($params['location_list'])->sortBy('time')->toArray()[0];
        $firstTracking = $this->getInfo(['tour_no' => $tour->tour_no], ['*'], false, ['time' => 'desc']);
        if (!empty($firstTracking)) {
            $firstTracking = $firstTracking->toArray();
        }
        $tracking['driver_id'] = auth()->user()->id;
        $tracking['tour_no'] = $tour->tour_no;
        $tracking['time'] = strtotime($tracking['time']);
        $tracking['stop_time'] = 0;
        return $this->moveCheck($tracking, $firstTracking);
    }

    /**
     * @param $tracking
     * @param $firstTracking
     * @return string
     * @throws BusinessLogicException
     */
    public function moveCheck($tracking, $firstTracking)
    {
        if (!empty($firstTracking) && abs($tracking['lon'] - $firstTracking['lon']) < BaseConstService::LOCATION_DISTANCE_RANGE &&
            abs($tracking['lat'] - $firstTracking['lat']) < BaseConstService::LOCATION_DISTANCE_RANGE) {
            $stopTime = $firstTracking['stop_time'] + abs($tracking['time'] - $firstTracking['time']);
            $row = parent::update(['id' => $firstTracking['id']], ['stop_time' => $stopTime, 'time' => $tracking['time']]);
        } else {
            $tracking['tour_driver_event_id'] = null;
            $row = $this->create($tracking);
            if ($row == false) {
                throw new BusinessLogicException('操作失败');
            }
        }
        return '';
    }
}
