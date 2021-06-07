<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/10/29
 * Time: 11:28
 */

namespace App\Services\Driver;

use App\Models\Warehouse;
use App\Services\BaseConstService;

class WareHouseService extends BaseService
{
    public function __construct(Warehouse $warehouse, $resource = null, $infoResource = null)
    {
        parent::__construct($warehouse, $resource, $infoResource);
    }

    public function getPageList()
    {
//        $rootWarehouse = parent::getInfo(['parent' => 0], ['*'], false);
//        $this->query->where('id', '<>', $rootWarehouse['id']);
        return parent::getPageList();
    }

    public function home()
    {
        $where = ['warehouse_id' => auth()->user()->warehouse_id];
        $notOutBagCount = $this->getBagService()->count(array_merge($where, ['status' => BaseConstService::BAG_STATUS_1]));
        $notOutShiftCount = $this->getShiftService()->count(array_merge($where, ['status' => BaseConstService::SHIFT_STATUS_1]));
        $notInShiftCount = $this->getShiftService()->count(array_merge($where, ['status' => BaseConstService::BAG_STATUS_2]));
        $notUnloadShiftCount = $this->getShiftService()->count(array_merge($where, ['status' => BaseConstService::SHIFT_STATUS_3]));
        $notUnpackBagCount = $this->getBagService()->count(array_merge($where, ['status' => BaseConstService::BAG_STATUS_4]));
        return [
            'count'=>[
                'not_out_bag' => $notOutBagCount,
                'not_out_shift' => $notOutShiftCount,
                'not_in_shift' => $notInShiftCount,
                'not_unload_shift' => $notUnloadShiftCount,
                'not_unpack_bag' => $notUnpackBagCount
            ]
        ];
    }
}
