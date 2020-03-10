<?php

namespace App\Models;

use Illuminate\Support\Facades\App;

/**
 * 汽车品牌型号表
 * Class CarBrand
 * @package App\Models
 */
class CarModel extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'car_model';

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
        'cn_name',
        'en_name',
        'brand_id',
        'created_at',
        'updated_at',
        'company_id'
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

    protected $appends = [
        'name'
    ];

    /**
     * 一个型号属于一个品牌
     */
    public function brand()
    {
        return $this->belongsTo(CarBrand::class, 'brand_id', 'id');
    }

    public function getNameAttribute()
    {
        if (App::getLocale() === 'cn') {
            return $this->cn_name ?? '';
        }
        return $this->en_name ?? '';
    }
}
