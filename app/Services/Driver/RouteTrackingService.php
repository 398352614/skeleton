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
    public function store($params){
        $params['time']=strtotime(date("Y-m-d H:i:s"),$params['time']);
        $params['driver_id']=auth()->user()->id;
        $tour = Tour::query()->where('driver_id', $params['driver_id'])->where('status', BaseConstService::TOUR_STATUS_4)->first();
        if($tour === false){
            throw new BusinessLogicException('当前司机不存在派送中线路');
        }
        $params['tour_no']=$tour->tour_no;
        $params['time']=time();
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
    public function createByList($params){
        $data=$params['location_list'];
        $tour = Tour::query()->where('driver_id', auth()->user()->id)->where('tour_no',$params['tour_no'])->where('status', BaseConstService::TOUR_STATUS_4)->first();
        if(empty($tour)){
            throw new BusinessLogicException('当前司机不存在派送中线路');
        }
        for($i=0,$j=count($data);$i<$j;$i++){
            $data[$i]['driver_id']=auth()->user()->id;
            $data[$i]['tour_no']=$tour->tour_no;
            $data[$i]['time']=strtotime($data[$i]['time']);
        }
        parent::insertAll($data);
    }
}
