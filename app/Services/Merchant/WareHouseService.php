<?php
/**
 * 仓库服务
 * User: long
 * Date: 2019/12/21
 * Time: 11:21
 */

namespace App\Services\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Merchant\WareHouseResource;
use App\Models\Warehouse;
use App\Services\BaseService;
use App\Traits\LocationTrait;

class WareHouseService extends BaseService
{
    public function __construct(Warehouse $warehouse)
    {
        parent::__construct($warehouse);
    }
}
