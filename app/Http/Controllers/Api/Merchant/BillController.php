<?php
/**
 * 客户管理-收货方 接口
 * User: long
 * Date: 2020/1/10
 * Time: 13:38
 */

namespace App\Http\Controllers\Api\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Merchant\BillService;

/**
 * Class AddressController
 * @package App\Http\Controllers\Api\Merchant
 * @property BillService $service
 */
class BillController extends BaseController
{
    public function __construct(BillService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws BusinessLogicException
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 充值
     * @throws BusinessLogicException
     */
    public function merchantRecharge()
    {
        return $this->service->storeByRecharge($this->data);
    }

    /**
     * @param $id
     * @return void
     * @throws BusinessLogicException
     */
    public function pay()
    {
        return $this->service->pay($this->data);
    }

    /**
     * 审核
     * @param $id
     * @throws BusinessLogicException
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
