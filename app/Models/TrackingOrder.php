<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;
use App\Traits\SearchTrait;
use Illuminate\Support\Facades\App;

/**
 * 运单表
 * Class Employee
 * @package App\Models
 */
class TrackingOrder extends BaseModel
{
    /**
     * 运单表
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tracking_order';

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
        'out_user_id',
        'out_order_no',
        'order_no',
        'tracking_order_no',
        'batch_no',
        'tour_no',
        'type',
        'execution_date',
        'sender_fullname',
        'sender_phone',
        'sender_country',
        'sender_post_code',
        'sender_house_number',
        'sender_city',
        'sender_street',
        'sender_address',
        'receiver_fullname',
        'receiver_phone',
        'receiver_country',
        'receiver_post_code',
        'receiver_house_number',
        'receiver_city',
        'receiver_street',
        'receiver_address',
        'lon',
        'lat',
        'driver_id',
        'driver_name',
        'driver_phone',
        'car_id',
        'car_no',
        'status',
        'out_status',
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
        'status_name',
        'out_status_name',
        'type_name',
        'merchant_id_name',
        'country_name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    private function getRelationQuery($where, $table, $tableWhere)
    {
        $where = array_merge(array_key_prefix($where, $this->table . '.'), array_key_prefix($tableWhere, $table . '.'));
        $query = $this->newQuery()->with($table);
        SearchTrait::buildQuery($query, $where);
        return $query;
    }

    public function getOrder($where = [], $orderWhere = [], $selectFields = ['*'])
    {
        $orderTable = Order::query()->newModelInstance()->getTable();
        $query = $this->getRelationQuery($where, $orderTable, $orderWhere);
        return $query->first($selectFields);
    }

    public function getOrderList($where = [], $orderWhere = [], $selectFields = ['*'])
    {
        $orderTable = Order::query()->newModelInstance()->getTable();
        $query = $this->getRelationQuery($where, $orderTable, $orderWhere);
        return $query->get($selectFields);
    }

    public function getPackageList($where = [], $selectFields = ['*'])
    {
        $this->newQuery()->with('order')->where('order_no', 'TMS')->get();
    }

    public function getMaterialList($where = [], $selectFields = ['*'])
    {
        $this->newQuery()->with('order')->where('order_no', 'TMS')->get();
    }

}
