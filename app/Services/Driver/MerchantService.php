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
use App\Services\BaseConstService;

use App\Models\MerchantApi;

/**
 * Class MerchantService
 * @package App\Services\Admin
 */
class MerchantService extends BaseService
{
    public $filterRules = [
        'name' => ['like', 'name'],
        'additional_status' => ['=', 'additional_status']
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
        return parent::setFilter()->getList([], ['*'], false);
    }
}
