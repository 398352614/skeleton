<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 轮播图
 * Class Carousel
 * @package App\Models
 */
class Carousel extends BaseModel
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
    protected $table = 'carousel';
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
        'status',
        'name',
        'picture_url',
        'sort_id',
        'rolling_time',
        'jump_type',
        'inside_jump_type',
        'outside_jump_url',
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
        'jump_type_name',
        'inside_jump_type_name',
        'rolling_time_name'
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

    public function getJumpTypeNameAttribute()
    {
        return empty($this->jump_type) ? null : ConstTranslateTrait::carouselJumpTypeList($this->jump_type);
    }

    public function getInsideJumpTypeNameAttribute()
    {
        return empty($this->inside_jump_type) ? null : ConstTranslateTrait::carouselInsideJumpTypeList($this->inside_jump_type);
    }

    public function getRollingTimeNameAttribute()
    {
        return empty($this->rolling_time) ? null : ConstTranslateTrait::carouselRollingTimeList($this->rolling_time);
    }
}
