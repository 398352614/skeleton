<?php

namespace App\Models;

use App\Services\BaseConstService;
use App\Traits\ConstTranslateTrait;

/**
 * 站点异常表
 * Class Employee
 * @package App\Models
 */
class BatchException extends BaseModel
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'batch_exception';

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
        'batch_exception_no',
        'batch_no',
        'receiver',
        'status',
        'source',
        'stage',
        'type',
        'remark',
        'picture',
        'deal_remark',
        'deal_id',
        'deal_name',
        'deal_time',
        'driver_id',
        'driver_name',
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

    protected $appends = [
        'status_name',
        'stage_name',
        'type_name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];


    public function getStatusNameAttribute()
    {
        return ConstTranslateTrait::$orderStatusList[$this->status];
    }

    public function getStageNameAttribute()
    {
        return ConstTranslateTrait::$batchExceptionStageList[$this->stage];
    }

    public function getTypeNameAttribute()
    {
        if (intval($this->stage) == BaseConstService::BATCH_EXCEPTION_STAGE_1) {
            return ConstTranslateTrait::$batchExceptionFirstStageTypeList[$this->type];
        }
        return ConstTranslateTrait::$batchExceptionSecondStageTypeList[$this->type];
    }


}
