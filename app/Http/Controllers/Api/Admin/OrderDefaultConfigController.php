<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 4/6/2021
 * Time : 6:46 PM
 * Email: i@nizer.in
 * Blog : nizer.in
 * FileName: OrderDefaultConfigController.php
 */


namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\OrderDefaultConfigService;

/**
 * Class OrderDefaultConfigController
 * @package App\Http\Controllers\Api\Admin
 */
class OrderDefaultConfigController extends BaseController
{
    /**
     * OrderDefaultConfigController constructor.
     * @param  OrderDefaultConfigService  $service
     * @param  array  $exceptMethods
     */
    public function __construct(OrderDefaultConfigService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function detail()
    {
        return $this->service->getInfo([], ['*'], false);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function update()
    {
        return $this->service->updateOrCreate([], $this->data);
    }
}
