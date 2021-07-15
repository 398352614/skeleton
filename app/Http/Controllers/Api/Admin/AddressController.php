<?php
/**
 * 客户管理-收货方 接口
 * User: long
 * Date: 2020/1/10
 * Time: 13:38
 */

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\AddressService;

/**
 * Class AddressController
 * @package App\Http\Controllers\Api\Admin
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
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function excelExport()
    {
        return $this->service->export($this->data['id_list']);
    }

    /**
     * 导入
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function import()
    {
        return $this->service->import($this->data);
    }

    /**
     * 获取模板
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function excelTemplate()
    {
        return $this->service->excelTemplate();
    }

    /**
     * 导入检查
     * @return mixed
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function importCheckByList()
    {
        return $this->service->importCheckByList($this->data);
    }

    /**
     * 批量新增
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function storeByList()
    {
        return $this->service->createByList($this->data);
    }
}
