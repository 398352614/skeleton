<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 3/26/2021
 * Time : 2:39 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: OrderCustomerRecordService.php
 */


namespace App\Services\Admin;

use App\Http\Resources\Api\Admin\OrderCustomerRecordResource;
use App\Models\OrderCustomerRecord;

/**
 * Class OrderCustomerRecordService
 * @package App\Services\Admin
 */
class OrderCustomerRecordService extends BaseService
{
    public $filterRules  = [
        'order_no' => ['=', 'order_no']
    ];
    /**
     * OrderCustomerRecordService constructor.
     * @param  OrderCustomerRecord  $model
     * @param  null  $infoResource
     */
    public function __construct(OrderCustomerRecord $model, $infoResource = null)
    {
        parent::__construct($model, OrderCustomerRecordResource::class, $infoResource);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPageList()
    {
        $this->query->join('employee', 'order_customer_record.operator_id', '=', 'employee.id', 'left');
        $this->query->select(['order_customer_record.*', 'employee.fullname']);

        return parent::getPageList();
    }

    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function create($data)
    {
        $data['operator_id'] = auth()->user()->id;

        return parent::create($data);
    }
}
