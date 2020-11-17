<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 订单轨迹表
 * Class OrderTrail
 * @package App\Models
 */
class TrackingOrderTrail extends BaseModel
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tracking_order_trail';

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
        'content',
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

    public function getContentAttribute($value)
    {
        if(preg_match_all('/(?<=\[)[^]]+/',$value,$params)){
            for($i=0,$j=count($params[0]);$i<$j;$i++){
                $k=1;
                $data['params'.($i+1)]=$params[0][$i];
                $value=str_replace($data['params'.($i+1)],':params'.($i+1),$value,$k);
            }
            return !empty($value)?__($value,$data): null;
        }else{
            return !empty($value)?__($value): null;
        }
    }
}
