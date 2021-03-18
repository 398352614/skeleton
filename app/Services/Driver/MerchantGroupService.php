<?php
/**
 * 货主组列表 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Driver;

use App\Models\MerchantGroup;

/**
 * Class MerchantService
 * @package App\Services\Admin
 */
class MerchantGroupService extends BaseService
{
    public function __construct(MerchantGroup $merchantGroup)
    {
        parent::__construct($merchantGroup);
    }
}
