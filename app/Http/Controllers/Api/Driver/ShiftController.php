<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/11/29
 * Time: 16:57
 */

namespace App\Http\Controllers\Api\Driver;


use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Driver\ShiftService;

/**
 * Class StockController
 * @package App\Http\Controllers\Api\Driver
 * @property ShiftService $service
 */
class ShiftController extends BaseController
{
    public function __construct(ShiftService $service)
    {
        parent::__construct($service);
    }

    /**
     * 查询
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 详情
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
     * 扫描内容物（包裹，袋）
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     * @throws BusinessLogicException
     */
    public function loadItem($id)
    {
        return $this->service->loadItem($id, $this->data);
    }

    /**
     * 移除内容物（包裹，袋）
     * @param $id
     * @return void
     * @throws BusinessLogicException
     */
    public function removeItem($id)
    {
        return $this->service->removeItem($id, $this->data);
    }

    /**
     * 卸下内容物（包裹，袋）
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|mixed
     * @throws BusinessLogicException
     */
    public function unloadItem($id)
    {
        return $this->service->unloadItem($id, $this->data);
    }

    /**
     * @param $id
     * @return string
     * @throws BusinessLogicException
     */
    public function unloadItemList($id)
    {
        return $this->service->unloadItemList($id, $this->data);
    }

    /**
     * 发车
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|mixed
     * @throws BusinessLogicException
     */
    public function outWarehouse($id)
    {
        return $this->service->outWarehouse($id);
    }

    /**
     * 到车
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|mixed
     * @throws BusinessLogicException
     */
    public function inWarehouse($id)
    {
        return $this->service->inWarehouse($id);
    }

    /**
     * 到车
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|mixed
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
