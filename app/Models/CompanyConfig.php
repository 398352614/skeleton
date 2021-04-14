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
        'line_rule_name'
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
        'weight_unit' => 'int',
        'currency_unit' => 'int',
        'volume_unit' => 'int'
    ];


    public function getLineRuleNameAttribute()
    {
        return empty($this->line_rule) ? null : ConstTranslateTrait::lineRuleList($this->line_rule);
    }
}
