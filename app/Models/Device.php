<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Facades\App;

/**
 * 设备表
 * Class Car
 * @package App\Models
 */
class Device extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'device';

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
        'driver_id',
        'number',
        'status',
        'mode',
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
        'driver_id_name'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id');
    }

    public function getDriverIdNameAttribute()
    {
        if (empty($this->driver) || empty($this->driver)) return null;
        return $this->driver->fullname;
    }

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::deviceStatusList($this->status);
    }

}

