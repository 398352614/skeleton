<?php
/**
 * 角色管理
 * User: long
 * Date: 2020/5/13
 * Time: 16:21
 */

namespace App\Http\Controllers\Api\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\MapConfigService;

/**
 * Class RoleController
 * @package App\Http\Controllers\Api\Admin
 * @property MapConfigService $service
 */
class MapConfigController extends BaseController
{
    /**
     * RoleController constructor.
     * @param MapConfigService $service
     * @param array $exceptMethods
     */
    public function __construct(MapConfigService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }


    /**
     * 获取详情
     * @return mixed
     * @throws BusinessLogicException
     */
    public function show()
    {
        return $this->service->show();
    }

    /**
     * 修改
     * @return mixed
     * @throws BusinessLogicException
     */
    public function update()
    {
        return $this->service->updateByCompanyId($this->data);
    }
}
