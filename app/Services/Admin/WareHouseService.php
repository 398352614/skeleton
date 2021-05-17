<?php
/**
 * 网点服务
 * User: long
 * Date: 2019/12/21
 * Time: 11:21
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\WareHouseResource;
use App\Models\Institution;
use App\Models\Warehouse;
use App\Services\CommonService;
use App\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WareHouseService
 * @package App\Services\Admin
 */
class WareHouseService extends BaseService
{
    /**
     * @var \string[][]
     */
    public $filterRules = [
        'country' => ['=', 'country'],
        'acceptance_type' => ['like', 'acceptance_type']
    ];

    /**
     * WareHouseService constructor.
     * @param Warehouse $warehouse
     */
    public function __construct(Warehouse $warehouse)
    {
        parent::__construct($warehouse, WareHouseResource::class, WareHouseResource::class);
    }

    /**
     * 获取详情
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
        $parentWarehouse = parent::getInfo(['id' => $info['parent']], ['*'], false);
        if (!empty($parentWarehouse)) {
            $info['parent_name'] = $parentWarehouse['name'];
        }
        return $info;
    }

    /**
     * 新增
     * @param $params
     * @return Builder|Model
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $this->checkDistance($params['parent']);
        $this->fillData($params);

        $warehouse = parent::create($params);

        $warehouse->moveTo($params['parent']);

        return $warehouse;
    }

    /**
     * 通过ID修改
     * @param $id
     * @param $data
     * @return void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $dbData = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($dbData)) {
            throw new BusinessLogicException('数据不存在');
        }
//        $lineIdList = $data['line_ids'];
//        if (empty($lineIdList)) {
//            $lineIdList = [];
//        } else {
//            $lineIdList = explode(',', $lineIdList);
//        }
        //不修改line_ids
//                $this->check($dbData, $lineIdList);
        $this->fillData($data, $dbData->toArray());
        unset($data['created_at'], $data['updated_at']);
        $data['line_ids'] = $dbData['line_ids'];
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('网点修改失败，请重新操作');
        }
        return;
//        //增加，更新线路的网点ID
//        $this->getLineService()->updateWarehouse($dbData['id'], $lineIdList);
//        //减少，更新线路的上级网点ID
//        $parentWarehouse = parent::getInfo(['id' => $dbData['parent']], ['*'], false);
//        if (empty($parentWarehouse)) {
//            return;
//        }
//
//        $less = [];
//        //检查每个原线路列表，如果没出现在新线路列表中，则代表删去了，应回归上级网点
//        foreach (explode(',', $dbData['line_ids']) as $k => $v) {
//            if (!in_array($v, explode(',', $data['line_ids']))) {
//                $less[] = $v;
//            }
//        }
//        $this->getLineService()->updateWarehouse($dbData['parent'], $less);
////        $row = parent::update(['id' => $dbData['parent']], ['line_ids' => $parentWarehouse['line_ids']]);
////        if ($row == false) {
////            throw new BusinessLogicException('操作失败');
////        }
    }

    /**
     * 批量新增线路
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function addLineList($id, $params)
    {
        $dbData = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($dbData)) {
            throw new BusinessLogicException('数据不存在');
        }
        if (empty($params['line_ids'])) {
            $lineIdList = [];
        } else {
            $lineIdList = explode(',', $params['line_ids']);
        }
        $this->check($dbData, $lineIdList);
        if ($dbData['line_ids'] == '') {
            $lineIds = $params['line_ids'];
        } else {
            $lineIds = $dbData['line_ids'] . ',' . $params['line_ids'];
        }
        $rowCount = parent::updateById($id, ['line_ids' => $lineIds]);
        if ($rowCount === false) {
            throw new BusinessLogicException('网点修改失败，请重新操作');
        }
        //增加，更新线路的网点ID
        $addList = explode(',', $params['line_ids']);
        $this->getLineService()->updateWarehouse($id, $addList);

    }

    /**
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function removeLineList($id, $params)
    {
        $dbData = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($dbData)) {
            throw new BusinessLogicException('数据不存在');
        }
        $removeLineList = explode(',', $params['line_ids']);
        $childWarehouseList = parent::getList(['parent' => $dbData['id']], ['*'], false);
        if (!empty($childWarehouseList)) {
            $childWarehouseList = $childWarehouseList->toArray();
            foreach ($childWarehouseList as $k => $v) {
                if (!empty($v['line_ids']) && array_intersect($removeLineList, explode(',', $v['line_ids']))) {
                    throw new BusinessLogicException('请先删除下级网点的该线路');
                }
            }
        }

        $lineList = explode(',', $dbData['line_ids']);
        $newLineList = [];
        foreach ($lineList as $K => $v) {
            if (!in_array($v, $removeLineList)) {
                $newLineList[] = $v;
            }
        }
        $newLineList = implode(',', $newLineList);
        $rowCount = parent::updateById($id, ['line_ids' => $newLineList]);
        if ($rowCount === false) {
            throw new BusinessLogicException('网点修改失败，请重新操作');
        }
        //减少，更新线路的上级网点ID
        $parentWarehouse = parent::getInfo(['id' => $dbData['parent']], ['*'], false);
        if (empty($parentWarehouse)) {
            return;
        }
        $less = explode(',', $params['line_ids']);
        $this->getLineService()->updateWarehouse($dbData['parent'], $less);
    }

    /**
     * 填充数据
     * @param $params
     * @param $dbInfo
     */
    private function fillData(&$params, $dbInfo = [])
    {
        //填充地址
        $params['country'] = !empty($dbInfo['country']) ? $dbInfo['country'] : CompanyTrait::getCountry();
        if ((CompanyTrait::getAddressTemplateId() == 1) || empty($params['address'])) {
            $params['address'] = CommonService::addressFieldsSortCombine($params, ['country', 'city', 'street', 'house_number', 'post_code']);
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        //删除网点前 先验证线路是否存在
        $warehouse = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($warehouse)) {
            return;
        }
        /** @var Warehouse $warehouse */
        if ($this->isRoot($warehouse)) {
            throw new BusinessLogicException('无法删除根网点');
        }

        if ($this->hasChildren($warehouse)) {
            throw new BusinessLogicException('请先删除子网点');
        }

        if (!empty($this->getEmployeeService()->getList(['warehouse_id' => $id], ['*'], false)->toArray())) {
            throw new BusinessLogicException('请先删除该网点下的所有员工');
        }
        if (!empty($this->getDriverService()->getList(['warehouse_id' => $id], ['*'], false)->toArray())) {
            throw new BusinessLogicException('请先删除该网点下的所有司机');
        }
        $row = parent::delete(['id' => $id]);
        if ($row === false) {
            throw new BusinessLogicException('网点删除失败，请重新操作');
        }
        $parentWarehouse = parent::getInfo(['id' => $warehouse['parent']], ['*'], false);
        $row = $this->getLineService()->update(['warehouse_id' => $id], ['warehouse_id' => $parentWarehouse['id']]);
        if ($row === false) {
            throw new BusinessLogicException('操作失败');
        }
        if (!empty($warehouse['line_ids'])) {
            $row = parent::updateById($parentWarehouse['id'], ['line_ids' => $parentWarehouse['line_ids'] . ',' . $warehouse['line_ids']]);
            if ($row === false) {
                throw new BusinessLogicException('操作失败');
            }
        }
    }

    /**
     * 获取网点下所有线路
     * @param $id
     * @return Collection
     * @throws BusinessLogicException
     */
    public function getLineList($id)
    {
        $warehouse = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('网点不存在');
        }
        $ids = explode(',', $warehouse['line_ids']);
        return $this->getLineService()->getPageListByIds($ids);
    }

    /**
     * 获取网点可选线路（获取直属上级网点的线路）
     * @param $parentId
     * @return Collection
     */
    public function getAbleLineList($parentId)
    {
        return $this->getLineService()->getPageListByWarehouse($parentId);
    }

//    /**
//     * 添加线路至网点
//     * @param $id
//     * @param $params
//     * @throws BusinessLogicException
//     */
//    public function addLine($id, $params)
//    {
//        $lineIdList = $params['line_ids'];
//        $lineList = $this->getLineService()->getList(['id' => ['in', $lineIdList]], ['*'], false);
//        $warehouse = parent::getInfo(['id' => $id], ['*'], false);
//        $this->check($warehouse, $lineList);
//        //从上级网点移除
//        $parentLineIdList = array_diff($lineIdList, $params['line_ids']);
//        $row = parent::update(['id' => $warehouse->toArray()['parent']], ['line_ids' => $parentLineIdList]);
//        if ($row == false) {
//            throw new BusinessLogicException('操作失败');
//        }
//        $this->getLineService()->updateWarehouse($warehouse['parent'], $parentLineIdList);
//        //新增线路
//        $row = parent::update(['id' => $id], ['line_ids' => $lineIdList]);
//        if ($row == false) {
//            throw new BusinessLogicException('操作失败');
//        }
//        $this->getLineService()->updateWarehouse($warehouse['id'], $lineIdList);
//    }

//    /**
//     * 移除线路
//     * @param $id
//     * @param $params
//     * @throws BusinessLogicException
//     */
//    public function removeLine($id, $params)
//    {
//        $removeLineIdList = $params['line_ids'];
//        $warehouse = parent::getInfo(['id' => $id], ['*'], false);
//        if (empty($warehouse)) {
//            throw new BusinessLogicException('网点不存在');
//        }
//        $warehouse = $warehouse->toArray();
//        $lineIdList = array_diff($warehouse['line_ids'], $removeLineIdList);
//        $parentWarehouse = parent::getInfo(['id' => $warehouse['parent']], ['*'], false);
//        if (empty($parentWarehouse)) {
//            throw new BusinessLogicException('网点不存在');
//        }
//        $parentWarehouse = $parentWarehouse->toArray();
//        $parentLineIdList = array_merge($parentWarehouse['line_ids'], $params['line_ids']);
//        //从上级网点新增
//        $row = parent::update(['id' => $warehouse['parent']], ['line_ids' => $parentLineIdList]);
//        if ($row == false) {
//            throw new BusinessLogicException('操作失败');
//        }
//        $this->getLineService()->updateWarehouse($warehouse['parent'], $parentLineIdList);
//        //移除线路
//        $row = parent::update(['id' => $id], ['line_ids' => $lineIdList]);
//        if ($row == false) {
//            throw new BusinessLogicException('操作失败');
//        }
//        $this->getLineService()->updateWarehouse($warehouse['id'], $lineIdList);
//    }

    /**
     * 检查
     * @param $warehouse
     * @param $lineIdList
     * @throws BusinessLogicException
     */
    public function check($warehouse, $lineIdList)
    {
        $siblingLineIdList = [];
        //检查上级网点是否有这些线路
        if ($warehouse['parent'] !== 0) {
            $parentWarehouse = parent::getInfo(['id' => $warehouse['parent']], ['*'], false);
            if (empty($parentWarehouse)) {
                throw new BusinessLogicException('数据不存在');
            }
            $parentLineIdList = explode(',', $parentWarehouse['line_ids']) ?? [];
            if (!empty(array_diff($lineIdList, $parentLineIdList))) {
                throw new BusinessLogicException('所选线路不在上级网点中，请先将线路分配至上级网点');
            }
            //检查同级网点是否无这些线路
            $siblingWarehouseList = parent::getList(['parent' => $warehouse['parent'], 'id' => ['<>', $warehouse['id']]], '*', false);
            foreach ($siblingWarehouseList as $k => $v) {
                if (!empty($v['line_ids'])) {
                    $siblingLineIdList = array_merge($siblingLineIdList, explode(',', $v['line_ids']));
                }
            }
            foreach ($lineIdList as $k => $v) {
                if (in_array($v, $siblingLineIdList)) {
                    throw new BusinessLogicException('所选线路在同级其他网点中，请先将其移除');
                }
            }
        }
    }

    /**
     * @return array|mixed
     */
    public function getTree(): array
    {
        return Warehouse::getRoots()->first()->getTree() ?? [];
    }

    /**
     * 检查层数
     *
     * @param $parent
     * @throws BusinessLogicException
     */
    public function checkDistance($parent)
    {
        $distance = Warehouse::findOrFail($parent)->getRoot()->distance;
        if ($distance > 2) {
            throw new BusinessLogicException('网点层级最高为3级');
        }
    }

    /**
     * 创建树
     *
     * @param Warehouse $warehouse
     * @return bool
     */
    public function createRoot(Warehouse $warehouse)
    {
        return $warehouse->makeRoot();
    }

    /**
     * 移动到某一个节点下面
     */
    public function moveNode(int $id, int $parent)
    {
        /** @var Warehouse $warehouse */
        $warehouse = Warehouse::findOrFail($id);

        return $warehouse->moveTo($parent);
    }

    /**
     * 是否有孩子节点
     *
     * @param Warehouse $warehouse
     * @return bool
     */
    protected function hasChildren(Warehouse $warehouse): bool
    {
        return $warehouse->getChildren()->count() > 0;
    }

    /**
     * 是否是根节点
     *
     * @param Warehouse $warehouse
     * @return bool
     */
    protected function isRoot(Warehouse $warehouse): bool
    {
        return $warehouse->isRoot();
    }
}
