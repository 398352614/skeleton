<?php
/**
 * 网点服务
 * User: long
 * Date: 2019/12/21
 * Time: 11:21
 */

namespace App\Services\Merchant;

use App\Models\Warehouse;

class WareHouseService extends BaseService
{
    public function __construct(Warehouse $warehouse)
    {
        parent::__construct($warehouse);
    }
}
