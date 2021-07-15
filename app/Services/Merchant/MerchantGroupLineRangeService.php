<?php
/**
 * 货主组线路范围 服务
 * User: long
 * Date: 2020/8/13
 * Time: 13:46
 */

namespace App\Services\Merchant;

use App\Models\MerchantGroupLineRange;

class MerchantGroupLineRangeService extends BaseService
{
    public function __construct(MerchantGroupLineRange $model)
    {
        parent::__construct($model, null);
    }
}
