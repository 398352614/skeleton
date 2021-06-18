<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 材料表
 * Class Employee
 * @package App\Models
 */
class Material extends BaseModel
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'material';

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
        'tracking_order_no',
        'order_no',
        'execution_date',
        'name',
        'type',
        'pack_type',
        'code',
        'out_order_no',
        'expect_quantity',
        'actual_quantity',
        'remark',
        'weight',
        'size',
        'price',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected $appends = [
        'type_name',
        'pack_type_name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public function getTypeNameAttribute()
    {
        return empty($this->type) ? null : ConstTranslateTrait::materialTypeList($this->type);
    }

    public function getPackTypeNameAttribute()
    {
        return empty($this->pack_type) ? null : ConstTranslateTrait::materialPackTypeList($this->pack_type);
    }
}
