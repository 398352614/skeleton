<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * tour_driver_event 表对应的模型,线路司机事件
 * Class TourDriverEvent
 * @package App\Models
 */
class TourDriverEvent extends BaseModel
{
    /**
     * 线路追踪
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tour_driver_event';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'lon',
        'lat',
        'content',
        'address',
        'icon_id',
        'icon_path',
        'tour_no',
        'route_tracking_id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public function getContentAttribute($value)
    {
        if(preg_match('/\[.*]/',$value,$params)){
            $params=str_replace('[','',$params);
            $params=str_replace(']','',$params);
            $value=str_replace($params,':params',$value);
            return !empty($value)?__($value,['params'=>$params[0]]): null;
        }else{
            return !empty($value)?__($value): null;
        }
    }
}
