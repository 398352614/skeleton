<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/12/2021
 * Time : 4:55 PM
 * Email: nzl9851@88.com
 * Blog : nizer.in
 * FileName: CarMaintainController.php
 */


namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\CarMaintainService;

/**
 * 车辆维修保养
 * Class CarMaintainController
 * @package App\Http\Controllers\Api\Admin
 */
class CarMaintainController extends BaseController
{
    /**
     * CarMaintainController constructor.
     * @param  CarMaintainService  $service
     * @param  array  $exceptMethods
     */
    public function __construct(CarMaintainService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * 列表
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 新增
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        return $this->service->create($this->data);
    }

    /**
     * 详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function detail($id)
    {
        return $this->service->getInfo(['id' => $id], ['*'], false);
    }

    /**
     * @param $id
     * @return int
     */
    public function update($id)
    {
        return $this->service->update(['id' => $id], $this->data);
    }

    /**
     * 导出 excel
     * @return mixed
     */
    public function export()
    {
       return $this->service->exportExcel($this->data['id_list']);
    }

    /**
     * 批量删除
     * @return mixed
     */
    public function destroyAll()
    {
        return $this->service->destroyAll($this->data['id_list']);
    }

    /**
     * 批量收票
     * @return mixed
     */
    public function ticketAll()
    {
        return $this->service->ticketAll($this->data['id_list']);
    }
}
