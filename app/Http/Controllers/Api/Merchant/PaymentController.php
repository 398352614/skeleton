<?php
/**
 * 联系人管理-收货方 接口
 * User: long
 * Date: 2020/3/16
 * Time: 13:38
 */

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\BaseController;
use App\Services\Merchant\AddressService;
use App\Services\Merchant\PaymentService;

/**
 * Class AddressController
 * @package App\Http\Controllers\Api\Merchant
 * @property AddressService $service
 */
class PaymentController extends BaseController
{
    public function __construct(PaymentService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * 支付查询
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed|object|null
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 支付详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * 支付新增
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * 支付执行
     * @param $id
     * @return bool|int|void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function pay($id)
    {
        return $this->service->pay($id, $this->data);
    }

    /**
     * 支付取消
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function cancel($id)
    {
        return $this->service->cancel($id);
    }
}
