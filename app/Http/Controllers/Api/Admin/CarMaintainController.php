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
}
