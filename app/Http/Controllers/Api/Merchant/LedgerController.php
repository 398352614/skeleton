<?php
/**
 * 客户管理-收货方 接口
 * User: long
 * Date: 2020/1/10
 * Time: 13:38
 */

namespace App\Http\Controllers\Api\Merchant;


use App\Http\Controllers\BaseController;
use App\Services\Merchant\LedgerService;

/**
 * Class AddressController
 * @package App\Http\Controllers\Api\Admin
 * @property LedgerService $service
 */
class LedgerController extends BaseController
{
    public function __construct(LedgerService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function show()
    {
        return $this->service->show($this->data);
    }

    /**
     * 新增
     * @throws \App\Exceptions\BusinessLogicException
     */
//    public function store()
//    {
//        return $this->service->store($this->data);
//    }

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

    public function log($id)
    {
        return $this->service->log($id);
    }

    /**
     * 删除
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
//    public function destroy($id)
//    {
//        return $this->service->destroy($id);
//    }

}
