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
use App\Models\Warehouse;
use App\Services\CommonService;
use App\Traits\CompanyTrait;

class WareHouseService extends BaseService
{
    public $filterRules = [
        'country' => ['=', 'country'],
    ];

    public function __construct(Warehouse $warehouse)
    {
        parent::__construct($warehouse, WareHouseResource::class, WareHouseResource::class);
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $this->fillData($params);
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('网点新增失败,请重新操作');
        }
    }

    /**
     * 通过ID修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $dbData = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($dbData)) {
            throw new BusinessLogicException('数据不存在');
        }
        $lineIdList = $data['line_ids'];
        $this->check($dbData, $lineIdList);
        $this->fillData($data, $dbData->toArray());
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('网点修改失败，请重新操作');
        }
        //更新线路的网点ID
        $this->getLineService()->updateWarehouse($dbData['id'], $lineIdList);
        //更新上级网点
        $parentWarehouse = parent::getInfo(['id' => $data['parent_id']], ['*'], false);
        if (empty($parentWarehouse)) {
            throw new BusinessLogicException('网点不存在');
        }
        $more = array_diff(explode(',', $data['line_ids']), explode(',', $dbData['line_ids']));
        $less = array_diff(explode(',', $dbData['line_ids']), explode(',', $data['line_ids']));
        if (!empty($more)) {
            $parentWarehouse['line_ids'] = implode(',', array_diff(json_decode($parentWarehouse['line_ids']), $more));
        } elseif (!empty($less)) {
            $parentWarehouse['line_ids'] = implode(',', array_merge(json_decode($parentWarehouse['line_ids']), $less));
        } else {
            return;
        }
        $row = parent::update(['id' => $dbData['parent_id']], ['line_ids' => $parentWarehouse['line_ids']]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        $this->getLineService()->updateWarehouse($dbData['parent_id'], $parentWarehouse['line_ids']);
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
        $line = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($line)) {
            return;
        }
        if (!empty($line->toArray()['line_ids'])) {
            throw new BusinessLogicException('请先删除线路该网点下的线路');
        }
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('网点删除失败，请重新操作');
        }
    }

    /**
     * 获取网点下所有线路
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLineList($id)
    {
        return $this->getLineService()->getPageListByWarehouse($id);
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
//        $row = parent::update(['id' => $warehouse->toArray()['parent_id']], ['line_ids' => $parentLineIdList]);
//        if ($row == false) {
//            throw new BusinessLogicException('操作失败');
//        }
//        $this->getLineService()->updateWarehouse($warehouse['parent_id'], $parentLineIdList);
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
//        $parentWarehouse = parent::getInfo(['id' => $warehouse['parent_id']], ['*'], false);
//        if (empty($parentWarehouse)) {
//            throw new BusinessLogicException('网点不存在');
//        }
//        $parentWarehouse = $parentWarehouse->toArray();
//        $parentLineIdList = array_merge($parentWarehouse['line_ids'], $params['line_ids']);
//        //从上级网点新增
//        $row = parent::update(['id' => $warehouse['parent_id']], ['line_ids' => $parentLineIdList]);
//        if ($row == false) {
//            throw new BusinessLogicException('操作失败');
//        }
//        $this->getLineService()->updateWarehouse($warehouse['parent_id'], $parentLineIdList);
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
        $lineIdList = explode(',', $lineIdList);
        $siblingLineIdList = [];
        //检查上级网点是否有这些线路
        $parentWarehouse = parent::getInfo(['id' => $warehouse['parent_id']], '*', false);
        $parentLineIdList = $parentWarehouse->toArray()['line_ids'];
        if (!empty(array_diff($lineIdList, $parentLineIdList))) {
            throw new BusinessLogicException('所选线路不在上级网点中，请先将线路分配至上级网点');
        }
        //检查同级网点是否无这些线路
        $siblingWarehouseList = parent::getList(['parent_id' => $warehouse['parent_id'], 'id' => ['<>', $warehouse['id']]], '*', false);
        foreach ($siblingWarehouseList as $k => $v) {
            $siblingLineIdList = array_merge($siblingLineIdList, $v['line_ids']);
        }
        if (!empty(array_diff($lineIdList, $siblingLineIdList))) {
            throw new BusinessLogicException('所选线路在同级其他网点中，请先将其移除');
        }
    }
}
