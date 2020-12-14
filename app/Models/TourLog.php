<?php

namespace App\Models;

/**
 * tour_log 表对应的模型,相当于司机的一趟任务
 * Class Tour
 * @package App\Models
 */
class TourLog extends BaseModel
{
    /**
     * 在途日志
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tour_log';

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
        'action',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

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
