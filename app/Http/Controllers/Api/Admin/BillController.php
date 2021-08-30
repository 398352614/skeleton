<?php
/**
 * 客户管理-收货方 接口
 * User: long
 * Date: 2020/1/10
 * Time: 13:38
 */

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\BillService;
use App\Services\Admin\LedgerService;

/**
 * Class AddressController
 * @package App\Http\Controllers\Api\Admin
 * @property BillService $service
 */
class BillController extends BaseController
{
    public function __construct(BillService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 充值
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function merchantRecharge()
    {
        return $this->service->merchantRecharge($this->data);
    }

//    /**
//     * @param $id
//     * @return int|void
//     * @throws \App\Exceptions\BusinessLogicException
//     */
//    public function update($id)
//    {
//        return $this->service->update($id,$this->data);
//    }

    /**
     * 审核
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function verify($id)
    {
        return $this->service->verify($id,$this->data);
    }

    /**
     * 查看详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function show($id)
    {
        return $this->service->show($id);
    }
}
