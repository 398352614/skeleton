<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * route_tracking 表对应的模型,线路追踪
 * Class RouteTracking
 * @package App\Models
 */
class RouteTracking extends BaseModel
{
    /**
     * 线路追踪
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'route_tracking';

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
        'tour_no',
        'driver_id',
        'time',
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


    // public function getStatusNameAttribute()
    // {
    //     return ConstTranslateTrait::$tourStatusList[$this->status];
    // }

}
