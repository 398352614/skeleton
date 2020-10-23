<?php


namespace App\Models;


use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Facades\DB;

/**
 * 充值
 * Class Recharge
 * @package App\Models
 */
class RechargeStatistics extends BaseModel
{
    /**
     *
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recharge_statistics';

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
        'tour_no',
        'execution_date',
        'recharge_date',
        'driver_id',
        'driver_name',
        'total_recharge_amount',
        'recharge_count',
        'status',
        'verify_date',
        'verify_time',
        'verify_recharge_amount',
        'verify_remark',
        'verify_name',
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
    ];

    public function getMerchantNameAttribute()
    {
        return empty($this->merchant_id) ? '' : DB::table('merchant')->where('id', '=', $this->merchant_id)->first()->name;
    }

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::verifyStatusList($this->status);
    }
}
