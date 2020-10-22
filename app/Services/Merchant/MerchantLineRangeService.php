<?php
/**
 * 商户线路范围 服务
 * User: long
 * Date: 2020/8/13
 * Time: 13:46
 */

namespace App\Services\Merchant;

use App\Models\MerchantLineRange;


class MerchantLineRangeService extends BaseService
{
    public function __construct(MerchantLineRange $model)
    {
        parent::__construct($model, null);
    }
}
