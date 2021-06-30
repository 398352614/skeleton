<?php
/**
 * 运价管理
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\TransportPriceResource;
use App\Models\KilometresCharging;
use App\Models\SpecialTimeCharging;
use App\Models\TransportPrice;
use App\Models\TransportPriceOperation;
use App\Models\WeightCharging;
use App\Services\BaseConstService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

/**
 * Class TransportPriceService
 * @package App\Services\Admin
 * @property KilometresCharging $kilometresChargingModel
 * @property WeightCharging weightChargingModel
 * @property SpecialTimeCharging $specialTimeChargingModel
 */
class TransportPriceService extends BaseService
{
    public $filterRules = [
        'name' => ['like', 'name']];

    public function __construct(
        TransportPrice $transportPrice,
        KilometresCharging $kilometresCharging,
        WeightCharging $weightCharging,
        SpecialTimeCharging $specialTimeCharging)
    {
        parent::__construct($transportPrice, TransportPriceResource::class);
        //子模型
        $this->kilometresChargingModel = $kilometresCharging;
        $this->weightChargingModel = $weightCharging;
        $this->specialTimeChargingModel = $specialTimeCharging;
    }

    /**
     * 列表查询
     * @return array|mixed
     */
    public function getPageList()
    {
        $list = parent::getPageList();
        if (empty($list)) return [];
        $idList = array_column($list->all(), 'id');
        $kmList = $this->kilometresChargingModel->newQuery()->whereIn('transport_price_id', $idList)->get()->toArray();
        $weightList = $this->weightChargingModel->newQuery()->whereIn('transport_price_id', $idList)->get()->toArray();
        $specialTimeList = $this->specialTimeChargingModel->newQuery()->whereIn('transport_price_id', $idList)->get()->toArray();
        $kmList = !empty($kmList) ? array_create_group_index($kmList, 'transport_price_id') : [];
        $weightList = !empty($weightList) ? array_create_group_index($weightList, 'transport_price_id') : [];
        $specialTimeList = !empty($specialTimeList) ? array_create_group_index($specialTimeList, 'transport_price_id') : [];
        foreach ($list as &$item) {
            $transportPriceId = $item['id'];
            if (!empty($kmList[$transportPriceId])) {
                $item['km_list'] = $kmList[$transportPriceId];
            } else {
                $item['km_list'] = [];
            }
            if (!empty($weightList[$transportPriceId])) {
                $item['weight_list'] = $weightList[$transportPriceId];
            } else {
                $item['weight_list'] = [];
            }
            if (!empty($specialTimeList[$transportPriceId])) {
                $item['special_time_list'] = $specialTimeList[$transportPriceId];
            } else {
                $item['special_time_list'] = [];
            }
            $part = [];
            if ($item['starting_price'] !== 0) {
                $part[] = __('固定费用');
            }
            if (!empty($item['weight_list'])) {
                $part[] = __('重量');
            }
            if (!empty($item['km_list'])) {
                $part[] = __('里程');
            }
            $item['part'] = implode(',', $part);
        }
        return $list;
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $info->toArray();
        $info['km_list'] = $this->kilometresChargingModel->newQuery()->where('transport_price_id', '=', $id)->get()->toArray();
        $info['weight_list'] = $this->weightChargingModel->newQuery()->where('transport_price_id', '=', $id)->get()->toArray();
        $info['special_time_list'] = $this->specialTimeChargingModel->newQuery()->where('transport_price_id', '=', $id)->get()->toArray();
        for ($i = 0; $i < count($info['special_time_list']); $i++) {
            $info['special_time_list'][$i]['period'] = [$info['special_time_list'][$i]['start'], $info['special_time_list'][$i]['end']];
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
        $this->check($params);
        $transportPrice = parent::create($params);
        if ($transportPrice === false) {
            throw new BusinessLogicException('新增失败，请重新操作');
        }
        $id = $transportPrice->getAttribute('id');
        $this->insertDetailsAll($id, $params);
        $this->operationLog($id, BaseConstService::OPERATION_STORE, $this->show($transportPrice->getAttribute('id')));
    }

    /**
     * 修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $dbData = $this->show($id);
        $this->check($data);
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        //删除公里计费，重量计费，特殊时段计费列表
        $rowCount = $this->kilometresChargingModel->newQuery()->where('transport_price_id', '=', $id)->delete();
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        $rowCount = $this->weightChargingModel->newQuery()->where('transport_price_id', '=', $id)->delete();
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        $rowCount = $this->specialTimeChargingModel->newQuery()->where('transport_price_id', '=', $id)->delete();
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //新增公里计费，重量计费，特殊时段计费列表
        $this->insertDetailsAll($id, $data);
        $this->operationLog($id, BaseConstService::OPERATION_UPDATE, $dbData, $data);
    }

    /**
     * 记录日志
     * @param $id
     * @param $operation
     * @param $content
     * @param string $secondContent
     */
    public function operationLog($id, $operation, $content = '', $secondContent = '')
    {
        $data = [
            'company_id' => auth()->user()->id,
            'operation' => $operation,
            'operator' => auth()->user()->fullname,
            'transport_price_id' => $id,
            'content' => json_encode($content),
            'second_content' => json_encode($secondContent)
        ];
        $row = TransportPriceOperation::query()->create($data);
        if ($row == false) {
            Log::channel('info')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'data', $data);
        }
    }

    /**
     * 查询日志
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function operationLogIndex($id)
    {
        $list = TransportPriceOperation::query()->where('transport_price_id', $id)->get();
        foreach ($list as $k => $v) {
            $list[$k]['content'] = json_decode($list[$k]['content']);
            $list[$k]['second_content'] = json_decode($list[$k]['second_content']);
        }
        return $list;
    }

    /**
     * 价格测试
     * @param $id
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getPriceResult($id, $params)
    {
        $info = $this->show($id);
        $price = $info['starting_price'];
        //公里计费
        if (!empty($params['km'])) {
            if (empty($info['km_list'])) {
                throw new BusinessLogicException('当前运价没有公里计费列表');
            }
            $km = Arr::first($info['km_list'], function ($km, $key) use ($params) {
                if (($km['start'] <= $params['km']) && ($km['end'] >= $params['km'])) {
                    return true;
                }
                return false;
            });
            if (empty($km)) {
                throw new BusinessLogicException('当前公里不在该运价范围内');
            }
            $price += $km['price'];
        }
        //重量计费
        if (!empty($params['weight'])) {
            if (empty($info['weight_list'])) {
                throw new BusinessLogicException('当前运价没有重量计费列表');
            }
            $weight = Arr::first($info['weight_list'], function ($weight, $key) use ($params) {
                if (($weight['start'] <= $params['weight']) && ($weight['end'] >= $params['weight'])) {
                    return true;
                }
                return false;
            });
            if (empty($weight)) {
                throw new BusinessLogicException('当前重量不在该运价范围内');
            }
            $price += $weight['price'];
        }
        //特殊时段计费
        if (!empty($params['special_time'])) {
            if (empty($info['special_time_list'])) {
                throw new BusinessLogicException('当前运价没有特殊时段计费列表');
            }
            $startDay = '1970-01-02';
            $special = strtotime($startDay . $params['special_time']);
            $specialTime = Arr::first($info['special_time_list'], function ($specialTime, $key) use ($params, $startDay, $special) {
                $start = strtotime($startDay . $specialTime['start']);
                $end = strtotime($startDay . $specialTime['end']);
                if (($start <= $special) && ($end >= $special)) {
                    return true;
                }
                return false;
            });
            /*            if (empty($specialTime)) {
                            throw new BusinessLogicException('当前时间不在该运价范围内');
                        }*/
            $price += $specialTime['price'] ?? 0;
        }
        $info['price'] = $price;
        return $info;
    }


    /**
     * 验证
     * @param $params
     * @throws BusinessLogicException
     */
    public function check(&$params)
    {
        if (empty($params['starting_price']) && empty($params['km_list']) && empty($params['weight_list'])) {
            throw new BusinessLogicException('固定费用/距离费用/重量费用至少配置一项');
        }
        //公里计费
        if (!empty($params['km_list'])) {
            $kmList = $params['km_list'];
            $length = count($kmList);
            for ($i = 0; $i <= $length - 1; $i++) {
                for ($j = $i + 1; $j <= $length - 1; $j++) {
                    if (max($kmList[$i]['start'], $kmList[$j]['start']) < min($kmList[$i]['end'], $kmList[$j]['end'])) {
                        throw new BusinessLogicException('公里区间有重叠');
                    }
                }
                //连贯性测试
                $kmList[-1]['end'] = 0;
                if ($kmList[$i]['start'] !== $kmList[$i - 1]['end']) {
                    throw new BusinessLogicException('公里区间不连贯');
                }
            }
            //最大值测试
            if (!in_array(BaseConstService::INFINITY, collect($kmList)->pluck('end')->toArray())) {
                throw new BusinessLogicException('公里区间未涵盖所有范围');
            }
        }
        //重量计费
        if (!empty($params['weight_list'])) {
            $weightList = $params['weight_list'];
            $length = count($weightList);
            for ($i = 0; $i <= $length - 1; $i++) {
                for ($j = $i + 1; $j <= $length - 1; $j++) {
                    if (max($weightList[$i]['start'], $weightList[$j]['start']) < min($weightList[$i]['end'], $weightList[$j]['end'])) {
                        throw new BusinessLogicException('重量区间有重叠');
                    }
                    //连贯性测试
                    $weightList[-1]['end'] = 0;
                    if ($weightList[$i]['start'] !== $weightList[$i - 1]['end']) {
                        throw new BusinessLogicException('重量区间不连贯');
                    }
                }
                //最大值测试
                if (!in_array(BaseConstService::INFINITY, collect($weightList)->pluck('end')->toArray())) {
                    throw new BusinessLogicException('重量区间未涵盖所有范围');
                }
            }
        }
        //时段计费
        if (!empty($params['special_time_list'])) {
            $specialTimeList = $params['special_time_list'];
            $length = count($specialTimeList);
            for ($i = 0; $i <= $length - 1; $i++) {
                for ($j = $i + 1; $j <= $length - 1; $j++) {
                    if ($this->isTimeCross($specialTimeList[$i]['start'], $specialTimeList[$i]['end'], $specialTimeList[$j]['start'], $specialTimeList[$j]['end'])) {
                        throw new BusinessLogicException('时间段有重叠');
                    }
                }
            }
        }
    }

    /**
     * PHP计算两个时间段是否有交集（边界重叠不算）
     *
     * @param string $beginTime1 开始时间1
     * @param string $endTime1 结束时间1
     * @param string $beginTime2 开始时间2
     * @param string $endTime2 结束时间2
     * @return bool
     */
    private function isTimeCross($beginTime1 = '', $endTime1 = '', $beginTime2 = '', $endTime2 = '')
    {
        $beginTime1 = strtotime('1970-01-02 ' . $beginTime1);
        $endTime1 = strtotime('1970-01-02 ' . $endTime1);
        $beginTime2 = strtotime('1970-01-02 ' . $beginTime2);
        $endTime2 = strtotime('1970-01-02 ' . $endTime2);
        $status = $beginTime2 - $beginTime1;
        if ($status > 0) {
            $status2 = $beginTime2 - $endTime1;
            if ($status2 >= 0) {
                return false;
            } else {
                return true;
            }
        } else {
            $status2 = $endTime2 - $beginTime1;
            if ($status2 > 0) {
                return true;
            } else {
                return false;
            }
        }
    }


    /**
     * 批量新增公里计费，重量计费，特殊时段计费列表
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    private function insertDetailsAll($id, $params)
    {
        //公里计费
        if (!empty($params['km_list'])) {
            data_fill($params, 'km_list.*.transport_price_id', $id);
            $rowCount = $this->kilometresChargingModel->insertAll($params['km_list']);
            if ($rowCount === false) {
                throw new BusinessLogicException('新增失败，请重新操作');
            }
        }
        //重量计费
        if (!empty($params['weight_list'])) {
            data_fill($params, 'weight_list.*.transport_price_id', $id);
            $rowCount = $this->weightChargingModel->insertAll($params['weight_list']);
            if ($rowCount === false) {
                throw new BusinessLogicException('新增失败，请重新操作');
            }
        }
        //特殊时段计费
        if (!empty($params['special_time_list'])) {
            data_fill($params, 'special_time_list.*.transport_price_id', $id);
            $rowCount = $this->specialTimeChargingModel->insertAll($params['special_time_list']);
            if ($rowCount === false) {
                throw new BusinessLogicException('新增失败，请重新操作');
            }
        }
    }

    /**
     * 运价计算(插入运价字段)
     * step阶梯式multiply乘积式
     * @param $data
     * @param null $transportPriceId
     * @return array|void
     * @throws BusinessLogicException
     */
    public function priceCount($data, $transportPriceId = null)
    {
        $data['distance'] = $data['distance'] / 1000;

        //预设为0
        $data['starting_price'] = $data['settlement_amount'] = $data['package_settlement_amount'] = $data['count_settlement_amount'] = 0.00;
        if (!empty($data['package_list'])) {
            foreach ($data['package_list'] as $k => $v) {
                $data['package_list'][$k]['settlement_amount'] = $data['package_list'][$k]['count_settlement_amount'] = 0.00;
            }
        }
        if (empty($transportPriceId)) {
            $transportPriceId = $this->getTransportPriceIdByMerchantId($data['merchant_id']);
        }
        //没有运价就返回0
        if ($transportPriceId == null) {
            return $data;
        }
        $transportPrice = $this->show($transportPriceId);
        $data['transport_price_id'] = $transportPriceId;
        $data['transport_price_type'] = $transportPrice['type'];
        if ($transportPrice['status'] == BaseConstService::ON) {
            if (!empty($data['package_list'])) {
                //根据计算方式计算包裹运价
                if ($transportPrice['type'] == BaseConstService::TRANSPORT_PRICE_TYPE_1) {
                    $data = $this->multiplyWeightMultiplyDistance($data, $transportPrice);
                } elseif ($transportPrice['type'] == BaseConstService::TRANSPORT_PRICE_TYPE_2) {
                    $data = $this->stepWeightStepDistance($data, $transportPrice);
                } elseif ($transportPrice['type'] == BaseConstService::ONLY_START_PRICE) {
                    $data['settlement_amount'] = $data['count_settlement_amount'] = 0.00;
                } else {
                    throw new BusinessLogicException('暂无预计运价，运价以实际为准');
                }
            }
            $data['starting_price'] = $transportPrice['starting_price'];
            $data['package_settlement_amount'] = number_format_simple($data['count_settlement_amount'], 2,'.','');
            $data['settlement_amount'] = $data['count_settlement_amount'] = number_format_simple(round($data['count_settlement_amount'] + $data['starting_price'], 2),2,'.','');
        }
        if (!empty($data['package_list'])) {
            foreach ($data['package_list'] as $k => $v) {
                $data['package_list'][$k]['settlement_amount'] = number_format_simple($data['package_list'][$k]['settlement_amount'], 2,'.','');
                $data['package_list'][$k]['count_settlement_amount'] = number_format_simple($data['package_list'][$k]['count_settlement_amount'], 2,'.','');
            }
        }
        return $data;
    }

    /**
     * 通过货主ID获得运价方案
     * @param $merchantId
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getTransportPriceIdByMerchantId($merchantId)
    {
        //获取货主组
        $merchant = $this->getMerchantService()->getInfo(['id' => $merchantId], ['*'], false);
        if (empty($merchant)) {
            throw new BusinessLogicException('货主不存在');
        }
        //获取货主组
        $merchantGroup = $this->getMerchantGroupService()->getInfo(['id' => $merchant['merchant_group_id']], ['*'], false);
        if (empty($merchant)) {
            return null;
        }
        return $merchantGroup['transport_price_id'];
    }

    /**
     * 重量乘积，距离乘积
     * @param $data
     * @param $transportPrice
     * @return array
     */
    public function multiplyWeightMultiplyDistance($data, $transportPrice)
    {
        foreach ($data['package_list'] as $k => $package) {
            $weightPrice = $this->getWeightPrice($package['weight'], $transportPrice);
            $distancePrice = $this->getDistancePrice($data['distance'], $transportPrice);
            if ($weightPrice == null && $distancePrice !== null) {
                $weightPrice = 1;
            } elseif ($weightPrice !== null && $distancePrice == null) {
                $distancePrice = 1;
            } elseif ($weightPrice == null && $distancePrice == null) {
                $weightPrice = $distancePrice = 0;
            }
            //公式
            $data['package_list'][$k]['settlement_amount'] = $data['package_list'][$k]['count_settlement_amount'] = round(
                floatval($package['weight']) *
                floatval($weightPrice) *
                floatval($data['distance']) *
                floatval($distancePrice)
                , 2);
            $data['settlement_amount'] = $data['count_settlement_amount'] = round(
                floatval($data['count_settlement_amount']) +
                floatval($data['package_list'][$k]['count_settlement_amount'])
                , 2);
            $data['package_settlement_amount'] = round($data['package_settlement_amount'] + $data['package_list'][$k]['count_settlement_amount'], 2);
        }
        return $data;
    }

    /**
     * 重量阶梯，距离阶梯
     * @param $data
     * @param $transportPrice
     * @return array
     */
    public function stepWeightStepDistance($data, $transportPrice)
    {
        foreach ($data['package_list'] as $k => $package) {
            $weightPrice = $this->getWeightPrice($package['weight'], $transportPrice);
            $distancePrice = $this->getDistancePrice($data['distance'], $transportPrice);
            if ($weightPrice == null && $distancePrice !== null) {
                $weightPrice = 1;
            } elseif ($weightPrice !== null && $distancePrice == null) {
                $distancePrice = 1;
            } elseif ($weightPrice == null && $distancePrice == null) {
                $weightPrice = $distancePrice = 0;
            }
            //公式
            $data['package_list'][$k]['settlement_amount'] = $data['package_list'][$k]['count_settlement_amount'] = round(
                floatval($weightPrice) *
                floatval($distancePrice)
                , 2);
            $data['settlement_amount'] = $data['count_settlement_amount'] = round(
                floatval($data['count_settlement_amount']) +
                floatval($data['package_list'][$k]['count_settlement_amount'])
                , 2);
            $data['package_settlement_amount'] = round($data['package_settlement_amount'] + $data['package_list'][$k]['count_settlement_amount'], 2);
        }
        return $data;
    }

    /**
     * 按重量价格档，获取单位重量价格
     * @param $weight
     * @param $transportPrice
     * @return int
     */
    public function getWeightPrice($weight, $transportPrice)
    {
        $weightPrice = collect($transportPrice['weight_list'])->where('start', '<=', $weight)->where('end', '>', $weight)->all();
        $weightPrice = array_values($weightPrice);
        return $weightPrice[0]['price'] ?? null;

    }

    /**
     * 按重量价格档，获取单位重量价格
     * @param $distance
     * @param $transportPrice
     * @return int
     */
    public function getDistancePrice($distance, $transportPrice)
    {
        $distancePrice = collect($transportPrice['km_list'])->where('start', '<=', $distance)->where('end', '>', $distance)->all();
        $distancePrice = array_values($distancePrice);
        return $distancePrice[0]['price'] ?? null;
    }
}
