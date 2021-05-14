<?php


namespace App\Services\Driver;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\ShiftResource;
use App\Models\Shift;
use App\Services\BaseConstService;
use App\Services\Driver\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;

class ShiftService extends BaseService
{
    public $filterRules = [
        'status' => ['=', 'status'],
        'bag_no' => ['like', 'bag_no']
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(Shift $model)
    {
        parent::__construct($model, ShiftResource::class);
    }

    /**
     * 查询
     * @return Builder[]|Collection|AnonymousResourceCollection
     */
    public function getPageList()
    {
        return parent::getPageList();
    }

    /**
     * 详情
     * @param $id
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info['tracking_package_list'] = $this->getTrackingPackageService()->getList(['shift_no' => $info['shift_no']], ['*'], false);
        $info['bag_list'] = $this->getBagService()->getList(['shift_no' => $info['shift_no']], ['*'], false);
        foreach ($info['tracking_package_list'] as $k => $v) {
            $info['tracking_package_list'][$k]['shift_type'] = BaseConstService::SHIFT_LOAD_TYPE_1;
            $info['tracking_package_list'][$k]['item_no'] = $v['express_first_no'];
            $info['tracking_package_list'][$k]['package_count'] = 0;
        }
        foreach ($info['bag_list'] as $k => $v) {
            $info['bag_list'][$k]['shift_type'] = BaseConstService::SHIFT_LOAD_TYPE_2;
            $info['bag_list'][$k]['item_no'] = $v['bag_no'];
        }
        $itemList = array_merge($info['tracking_package_list']->toArray(), $info['bag_list']->toArray());
        if (!empty($itemList)) {
            $itemList = collect($itemList)->sortBy('created_at');
            foreach ($itemList as &$v) {
                $v['item_no'] = BaseConstService::SHIFT_LOAD_TYPE_2;
            }
            foreach ($itemList as $k => $v) {
                $itemList[$k] = Arr::only($v, ['item_no', 'next_warehouse_name', 'weight', 'package_count','shift_type']);
            }
        }
        $info['item_list'] = $itemList;
        unset($info['tracking_package_list'], $info['bag_list']);
        return $info;
    }

    /**
     * 新增
     * @param array $data
     * @return array
     * @throws BusinessLogicException
     */
    public function store(array $data)
    {
        $data['shift_no'] = $this->getOrderNoRuleService()->createShiftNo();
        $data['warehouse_id'] = auth()->user()->warehouse_id;
        $data['warehouse_name'] = $this->getWareHouseService()->getInfo(['id' => $data['warehouse_id']], ['*'], false)['name'];
        $data['next_warehouse_name'] = $this->getWareHouseService()->getInfo(['id' => $data['next_warehouse_id']], ['*'], false)['name'];
        $data['driver_name'] = auth()->user()->fullname;
        $data['car_no'] = $this->getCarService()->getInfo(['id' => intval($data['car_id'])], ['*'], false)['car_no'] ?? '';
        $rowCount = parent::create($data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        return [
            'shift_no' => $data['shift_no'],
            'id' => $rowCount->id
        ];
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $shift = parent::getInfo(['id' => $id], ['*'], false);
        if ($shift === false) {
            throw new BusinessLogicException('袋号不存在');
        }
        if ($shift['status'] !== BaseConstService::SHIFT_STATUS_1) {
            throw new BusinessLogicException('只有未发车的车次才能删除');
        }
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败，请重新操作');
        }
        //未装袋包裹回未装袋状态
        $this->getTrackingPackageService()->update(['shift_no' => $shift['shift_no'], 'bag_no' => ''], [
            'status' => BaseConstService::TRACKING_PACKAGE_STATUS_2,
            'shift_no' => '',
            'pack_time' => null,
            'pack_operator' => '',
            'pack_operator_id' => '',
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败，请重新操作');
        }
        //已装袋包裹回已装袋状态
        $bagList = $this->getBagService()->getList(['shift_no' => $shift['shift_no']], ['*'], false);
        if (!empty($bagList)) {
            $this->getTrackingPackageService()->update(['bag_no' => $bagList->pluck(['bag_no'])->toArray()], [
                'status' => BaseConstService::TRACKING_PACKAGE_STATUS_1,
                'shift_no' => '',
                'pack_time' => null,
                'pack_operator' => '',
                'pack_operator_id' => '',
            ]);
        }
        $this->getBagService()->update(['shift_no' => $shift['shift_no']], [
            'status' => BaseConstService::BAG_STATUS_1,
            'shift_no' => '',
            'pack_time' => null,
            'pack_operator' => '',
            'pack_operator_id' => '',
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败，请重新操作');
        }
    }

    /**
     * 验证
     * @param $bag
     * @param $trackingPackage
     * @throws BusinessLogicException
     */
    public function check(&$bag, $trackingPackage)
    {
        if (empty($trackingPackage) || in_array($trackingPackage['status'], [
                BaseConstService::TRACKING_PACKAGE_STATUS_4,
                BaseConstService::TRACKING_PACKAGE_STATUS_5,
                BaseConstService::TRACKING_PACKAGE_STATUS_6,
                BaseConstService::TRACKING_PACKAGE_STATUS_7,
            ])) {
            throw new BusinessLogicException('包裹状态错误');
        }
        if ($trackingPackage['status'] == BaseConstService::TRACKING_PACKAGE_STATUS_2) {
            throw new BusinessLogicException('包裹已装袋，请勿重复扫描');
        }
        if ($trackingPackage['status'] == BaseConstService::TRACKING_PACKAGE_STATUS_3) {
            throw new BusinessLogicException('包裹已装车，不允许装袋');
        }
        if ($bag['next_warehouse_id'] !== $trackingPackage['next_warehouse_id']) {
            throw new BusinessLogicException('包裹与袋号的下一站不一致');
        }
    }

    /**
     * 内容物扫描
     * @param $id
     * @param $data
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function loadItem($id, $data)
    {
        $shift = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($shift)) {
            throw new BusinessLogicException('数据不存在');
        }
        $trackingPackage = $this->getTrackingPackageService()->getInfo(['express_first_no' => $data['item_no']], ['*'], false);
        $bag = $this->getBagService()->getInfo(['bag_no' => $data['item_no']], ['*'], false);
        //袋存在，包裹不存在取袋
        if (empty($trackingPackage) && !empty($bag)) {
            $info = $this->loadBag($bag, $shift);
        } elseif (!empty($trackingPackage) && empty($bag)) {
            //袋不存在，包裹存在取包裹
            $info = $this->loadTrackingPackage($trackingPackage, $shift);
        } elseif (!empty($bag) && !empty($trackingPackage)) {
            //同时存在时，不带is_bag参数报错。5007错误通知手持端加上is_bag参数重新请求
            if (empty($data['is_bag'])) {
                throw new BusinessLogicException('包裹号和袋号同时存在', 5007);
            } elseif ($data['is_bag'] == BaseConstService::YES) {
                //is_bag为1时取袋
                $info = $this->loadBag($bag, $shift);
            } else {
                //is_bag为2时取包裹
                $info = $this->loadTrackingPackage($trackingPackage, $shift);
            }
        } else {
            //包裹和袋均不存在报错
            throw new BusinessLogicException('包裹号或袋号不存在');
        }
        $this->recount($id);
        return $info;
    }

    /**
     * 袋号装车
     * @param $bag
     * @param $shift
     * @return array
     * @throws BusinessLogicException
     */
    public function loadBag($bag, $shift)
    {
        if ($bag['status'] == BaseConstService::BAG_STATUS_2) {
            throw new BusinessLogicException('重复扫描');
        }
        if ($bag['status'] !== BaseConstService::BAG_STATUS_1) {
            throw new BusinessLogicException('状态错误');
        }
        if ($bag['next_warehouse_id'] !== $shift['next_warehouse_id']) {
            throw new BusinessLogicException('袋号与下一站不一致');
        }
        $this->getBagService()->updateById($bag['id'], [
            'status' => BaseConstService::BAG_STATUS_2,
            'shift_no' => $shift['shift_no'],
            'load_time' => now()->format('Y-m-d H:i:s'),
            'load_operator' => auth()->user()->fullname,
            'load_operator_id' => auth()->user()->id,
        ]);
        //更新袋子里的包裹
        $row = $this->getTrackingPackageService()->update(['bag_no' => $bag['bag_no']], [
            'status' => BaseConstService::TRACKING_PACKAGE_STATUS_3,
        ]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        return [
            'shift_type' => BaseConstService::SHIFT_LOAD_TYPE_2,
            'item_no' => $bag['bag_no'],
            'shift_no' => $shift['shift_no'],
            'car_no' => $shift['car_no'],
            'next_warehouse_name' => $bag['next_warehouse_id']
        ];
    }

    /**
     * 包裹装车
     * @param $trackingPackage
     * @param $shift
     * @return array
     * @throws BusinessLogicException
     */
    public function loadTrackingPackage($trackingPackage, $shift)
    {
        if ($trackingPackage['status'] == BaseConstService::TRACKING_PACKAGE_STATUS_3) {
            throw new BusinessLogicException('重复扫描');
        }
        if (!in_array($trackingPackage['status'], [BaseConstService::TRACKING_PACKAGE_STATUS_1, BaseConstService::TRACKING_PACKAGE_STATUS_2])) {
            throw new BusinessLogicException('状态错误');
        }
        if ($trackingPackage['next_warehouse_id'] !== $shift['next_warehouse_id']) {
            throw new BusinessLogicException('袋号与下一站不一致');
        }
        $row = $this->getTrackingPackageService()->updateById($trackingPackage['id'], [
            'status' => BaseConstService::TRACKING_PACKAGE_STATUS_2,
            'shift_no' => $shift['shift_no'],
            'load_time' => now(),
            'load_operator' => auth()->user()->fullname,
            'load_operator_id' => auth()->user()->id,
        ]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        return [
            'shift_type' => BaseConstService::SHIFT_LOAD_TYPE_1,
            'item_no' => $trackingPackage['express_first_no'],
            'shift_no' => $shift['shift_no'],
            'car_no' => $shift['car_no'],
            'next_warehouse_name' => $trackingPackage['next_warehouse_id']
        ];
    }

    /**
     * 重新计算袋号信息
     * @param $id
     * @throws BusinessLogicException
     */
    public function recount($id)
    {
        $shift = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($shift)) {
            throw new BusinessLogicException('数据不存在');
        }
        $trackingPackageList = $this->getTrackingPackageService()->getList(['shift_no' => $shift['shift_no']], ['weight'], false);
        $totalWeight = $trackingPackageList->sum('weight');
        $totalCount = count($trackingPackageList);
        $this->updateById($id, ['weight' => $totalWeight, 'package_count' => $totalCount]);
    }

    /**
     * 通过ID 修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }

    /**
     * 移除装车扫描
     * @param $id
     * @param $data
     * @throws BusinessLogicException
     */
    public function removeItem($id, $data)
    {
        $shift = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($shift)) {
            throw new BusinessLogicException('数据不存在');
        }
        if (!empty($data['shift_type']) && $data['shift_type'] == BaseConstService::SHIFT_LOAD_TYPE_1) {
            $trackingPackage = $this->getTrackingPackageService()->getInfo(['express_first_no' => $data['item_no']], ['*'], false);
            if (!empty($trackingPackage) || $trackingPackage['status'] == BaseConstService::TRACKING_PACKAGE_STATUS_2) {
                $row = $this->getTrackingPackageService()->updateById($trackingPackage['id'], [
                    'status' => BaseConstService::TRACKING_PACKAGE_STATUS_2,
                    'shift_no' => '',
                    'load_time' => null,
                    'load_operator' => '',
                    'load_operator_id' => null,
                ]);
                if ($row == false) {
                    throw new BusinessLogicException('操作失败');
                }
            }
        } else {
            $bag = $this->getBagService()->getInfo(['bag_no' => $data['item_no']], ['*'], false);
            if (!empty($bag) || $bag['status'] == BaseConstService::BAG_STATUS_2) {
                $row = $this->getBagService()->updateById($bag['id'], [
                    'status' => BaseConstService::BAG_STATUS_1,
                    'shift_no' => '',
                    'load_time' => null,
                    'load_operator' => '',
                    'load_operator_id' => null,
                ]);
                $row = $this->getTrackingPackageService()->update(['bag_no' => $bag['bag_no']], [
                    'status' => BaseConstService::TRACKING_PACKAGE_STATUS_2,
                ]);
                if ($row == false) {
                    throw new BusinessLogicException('操作失败');
                }
            }
        }
        $this->recount($id);
    }

    /**
     * 卸车扫描
     * @param $id
     * @param $data
     * @return Builder|mixed
     * @throws BusinessLogicException
     */
    public function unloadItem($id, $data)
    {
        $shift = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($shift)) {
            throw new BusinessLogicException('数据不存在');
        }
        $trackingPackage = $this->getTrackingPackageService()->getInfo(['express_first_no' => $data['item_no'], 'shift_no' => $shift['shift_no']], ['*'], false);
        $bag = $this->getBagService()->getInfo(['bag_no' => $data['item_no'], 'shift_no' => $shift['shift_no']], ['*'], false);
        if (!empty($trackingPackage) && empty($bag)) {
            $info = $this->unloadTrackingPackage($trackingPackage, $shift);
        } elseif (empty($trackingPackage) && !empty($bag)) {
            $info = $this->unloadBag($bag, $shift);
        } elseif (empty($trackingPackage) && empty($bag)) {
            throw new BusinessLogicException('包裹或袋号不属于该车次');
        } else {
            //同时存在时，不带is_bag参数报错。5007错误通知手持端加上is_bag参数重新请求
            if (empty($data['is_bag'])) {
                throw new BusinessLogicException('包裹号和袋号同时存在', 5007);
            } elseif ($data['is_bag'] == BaseConstService::YES) {
                //is_bag为1时取袋
                $info = $this->unloadBag($bag, $shift);
            } else {
                //is_bag为2时取包裹
                $info = $this->unloadTrackingPackage($trackingPackage, $shift);
            }
        }
        $this->emptyCheck($shift);
        return $info;
    }

    /**
     * 包裹卸车
     * @param $trackingPackage
     * @param $shift
     * @return array
     * @throws BusinessLogicException
     */
    public function unloadTrackingPackage($trackingPackage, $shift)
    {

        if ($trackingPackage['status'] == BaseConstService::TRACKING_PACKAGE_STATUS_6) {
            throw new BusinessLogicException('重复扫描');
        }
        if (!in_array($trackingPackage['status'], [BaseConstService::TRACKING_PACKAGE_STATUS_4, BaseConstService::TRACKING_PACKAGE_STATUS_5])) {
            throw new BusinessLogicException('状态错误');
        }
        $row = $this->getTrackingPackageService()->updateById($trackingPackage['id'], [
            'status' => BaseConstService::TRACKING_PACKAGE_STATUS_7,
            'shift_no' => $shift['shift_no'],
            'unload_time' => now(),
            'unload_operator' => auth()->user()->fullname,
            'unload_operator_id' => auth()->user()->id,
        ]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        return [
            'shift_type' => BaseConstService::SHIFT_LOAD_TYPE_1,
            'item_no' => $trackingPackage['express_first_no'],
            'shift_no' => $shift['shift_no'],
        ];
    }

    /**
     * 袋号卸车
     * @param $bag
     * @param $shift
     * @return array
     * @throws BusinessLogicException
     */
    public function unloadBag($bag, $shift)
    {
        if ($bag['status'] == BaseConstService::BAG_STATUS_5) {
            throw new BusinessLogicException('重复扫描');
        }
        if ($bag['status'] !== BaseConstService::BAG_STATUS_4) {
            throw new BusinessLogicException('状态错误');
        }
        $row = $this->getBagService()->updateById($bag['id'], [
            'status' => BaseConstService::BAG_STATUS_5,
            'unload_time' => now(),
            'unload_operator' => auth()->user()->fullname,
            'unload_operator_id' => auth()->user()->id,
        ]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        //更新袋子里的包裹
        $row = $this->getTrackingPackageService()->update(['bag_no', $bag['bag_no']], [
            'status' => BaseConstService::TRACKING_PACKAGE_STATUS_6
        ]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        return [
            'shift_type' => BaseConstService::SHIFT_LOAD_TYPE_2,
            'item_no' => $bag['bag_no'],
            'shift_no' => $shift['shift_no'],
        ];
    }

    /**
     * 车次卸完检查
     * @param $shift
     * @throws BusinessLogicException
     */
    public function emptyCheck($shift)
    {
        $trackingPackageList = $this->getTrackingPackageService()->getList([
            'shift_no' => $shift['shift_no'],
            'status' => BaseConstService::TRACKING_PACKAGE_STATUS_5
        ], ['*'], false);
        $bag = $this->getBagService()->getList([
            'shift_no' => $shift['shift_no'],
            'status' => BaseConstService::BAG_STATUS_4
        ], ['*'], false);
        if (empty($trackingPackageList) && empty($bag)) {
            $row = parent::updateById($shift['id'], [
                'status' => BaseConstService::SHIFT_STATUS_4
            ]);
            if ($row == false) {
                throw new BusinessLogicException('操作失败');
            }
        }
    }

    /**
     * 批量卸车扫描
     * @param $id
     * @param $data
     * @return string
     * @throws BusinessLogicException
     */
    public function unloadItemList($id, $data)
    {
        $shift = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($shift)) {
            throw new BusinessLogicException('数据不存在');
        }
        foreach ($data as $k => $v) {
            if ($v['shift_type'] == BaseConstService::SHIFT_LOAD_TYPE_1) {
                $trackingPackage = $this->getTrackingPackageService()->getInfo(['express_first_no' => $v['item_no'], 'shift_no' => $shift['shift_no']], ['*'], false);
                $this->unloadTrackingPackage($trackingPackage, $shift);
            } else {
                $bag = $this->getBagService()->getInfo(['bag_no' => $v['bag_no'], 'shift_no' => $shift['shift_no']], ['*'], false);
                $this->unloadBag($bag, $shift);
            }
        }
        $this->emptyCheck($shift);
        return;
    }

    /**
     * 车次发车
     * @param $id
     * @throws BusinessLogicException
     */
    public function outWarehouse($id)
    {
        $shift = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($shift)) {
            throw new BusinessLogicException('数据不存在');
        }
        $row = parent::updateById($id, [
            'status' => BaseConstService::SHIFT_STATUS_2,
            'begin_time' => now()
        ]);
        if ($row == false) {
            throw new BusinessLogicException('出车失败1');
        }
        $trackingPackage = $this->getTrackingPackageService()->getList(['shift_no' => $shift['shift_no']], ['*'], false);
        if ($trackingPackage->isNotEmpty()) {
            $row = $this->getTrackingPackageService()->update(['shift_no' => $shift['shift_no']], ['status' => BaseConstService::TRACKING_PACKAGE_STATUS_4]);
            if ($row == false) {
                throw new BusinessLogicException('出车失败2');
            }
        }
        $bag = $this->getBagService()->getList(['shift_no' => $shift['shift_no']], ['*'], false);
        if ($bag->isNotEmpty()) {
            $row = $this->getBagService()->update(['shift_no' => $shift['shift_no']], ['status' => BaseConstService::BAG_STATUS_3]);
            if ($row == false) {
                throw new BusinessLogicException('出车失败3');
            }
        }
    }

    /**
     * 车次到车
     * @param $id
     * @throws BusinessLogicException
     */
    public function inWarehouse($id)
    {
        $shift = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($shift)) {
            throw new BusinessLogicException('数据不存在');
        }
        $row = parent::updateById($id, [
            'status' => BaseConstService::SHIFT_STATUS_3,
            'end_time' => now()
        ]);
        if ($row == false) {
            throw new BusinessLogicException('到车失败');
        }
        $this->getTrackingPackageService()->update(['shift_no' => $shift['shift_no']], ['status' => BaseConstService::TRACKING_PACKAGE_STATUS_5]);
        $this->getBagService()->update(['shift_no' => $shift['shift_no']], ['status' => BaseConstService::BAG_STATUS_4]);

    }
}
