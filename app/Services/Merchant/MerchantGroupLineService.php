<?php
/**
 * 货主线路范围 服务
 * User: long
 * Date: 2020/8/13
 * Time: 13:46
 */

namespace App\Services\Merchant;

use App\Models\MerchantGroupLine;

class MerchantGroupLineService extends BaseService
{
    public function __construct(MerchantGroupLine $model)
    {
        parent::__construct($model, null);
    }

}
