<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 公司配置 表
 * Class Employee
 * @package App\Models
 */
class CompanyConfig extends BaseModel
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_config';

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
        'address_template_id',
        'line_rule',
        'scheduling_rule',
        'show_type',
        'weight_unit',
        'currency_unit',
        'volume_unit',
        'stock_exception_verify',
        'map',
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
        'line_rule_name',
        'weight_unit_name',
        'weight_unit_symbol',
        'currency_unit_name',
        'currency_unit_symbol',
        'volume_unit_name',
        'volume_unit_symbol',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * @var string[]
     */
    protected $casts = [
        'weight_unit'       => 'int',
        'currency_unit'     => 'int',
        'volume_unit'       => 'int',
        'scheduling_rule'   => 'int',
        'line_rule'         => 'int'
    ];

    /**
     * @return string
     */
    public function getLineRuleNameAttribute()
    {
        return empty($this->line_rule) ? '' : ConstTranslateTrait::lineRuleList($this->line_rule);
    }


    /**
     * @return string
     */
    public function getWeightUnitNameAttribute()
    {
        return empty($this->weight_unit) ? '' : ConstTranslateTrait::weightUnitTypeList($this->weight_unit);
    }

    /**
     * @return string
     */
    public function getWeightUnitSymbolAttribute()
    {
        return empty($this->weight_unit) ? '' : ConstTranslateTrait::weightUnitTypeSymbol($this->weight_unit);
    }

    /**
     * @return string
     */
    public function getCurrencyUnitNameAttribute()
    {
        return empty($this->currency_unit) ? '' : ConstTranslateTrait::currencyUnitTypeList($this->currency_unit);
    }

    /**
     * @return string
     */
    public function getCurrencyUnitSymbolAttribute()
    {
        return empty($this->currency_unit) ? '' : ConstTranslateTrait::currencyUnitTypeSymbol($this->currency_unit);

    }

    /**
     * @return string
     */
    public function getVolumeUnitNameAttribute()
    {
        return empty($this->volume_unit) ? '' : ConstTranslateTrait::volumeUnitTypeList($this->volume_unit);
    }

    /**
     * @return string
     */
    public function getVolumeUnitSymbolAttribute()
    {
        return empty($this->volume_unit) ? '' : ConstTranslateTrait::volumeUnitTypeSymbol($this->volume_unit);
    }
}
