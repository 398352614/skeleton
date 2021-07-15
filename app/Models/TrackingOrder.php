<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;
use App\Traits\SearchTrait;
use Illuminate\Support\Arr;

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
        'line_id',
        'line_name',
        'type',
        'execution_date',
        'warehouse_fullname',
        'warehouse_phone',
        'warehouse_country',
        'warehouse_province',
        'warehouse_post_code',
        'warehouse_house_number',
        'warehouse_city',
        'warehouse_district',
        'warehouse_street',
        'warehouse_address',
        'warehouse_lon',
        'warehouse_lat',
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
        'driver_id',
        'driver_name',
        'driver_phone',
        'car_id',
        'car_no',
        'status',
        'out_status',
        'exception_label',
        'cancel_type',
        'cancel_remark',
        'cancel_picture',
        'mask_code',
        'special_remark',
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
//        'merchant_id_name',
        'country_name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public function getTypeNameAttribute()
    {
        return empty($this->type) ? null : ConstTranslateTrait::trackingOrderTypeList($this->type);
    }

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::trackingOrderStatusList($this->status);
    }

    public function getOutStatusNameAttribute()
    {
        return empty($this->out_status) ? null : ConstTranslateTrait::outStatusList($this->out_status);
    }

//    public function getMerchantIdNameAttribute()
//    {
//        if (empty($this->merchant) || empty($this->merchant_id)) return '';
//        return $this->merchant->name;
//    }


//    public function merchant()
//    {
//        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
//    }

    /**
     * 获取关联查询构造器
     * @param $where
     * @param $table
     * @param $tableWhere
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getRelationQuery($where, $table, $tableWhere)
    {
        $where = array_merge(array_key_prefix($where, $this->table . '.'), array_key_prefix($tableWhere, $table . '.'));
        $query = $this->newQuery()->join($table, $this->table . '.order_no', '=', $table . '.order_no');
        !empty($where) && SearchTrait::buildQuery($query, $where);
        return $query;
    }

    /**
     * 为字段填充表名
     * @param $table
     * @param $selectFields
     * @return array
     */
    private function fillSelectFieldsTable($table, $selectFields)
    {
        if ($selectFields == ['*']) {
            $selectFields = Order::query()->newModelInstance()->getFillable();
        }
        $selectFields = collect($selectFields)->transform(function ($field, $key) use ($table) {
            return $table . '.' . $field . ' as ' . $field;
        })->all();
        $selectFields = Arr::prepend($selectFields, $this->table . '.tracking_order_no as tracking_order_no');
        return $selectFields;
    }

    public function getOrder($where = [], $orderWhere = [], $selectFields = ['*'])
    {
        $orderTable = Order::query()->newModelInstance()->getTable();
        $query = $this->getRelationQuery($where, $orderTable, $orderWhere);
        $order = $query->first($this->fillSelectFieldsTable($orderTable, $selectFields));
        return !empty($order) ? $order->toArray() : [];
    }

    public function getOrderList($where = [], $orderWhere = [], $selectFields = ['*'])
    {
        $orderTable = Order::query()->newModelInstance()->getTable();
        $query = $this->getRelationQuery($where, $orderTable, $orderWhere);
        return $query->get($this->fillSelectFieldsTable($orderTable, $selectFields))->toArray();
    }
}
