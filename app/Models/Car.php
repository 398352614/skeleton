<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Facades\App;

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
        'distance',
        'outgoing_time',
        'car_brand_id',
        'car_model_id',
        'car_length',
        'car_model_type',
        'gps_device_number',
        'ownership_type',
        'insurance_company',
        'insurance_type',
        'month_insurance',
        'rent_start_date',
        'rent_end_date',
        'rent_month_fee',
        'repair',
        'remark',
        'relate_material',
        'relate_material_name',
        'relate_material_list',
        'is_locked',
        'created_at',
        'updated_at',
    ];

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

    protected $appends = [
        'brand_name',
        'model_name',
        'transmission_name',
        'fuel_type_name',
        'ownership_type_name',
        'repair_name'
    ];

    public function brand()
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id', 'id');
    }

    public function model()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id', 'id');
    }

    public function getBrandNameAttribute()
    {
        if (empty($this->brand)) return '';
        return (App::getLocale() === 'cn') ? $this->brand->cn_name : $this->brand->en_name;
    }

    public function getModelNameAttribute()
    {
        if (empty($this->model)) return '';
        return (App::getLocale() === 'cn') ? $this->model->cn_name : $this->model->en_name;
    }

    public function getTransmissionNameAttribute()
    {
        return empty($this->transmission) ? null : ConstTranslateTrait::carTransmissionList($this->transmission);
    }

    public function getFuelTypeNameAttribute()
    {
        return empty($this->fuel_type) ? null : ConstTranslateTrait::carFuelTypeList($this->fuel_type);
    }

    public function getOwnershipTypeNameAttribute()
    {
        return empty($this->ownership_type) ? null : ConstTranslateTrait::carOwnerShipTypeList($this->ownership_type);
    }

    public function getRepairNameAttribute()
    {
        return empty($this->repair) ? null : ConstTranslateTrait::carRepairList($this->repair);
    }

    /**
     * @param $value
     * @return string
     */
    public function getCarLength($value): string
    {
        return empty($value) ? '' : ConstTranslateTrait::carLengthTypeList($value);
    }

    /**
     * @param $value
     * @return string
     */
    public function getCarModelType($value): string
    {
        return empty($value) ? '' : ConstTranslateTrait::carModelTypeList($value);
    }
}

