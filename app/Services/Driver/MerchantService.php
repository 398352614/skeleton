<?php
/**
 * 商户列表 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Driver;

use App\Http\Resources\Api\Driver\MerchantResource;
use App\Models\Merchant;
use App\Models\MerchantApi;
use App\Services\BaseConstService;

/**
 * Class MerchantService
 * @package App\Services\Admin
 */
class MerchantService extends BaseService
{
    public $filterRules = [
        'name' => ['like', 'name'],
    ];

    public function __construct(Merchant $merchant)
    {
        parent::__construct($merchant, MerchantResource::class);
    }

    public function getMerchantList()
    {
        if (!empty($this->formData['recharge_status'])) {
            $merchantIdList = MerchantApi::query()->where('recharge_status', $this->formData['recharge_status'])->pluck('merchant_id');
            $this->query->whereIn('id', $merchantIdList);
        }
        $this->query->where('status', BaseConstService::MERCHANT_RECHARGE_STATUS_1);
        if (!empty($this->formData['additional_status'])) {
            $merchantGroupList = $this->getMerchantGroupService()->getList(['additional_status' => $this->formData['additional_status'], 'status' => BaseConstService::ADDITIONAL_PACKAGE_STATUS_2], ['*'], false);
            if (!empty($merchantIdList)) {
                $merchantGroupList = $merchantGroupList->pluck('id')->toArray();
                $this->query->whereIn('merchant_group_id', $merchantGroupList);
            }
        }
        return parent::setFilter()->getList([], ['*'], false);
    }
}
