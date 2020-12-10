<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 订单表
 * Class Employee
 * @package App\Models
 */
class Order extends BaseModel
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order';

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
        'order_no',
        'tracking_order_no',
        'execution_date',
        'second_execution_date',
        'out_order_no',
        'out_group_order_no',
        'mask_code',
        'source',
        'list_mode',
        'type',
        'out_user_id',
        'nature',
        'settlement_type',
        'settlement_amount',
        'replace_amount',
        'delivery',
        'status',
        'exception_label',
        'cancel_type',
        'cancel_remark',
        'cancel_picture',
        'second_place_fullname',
        'second_place_phone',
        'second_place_country',
        'second_place_post_code',
        'second_place_house_number',
        'second_place_city',
        'second_place_street',
        'second_place_address',
        'second_place_lon',
        'second_place_lat',
        'place_fullname',
        'place_phone',
        'place_country',
        'place_post_code',
        'place_house_number',
        'place_city',
        'place_street',
        'place_address',
        'place_lon',
        'place_lat',
        'special_remark',
        'remark',
        'unique_code',
        'sticker_amount',
        'delivery_amount',
        'out_status',
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
        'out_status_name',
        'exception_label_name',
        'type_name',
        'merchant_id_name',
        'place_country_name',
        'second_place_country_name',
        'country_name',
        'settlement_type_name',
        'source_name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public function getSecondExecutionDateAttribute($value)
    {
        return (empty($value) || ($value == '0000-00-00')) ? null : $value;
    }


    public function getTypeNameAttribute()
    {
        return empty($this->type) ? null : ConstTranslateTrait::orderTypeList($this->type);
    }

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::orderStatusList($this->status);
    }

    public function getOutStatusNameAttribute()
    {
        return empty($this->out_status) ? null : ConstTranslateTrait::outStatusList($this->out_status);
    }

    public function getExceptionLabelNameAttribute()
    {
        return empty($this->exception_label) ? null : ConstTranslateTrait::orderExceptionLabelList($this->exception_label);
    }

    public function getMerchantIdNameAttribute()
    {
        if (empty($this->merchant) || empty($this->merchant_id)) return '';
        return $this->merchant->name;
    }


    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }

    public function getShortAttribute()
    {
        return empty($this->place_country) ? null : $this->getOriginal('place_country');
    }

    public function getSourceNameAttribute()
    {
        return empty($this->source) ? null : ConstTranslateTrait::orderSourceList($this->source);
    }

    public function getSettlementTypeNameAttribute()
    {
        return empty($this->settlement_type) ? null : ConstTranslateTrait::orderSettlementTypeList($this->settlement_type);
    }
}
