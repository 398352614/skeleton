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
}
