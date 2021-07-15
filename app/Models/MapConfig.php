<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 4/6/2021
 * Time : 4:52 PM
 * Email: i@nizer.in
 * Blog : nizer.in
 * FileName: OrderDefaultConfig.php
 */


namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * Class OrderDefaultConfig
 * @package App\Models
 */
class MapConfig extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'map_config';

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'company_id',
        'front_type',
        'back_type',
        'mobile_type',
        'google_key',
        'google_secret',
        'baidu_key',
        'baidu_secret',
        'tencent_key',
        'tencent_secret',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    protected $appends = [
        'front_type_name',
        'back_type_name',
        'mobile_type_name',
    ];

    public function getFrontTypeNameAttribute()
    {
        return empty($this->front_type) ? null : ConstTranslateTrait::mapConfigFrontTypeList($this->front_type);
    }

    public function getBackTypeNameAttribute()
    {
        return empty($this->back_type) ? null : ConstTranslateTrait::mapConfigBackTypeList($this->back_type);
    }

    public function getMobileTypeNameAttribute()
    {
        return empty($this->mobile_type) ? null : ConstTranslateTrait::mapConfigMobileTypeList($this->mobile_type);
    }
}
