<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\MerchantApiService;
use Illuminate\Support\Arr;

/**
 * 货主api
 * Class OrderController
 * @package App\Http\Controllers\Api\Admin
 * @property MerchantApiService $service
 */
class MerchantApiController extends BaseController
{
    public function __construct(MerchantApiService $service)
    {
        parent::__construct($service);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 获取详情
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = $this->service->getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info->toArray();
    }

    /**
     * 新增
     * @throws BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * 修改
     * @param $id
     * @throws BusinessLogicException
     */
    public function update($id)
    {
        $rowCount = $this->service->update(['id' => $id], Arr::only($this->data, ['url', 'white_ip_list', 'status', 'recharge_status']));
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }

    /**
     * 修改状态
     * @param $id
     * @return string
     * @throws BusinessLogicException
     */
    public function status($id)
    {
        return $this->service->status($id, $this->data);
    }
}
