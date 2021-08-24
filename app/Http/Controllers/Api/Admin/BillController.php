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

}
