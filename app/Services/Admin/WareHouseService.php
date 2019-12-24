<?php
/**
 * 仓库服务
 * User: long
 * Date: 2019/12/21
 * Time: 11:21
 */

namespace App\Services\Admin;


use App\Models\Warehouse;
use App\Services\BaseService;

class WareHouseService extends BaseService
{
    public function __construct(Warehouse $warehouse)
    {
        $this->model = $warehouse;
        $this->query = $this->model::query();
    }
}