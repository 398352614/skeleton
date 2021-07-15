<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Merchant\MerchantApiService;
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

    /**
     * 获取详情
     * @param $merchantId
     * @return array
     * @throws BusinessLogicException
     */
    public function show()
    {
        $info = $this->service->getInfo(['merchant_id' => auth()->id()], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info->toArray();
    }

    /**
     * 修改
     * @param $merchantId
     * @throws BusinessLogicException
     */
    public function update()
    {
        $rowCount = $this->service->update(['merchant_id' => auth()->id()], Arr::only($this->data, ['url', 'white_ip_list', 'status']));
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }
}
