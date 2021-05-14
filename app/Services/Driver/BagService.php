<?php


namespace App\Services\Driver;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\BagResource;
use App\Models\Bag;
use App\Services\BaseConstService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BagService extends BaseService
{
    public $filterRules = [
        'status' => ['=', 'status'],
        'bag_no' => ['like', 'bag_no']
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(Bag $model)
    {
        parent::__construct($model, BagResource::class);
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
        $info['tracking_package_list'] = $this->getTrackingPackageService()->getList(['bag_no' => $info['bag_no']], ['*'], false);
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
        $data['bag_no'] = $this->getOrderNoRuleService()->createBagNo();
        $data['warehouse_id'] = auth()->user()->warehouse_id;
        $data['warehouse_name'] = $this->getWareHouseService()->getInfo(['id' => auth()->user()->warehouse_id], ['*'], false);
        if (!empty($data['warehouse_name'])) {
            $data['warehouse_name'] = $data['warehouse_name']['name'];
        } else {
            $data['warehouse_name'] = '';
        }
        $data['next_warehouse_name'] = $this->getWareHouseService()->getInfo(['id' => $data['next_warehouse_id']], ['*'], false);
        if (!empty($data['next_warehouse_name'])) {
            $data['next_warehouse_name'] = $data['next_warehouse_name']['name'];
        } else {
            $data['next_warehouse_name'] = '';
        }
        $rowCount = parent::create($data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        return [
            'bag_no' => $data['bag_no'],
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
        $bag = parent::getInfo(['id' => $id], ['*'], false);
        if ($bag === false) {
            throw new BusinessLogicException('袋号不存在');
        }
        if ($bag['status'] !== BaseConstService::BAG_STATUS_1) {
            throw new BusinessLogicException('只有未发车的袋号才能删除');
        }
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败，请重新操作');
        }
        $this->getTrackingPackageService()->update(['bag_no' => $bag['bag_no']], [
            'status' => BaseConstService::TRACKING_PACKAGE_STATUS_1,
            'bag_no' => '',
            'pack_time' => null,
            'pack_operator' => '',
            'pack_operator_id' => '',
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败，请重新操作');
        }
    }

    /**
     * 包裹扫描
     * @param $id
     * @param $data
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function packPackage($id, $data)
    {
        $bag = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($bag)) {
            throw new BusinessLogicException('数据不存在');
        }
        $package = $this->getPackageService()->getInfo(['express_first_no' => $data['express_first_no']], ['*'], false);
        if (empty($package)) {
            throw new BusinessLogicException('包裹不存在');
        }
        $trackingPackage = $this->getTrackingPackageService()->getInfo(['express_first_no' => $data['express_first_no']], ['*'], false);
        if (empty($trackingPackage)) {
            throw new BusinessLogicException('包裹阶段错误');
        }
        $this->check($bag, $trackingPackage);
        $row = $this->getTrackingPackageService()->updateById($trackingPackage['id'], [
            'status' => BaseConstService::TRACKING_PACKAGE_STATUS_2,
            'bag_no' => $bag['bag_no'],
            'pack_time' => now(),
            'pack_operator' => auth()->user()->fullname,
            'pack_operator_id' => auth()->user()->id,
        ]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        $this->recount($id);
        return $trackingPackage;
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
     * 重新计算袋号信息
     * @param $id
     * @throws BusinessLogicException
     */
    public function recount($id)
    {
        $bag = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($bag)) {
            throw new BusinessLogicException('数据不存在');
        }
        $trackingPackageList = $this->getTrackingPackageService()->getList(['bag_no' => $bag['bag_no']], ['weight'], false);
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
     * 移除包裹扫描
     * @param $id
     * @param $data
     * @throws BusinessLogicException
     */
    public function removePackage($id, $data)
    {
        $bag = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($bag)) {
            throw new BusinessLogicException('数据不存在');
        }
        $package = $this->getPackageService()->getInfo(['express_first_no' => $data['express_first_no']], ['*'], false);
        if (empty($package)) {
            throw new BusinessLogicException('包裹不存在');
        }
        $trackingPackage = $this->getTrackingPackageService()->getInfo(['express_first_no' => $data['express_first_no']], ['*'], false);
        if (!empty($trackingPackage) || $trackingPackage['status'] == BaseConstService::TRACKING_PACKAGE_STATUS_2) {
            $row = $this->getTrackingPackageService()->updateById($trackingPackage['id'], [
                'status' => BaseConstService::TRACKING_PACKAGE_STATUS_1,
                'bag_no' => '',
                'pack_time' => null,
                'pack_operator' => '',
                'pack_operator_id' => null,
            ]);

            if ($row == false) {
                throw new BusinessLogicException('操作失败');
            }
        }
        $this->recount($id);
    }

    /**
     * 批量包裹拆袋
     * @param $id
     * @param $data
     * @return Builder|mixed
     * @throws BusinessLogicException
     */
    public function unpackPackageList($id, $data)
    {
        $bag = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($bag)) {
            throw new BusinessLogicException('数据不存在');
        }
        $trackingPackageList = $this->getTrackingPackageService()->getList(['express_first_no' => $data['express_first_no_list']], ['*'], false);
        foreach ($trackingPackageList as $k) {
            if ($k['bag_no'] !== $bag['bag_no']) {
                throw new BusinessLogicException('包裹不属于该袋号');
            }
        }
        if (!empty($trackingPackageList)) {
            $row = $this->getTrackingPackageService()->update(['id' => ['in', $trackingPackageList->pluck('id')->toArray()]], [
                'status' => BaseConstService::TRACKING_PACKAGE_STATUS_7,
                'unpack_time' => now(),
                'unpack_operator' => auth()->user()->fullname,
                'unpack_operator_id' => auth()->user()->id,
            ]);
            if ($row == false) {
                throw new BusinessLogicException('操作失败');
            }
        }
        $this->emptyCheck($bag);
        if (count($trackingPackageList) == 1) {
            return $trackingPackageList[0];
        }
    }

    /**
     * 车次卸完检查
     * @param $bag
     * @throws BusinessLogicException
     */
    public function emptyCheck($bag)
    {
        $trackingPackageList = $this->getTrackingPackageService()->getList([
            'bag_no' => $bag['bag_no'],
            'status' => BaseConstService::TRACKING_PACKAGE_STATUS_6
        ], ['*'], false);

        if ($trackingPackageList->isEmpty()) {
            $row = parent::updateById($bag['id'], [
                'status' => BaseConstService::BAG_STATUS_5
            ]);
            if ($row == false) {
                throw new BusinessLogicException('操作失败');
            }
        }
    }
}
