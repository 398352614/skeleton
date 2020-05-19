<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\RouteTrackingResource;
use App\Models\RouteTracking;
use App\Models\Tour;
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
    public function getTourService(){
        return self::getInstance(TourService::class);
    }

    /**
     * 线路追踪
     * @return array
     * @throws BusinessLogicException
     */
    public function show(){
        $tour = null;
        if ($this->formData['driver_id'] ?? null) {
            $tour = Tour::query()->where('driver_id', $this->formData['driver_id'])->where('status',BaseConstService::TOUR_STATUS_4)->first();
        } else {
            $tour = Tour::query()->where('tour_no', $this->formData['tour_no'])->where('status',BaseConstService::TOUR_STATUS_4)->first();
        }
        if (!$tour) {
            throw new BusinessLogicException('没找到相关线路');
        }
        $routeTracking = $tour->routeTracking->sortBy('created_at');
        return success('', [
            'driver'                => Arr::except($tour->driver,'messager'),
            'route_tracking'        =>  $routeTracking,
            'time_consuming'        =>  '',
            'distance_consuming'    =>  '',
            'tour_event'            =>  $tour->tourDriverEvent,
        ]);
    }

    /**
     * 获取所有车辆位置
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws BusinessLogicException
     */
    public function index(){
        $info=$this->getTourService()->getList(['status'=>BaseConstService::TOUR_STATUS_4],['*'],false)->toArray();
        for($i=0,$j=count($info);$i<$j;$i++){
            $info[$i]=Arr::only($info[$i],['id','driver_id','driver_name','driver_phone','car_no','line_name','tour_no']);
            $data[$i]=parent::getList(['tour_no'=>$info[$i]['tour_no']],['*'],false,[],['created_at'=>'desc'])->toArray();
            if(empty($data[$i])){
                throw new BusinessLogicException('数据不存在');
            }
            $info[$i]['lon']=$data[$i][0]['lon'];
            $info[$i]['lat']=$data[$i][0]['lat'];
        }
        return $info;
    }
}
