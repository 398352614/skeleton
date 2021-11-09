<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/10/2021
 * Time : 4:25 PM
 * Email: nzl9851@88.com
 * Blog : nizer.in
 * FileName: CarAccident.php
 */


namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\CarAccidentService;

/**
 * 车辆事故处理
 * Class CarAccident
 * @package App\Http\Controllers\Api\Admin
 */
class CarAccidentController extends BaseController
{
    /**
     * CarAccident constructor.
     * @param  CarAccidentService  $service
     * @param  array  $exceptMethods
     */
    public function __construct(CarAccidentService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
       return $this->service->getPageList();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        return $this->service->create($this->data);
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
     * @param $id
     * @return int
     */
    public function update($id)
    {
        return $this->service->update(['id' => $id], $this->data);
    }

    /**
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function detail($id)
    {
        return $this->service->getInfo(['id' => $id], ['*'], false);
    }
}
