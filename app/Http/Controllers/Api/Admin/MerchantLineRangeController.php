<?php
/**
 * 商户线路范围 接口
 * User: long
 * Date: 2020/6/22
 * Time: 14:05
 */

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\MerchantLineRangeService;

/**
 * 费用
 * Class FeeController
 * @package App\Http\Controllers\Api\Admin
 * @property MerchantLineRangeService $service
 */
class MerchantLineRangeController extends BaseController
{
    public function __construct(MerchantLineRangeService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * 更新
     * @param $id
     * @throws BusinessLogicException
     */
    public function createOrUpdate($id)
    {
        return $this->service->createOrUpdate($id, $this->data);
    }
}