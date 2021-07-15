<?php
/**
 * Created Hunan NLE Network Technology Co., Ltd.
 * User : Crazy_Ning
 * Date : 3/23/2021
 * Time : 4:49 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: SparePartsController.php
 */


namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\SparePartsService;
use App\Traits\ConstTranslateTrait;

/**
 * 备品
 * Class SparePartsController
 * @package App\Http\Controllers\Api\Admin
 */
class SparePartsController extends BaseController
{
    /**
     * SparePartsController constructor.
     * @param  SparePartsService  $service
     * @param  array  $exceptMethods
     */
    public function __construct(SparePartsService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * @return array
     */
    public function init()
    {
        return [
            'unit_list' => ConstTranslateTrait::formatList(ConstTranslateTrait::$sparePartsUnit),
        ];
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
     * @return int
     */
    public function update($id)
    {
       return $this->service->update(['id' => $id], $this->data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->service->delete(['id' => $id]);
    }
}
