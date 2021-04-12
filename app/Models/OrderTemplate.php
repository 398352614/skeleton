<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 地址模板 表
 * Class Employee
 * @package App\Models
 */
class OrderTemplate extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_template';

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
        'destination_mode',
        'type',
        'is_default',
        'logo',
        'sender',
        'receiver',
        'destination',
        'carrier',
        'carrier_address',
        'contents',
        'package',
        'material',
        'count',
        'replace_amount',
        'settlement_amount',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected $appends = [
        'destination_mode_name',
        'type_name',
        'is_default_name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];


    public function getDestinationModeNameAttribute()
    {
        return empty($this->destination_mode) ? null : ConstTranslateTrait::orderTemplateDestinationModeList($this->destination_mode);
    }

    public function getTypeNameAttribute()
    {
        return empty($this->type) ? null : ConstTranslateTrait::orderTemplateTypeList($this->type);
    }

    public function getIsDefaultNameAttribute()
    {
        return empty($this->is_default) ? null : ConstTranslateTrait::orderTemplateIsDefaultList($this->is_default);
    }
}
