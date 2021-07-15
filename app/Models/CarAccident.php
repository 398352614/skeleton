<?php

namespace App\Models;


use App\Traits\ConstTranslateTrait;

/**
 * 车辆事故表
 * Class CarAccident
 * @package App\Models
 */
class CarAccident extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'car_accident';

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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'company_id',
        'car_id',
        'car_no',
        'driver_id',
        'driver_fullname',
        'driver_phone',
        'deal_type',
        'accident_location',
        'accident_date',
        'accident_duty',
        'accident_description',
        'accident_picture',
        'accident_no',
        'insurance_indemnity',
        'insurance_payment',
        'insurance_price',
        'insurance_date',
        'insurance_description',
        'created_at',
        'updated_at',
        'operator',
    ];

    /**
     * @param $value
     * @return mixed
     */
    public function getDealType($value): string
    {
        return empty($value) ? '' : ConstTranslateTrait::carAccidentDealType($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getAccidentDuty($value): string
    {
        return empty($value) ? '' : ConstTranslateTrait::carAccidentDuty($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getInsuranceIndemnity($value): string
    {
        return empty($value) ? '' : ConstTranslateTrait::carAccidentInsPay($value);
    }
}
