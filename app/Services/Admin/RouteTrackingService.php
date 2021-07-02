<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\RouteTrackingResource;
use App\Models\RouteTracking;
use App\Models\Tour;
use App\Models\TourDriverEvent;
use App\Services\BaseConstService;
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
     * 线路追踪
     * @return array
     * @throws BusinessLogicException
     */
    public function show()
    {
        $info = null;
        if (!empty($this->formData['driver_id'])) {
            $info = Tour::query()->where('driver_id', $this->formData['driver_id'])->first();
        } else {
            $info = Tour::query()->where('tour_no', $this->formData['tour_no'])->first();
        }
        if (!$info) {
            throw new BusinessLogicException('没找到相关进行中的线路');
        }
        //获取轨迹
        $routeTrackingList = parent::getList(['tour_no' => $info['tour_no']], ['*'], true);
        if (empty($routeTrackingList)) {
            throw new BusinessLogicException('数据不存在');
        }
        $routeTrackingList = $routeTrackingList->toArray(request());
        $routeTrackingList = $this->reduceData($routeTrackingList);
        foreach ($routeTrackingList as $k => $v) {
            $routeTrackingList[$k] = $this->makeStopEvent($v);
        }
        //获取事件
        $batchList = $this->getBatchService()->getList(['tour_no' => $info['tour_no']], ['*'], true)->all();
        $batchList = collect($batchList)->sortBy('sort_id')->all();
        $batchList = array_values($batchList);
        foreach ($batchList as $k => $v) {
            $batchList[$k] = collect($batchList[$k])->toArray();
            $batchList[$k]['sort_id'] = $k + 1;
            $batchList[$k] = array_only_fields_sort($batchList[$k], ['batch_no', 'place_fullname', 'place_address', 'place_lon', 'place_lat', 'expect_arrive_time', 'actual_arrive_time', 'sort_id']);
            $batchList[$k]['event'] = [];
        }
        $infoEventList = $this->getTourDriverService()->getList(['tour_no' => $info['tour_no']]);
        if (empty($infoEventList)) {
            throw new BusinessLogicException('数据不存在');
        }
        foreach ($batchList as $k => $v) {
            $infoEvent = $infoEventList->where('batch_no', $v['batch_no'])->all();
            if (!empty($infoEvent)) {
                $batchList[$k]['event'] = array_merge($batchList[$k]['event'], $infoEvent);
            }
        }
        $batchList = collect($batchList)->whereNotNull('event')->where('event', '<>', [])->sortBy('actual_arrive_time')->all();
        $info = TourDriverEvent::query()->where('tour_no', $info['tour_no'])->get()->toArray();
        //插入出库事件
        $out = [[
            'place_lon' => $info['warehouse_lon'],
            'place_lat' => $info['warehouse_lat'],
            'place_fullname' => $info['warehouse_name'],
            'event' => [collect($info)->sortBy('id')->first()
            ]]];
        $batchList = array_merge($out, array_values($batchList));
        //插入入库事件
        if ($info['status'] == 5) {
            $in = [[
                'place_lon' => $info['warehouse_lon'],
                'place_lat' => $info['warehouse_lat'],
                'place_fullname' => $info['warehouse_name'],
                'event' => [
                    collect($info)->sortByDesc('id')->first()]
            ]];
            $batchList = array_merge(array_values($batchList), $in);
        }
        if (!empty($info->driver)) {
            $driver = Arr::only($info->driver->toArray(), ['id', 'email', 'fullname', 'phone']);
        } else {
            $driver = ['id' => '', 'email' => '', 'fullname' => '', 'phone' => ''];
        }
        return [
            'driver' => $driver,
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
        if (!empty($routeTracking['stop_time']) && $routeTracking['stop_time'] / 60 > BaseConstService::STOP_TIME) {
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
     * 获取所有车辆位置
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws BusinessLogicException
     */
    public function index()
    {
        $info=[];
        if (!empty($this->formData['driver_name']) && $this->formData['is_online'] == BaseConstService::YES) {
            $info = $this->getTourService()->getList(['status' => BaseConstService::TOUR_STATUS_4, 'driver_name' => ['=', $this->formData['driver_name']]], ['*'], false)->toArray();
            for ($i = 0, $j = count($info); $i < $j; $i++) {
                $info[$i] = Arr::only($info[$i], ['id', 'driver_id', 'driver_name', 'driver_phone', 'car_no', 'line_name', 'tour_no']);
                $data[$i] = parent::getList(['tour_no' => $info[$i]['tour_no']], ['*'], false, [], ['time' => 'desc'])->toArray();
                $info[$i]['lon'] = $data[$i][0]['lon'] ?? '';
                $info[$i]['lat'] = $data[$i][0]['lat'] ?? '';
                $info[$i]['time'] = $data[$i][0]['time_human'] ?? '';
            }
        } elseif (empty($this->formData['driver_name']) && $this->formData['is_online'] == BaseConstService::YES) {
            $info = $this->getTourService()->getList(['status' => BaseConstService::TOUR_STATUS_4], ['*'], false)->toArray();
            for ($i = 0, $j = count($info); $i < $j; $i++) {
                $info[$i] = Arr::only($info[$i], ['id', 'driver_id', 'driver_name', 'driver_phone', 'car_no', 'line_name', 'tour_no']);
                $data[$i] = parent::getList(['tour_no' => $info[$i]['tour_no']], ['*'], false, [], ['time' => 'desc'])->toArray();
                $info[$i]['lon'] = $data[$i][0]['lon'] ?? '';
                $info[$i]['lat'] = $data[$i][0]['lat'] ?? '';
                $info[$i]['time'] = $data[$i][0]['time_human'] ?? '';
            }
        } elseif (!empty($this->formData['driver_name']) && $this->formData['is_online'] == BaseConstService::NO) {
            $info = $this->getTourService()->getList(['status' => BaseConstService::TOUR_STATUS_4], ['*'], false)->toArray();
            for ($i = 0, $j = count($info); $i < $j; $i++) {
                $info[$i] = Arr::only($info[$i], ['id', 'driver_id', 'driver_name', 'driver_phone', 'car_no', 'line_name', 'tour_no']);
                $data[$i] = parent::getList(['tour_no' => $info[$i]['tour_no']], ['*'], false, [], ['time' => 'desc'])->toArray();
                $info[$i]['lon'] = $data[$i][0]['lon'] ?? '';
                $info[$i]['lat'] = $data[$i][0]['lat'] ?? '';
                $info[$i]['time'] = $data[$i][0]['time_human'] ?? '';
            }
            $notOnlineDriver = collect($info)->pluck('driver_id')->toArray();
            unset($info);
            $driver = $this->getDriverService()->query->where('fullname',$this->formData['driver_name'])->whereNotIn('id', $notOnlineDriver)->get();
            foreach ($driver as $k => $v) {
                $info[$k]['driver_id'] = $v['id'];
                $info[$k]['driver_name'] = $v['fullname'];
                $info[$k]['driver_phone'] = $v['phone'];
                $info[$k]['car_no'] = $info[$k]['id'] = $info[$k]['lat'] = $info[$k]['lon'] = $info[$k]['line_name'] = $info[$k]['time'] = $info[$k]['tour_no'] = '';
            }
        } else {
            $info = $this->getTourService()->getList(['status' => BaseConstService::TOUR_STATUS_4], ['*'], false)->toArray();
            for ($i = 0, $j = count($info); $i < $j; $i++) {
                $info[$i] = Arr::only($info[$i], ['id', 'driver_id', 'driver_name', 'driver_phone', 'car_no', 'line_name', 'tour_no']);
                $data[$i] = parent::getList(['tour_no' => $info[$i]['tour_no']], ['*'], false, [], ['time' => 'desc'])->toArray();
                $info[$i]['lon'] = $data[$i][0]['lon'] ?? '';
                $info[$i]['lat'] = $data[$i][0]['lat'] ?? '';
                $info[$i]['time'] = $data[$i][0]['time_human'] ?? '';
            }
            $notOnlineDriver = collect($info)->pluck('driver_id')->toArray();
            unset($info);
            $driver = $this->getDriverService()->query->whereNotIn('id', $notOnlineDriver)->get();
            foreach ($driver as $k => $v) {
                $info[$k]['driver_id'] = $v['id'];
                $info[$k]['driver_name'] = $v['fullname'];
                $info[$k]['driver_phone'] = $v['phone'];
                $info[$k]['car_no'] = $info[$k]['id'] = $info[$k]['lat'] = $info[$k]['lon'] = $info[$k]['line_name'] = $info[$k]['time'] = $info[$k]['tour_no'] = '';
            }
        }
        if (empty($info)) {
            return [];
        }
        return $info;
    }
}
