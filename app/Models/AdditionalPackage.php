<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

/**
 *  顺带包裹表
 * Class Employee
 * @package App\Models
 */
class AdditionalPackage extends BaseModel
{
    /**
     *
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'additional_package';

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
        'batch_no',
        'tour_no',
        'line_id',
        'line_name',
        'package_no',
        'execution_date',
        'status',
        'sticker_no',
        'sticker_amount',
        'delivery_amount',
        'place_fullname',
        'place_phone',
        'place_country',
        'place_province',
        'place_post_code',
        'place_house_number',
        'place_city',
        'place_district',
        'place_street',
        'place_address',
        'place_lon',
        'place_lat',
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
        'merchant_name',
    ];

    public function getMerchantNameAttribute()
    {
        if (empty($this->merchant_id)) {
            return '';
        } else {
            $merchant = DB::table('merchant')->where('id', '=', $this->merchant_id)->first();
            if (empty($merchant)) {
                return '';
            } else {
                return $merchant->name;
            }
        }
    }
}
