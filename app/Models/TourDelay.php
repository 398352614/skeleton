<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;
use Carbon\CarbonInterval;

/**
 * 线路任务材料表
 * Class Employee
 * @package App\Models
 */
class TourDelay extends BaseModel
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tour_delay';

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
        'execution_date',
        'line_id',
        'line_name',
        'driver_id',
        'driver_name',
        'delay_time',
        'delay_type',
        'delay_remark',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'delay_type_name',
        'delay_time_human'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * @return null |null
     */
    public function getDelayTypeNameAttribute()
    {
        return empty($this->delay_type) ? null : ConstTranslateTrait::tourDelayTypeList($this->delay_type);
    }

    public function getDelayTimeHumanAttribute()
    {
        return empty($this->delay_time) ? null : CarbonInterval::second($this->delay_time)->cascade()->forHumans();
    }
}
