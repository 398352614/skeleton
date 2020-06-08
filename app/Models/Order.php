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
        'execution_date',
        'batch_no',
        'tour_no',
        'out_order_no',
        'express_first_no',
        'express_second_no',
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
        'sender_fullname',
        'sender_phone',
        'sender_country',
        'sender_post_code',
        'sender_house_number',
        'sender_city',
        'sender_street',
        'sender_address',
        'receiver_fullname',
        'receiver_phone',
        'receiver_country',
        'receiver_post_code',
        'receiver_house_number',
        'receiver_city',
        'receiver_street',
        'receiver_address',
        'lon',
        'lat',
        'special_remark',
        'remark',
        'unique_code',
        'driver_id',
        'driver_name',
        'driver_phone',
        'car_id',
        'car_no',
        'sticker_no',
        'sticker_amount',
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
        'exception_label_name',
        'type_name',
        'merchant_id_name',
        'receiver_country_name',
        'sender_country_name',
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


    public function getTypeNameAttribute()
    {
        return empty($this->type) ? null : ConstTranslateTrait::orderTypeList($this->type);
    }

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::orderStatusList($this->status);
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
        return empty($this->receiver_country) ? null : $this->getOriginal('receiver_country');
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
