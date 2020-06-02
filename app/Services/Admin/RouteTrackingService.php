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
        $info=[];
        if ($this->formData['driver_id'] ?? null) {
            $tour = Tour::query()->where('driver_id', $this->formData['driver_id'])->where('status',BaseConstService::TOUR_STATUS_4)->first();
        } else {
            $tour = Tour::query()->where('tour_no', $this->formData['tour_no'])->where('status',BaseConstService::TOUR_STATUS_4)->first();
        }
        if (!$tour) {
            throw new BusinessLogicException('没找到相关线路');
        }
        $routeTracking = $tour->routeTracking->sortBy('time_human')->toArray();
        foreach ($routeTracking as $k=>$v){
            if(!empty($v['tour_driver_event_id'])){
                $routeTracking[$k]['event']=$tour->tourDriverEvent->where('id',$v['tour_driver_event_id'])
                    ->map(function ($item){
                        $item['time']=date_format($item['created_at'],"Y-m-d H:i:s");
                        $item['type']='station';
                        return $item->only('content','time','type');
                    })->toArray();
            }
        }
        $routeTracking[0]['stopTime']=0.0;
        for($i=1,$j=count($routeTracking);$i<$j;$i++){
            if(abs($routeTracking[$i]['lon']-$routeTracking[$i-1]['lon']) < BaseConstService::LOCATION_DISTANCE_RANGE &&
                abs($routeTracking[$i]['lat']-$routeTracking[$i-1]['lat']) < BaseConstService::LOCATION_DISTANCE_RANGE){
                $routeTracking[$i]['stopTime']=round($routeTracking[$i-1]['stopTime']+abs($routeTracking[$i]['time']-$routeTracking[$i-1]['time'])/60);
                if($routeTracking[$i]['stopTime'] >= BaseConstService::STOP_TIME){
                    $content[$i][]=[
                        'content'=>__("司机已在此停留[:time]分钟",['time'=>$routeTracking[$i]['stopTime']]),
                        'time'=>$routeTracking[$i]['time_human'],
                        'type'=>'stop',
                    ];
                    $routeTracking[$i]['event']=array_values(array_merge($routeTracking[$i]['event'] ?? [],[collect($content[$i])->sortByDesc('time')->first()]));
                    if($routeTracking[$i-1]['stopTime']>=BaseConstService::STOP_TIME){
                        $routeTracking[$i]['event']=array_merge($routeTracking[$i]['event'],$routeTracking[$i-1]['event']);
                        $info[$i]=Arr::except($routeTracking[$i],['stopTime','created_at','updated_at','time','tour_driver_event_id','driver_id']);
                        $info=Arr::except($info,[$i-1]);
                        $info[$i]['event']=array_merge([collect($info[$i]['event'])->groupBy('type')->sortByDesc('time')['stop'][0]],collect($info[$i]['event'])->groupBy('type')->toArray()['station'] ?? []);
                    }else{
                        $info[$i]=Arr::except($routeTracking[$i],['stopTime','created_at','updated_at','time','tour_driver_event_id','driver_id']);
                    }
                }
            }else{
                $routeTracking[$i]['stopTime']=0.0;
                $info[$i]=Arr::except($routeTracking[$i],['stopTime','created_at','updated_at','time','tour_driver_event_id','driver_id']);
            }
        }
        return success('', [
            'route_tracking'        =>  array_values($info),
            'driver'                => Arr::except($tour->driver,'messager'),
            'tour_event'            =>  $tour->tourDriverEvent,
            'time_consuming'        =>  '',
            'distance_consuming'    =>  '',
        ]);
    }

    /**
     * 司机服务
     * @return DriverService
     */
    public function getDriverService(){
        return self::getInstance(DriverService::class);
    }

    /**
     * 获取所有车辆位置
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws BusinessLogicException
     */
    public function index(){
        if(!empty($this->formData['driver_name'])){
            $info=$this->getTourService()->getList(['status'=>BaseConstService::TOUR_STATUS_4,'driver_name'=>['like',$this->formData['driver_name']]],['*'],false)->toArray();
        }else{
            $info=$this->getTourService()->getList(['status'=>BaseConstService::TOUR_STATUS_4],['*'],false)->toArray();
        }
        for($i=0,$j=count($info);$i<$j;$i++){
            $info[$i]=Arr::only($info[$i],['id','driver_id','driver_name','driver_phone','car_no','line_name','tour_no']);
            $data[$i]=parent::getList(['tour_no'=>$info[$i]['tour_no']],['*'],false,[],['created_at'=>'desc'])->toArray();
            $info[$i]['lon']=$data[$i][0]['lon'] ?? '';
            $info[$i]['lat']=$data[$i][0]['lat'] ?? '';
            $info[$i]['time']=$data[$i][0]['time_human'] ?? '';
        }
        return $info;
    }
}
