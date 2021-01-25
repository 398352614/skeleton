<?php


namespace App\Models;


use Illuminate\Support\Facades\DB;

/**
 * 充值
 * Class Recharge
 * @package App\Models
 */
class MerchantRecharge extends BaseModel
{    /**
 *
 * The table associated with the model.
 *
 * @var string
 */
    protected $table = 'merchant_recharge';

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
        'url',
        'status',

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
        }    }

}
