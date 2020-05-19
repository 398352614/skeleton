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
            $tour = Tour::query()->where('driver_id', $this->formData['driver_id'])->first();
        } else {
            $tour = Tour::query()->where('tour_no', $this->formData['tour_no'])->first();
        }
        if (!$tour) {
            throw new BusinessLogicException('没找到相关线路');
        }
        if($tour->status !== BaseConstService::TOUR_STATUS_4){
            throw new BusinessLogicException('该取件线路不在取派中，无法追踪');
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
            $data[$i]=$this->getInfo(['tour_no'=>$info[$i]['tour_no']],['*'],true,['created_at'=>'desc']);
            if(empty($data[$i])){
                throw new BusinessLogicException('数据不存在');
            }
            $info[$i]['lon']=$data[$i]['lon'];
            $info[$i]['lat']=$data[$i]['lat'];
        }
        return $info;
    }
}
