<?php
/**
 * 商户列表 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Driver;

use App\Http\Resources\MerchantResource;
use App\Models\Merchant;
use App\Services\BaseService;


/**
 * Class MerchantService
 * @package App\Services\Admin
 */
class MerchantService extends BaseService
{
    public $filterRules = [
        'name' => ['like', 'name'],
        'recharge_status' => ['=', 'recharge_status'],
        'status' => ['=', 'status']
    ];

    public function __construct(Merchant $merchant)
    {
        parent::__construct($merchant, MerchantResource::class);
    }

    public function getMerchantList()
    {
        return parent::setFilter()->getList([], ['*'], false);
    }
}
