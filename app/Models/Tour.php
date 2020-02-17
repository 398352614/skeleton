<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;
use Carbon\CarbonInterval;

/**
 * tour 表对应的模型,相当于司机的一趟任务
 * Class Tour
 * @package App\Models
 */
class Tour extends BaseModel
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tour';

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
        'tour_no',
        'line_id',
        'line_name',
        'execution_date',
        'driver_id',
        'driver_name',
        'driver_phone',
        'driver_rest_time',
        'driver_avt_id',
        'car_id',
        'car_no',
        'warehouse_id',
        'warehouse_name',
        'warehouse_phone',
        'warehouse_post_code',
        'warehouse_city',
        'warehouse_street',
        'warehouse_house_number',
        'warehouse_address',
        'warehouse_lon',
        'warehouse_lat',
        'status',
        'begin_time',
        'begin_signature',
        'begin_signature_remark',
        'begin_signature_first_pic',
        'begin_signature_second_pic',
        'begin_signature_third_pic',
        'end_time',
        'end_signature',
        'end_signature_remark',
        'expect_distance',
        'actual_distance',
        'expect_time',
        'actual_time',
        'expect_pickup_quantity',
        'actual_pickup_quantity',
        'expect_pie_quantity',
        'actual_pie_quantity',
        'sticker_amount',
        'replace_amount',
        'settlement_amount',
        'remark',
        'created_at',
        'updated_at',
        'lave_distance',
        'expect_time_human',
        'actual_time_human',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $appends = [
        'status_name',
        'expect_time_human',
        'actual_time_human'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];


    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::$tourStatusList[$this->status];
    }

    public function getExpectTimeHumanAttribute()
    {
        return empty($this->expect_time) ? null : CarbonInterval::second($this->expect_time)->cascade()->forHumans();
    }

    public function getActualTimeHumanAttribute()
    {
        return empty($this->actual_time) ? null : CarbonInterval::second($this->actual_time)->cascade()->forHumans();
    }

    /**
     * 一个线路任务存在多个批次(站点)
     */
    public function batchs()
    {
        return $this->hasMany(Batch::class, 'tour_no', 'tour_no');
    }

    public function routeTracking()
    {
        return $this->hasMany(RouteTracking::class, 'tour_no', 'tour_no');
    }

    /**
     * 获取司机位置属性
     */
    public function getDriverLocationAttribute()
    {
        if ($this->routeTracking->count()) {
            $sorted = $this->routeTracking->sortByDesc('created_at');
                return [
                    'latitude' => $sorted[0]->lat,
                    'longitude' => $sorted[0]->lon,
                ];
            }
        return [
            'latitude' => $this->warehouse_lat,
            'longitude' => $this->warehouse_lon,
        ];
    }

    public function getExpectDistanceAttribute($value)
    {
        return round($value / 1000, 2);
    }

    /*    public function getExpectTimeAttribute($value)
    {
        return (int) ($value / 60);
    }*/
}
