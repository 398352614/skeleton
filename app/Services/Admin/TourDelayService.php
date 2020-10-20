<?php


namespace App\Services\Admin;


use App\Http\Resources\Api\Admin\TourDelayResource;
use App\Models\TourDelay;
use App\Services\BaseService;
use App\Traits\ConstTranslateTrait;

class TourDelayService extends BaseService
{
    public function __construct(TourDelay $tourDelay)
    {
        parent::__construct($tourDelay,TourDelayResource::class);
    }

    public $filterRules = [
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'driver_name' => ['like', 'driver_name'],
        'line_name' => ['like', 'line_name'],
        'delay_type' => ['=', 'delay_type'],
        'tour_no' => ['like', 'tour_no']
    ];

    /**
     * 查询
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPageList()
    {
        $this->query->orderByDesc('id');
        return parent::getPageList();
    }

    /**
     * 获取字典
     * @return mixed
     */
    public function init(){
         $data['tour_delay_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::tourDelayTypeList());
        return $data;
    }
}
