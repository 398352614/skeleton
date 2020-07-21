<?php

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\RouteTrackingResource;
use App\Models\RouteTracking;
use App\Models\Tour;
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
        parent::__construct($tracking, RouteTrackingResource::class, RouteTrackingResource::class);
    }

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $params['time'] = strtotime(date("Y-m-d H:i:s"), $params['time']);
        $params['driver_id'] = auth()->user()->id;
        $tour = Tour::query()->where('driver_id', $params['driver_id'])->where('status', BaseConstService::TOUR_STATUS_4)->first();
        if ($tour === false) {
            throw new BusinessLogicException('当前司机不存在派送中线路');
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
     * @throws BusinessLogicException
     */
    public function createByList($params)
    {
        $tour = Tour::query()->where('driver_id', auth()->user()->id)->where('status', BaseConstService::TOUR_STATUS_4)->first();
        if (empty($tour)) {
            throw new BusinessLogicException('当前司机不存在派送中线路');
        }
        $tracking = collect($params['location_list'])->sortBy('time')->toArray()[0];
        $firstTracking = $this->getInfo(['tour_no' => $tour->tour_no], ['*'], false, ['time' => 'desc']);
        if (!empty($firstTracking)) {
            $firstTracking = $firstTracking->toArray();
        }
        $tracking['driver_id'] = auth()->user()->id;
        $tracking['tour_no'] = $tour->tour_no;
        $tracking['time'] = strtotime($tracking['time']);
        return $this->moveCheck($tracking, $firstTracking);
    }

    /**
     * @param $tracking
     * @param $firstTracking
     * @throws BusinessLogicException
     */
    public function moveCheck($tracking, $firstTracking)
    {
        if (!empty($firstTracking) && abs($tracking['lon'] - $firstTracking['lon']) < BaseConstService::LOCATION_DISTANCE_RANGE &&
            abs($tracking['lat'] - $firstTracking['lat']) < BaseConstService::LOCATION_DISTANCE_RANGE) {
            $stopTime = $firstTracking['stop_time'] + abs($tracking['time'] - $firstTracking['time']);
            $row = parent::update(['id' => $firstTracking['id']], ['stop_time' => $stopTime]);
            if ($row == false) {
                throw new BusinessLogicException('操作失败');
            }
        } else {
            $tracking['tour_driver_event_id'] = null;
            $row = $this->create($tracking);
            if ($row == false) {
                throw new BusinessLogicException('操作失败');
            }
        }
    }
}
