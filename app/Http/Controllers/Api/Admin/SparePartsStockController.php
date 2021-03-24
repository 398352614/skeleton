<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/24/2021
 * Time : 2:29 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: SparePartsStockController.php
 */


namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\SparePartsStockService;

/**
 * Class SparePartsStockController
 * @package App\Http\Controllers\Api\Admin
 */
class SparePartsStockController extends BaseController
{
    /**
     * SparePartsStockController constructor.
     * @param  SparePartsStockService  $service
     * @param  array  $exceptMethods
     */
    public function __construct(SparePartsStockService $service, $exceptMethods = [])
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
