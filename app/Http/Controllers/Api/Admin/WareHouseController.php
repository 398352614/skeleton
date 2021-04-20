<?php
/**
 * 网点管理
 * User: long
 * Date: 2019/12/25
 * Time: 15:16
 */

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\WareHouseService;

/**
 * Class WarehouseController
 * @package App\Http\Controllers\Api\Admin
 * @property WareHouseService $service
 */
class WareHouseController extends BaseController
{
    public function __construct(WareHouseService $service)
    {
        parent::__construct($service);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }


    /**
     * 新增
     * @throws BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * 修改
     * @param $id
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }

    /**
     * 删除
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws BusinessLogicException
     */
    public function getLineList($id)
    {
        return $this->service->getLineList($id);
    }

//    /**
//     * 删除
//     * @param $id
//     * @throws BusinessLogicException
//     */
//    public function addLine($id)
//    {
//        return $this->service->addLine($id,$this->data);
//    }

//    /**
//     * 删除
//     * @param $id
//     * @throws BusinessLogicException
//     */
//    public function removeLine($id)
//    {
//        return $this->service->removeLine($id,$this->data);
//    }
}
