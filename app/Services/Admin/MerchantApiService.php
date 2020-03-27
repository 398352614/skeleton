<?php
/**
 * 商户API 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;


use App\Models\MerchantApi;
use App\Services\BaseService;

class MerchantApiService extends BaseService
{
    public function __construct(MerchantApi $merchantApi)
    {
        parent::__construct($merchantApi);
    }
}
