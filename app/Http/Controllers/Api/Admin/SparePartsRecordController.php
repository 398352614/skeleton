<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/24/2021
 * Time : 3:10 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: SparePartsRecordController.php
 */


namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\SparePartsRecordServices;

/**
 * Class SparePartsRecord
 * @package App\Http\Controllers\Api\Admin
 */
class SparePartsRecordController extends BaseController
{
    /**
     * SparePartsRecordController constructor.
     * @param  SparePartsRecordServices  $service
     * @param  array  $exceptMethods
     */
    public function __construct(SparePartsRecordServices $service, $exceptMethods = [])
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
     * @param $id
     * @return mixed
     */
    public function invalid($id)
    {
        return $this->service->invalid($id);
    }
}
