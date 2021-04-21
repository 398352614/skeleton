<?php

namespace App\Models;

use Jiaxincui\ClosureTable\Traits\ClosureTable;

use App\Traits\ConstTranslateTrait;

/**
 * 线路表
 * Class Employee
 * @package App\Models
 */
class Warehouse extends BaseModel
{
    use ClosureTable;
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'warehouse';

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
        'name',
        'type',
        'is_center',
        'acceptance_type',
        'line_ids',
        'fullname',
        'company_name',
        'phone',
        'email',
        'avatar',
        'country',
        'province',
        'post_code',
        'house_number',
        'city',
        'district',
        'address',
        'street',
        'lon',
        'lat',
        'parent',
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

    protected $appends = [
        'type_name',
        'acceptance_type_name',
        'is_center_name',
    ];

    public function getTypeNameAttribute()
    {
        return empty($this->type) ? null : ConstTranslateTrait::warehouseTypeList($this->type);
    }

    public function getAcceptanceTypeNameAttribute()
    {
        if (!empty($this->acceptance_type)) {
            $data = explode(',', $this->acceptance_type);
            foreach ($data as $k => $v) {
                $data[$k] = ConstTranslateTrait::warehouseAcceptanceTypeList($v);
            }
            $data = implode(',', $data);
        } else {
            $data = '';
        }
        return $data;
    }

    public function getIsCenterNameAttribute()
    {
        return empty($this->is_center) ? null : ConstTranslateTrait::warehouseIsCenterTypeList($this->is_center);
    }

}
