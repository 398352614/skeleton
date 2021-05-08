<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/10/29
 * Time: 11:28
 */

namespace App\Services\Driver;

use App\Models\Warehouse;

class WareHouseService extends BaseService
{
    public function __construct(Warehouse $warehouse, $resource = null, $infoResource = null)
    {
        parent::__construct($warehouse, $resource, $infoResource);
    }

    public function getPageList()
    {
        $rootWarehouse = parent::getInfo(['parent' => 0], ['*'], false);
        $this->query->where('id', '<>', $rootWarehouse['id']);
        return parent::getPageList();
    }
}
