<?php

namespace App\Models;
/**
 * 汽车表
 * Class Car
 * @package App\Models
 */
class Car extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'car';

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
        'car_no',
        'outgoing_time',
        'car_brand_id',
        'car_model_id',
        'frame_number',
        'engine_number',
        'transmission',
        'fuel_type',
        'current_miles',
        'annual_inspection_date',
        'ownership_type',
        'received_date',
        'month_road_tax',
        'insurance_company',
        'insurance_type',
        'month_insurance',
        'rent_start_date',
        'rent_end_date',
        'rent_month_fee',
        'repair',
        'remark',
        'relate_material',
        'is_locked',
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
}
