<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;
use Carbon\CarbonInterval;

/**
 * batch 表对应的模型,相当于一个在途站点
 * Class Tour
 * @package App\Models
 */
class Batch extends BaseModel
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'batch';

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
        'merchant_id',
        'batch_no',
        'tour_no',
        'line_id',
        'line_name',
        'execution_date',
        'status',
        'exception_label',
        'cancel_type',
        'cancel_remark',
        'cancel_picture',
        'driver_id',
        'driver_name',
        'driver_phone',
        'driver_rest_time',
        'car_id',
        'car_no',
        'sort_id',
        'is_skipped',
        'expect_pickup_quantity',
        'actual_pickup_quantity',
        'expect_pie_quantity',
        'actual_pie_quantity',
        'place_fullname',
        'place_phone',
        'place_country',
        'place_province',
        'place_post_code',
        'place_house_number',
        'place_city',
        'place_district',
        'place_street',
        'place_address',
        'place_lon',
        'place_lat',
        'expect_arrive_time',
        'actual_arrive_time',
        'expect_distance',
        'actual_distance',
        'expect_time',
        'actual_time',
        'sticker_amount',
        'delivery_amount',
        'replace_amount',
        'actual_replace_amount',
        'settlement_amount',
        'actual_settlement_amount',
        'signature',
        'pay_type',
        'pay_picture',
        'created_at',
        'updated_at',
        'auth_fullname',
        'auth_birth_date',
        'out_expect_time',
        'out_expect_distance',
        'out_expect_arrive_time',
        'sign_time'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $appends = [
        'status_name',
        'exception_label_name',
        'pay_type_name',
        'place_country_name',
        'expect_time_human',
        'out_expect_time_human',
        'actual_time_human',
        'is_skipped_name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::batchStatusList($this->status);
    }

    public function getPayTypeNameAttribute()
    {
        return empty($this->pay_type) ? null : ConstTranslateTrait::batchPayTypeList($this->pay_type);
    }

    public function getExceptionLabelNameAttribute()
    {
        return empty($this->exception_label) ? null : ConstTranslateTrait::batchExceptionLabelList($this->exception_label);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'batch_no', 'batch_no');
    }

    public function getExpectTimeHumanAttribute()
    {
        return empty($this->expect_time) ? null : CarbonInterval::second($this->expect_time)->cascade()->forHumans();
    }

    public function getOutExpectTimeHumanAttribute()
    {
        return empty($this->out_expect_time) ? null : CarbonInterval::second($this->out_expect_time)->cascade()->forHumans();
    }

    public function getActualTimeHumanAttribute()
    {
        return empty($this->actual_time) ? null : CarbonInterval::second($this->actual_time)->cascade()->forHumans();
    }

    public function getIsSkippedNameAttribute()
    {
        return empty($this->is_skipped) ? null : ConstTranslateTrait::isSkippedList($this->is_skipped);
    }
}
