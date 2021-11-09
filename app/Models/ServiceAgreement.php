<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 轮播图
 * Class Carousel
 * @package App\Models
 */
class ServiceAgreement extends BaseModel
{
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
     * 袋表
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_agreement';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'type',
        'tittle',
        'text',
        'operator_name',
        'operator_id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $appends = [
        'status_name',
        'type_name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::bagStatusList($this->status);
    }

    public function getTypeAttribute()
    {
        return empty($this->type) ? null : ConstTranslateTrait::serviceAgreementTypeList($this->type);
    }

}
