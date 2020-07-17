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
        $data = [];
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
        foreach ($routeTrackingList as $k =>$v){
            $routeTrackingList[$k]=$this->makeStopEvent($v);
        }
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no']], [
            'batch_no', 'receiver_fullname', 'receiver_address', 'receiver_lon', 'receiver_lat', 'expect_arrive_time', 'actual_arrive_time', 'sort_id'], false)->all();
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

    public function makeStopEvent($routeTracking)
    {
        if (!empty($routeTracking['stop_time'] && $routeTracking['stop_time'] > BaseConstService::STOP_TIME)) {
            $routeTracking['event']['content'] = __("司机已在此停留[:time]分钟", ['time' => $routeTracking['stop_time']]);
            $routeTracking['event']['time'] = $routeTracking['time_human'];
            $routeTracking['event']['type'] = 'stop';
        }
        return $routeTracking;
    }

    public function showByPart($tour, $batchList, $routeTracking)
    {
        $info = [];
        $content = [];
        $batchNo = '';
        if (!empty($routeTracking)) {
            foreach ($routeTracking as $k => $v) {
                if (!empty($v['tour_driver_event_id'])) {
                    $routeTracking[$k]['event'] = array_values($tour->tourDriverEvent->where('id', $v['tour_driver_event_id'])
                        ->map(function ($item) use ($batchList) {
                            $item['time'] = date_format($item['created_at'], "Y-m-d H:i:s");
                            $item['type'] = 'station';
                            return $item->only('content', 'time', 'type', 'batch_no');
                        })->toArray());
                    $batchNo = collect($routeTracking[$k]['event'])->whereNotNull('batch_no')->first()['batch_no'];
                }
                if (!empty($batchNo) && empty($routeTracking[$k]['address'])) {
                    $routeTracking[$k]['address'] = $batchList->where('batch_no', $batchNo)->first()->toArray()['receiver_address'] ?? '';
                }
                if (!empty($batchNo) && empty($routeTracking[$k]['receiver_name'])) {
                    $routeTracking[$k]['receiver_fullname'] = $batchList->where('batch_no', $batchNo)->first()->toArray()['receiver_fullname'] ?? '';
                }
                if (!empty($batchNo) && empty($routeTracking[$k]['sort_id'])) {
                    $routeTracking[$k]['sort_id'] = $batchList->where('batch_no', $batchNo)->first()->toArray()['sort_id'] ?? 1000;
                }
                if (!empty($batchNo) && empty($routeTracking[$k]['expect_arrive_time'])) {
                    $routeTracking[$k]['expect_arrive_time'] = $batchList->where('batch_no', $batchNo)->first()->toArray()['expect_arrive_time'] ?? '';
                }
                if (!empty($batchNo) && empty($routeTracking[$k]['actual_arrive_time'])) {
                    $routeTracking[$k]['actual_arrive_time'] = $batchList->where('batch_no', $batchNo)->first()->toArray()['actual_arrive_time'] ?? '';
                }
            }
            $routeTracking = collect($routeTracking)->sortBy('time_human')->toArray();
            $routeTracking[0]['stopTime'] = 0;
            for ($i = 1, $j = count($routeTracking); $i < $j; $i++) {
                if (abs($routeTracking[$i]['lon'] - $routeTracking[$i - 1]['lon']) < BaseConstService::LOCATION_DISTANCE_RANGE &&
                    abs($routeTracking[$i]['lat'] - $routeTracking[$i - 1]['lat']) < BaseConstService::LOCATION_DISTANCE_RANGE) {
                    $routeTracking[$i]['stopTime'] = round($routeTracking[$i - 1]['stopTime'] + abs($routeTracking[$i]['time'] - $routeTracking[$i - 1]['time']) / 60);
                    //造停点事件
                    if ($routeTracking[$i]['stopTime'] >= BaseConstService::STOP_TIME) {
                        $content[$i][] = [
                            'content' => __("司机已在此停留[:time]分钟", ['time' => $routeTracking[$i]['stopTime']]),
                            'time' => $routeTracking[$i]['time_human'],
                            'type' => 'stop'
                        ];
                        $routeTracking[$i]['event'] = array_merge($routeTracking[$i - 1]['event'] ?? [], [collect($content[$i])->sortByDesc('time')->first()]);
                    } else {
                        $routeTracking[$i]['event'] = $routeTracking[$i]['event'] ?? [];
                    }
                    //合并
                    if (!empty($routeTracking[$i - 1]['event'])) {
                        $routeTracking[$i]['event'] = array_merge($routeTracking[$i - 1]['event'], $routeTracking[$i]['event']);
                    }
                    if (!empty($routeTracking[$i - 1]['address'])) {
                        $routeTracking[$i]['address'] = $routeTracking[$i - 1]['address'];
                    }
                    if (!empty($routeTracking[$i]['event']) && !empty(collect($routeTracking[$i]['event'])->groupBy('type')->sortByDesc('time')['stop'])) {
                        $routeTracking[$i]['event'] = array_merge([collect($routeTracking[$i]['event'])->groupBy('type')->sortByDesc('time')->toArray()['stop'][0]], collect($routeTracking[$i]['event'])->groupBy('type')->toArray()['station'] ?? []);
                    } elseif (!empty($routeTracking[$i]['event'])) {
                        $routeTracking[$i]['event'] = collect($routeTracking[$i]['event'])->groupBy('type')->toArray()['station'] ?? [];
                    }
                    $routeTracking = Arr::except($routeTracking, $i - 1);
                    $info = Arr::except($info, $i - 1);
                } else {
                    $routeTracking[$i]['stopTime'] = 0;
                }
                $info[$i] = Arr::except($routeTracking[$i], ['stopTime', 'created_at', 'updated_at', 'time', 'tour_driver_event_id', 'driver_id']);
            }
            if (!empty($routeTracking[0])) {
                $info[0] = Arr::except($routeTracking[0], ['stopTime', 'created_at', 'updated_at', 'time', 'tour_driver_event_id', 'driver_id']);
            }
            $routeTracking = null;
            $info = array_values(collect($info)->sortBy('time_human')->toArray());
            for ($i = 0, $j = count($info); $i < $j; $i++) {
                if (empty($info[$i]['address'])) {
                    $info[$i]['address'] = "";
                }
                if (empty($info[$i]['receiver_fullname'])) {
                    $info[$i]['receiver_fullname'] = "";
                }
                if (empty($info[$i]['actual_arrive_time'])) {
                    $info[$i]['actual_arrive_time'] = "";
                }
                if (empty($info[$i]['expect_distance'])) {
                    $info[$i]['expect_distance'] = "";
                }
                if (empty($info[$i]['sort_id'])) {
                    $info[$i]['sort_id'] = 1000;
                }
                if (empty($info[$i]['event'])) {
                    $info[$i]['event'] = [];
                }
                if (!empty(collect($info[$i]['event'])->where('type', 'station')->first())) {
                    $info[$i]['type'] = 'station';
                } elseif (!empty(collect($info[$i]['event'])->where('type', 'stop')->first())) {
                    $info[$i]['type'] = 'stop';
                } else {
                    $info[$i]['type'] = '';
                }
            }
        }
        $tour = null;
        $batchList = null;
        return $info;
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
