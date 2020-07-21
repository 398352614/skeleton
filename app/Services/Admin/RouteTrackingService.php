<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\RouteTrackingResource;
use App\Listeners\TourDriver;
use App\Models\RouteTracking;
use App\Models\Tour;
use App\Models\TourDriverEvent;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Support\Arr;

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
     * 取件线路 服务
     * @return TourService
     */
    public function getTourService()
    {
        return self::getInstance(TourService::class);
    }

    /**
     * 站点 服务
     * @return BatchService
     */
    public function getBatchService()
    {
        return self::getInstance(BatchService::class);
    }

    /**
     * 线路追踪
     * @return array
     * @throws BusinessLogicException
     */
    public function show()
    {
        $tour = null;
        if (!empty($this->formData['driver_id'])) {
            $tour = Tour::query()->where('driver_id', $this->formData['driver_id'])->first();
        } else {
            $tour = Tour::query()->where('tour_no', $this->formData['tour_no'])->first();
        }
        if (!$tour) {
            throw new BusinessLogicException('没找到相关进行中的线路');
        }
        $routeTrackingList = $tour->routeTracking->toArray();
        $routeTrackingList = $this->reduceData($routeTrackingList);
        foreach ($routeTrackingList as $k => $v) {
            $routeTrackingList[$k] = $this->makeStopEvent($v);
        }
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no']], [
            'batch_no', 'receiver_fullname', 'receiver_address', 'receiver_lon', 'receiver_lat', 'expect_arrive_time', 'actual_arrive_time', 'sort_id'], false)->all();
        $batchList = collect($batchList)->sortBy('sort_id')->all();
        foreach ($batchList as $k => $v) {
            $batchList[$k]['sort_id'] = $k + 1;
        }
        foreach ($batchList as $k => $v) {
            $tourEvent = TourDriverEvent::query()->where('batch_no', $v['batch_no'])->get()->toArray();
            if (!empty($tourEvent)) {
                $batchList[$k]['event'] = $tourEvent;
            }
        }
        $batchList = collect($batchList)->whereNotNull('event')->all();
        $info = TourDriverEvent::query()->where('tour_no', $tour['tour_no'])->get()->toArray();
        $out = [[
            'receiver_lon' => $tour['warehouse_lon'],
            'receiver_lat' => $tour['warehouse_lat'],
            'receiver_fullname' => $tour['warehouse_name'],
            'event' => [collect($info)->sortBy('id')->first()
            ]]];
        $batchList = array_merge($out, array_values($batchList));
        if ($tour['status'] == 5) {
            $in = [[
                'receiver_lon' => $tour['warehouse_lon'],
                'receiver_lat' => $tour['warehouse_lat'],
                'receiver_fullname' => $tour['warehouse_name'],
                'event' => [
                    collect($info)->sortByDesc('id')->first()]
            ]];
            $batchList = array_merge(array_values($batchList), $in);
        }
        return [
            'driver' => Arr::only($tour->driver->toArray(), ['id', 'email', 'fullname', 'phone']),
            'route_tracking' => $routeTrackingList,
            'tour_event' => $batchList,
        ];
    }

    /**
     * 制造停点
     * @param $routeTracking
     * @return mixed
     */
    public function makeStopEvent($routeTracking)
    {
        if (!empty($routeTracking['stop_time'] && $routeTracking['stop_time'] / 60 > BaseConstService::STOP_TIME)) {
            $routeTracking['event'][0]['content'] = __("司机已在此停留[:time]分钟", ['time' => round($routeTracking['stop_time'] / 60)]);
            $routeTracking['event'][0]['time'] = $routeTracking['time_human'];
            $routeTracking['event'][0]['type'] = 'stop';
        }
        return $routeTracking;
    }

    /**
     * 间隔抽点
     * @param $data
     * @return array|int
     */
    public function reduceData($data)
    {
        $result = [];
        $stop = collect($data)->where('stop_time')->all();
        $count = count($data) - count($stop);
        $time = $count / BaseConstService::LOCATION_LIMIT;
        if ($time > 0) {
            for ($i = 0, $j = $count; $i < $j; $i++) {
                if (!empty($data[$i]['stop_time']) || $i % ($time + 1) == 0) {
                    $result[] = $data[$i];
                }
            }
        } else {
            $result = $data;
        }
        return $result;
    }

    /**
     * 司机服务
     * @return DriverService
     */
    public function getDriverService()
    {
        return self::getInstance(DriverService::class);
    }

    /**
     * 获取所有车辆位置
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws BusinessLogicException
     */
    public function index()
    {
        if (!empty($this->formData['driver_name'])) {
            $info = $this->getTourService()->getList(['status' => BaseConstService::TOUR_STATUS_4, 'driver_name' => ['like', $this->formData['driver_name']]], ['*'], false)->toArray();
        } else {
            $info = $this->getTourService()->getList(['status' => BaseConstService::TOUR_STATUS_4], ['*'], false)->toArray();
        }
        if (empty($info)) {
            throw new BusinessLogicException('暂无车辆信息');
        }
        for ($i = 0, $j = count($info); $i < $j; $i++) {
            $info[$i] = Arr::only($info[$i], ['id', 'driver_id', 'driver_name', 'driver_phone', 'car_no', 'line_name', 'tour_no']);
            $data[$i] = parent::getList(['tour_no' => $info[$i]['tour_no']], ['*'], false, [], ['time' => 'desc'])->toArray();
            $info[$i]['lon'] = $data[$i][0]['lon'] ?? '';
            $info[$i]['lat'] = $data[$i][0]['lat'] ?? '';
            $info[$i]['time'] = $data[$i][0]['time_human'] ?? '';
        }
        return $info;
    }
}
