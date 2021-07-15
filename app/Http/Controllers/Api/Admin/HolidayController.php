<?php
/**
 * 放假接口
 * User: long
 * Date: 2020/7/22
 * Time: 13:59
 */

namespace App\Http\Controllers\Api\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\HolidayService;

/**
 * Class HolidayController
 * @package App\Http\Controllers\Api\Admin
 * @property HolidayService $service
 */
class HolidayController extends BaseController
{
    public function __construct(HolidayService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * 新增
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * 修改
     * @param $id
     * @return bool|int|void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }

    /**
     * 删除
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }

    /**
     * 状态-启用/禁用
     * @param $id
     * @return
     * @throws BusinessLogicException
     */
    public function status($id)
    {
        return $this->service->status($id, $this->data);
    }


    public function merchantIndex()
    {
        return $this->service->merchantIndex();
    }

    /**
     * 新增货主列表
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function storeMerchantList($id)
    {
        return $this->service->storeMerchantList($id, $this->data['merchant_id_list']);
    }

    /**
     * 删除货主
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function destroyMerchant($id)
    {
        return $this->service->destroyMerchant($id, $this->data['merchant_id']);
    }
}
