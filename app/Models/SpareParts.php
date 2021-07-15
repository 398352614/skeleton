<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/23/2021
 * Time : 4:57 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: SpareParts.php
 */


namespace App\Models;

use App\Traits\ConstTranslateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SpareParts
 * @package App\Models
 */
class SpareParts extends BaseModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'spare_parts';

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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'company_id',
        'sp_no',
        'sp_name',
        'sp_brand',
        'sp_model',
        'sp_unit',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'sp_unit' => 'int'
    ];

    /**
     * @param  int  $value
     * @return mixed
     */
    public function getSpUnit(int $value)
    {
        return ConstTranslateTrait::sparePartsUnit($value);
    }

    /**
     * @param  string  $sp_no
     * @return int|mixed
     */
    public function getStock(string $sp_no): int
    {
        return SparePartsStock::query()->where('sp_no', $sp_no)->value('stock_quantity') ?? 0;
    }
}
