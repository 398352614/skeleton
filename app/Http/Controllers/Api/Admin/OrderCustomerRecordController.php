<?php
/**
 * Created by Hunan NLE Network Technology Co., Ltd.
 * User : Crazy_Ning
 * Date : 3/26/2021
 * Time : 2:28 PM
 * Email: nzl9851@88.com
 * Blog : nizer.in
 * FileName: OrderCustomerRecordController.php
 */


namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\OrderCustomerRecordService;

/**
 * 客服记录
 * Class OrderCustomerRecordController
 * @package App\Http\Controllers\Api\Admin
 */
class OrderCustomerRecordController extends BaseController
{
    /**
     * OrderCustomerRecordController constructor.
     * @param  OrderCustomerRecordService  $service
     * @param  array  $exceptMethods
     */
    public function __construct(OrderCustomerRecordService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        return $this->service->create($this->data);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list()
    {
        return $this->service->getPageList();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->service->delete(['id' => $id]);
    }
}
