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

/**
 * Class AddressController
 * @package App\Http\Controllers\Api\Merchant
 * @property AddressService $service
 */
class AddressController extends BaseController
{
    public function __construct(AddressService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
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
     * 查询地址
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed|object|null
     * @throws \App\Exceptions\BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function showByApi()
    {
        return $this->service->showByApi($this->data);
    }

    /**
     * 设置默认
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function changeDefault($id)
    {
        return $this->service->changeDefault($id);
    }

}
