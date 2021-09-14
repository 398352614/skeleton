<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * 货主表
 * Class Employee
 * @package App\Models
 */
class Merchant extends Authenticatable implements JWTSubject
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'merchant';

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
        'code',
        'type',
        'name',
        'below_warehouse',
        'warehouse_id',
        'short_name',
        'introduction',
        'email',
        'password',
        'country',
        'settlement_type',
        'auto_settlement',
        'settlement_time',
        'settlement_week',
        'settlement_date',
        'merchant_group_id',
        'contacter',
        'phone',
        'address',
        'avatar',
        'invoice_title',
        'taxpayer_code',
        'bank',
        'bank_account',
        'invoice_address',
        'invoice_email',
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
        'password',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    protected $appends = [
        'settlement_type_name',
        'status_name',
        'type_name',
        'country_name',
        'additional_status',
        'advance_days',
        'appointment_days',
        'delay_time',
        'pickup_count',
        'auto_settlement',
        'settlement_time',
        'settlement_week',
        'settlement_date',
        'pie_count',
    ];


    public function getSettlementTypeNameAttribute()
    {
        return empty($this->settlement_type) ? null : ConstTranslateTrait::merchantSettlementTypeList($this->settlement_type);
    }

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::merchantStatusList($this->status);
    }

    public function getTypeNameAttribute()
    {
        return empty($this->type) ? null : ConstTranslateTrait::merchantTypeList($this->type);
    }

    public function getCountryNameAttribute()
    {
        return empty($this->country) ? null : CountryTrait::getCountryName($this->country);
    }

    public function getAutoSettlementAttribute()
    {
        return empty($this->auto_settlement) ? null : ConstTranslateTrait::statusList($this->auto_settlement);
    }

    public function getAdditionalStatusAttribute()
    {
        if (empty($this->merchant_group_id) || empty($this->merchantGroup)) return null;
        return $this->merchantGroup->additional_status;
    }

    public function getAdvanceDaysAttribute()
    {
        if (empty($this->merchant_group_id) || empty($this->merchantGroup)) return null;
        return $this->merchantGroup->advance_days;
    }

    public function getAppointmentDaysAttribute()
    {
        if (empty($this->merchant_group_id) || empty($this->merchantGroup)) return null;
        return $this->merchantGroup->appointment_days;
    }

    public function getDelayTimeAttribute()
    {
        if (empty($this->merchant_group_id) || empty($this->merchantGroup)) return null;
        return $this->merchantGroup->delay_time;
    }

    public function getPickupCountAttribute()
    {
        if (empty($this->merchant_group_id) || empty($this->merchantGroup)) return null;
        return $this->merchantGroup->pickup_count;
    }

    public function getPieCountAttribute()
    {
        if (empty($this->merchant_group_id) || empty($this->merchantGroup)) return null;
        return $this->merchantGroup->pie_count;
    }


    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => 'merchant',
        ];
    }

    public function companyConfig()
    {
        return $this->belongsTo(CompanyConfig::class, 'company_id', 'company_id');
    }

    public function merchantGroup()
    {
        return $this->belongsTo(MerchantGroup::class, 'merchant_group_id', 'id');
    }
}
