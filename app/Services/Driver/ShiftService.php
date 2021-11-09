<?php


namespace App\Services\Driver;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\ShiftResource;
use App\Models\Shift;
use App\Services\BaseConstService;
use App\Services\PackageTrailService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ShiftService extends BaseService
{
    public $filterRules = [
        'status' => ['=', 'status'],
        'shift_no' => ['=', 'shift_no']
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(Shift $model)
    {
        parent::__construct($model, ShiftResource::class);
    }

    /**
     * 查询
     * @return Collection
     * @throws BusinessLogicException
     */
    public function getPageList()
    {
        $data = parent::getPageList();
        if ($data->isEmpty() && $this->formData['status'] == BaseConstService::SHIFT_STATUS_2) {
            throw new BusinessLogicException('当前无已发车车次');
        }
        return $data;
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
        if ($info['status'] == BaseConstService::SHIFT_STATUS_3) {
            $info['tracking_package_list'] = $this->getTrackingPackageService()->query->where('shift_no', $info['shift_no'])->where('status', BaseConstService::TRACKING_PACKAGE_STATUS_5)->where('bag_no', '')->get();
            $info['bag_list'] = $this->getBagService()->getList(['shift_no' => $info['shift_no'], 'status' => BaseConstService::BAG_STATUS_3], ['*'], false);
        } else {
            $info['tracking_package_list'] = $this->getTrackingPackageService()->query->where('shift_no', $info['shift_no'])->where('bag_no', '')->get();
            $info['bag_list'] = $this->getBagService()->getList(['shift_no' => $info['shift_no']], ['*'], false);
        }
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
                $itemList[$k] = Arr::only($v, ['item_no', 'next_warehouse_name', 'weight', 'package_count', 'shift_type']);
            }
        }
        if (!empty($itemList)) {
            $info['item_list'] = array_values($itemList->toArray());
        } else {
            $info['item_list'] = [];
        }
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
            'status' => BaseConstService::TRACKING_PACKAGE_STATUS_1,
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
            $this->getTrackingPackageService()->update(['bag_no' => ['in', $bagList->pluck(['bag_no'])->toArray()]], [
                'status' => BaseConstService::TRACKING_PACKAGE_STATUS_2,
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
     * @param int $ignore_rule
     * @throws BusinessLogicException
     */
//    public function check(&$bag, $trackingPackage, $ignore_rule = BaseConstService::NO)
//    {
//        if (empty($trackingPackage) || in_array($trackingPackage['status'], [
//                BaseConstService::TRACKING_PACKAGE_STATUS_4,
//                BaseConstService::TRACKING_PACKAGE_STATUS_5,
//                BaseConstService::TRACKING_PACKAGE_STATUS_6,
//                BaseConstService::TRACKING_PACKAGE_STATUS_7,
//            ])) {
//            throw new BusinessLogicException('包裹状态错误');
//        }
//        if ($trackingPackage['status'] == BaseConstService::TRACKING_PACKAGE_STATUS_2) {
//            throw new BusinessLogicException('包裹已装袋，请勿重复扫描');
//        }
//        if ($trackingPackage['status'] == BaseConstService::TRACKING_PACKAGE_STATUS_3) {
//            throw new BusinessLogicException('包裹已装车，不允许装袋');
//        }
//        if ($bag['next_warehouse_id'] !== $trackingPackage['next_warehouse_id'] && $ignore_rule) {
//            throw new BusinessLogicException('包裹与袋号的下一站不一致',5009);
//        }
//    }

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
        $trackingPackage = $this->getTrackingPackageService()->getInfo(['express_first_no' => $data['item_no']], ['*'], false, ['id' => 'desc']);
        if (!empty($trackingPackage) && $trackingPackage['status'] == BaseConstService::TRACKING_PACKAGE_STATUS_2) {
            throw new BusinessLogicException('包裹已装袋，请扫描袋号');
        }
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
                throw new BusinessLogicException('包裹号和袋号同时存在，请进行选择', 5007);
            } elseif ($data['is_bag'] == BaseConstService::YES) {
                //is_bag为1时取袋
                $info = $this->loadBag($bag, $shift, $data['ignore_rule']);
            } else {
                //is_bag为2时取包裹
                $info = $this->loadTrackingPackage($trackingPackage, $shift, $data['ignore_rule']);
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
     * @param int $ignoreRule
     * @return array
     * @throws BusinessLogicException
     */
    public function loadBag($bag, $shift, $ignoreRule = BaseConstService::NO)
    {
        if ($bag['status'] == BaseConstService::BAG_STATUS_2) {
            throw new BusinessLogicException('重复扫描');
        }
        if ($bag['status'] !== BaseConstService::BAG_STATUS_1) {
            throw new BusinessLogicException('状态错误');
        }
        if ($bag['next_warehouse_id'] !== $shift['next_warehouse_id'] && $ignoreRule == BaseConstService::NO) {
            throw new BusinessLogicException('袋号与下一站不一致', 5009);
        }
        $data = [
            'shift_no' => $shift['shift_no'],
            'load_time' => now()->format('Y-m-d H:i:s'),
            'load_operator' => auth()->user()->fullname,
            'load_operator_id' => auth()->user()->id,
        ];

        //更新袋子里的包裹
        $row = $this->getTrackingPackageService()->update(['bag_no' => $bag['bag_no']], [
            'status' => BaseConstService::TRACKING_PACKAGE_STATUS_3,
            'shift_no' => $shift['shift_no']
        ]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        $row = $this->getBagService()->update(['bag_no' => $bag['bag_no']], $data);
        if ($row == false) {
            throw new BusinessLogicException('更新袋号失败');
        }
        $bag = array_merge($bag->toArray(), $data);
        PackageTrailService::storeByBag($bag, BaseConstService::PACKAGE_TRAIL_LOAD);
        return [
            'shift_type' => BaseConstService::SHIFT_LOAD_TYPE_2,
            'item_no' => $bag['bag_no'],
            'shift_no' => $shift['shift_no'],
            'car_no' => $shift['car_no'],
            'next_warehouse_name' => $bag['next_warehouse_name'],
            'weight' => $bag['weight'],
            'package_count' => $bag['package_count']
        ];
    }

    /**
     * 包裹装车
     * @param $trackingPackage
     * @param $shift
     * @param int $ignoreRule
     * @return array
     * @throws BusinessLogicException
     */
    public function loadTrackingPackage($trackingPackage, $shift, $ignoreRule = BaseConstService::NO)
    {
        if ($trackingPackage['status'] == BaseConstService::TRACKING_PACKAGE_STATUS_3) {
            throw new BusinessLogicException('重复扫描');
        }
        if (!in_array($trackingPackage['status'], [BaseConstService::TRACKING_PACKAGE_STATUS_1, BaseConstService::TRACKING_PACKAGE_STATUS_2])) {
            throw new BusinessLogicException('状态错误');
        }
        if ($trackingPackage['next_warehouse_id'] !== $shift['next_warehouse_id'] && $ignoreRule == BaseConstService::NO) {
            throw new BusinessLogicException('袋号与下一站不一致', 5009);
        }
        $data = [
            'status' => BaseConstService::TRACKING_PACKAGE_STATUS_3,
            'shift_no' => $shift['shift_no'],
            'load_time' => now(),
            'load_operator' => auth()->user()->fullname,
            'load_operator_id' => auth()->user()->id,
        ];
        $row = $this->getTrackingPackageService()->update(['id' => $trackingPackage['id']], $data);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        $shift = array_merge($shift->toArray(), $data);
        PackageTrailService::storeByTrackingPackageList([$trackingPackage], BaseConstService::PACKAGE_TRAIL_LOAD, $shift);
        return [
            'shift_type' => BaseConstService::SHIFT_LOAD_TYPE_1,
            'item_no' => $trackingPackage['express_first_no'],
            'shift_no' => $shift['shift_no'],
            'car_no' => $shift['car_no'],
            'next_warehouse_name' => $trackingPackage['next_warehouse_name'],
            'weight' => $trackingPackage['weight']
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
        parent::updateById($id, ['weight' => $totalWeight, 'package_count' => $totalCount]);
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
            $trackingPackage = $this->getTrackingPackageService()->getInfo(['express_first_no' => $data['item_no']], ['*'], false, ['id' => 'desc']);
            if (!empty($trackingPackage) && $trackingPackage['status'] == BaseConstService::TRACKING_PACKAGE_STATUS_3) {
                $row = $this->getTrackingPackageService()->update(['id' => $trackingPackage['id']], [
                    'status' => BaseConstService::TRACKING_PACKAGE_STATUS_1,
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
            if (!empty($bag) && $bag['status'] == BaseConstService::BAG_STATUS_1) {
                $row = $this->getBagService()->update(['bag_no' => $bag['bag_no']], [
                    'status' => BaseConstService::BAG_STATUS_1,
                    'shift_no' => '',
                    'load_time' => null,
                    'load_operator' => '',
                    'load_operator_id' => null,
                ]);
                if ($row == false) {
                    throw new BusinessLogicException('删除失败');
                }
                $row = $this->getTrackingPackageService()->update(['bag_no' => $bag['bag_no']], [
                    'shift_no' => '',
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
        $trackingPackage = $this->getTrackingPackageService()->getInfo(['express_first_no' => $data['item_no'], 'shift_no' => $shift['shift_no']], ['*'], false, ['id' => 'desc']);
        $bag = $this->getBagService()->getInfo(['bag_no' => $data['item_no'], 'shift_no' => $shift['shift_no']], ['*'], false);
        if (!empty($trackingPackage) && $trackingPackage['status'] == BaseConstService::TRACKING_PACKAGE_STATUS_5) {
            throw new BusinessLogicException('包裹已装袋，请扫描袋号卸袋');
        }
        if (!empty($trackingPackage) && empty($bag)) {
            $info = $this->unloadTrackingPackage($trackingPackage, $shift);
        } elseif (empty($trackingPackage) && !empty($bag)) {
            $info = $this->unloadBag($bag, $shift);
        } elseif (empty($trackingPackage) && empty($bag)) {
            throw new BusinessLogicException('包裹或袋号不属于该车次');
        } else {
            //同时存在时，不带is_bag参数报错。5007错误通知手持端加上is_bag参数重新请求
            if (empty($data['is_bag'])) {
                throw new BusinessLogicException('包裹号和袋号同时存在，请进行选择', 5007);
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

        if (in_array($trackingPackage['status'], [BaseConstService::TRACKING_PACKAGE_STATUS_6, BaseConstService::TRACKING_PACKAGE_STATUS_7])) {
            throw new BusinessLogicException('重复扫描');
        }
        if (!in_array($trackingPackage['status'], [BaseConstService::TRACKING_PACKAGE_STATUS_4, BaseConstService::TRACKING_PACKAGE_STATUS_5])) {
            throw new BusinessLogicException('状态错误');
        }
        $data = [
            'status' => BaseConstService::TRACKING_PACKAGE_STATUS_7,
            'shift_no' => $shift['shift_no'],
            'unload_time' => now(),
            'unload_operator' => auth()->user()->fullname,
            'unload_operator_id' => auth()->user()->id,
        ];
        $row = $this->getTrackingPackageService()->update(['id' => $trackingPackage['id']], $data);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        $shift = array_merge($shift->toArray(), $data);
        PackageTrailService::storeByTrackingPackageList([$trackingPackage], BaseConstService::PACKAGE_TRAIL_UNLOAD, $shift);
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
        if ($bag['status'] == BaseConstService::BAG_STATUS_4) {
            throw new BusinessLogicException('重复扫描');
        }
        if ($bag['status'] !== BaseConstService::BAG_STATUS_3) {
            throw new BusinessLogicException('状态错误');
        }
        $data = [
            'status' => BaseConstService::BAG_STATUS_4,
            'unload_time' => now(),
            'unload_operator' => auth()->user()->fullname,
            'unload_operator_id' => auth()->user()->id,
        ];
        $this->getBagService()->update(['id' => $bag['id']], $data);
        $bag = array_merge($bag->toArray(), $data);
        //更新袋子里的包裹
        $row = $this->getTrackingPackageService()->update(['bag_no' => $bag['bag_no']], [
            'status' => BaseConstService::TRACKING_PACKAGE_STATUS_6
        ]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        PackageTrailService::storeByBag($bag, BaseConstService::PACKAGE_TRAIL_UNLOAD);
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
            'status' => BaseConstService::BAG_STATUS_3
        ], ['*'], false);
        if ($trackingPackageList->isEmpty() && $bag->isEmpty()) {
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
        foreach ($data['item_list'] as $k => $v) {
            if ($v['shift_type'] == BaseConstService::SHIFT_LOAD_TYPE_1) {
                $trackingPackage = $this->getTrackingPackageService()->getInfo(['express_first_no' => $v['item_no'], 'shift_no' => $shift['shift_no']], ['*'], false, ['id' => 'desc']);
                if (!empty($trackingPackage)) {
                    $this->unloadTrackingPackage($trackingPackage, $shift);
                }
            } else {
                $bag = $this->getBagService()->getInfo(['bag_no' => $v['item_no'], 'shift_no' => $shift['shift_no']], ['*'], false);
                if (!empty($bag)) {
                    $this->unloadBag($bag, $shift);
                }
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
            throw new BusinessLogicException('出车失败');
        }
        $trackingPackageList = $this->getTrackingPackageService()->getList(['shift_no' => $shift['shift_no']], ['*'], false);
        if ($trackingPackageList->isNotEmpty()) {
            $row = $this->getTrackingPackageService()->update(['shift_no' => $shift['shift_no']], ['status' => BaseConstService::TRACKING_PACKAGE_STATUS_4]);
            if ($row == false) {
                throw new BusinessLogicException('出车失败');
            }
        }
        $bag = $this->getBagService()->getList(['shift_no' => $shift['shift_no']], ['*'], false);
        if ($bag->isNotEmpty()) {
            $row = $this->getBagService()->update(['shift_no' => $shift['shift_no']], ['status' => BaseConstService::BAG_STATUS_2]);
            if ($row == false) {
                throw new BusinessLogicException('出车失败');
            }
        }
        //包裹出库
        $this->getStockService()->trackingPackageOutWarehouse($trackingPackageList, $shift);
        PackageTrailService::storeByShift($shift, BaseConstService::PACKAGE_TRAIL_OUT);
    }

    /**
     * 车次到车
     * @param $id
     * @return array|Builder|Model|object|null
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
        $this->getBagService()->update(['shift_no' => $shift['shift_no']], ['status' => BaseConstService::BAG_STATUS_3]);
        PackageTrailService::storeByShift($shift, BaseConstService::PACKAGE_TRAIL_IN);
        return $shift;
    }


}
