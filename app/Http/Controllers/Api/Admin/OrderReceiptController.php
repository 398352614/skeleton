<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 3/29/2021
 * Time : 3:29 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: OrderReceiptController.php
 */


namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\OrderReceiptService;

/**
 * Class OrderReceiptController
 * @package App\Http\Controllers\Api\Admin
 */
class OrderReceiptController extends BaseController
{
    /**
     * OrderReceiptController constructor.
     * @param  OrderReceiptService  $service
     * @param  array  $exceptMethods
     */
    public function __construct(OrderReceiptService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list()
    {
        return $this->service->getPageList();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        return $this->service->create($this->data);
    }

    /**
     * @param $id
     * @return int
     */
    public function update($id)
    {
        return $this->service->update(['id' => $id], $this->data);
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
