<?php
/**
 * 货主API 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Merchant;

use App\Models\MerchantApi;

class MerchantApiService extends BaseService
{
    public function __construct(MerchantApi $merchantApi)
    {
        parent::__construct($merchantApi);
    }
}
