<?php
/**
 * 联系人管理-收货方 接口
 * User: long
 * Date: 2020/3/16
 * Time: 13:38
 */

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Managner\Payment\Paypal;
use App\Services\Merchant\AddressService;
use App\Services\Merchant\PaypalService;

/**
 * Class AddressController
 * @package App\Http\Controllers\Api\Merchant
 * @property PaypalService $service
 */
class PaypalController extends BaseController
{
    public function __construct(PaypalService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * @return mixed
     */
    public function pay()
    {
        return $this->service->pay($this->data);
    }

    /**
     * 修改
     * @param $id
     * @return bool|int|void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

}
