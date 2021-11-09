<?php
/**
 * 客户管理-收货方 接口
 * User: long
 * Date: 2020/1/10
 * Time: 13:38
 */

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\BillVerifyService;

/**
 * Class AddressController
 * @package App\Http\Controllers\Api\Admin
 * @property BillVerifyService $service
 */
class BillVerifyController extends BaseController
{
    public function __construct(BillVerifyService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->service->autoStore($this->data);
    }

    /**
     * @param $id
     * @return void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function autoStore($id)
    {
        return $this->service->autoStore($id);
//        dispatch(new AutoBillVerify($id));
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function verify($id)
    {
        return $this->service->verify($id, $this->data);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
