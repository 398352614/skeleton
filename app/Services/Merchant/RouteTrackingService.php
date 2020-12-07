<?php

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Merchant\RouteTrackingResource;
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
        $tour = null;
        if (!empty($this->formData['driver_id'])) {
            $tour = Tour::query()->where('driver_id', $this->formData['driver_id'])->first();
        } else {
            $tour = Tour::query()->where('tour_no', $this->formData['tour_no'])->first();
        }
        if (!$tour) {
            throw new BusinessLogicException('没找到相关进行中的线路');
        }
        $routeTrackingList = parent::getList(['tour_no' => $tour['tour_no']], ['*'], true);
        if (empty($routeTrackingList)) {
            throw new BusinessLogicException('数据不存在');
        }
        $routeTrackingList = $routeTrackingList->toArray(request());
        $routeTrackingList = $this->reduceData($routeTrackingList);
        foreach ($routeTrackingList as $k => $v) {
            $routeTrackingList[$k] = $this->makeStopEvent($v);
        }
        $this->getBatchList($this->formData['tour_no']);
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no']], ['*'], true)->all();
        $batchList = collect($batchList)->sortBy('sort_id')->all();
        foreach ($batchList as $k => $v) {
            $batchList[$k]['sort_id'] = $k + 1;
            $batchList[$k] = array_only_fields_sort($batchList[$k], ['batch_no', 'place_fullname', 'place_address', 'place_lon', 'place_lat', 'expect_arrive_time', 'actual_arrive_time', 'sort_id']);
        }
        $tourEventList = $this->getTourDriverService()->getList(['tour_no' => $tour['tour_no']]);
        if (empty($tourEventList)) {
            throw new BusinessLogicException('数据不存在');
        }
        foreach ($batchList as $k => $v) {
            $tourEvent = $tourEventList->where('batch_no', $v['batch_no'])->first();
            if (!empty($tourEvent)) {
                $batchList[$k]['event'][] = $tourEvent;
            }
        }
        $batchList = collect($batchList)->whereNotNull('event')->sortBy('actual_arrive_time')->all();
        $info = TourDriverEvent::query()->where('tour_no', $tour['tour_no'])->get()->toArray();
        $out = [[
            'place_lon' => $tour['warehouse_lon'],
            'place_lat' => $tour['warehouse_lat'],
            'place_fullname' => $tour['warehouse_name'],
            'event' => [collect($info)->sortBy('id')->first()
            ]]];
        $batchList = array_merge($out, array_values($batchList));
        if ($tour['status'] == 5) {
            $in = [[
                'place_lon' => $tour['warehouse_lon'],
                'place_lat' => $tour['warehouse_lat'],
                'place_fullname' => $tour['warehouse_name'],
                'event' => [
                    collect($info)->sortByDesc('id')->first()]
            ]];
            $batchList = array_merge(array_values($batchList), $in);
        }
        if(empty($tour->driver)){
            throw new BusinessLogicException('司机不存在');
        }
        return [
            'driver' => Arr::only($tour->driver->toArray(), ['id', 'email', 'fullname', 'phone']),
            'route_tracking' => $routeTrackingList,
            'tour_event' => $batchList,
        ];
    }

    public function getBatchList($params)
    {
        $batchList = [];
        $batchNoList = $this->getOrderService()->query->where('merchant_id', auth()->user()->merchant_id)->where('tour_no', $params)->pluck('batch_no');
        if (!empty($batchNoList)) {
            $batchList = $this->getBatchService()->getList(['batch_no' => ['in', $batchNoList]]);
        }
        return $batchList;
    }

    /**
     * 制造停点
     * @param $routeTracking
     * @return mixed
     */
    public function makeStopEvent($routeTracking)
    {
        if (!empty($routeTracking['stop_time']) && $routeTracking['stop_time'] > BaseConstService::STOP_TIME) {
            $routeTracking['event'][0]['content'] = __("司机已在此停留[:time]分钟", ['time' => round($routeTracking['stop_time'] / 60, 2)]);
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
