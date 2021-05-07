<?php
/**
 * 线路 服务
 * User: long
 * Date: 2019/12/21
 * Time: 10:05
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\Line;
use App\Services\BaseConstService;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use Carbon\Carbon;
use Doctrine\DBAL\Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class LineService extends BaseLineService
{
    public function __construct(Line $line)
    {
        parent::__construct($line);
    }

    /**
     * 通过日期 获取线路列表
     * @param $date
     * @return array
     */
    public function getListByDate($date)
    {
        if (CompanyTrait::getLineRule() == BaseConstService::LINE_RULE_POST_CODE) {
            $lineRangeList = $this->getLineRangeService()->getList(['schedule' => Carbon::create($date)->dayOfWeek], ['line_id'], false, ['line_id'])->toArray();
        } else {
            $lineRangeList = $this->getLineAreaService()->getList(['schedule' => Carbon::create($date)->dayOfWeek], ['line_id'], false, ['line_id'])->toArray();
        }
        $list = parent::getList(['id' => ['in', array_column($lineRangeList, 'line_id')]], ['id', 'name'], false)->toArray();
        return $list;
    }

    /**
     * 邮编-列表查询
     * @return BaseLineService|array|mixed
     */
    public function postcodeIndex()
    {
        //如果存在post_code查询
        if (!empty($this->formData['post_code'])) {
            $postCode = explode_post_code($this->formData['post_code']);
            if (!is_numeric($postCode)) {
                $this->query->where('rule', '=', 0);//保证查不到的条件
            } else {
                $this->query->whereRaw("id IN (SELECT DISTINCT line_id FROM line_range WHERE post_code_start <= {$postCode} AND post_code_end >= {$postCode})");
            }
        }
        $this->filters['rule'] = ['=', BaseConstService::LINE_RULE_POST_CODE];
        $list = parent::getPageList();
        $lineIdList = array_column($list->all(), 'id');
        if (empty($lineIdList)) return $list;
        //获取线路范围列表
        $lineRangeList = $this->getLineRangeService()->getAllLineRange($lineIdList);
        $warehouseList = $this->getWareHouseService()->getList(['id' => ['in', $list->pluck('warehouse_id')->toArray()]], ['*'], false)->keyBy('id');
        if (empty($lineRangeList)) return $list;
        foreach ($list as &$line) {
            $line['line_range'] = $lineRangeList[$line['id']]['line_range'];
            $line['work_day_list'] = array_values(array_unique($lineRangeList[$line['id']]['work_day_list']));
            $line['warehouse_name'] = $warehouseList[$line['warehouse_id']]['name'];
        }
        return $list;
    }

    /**
     * 邮编-详情
     * @param $id
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function postcodeShow($id)
    {
        $info = parent::getInfo(['id' => $id, 'rule' => BaseConstService::LINE_RULE_POST_CODE], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('线路不存在');
        }
        $info = $info->toArray();
        $lineRangeList = $this->getLineRangeService()->getList(['line_id' => $info['id']], ['country', 'post_code_start', 'post_code_end', 'schedule'], false);
        if ($lineRangeList->isEmpty()) {
            $info['line_range'] = [];
            $info['work_day_list'] = '';
        } else {
            $info['line_range'] = $lineRangeList->map(function ($lineRange, $key) {
                return collect($lineRange)->only(['post_code_start', 'post_code_end']);
            })->unique(function ($item) {
                return $item['post_code_start'] . $item['post_code_end'];
            })->toArray();
            $info['work_day_list'] = implode(',', array_values(array_unique(array_column($lineRangeList->toArray(), 'schedule'))));
        }
        $merchantGroupCountList = $this->getMerchantGroupLineService()->getList(['line_id' => $id], ['merchant_group_id', 'pickup_min_count', 'pie_min_count'], false);
        $merchantGroupList = $this->getMerchantGroupService()->getList(['id' => ['in', $merchantGroupCountList->pluck('merchant_group_id')->toArray()]], ['*'], false);
        foreach ($merchantGroupCountList as $k => $v) {
            $merchantGroupCountList[$k]['merchant_group_name'] = $merchantGroupList->where('id', $v['merchant_group_id'])->first()['name'] ?? '';
        }
        $info['merchant_group_count_list'] = $merchantGroupCountList ?? [];
        return $info;

    }

    /**
     * 邮编-新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function postcodeStore($params)
    {
        //基础验证
        $this->check($params);
        //邮编范围验证
        $this->getLineRangeService()->checkRange($params['item_list'], $params['country'], $params['work_day_list']);
        //货主组最小订单量验证
        $this->getMerchantGroupLineService()->checkCount($params, $params['merchant_group_count_list']);
        //新增
        $lineId = $this->store($params);
        //邮编范围批量新增
        $this->getLineRangeService()->storeAll($lineId, $params['item_list'], $params['country'], $params['work_day_list']);
        //最小订单量批量新增
        $this->getMerchantGroupLineService()->storeAll($lineId, $params['merchant_group_count_list']);
        //更新网点
        $rootWarehouse = $this->getWareHouseService()->getInfo(['company_id' => auth()->user()->company_id, 'parent' => 0], ['*'], false);
        if ($rootWarehouse['line_ids'] == '') {
            $lineIds = $lineId;
        } else {
            $lineIds = $rootWarehouse['line_ids'] . ',' . $lineId;
        }
        $this->getWareHouseService()->updateById($rootWarehouse['id'], ['line_ids' => $lineIds]);
    }

    /**
     * 新增货主所有线路范围
     * @param $merchantGroupId
     * @throws BusinessLogicException
     */
    public function storeAllPostCodeLineRangeByMerchantGroupId($merchantGroupId)
    {
        $lineRangeList = $this->getLineRangeService()->getList([], ['*'], false)->toArray();
        data_set($lineRangeList, '*.merchant_group_id', $merchantGroupId);
        foreach ($lineRangeList as $key => $lineRange) {
            unset($lineRangeList[$key]['country_name']);
            unset($lineRangeList[$key]['id']);
        }
        $rowCount = $this->getMerchantGroupLineRangeService()->insertAll($lineRangeList);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * 邮编-修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function postcodeUpdate($id, $data)
    {
        $info = parent::getInfo(['id' => $id, 'rule' => BaseConstService::LINE_RULE_POST_CODE], ['id', 'country'], false);
        if (empty($info)) {
            throw new BusinessLogicException('线路不存在');
        }
        //基础验证
        $this->check($data, $info->toArray());
        //货主组最小订单量验证
        $this->getMerchantGroupLineService()->checkCount($data, $data['merchant_group_count_list']);
        //邮编范围验证
        $this->getLineRangeService()->checkRange($data['item_list'], $data['country'], $data['work_day_list'], $id);
        //修改
        $this->updateById($id, $data);
        //删除并新增线路范围
        $rowCount = $this->getLineRangeService()->delete(['line_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围修改失败');
        }
        $this->getLineRangeService()->storeAll($id, $data['item_list'], $data['country'], $data['work_day_list']);
        //删除并新增最小订单量
        $rowCount = $this->getMerchantGroupLineService()->delete(['line_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围修改失败');
        }
        $this->getMerchantGroupLineService()->storeAll($info['id'], $data['merchant_group_count_list']);
    }

    /**
     * 邮编-删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function postcodeDestroy($id)
    {
        $info = parent::getInfo(['id' => $id, 'rule' => BaseConstService::LINE_RULE_POST_CODE], ['id'], false);
        if (empty($info)) {
            throw new BusinessLogicException('线路不存在');
        }
        $this->destroy($id);
        //删除线路范围
        $rowCount = $this->getLineRangeService()->delete(['line_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围删除失败');
        }
        //删除货主线路范围
        $rowCount = $this->getMerchantGroupLineRangeService()->delete(['line_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //更新网点
        $rootWarehouse = $this->getWareHouseService()->getInfo(['company_id' => auth()->user()->company_id, 'parent' => 0], ['*'], false);
        $lineIds = str_replace($id, '', $rootWarehouse['line_ids']);
        $lineIds = str_replace(',,', ',', $lineIds);
        $this->getWareHouseService()->updateById($rootWarehouse['id'], ['line_ids' => $lineIds]);
    }

    /**
     * 区域-列表查询
     * @param $isGetArea
     * @return mixed
     */
    public function areaIndex($isGetArea)
    {
        $this->filters['rule'] = ['=', BaseConstService::LINE_RULE_AREA];
        $list = parent::getPageList();
        $workdayList = array_keys(ConstTranslateTrait::$weekList);
        foreach ($list as $key => $line) {
            $list[$key]['work_day_list'] = $workdayList;
        }
        if ($isGetArea === 2) return $list;

        $lineIdList = array_column($list->all(), 'id');
        if (empty($lineIdList)) return $list;

        $lineAreaList = $this->getLineAreaService()->getList(['line_id' => ['in', $lineIdList]], ['line_id', 'coordinate_list', 'country'], false, ['line_id', 'coordinate_list', 'country'])->toArray();
        $lineAreaList = array_create_group_index($lineAreaList, 'line_id');
        if (empty($lineAreaList)) return $list;
        foreach ($list as &$line) {
            $coordinateList = array_column($lineAreaList[$line['id']], 'coordinate_list');
            $line['coordinate_list'] = !empty($coordinateList) ? array_map(function ($coordinateItemList) {
                return json_decode($coordinateItemList, true);
            }, $coordinateList) : [];
            $line['work_day_list'] = $workdayList;
        }
        return $list;
    }

    /**
     * 区域-详情
     * @param $id
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function areaShow($id)
    {
        $info = parent::getInfo(['id' => $id, 'rule' => BaseConstService::LINE_RULE_AREA], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('线路不存在');
        }
        $lineAreaList = $this->getLineAreaService()->getList(['line_id' => $info['id']], ['coordinate_list'], false)->toArray();
        $coordinateList = array_column($lineAreaList, 'coordinate_list');
        $info['coordinate_list'] = array_map(function ($coordinateItemList) {
            return json_decode($coordinateItemList, true);
        }, $coordinateList);
        return $info;
    }

    /**
     * 区域-新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function areaStore($params)
    {
        //基础验证
        $this->check($params);
        //区域范围验证
        $this->getLineAreaService()->checkArea($params['coordinate_list'], $params['country']);
        //新增
        $lineId = $this->store($params, BaseConstService::LINE_RULE_AREA);
        //区域范围批量新增
        $this->getLineAreaService()->storeAll($lineId, $params['coordinate_list'], $params['country']);
    }

    /**
     * 区域-修改
     * @param $id
     * @param $data
     * @throws BusinessLogicException
     */
    public function areaUpdate($id, $data)
    {
        $info = parent::getInfo(['id' => $id, 'rule' => BaseConstService::LINE_RULE_AREA], ['id', 'country'], false);
        if (empty($info)) {
            throw new BusinessLogicException('线路不存在');
        }
        //基础验证
        $this->check($data, $info->toArray());
        //区域范围验证
        $this->getLineAreaService()->checkArea($data['coordinate_list'], $data['country'], $id);
        //修改
        $this->updateById($id, $data);
        //删除原来区域范围验证
        $rowCount = $this->getLineAreaService()->delete(['line_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围修改失败');
        }
        //批量新增
        $this->getLineAreaService()->storeAll($id, $data['coordinate_list'], $data['country']);
    }


    /**
     * 区域-线路删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function areaDestroy($id)
    {
        $info = parent::getInfo(['id' => $id, 'rule' => BaseConstService::LINE_RULE_AREA], ['id'], false);
        if (empty($info)) {
            throw new BusinessLogicException('线路不存在');
        }
        $this->destroy($id);
        //删除线路范围
        $rowCount = $this->getLineAreaService()->delete(['line_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围删除失败');
        }
    }

    /**
     * 通过网点ID获取线路列表
     * @param $ids
     * @return Collection
     */
    public function getPageListByIds($ids)
    {
        $this->query->whereIn('id', $ids);
        if (CompanyTrait::getLineRule() == BaseConstService::LINE_RULE_POST_CODE) {
            return $this->postcodeIndex();
        } else {
            return $this->areaIndex(2);
        }
    }

    /**
     * 通过网点ID获取线路列表
     * @param $warehouseId
     * @return Collection
     */
    public function getPageListByWarehouse($warehouseId)
    {
        $this->query->where('warehouse_id', $warehouseId);
        if (CompanyTrait::getLineRule() == BaseConstService::LINE_RULE_POST_CODE) {
            return $this->postcodeIndex();
        } else {
            return $this->areaIndex(2);
        }
    }

    /**
     * 批量修改线路的网点ID
     * @param $warehouseId
     * @param $lineIdList
     * @throws BusinessLogicException
     */
    public function updateWarehouse($warehouseId, $lineIdList)
    {
        dd($warehouseId,$lineIdList);
        if (empty($lineIdList)) {
            return;
        }
        $row = parent::update(['id' => ['in', $lineIdList]], ['warehouse_id' => $warehouseId]);
        if ($row == false) {
            throw new BusinessLogicException('更新线路失败');
        }
    }

    /**
     * 测试
     * @param $data
     * @return array
     * @throws BusinessLogicException
     */
    public function test($data)
    {
        $pickupWarehouse = $this->getWareHouseByAddress($this->pickupAddress($data));
        $pickupData = $this->centerCheck($pickupWarehouse, $pickupWarehouse, BaseConstService::ORDER_TYPE_1);
        $pieWarehouse = $this->getWareHouseByAddress($this->pieAddress($data));
        $pieData = $this->centerCheck($pieWarehouse, $pieWarehouse, BaseConstService::ORDER_TYPE_2);
        $data = array_values(array_filter(array_merge(
            [$this->formAddress($this->pickupAddress($data))],
            $pickupData,
            array_reverse($pieData),
            [$this->formAddress($this->pieAddress($data))]
        )));
        $data = $this->formTest($data);
        return $data;
    }

    /**
     * 取件路径
     * @param $data
     * @return array
     */
    public function pickupAddress($data)
    {
        if (empty($data['place_country'])) {
            $data['place_country'] = CompanyTrait::getCompany()['country'];
        }
        return [
            'type' => BaseConstService::TRACKING_ORDER_TYPE_1,
            'place_fullname' => $data['place_fullname'],
            'place_phone' => $data['place_phone'],
            'place_country' => $data['place_country'],
            'place_province' => $data['place_province'] ?? '',
            'place_post_code' => $data['place_post_code'],
            'place_house_number' => $data['place_house_number'],
            'place_city' => $data['place_city'],
            'place_district' => $data['place_district'] ?? '',
            'place_street' => $data['place_street'],
            'place_address' => $data['place_address'],
            'place_lat' => $data['place_lat'],
            'place_lon' => $data['place_lon'],
            'execution_date' => $data['execution_date']
        ];
    }

    /**
     * 派件路径
     * @param $data
     * @return array
     */
    public function pieAddress($data)
    {
        return [
            'type' => BaseConstService::TRACKING_ORDER_TYPE_2,
            'place_fullname' => $data['second_place_fullname'],
            'place_phone' => $data['second_place_phone'],
            'place_country' => $data['second_place_country'],
            'place_province' => $data['second_place_province'] ?? '',
            'place_post_code' => $data['second_place_post_code'],
            'place_house_number' => $data['second_place_house_number'],
            'place_city' => $data['second_place_city'],
            'place_district' => $data['second_place_district'] ?? '',
            'place_street' => $data['second_place_street'],
            'place_address' => $data['second_place_address'],
            'place_lat' => $data['second_place_lat'],
            'place_lon' => $data['second_place_lon'],
            'execution_date' => $data['second_execution_date']
        ];
    }

    public function formAddress($data)
    {
        return [
            'type' => $data['type'],
            'name' => $data['place_fullname'],
            'is_center' => 3
        ];
    }

    /**
     * 通过地址获取网点
     * @param $data
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function getWareHouseByAddress($data)
    {
        $line = $this->getBaseLineService()->getInfoByRule($data);
        //获取网点
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('网点不存在');
        }
        $warehouse = collect($warehouse)->toArray();
        return $warehouse;
    }

    /**
     * 回溯上级节点，直至遇到分拨中心
     * @param $warehouse
     * @param $data
     * @param $type
     * @return array
     */
    public function centerCheck($warehouse, $data, $type)
    {
        $warehouse['type'] = $type;
        if ($warehouse['is_center'] == BaseConstService::NO && $warehouse['parent'] !== 0) {
            $parentWarehouse = $this->getWareHouseService()->getInfo(['id' => $warehouse['parent']], ['*'], false);
            $data[] = [$parentWarehouse];
            $this->centerCheck($parentWarehouse, $data, $type);
        } else {
            return [[
                'name' => $data['name'],
                'is_center' => $data['is_center'],
                'type' => $data['type']
            ]];
        }
    }

    public function formTest(array $data)
    {
        for ($i = 0, $j = count($data) - 1; $i < $j; $i++) {
            if ($data[$i] == $data[$i + 1]) {
                $data[$i + 1]['type'] = BaseConstService::ORDER_TYPE_3;
                unset($data[$i]);
            }
        }
        $data = array_values($data);
        //类型1分拨3-寄件人，类型2分拨3-收件人，类型1分拨2-网点取件，类型2分拨2-网点派件，类型3分拨2-网点取件/派件，其他-分拨中心
        for ($i = 0, $j = count($data); $i < $j; $i++) {
            if ($data[$i]['type'] == BaseConstService::ORDER_TYPE_1 &&
                $data[$i]['is_center'] == 3
            ) {
                $data[$i]['status'] = BaseConstService::LINE_TEST_STATUS_1;
            } elseif ($data[$i]['type'] == BaseConstService::ORDER_TYPE_2 &&
                $data[$i]['is_center'] == 3
            ) {
                $data[$i]['status'] = BaseConstService::LINE_TEST_STATUS_5;
            } elseif ($data[$i]['type'] == BaseConstService::ORDER_TYPE_1 &&
                $data[$i]['is_center'] == BaseConstService::WAREHOUSE_IS_CENTER_2
            ) {
                $data[$i]['status'] = BaseConstService::LINE_TEST_STATUS_2;
            } elseif ($data[$i]['type'] == BaseConstService::ORDER_TYPE_2 &&
                $data[$i]['is_center'] == BaseConstService::WAREHOUSE_IS_CENTER_2
            ) {
                $data[$i]['status'] = BaseConstService::LINE_TEST_STATUS_4;
            } elseif ($data[$i]['type'] == BaseConstService::ORDER_TYPE_3 &&
                $data[$i]['is_center'] == BaseConstService::WAREHOUSE_IS_CENTER_2
            ) {
                $data[$i]['status'] = BaseConstService::LINE_TEST_STATUS_6;
            } else {
                $data[$i]['status'] = BaseConstService::LINE_TEST_STATUS_3;
            }
        }
        $array = [1 => '分拨中心', 2 => '网点', 3 => '客户地址'];
        foreach ($data as $k => $v) {
            $data[$k]['status_name'] = ConstTranslateTrait::lineTestStatusList($v['status']);
            $data[$k]['is_center_name'] = $array[$v['is_center']];
        }
        return $data;
    }
}
