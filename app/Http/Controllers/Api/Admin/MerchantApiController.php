<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\MerchantApiService;
use Illuminate\Support\Arr;

/**
 * 商户api
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

    /**
     * 获取详情
     * @param $merchantId
     * @return array
     * @throws BusinessLogicException
     */
    public function show($merchantId)
    {
        $info = $this->service->getInfo(['merchant_id' => $merchantId], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info->toArray();
    }

    /**
     * 修改
     * @param $id(此处前端传给我的是merchant_api表的id)
     * @throws BusinessLogicException
     */
    public function update($id)
    {
        $rowCount = $this->service->update(['id' => $id], Arr::only($this->data, ['url', 'white_ip_list', 'status','recharge_status']));
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }
}
