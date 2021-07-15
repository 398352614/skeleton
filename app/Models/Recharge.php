<?php


namespace App\Models;


use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Facades\DB;

/**
 * 充值
 * Class Recharge
 * @package App\Models
 */
class Recharge extends BaseModel
{
    /**
     *
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recharge';

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
        'recharge_statistics_id',
        'recharge_no',
        'tour_no',
        'execution_date',
        'transaction_number',
        'out_user_id',
        'out_user_name',
        'out_user_phone',
        'recharge_date',
        'recharge_time',
        'driver_id',
        'driver_name',
        'line_id',
        'line_name',
        'recharge_amount',
        'recharge_first_pic',
        'recharge_second_pic',
        'recharge_third_pic',
        'signature',
        'remark',
        'status',
        'verify_status',
        'driver_verify_status',
        'verify_recharge_amount',
        'verify_time',
        'verify_remark',
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
        'status_name',
        'verify_status_name'
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
        }    }

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::rechargeStatusList($this->status);
    }

    public function getVerifyStatusNameAttribute()
    {
        return empty($this->verify_status) ? null : ConstTranslateTrait::verifyStatusList($this->verify_status);
    }
}
