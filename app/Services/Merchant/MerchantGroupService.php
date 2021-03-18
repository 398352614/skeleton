<?php
/**
 * 货主组 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Merchant;

use App\Models\MerchantGroup;

class MerchantGroupService extends BaseService
{
    public function __construct(MerchantGroup $merchantGroup)
    {
        parent::__construct($merchantGroup);
    }
}
