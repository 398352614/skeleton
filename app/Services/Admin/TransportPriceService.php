<?php
/**
 * 运价管理
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\TransportPriceResource;
use App\Models\KilometresCharging;
use App\Models\OrderItem;
use App\Models\SpecialTimeCharging;
use App\Models\TransportPrice;
use App\Models\WeightCharging;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Arr;

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
        $this->model = $transportPrice;
        $this->query = $this->model::query();
        $this->resource = TransportPriceResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
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
            $item['km_list'] = $kmList[$transportPriceId];
            $item['weight_list'] = $weightList[$transportPriceId];
            $item['special_time_list'] = $specialTimeList[$transportPriceId];
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
        for($i=0;$i<count($info['special_time_list']);$i++){
            $info['special_time_list'][$i]['period']=[$info['special_time_list'][$i]['start'],$info['special_time_list'][$i]['end']];
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
            throw new BusinessLogicException('新增失败,请重新操作');
        }
        $this->insertDetailsAll($transportPrice->getAttribute('id'), $params);
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
        $this->check($params);
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败,请重新操作');
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
    }

    /**
     * 状态-启用/禁用
     * @param $id
     * @param $data
     * @throws BusinessLogicException
     */
    public function status($id, $data)
    {
        $rowCount = parent::updateById($id, ['status' => $data['status']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败,请重新操作');
        }
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
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $info->toArray();
        $price = $info['starting_price'];
        //公里计费
        if (!empty($params['km'])) {
            $km = $this->kilometresChargingModel->newQuery()->where('start', '<=', $params['km'])->where('end', '>', $params['km'])->first();
            if (empty($km)) {
                throw new BusinessLogicException('当前公里不在该运价范围内');
            }
            $km = $km->toArray();
            $price += $km['price'];
        }
        //重量计费
        if (!empty($params['weight'])) {
            $weight = $this->weightChargingModel->newQuery()->where('start', '<=', $params['weight'])->where('end', '>', $params['weight'])->first();
            if (empty($weight)) {
                throw new BusinessLogicException('当前重量不在该运价范围内');
            }
            $weight = $weight->toArray();
            $price += $weight['price'];
        }
        //特殊时段计费
        if (!empty($params['special_time'])) {
            $specialTime = $this->specialTimeChargingModel->newQuery()->where('start', '<=', $params['special_time'])->where('end', '>', $params['special_time'])->first();
            if (empty($specialTime)) {
                throw new BusinessLogicException('当前时间不在该运价范围内');
            }
            $specialTime = $specialTime->toArray();
            $price += $specialTime['price'];
        }
        $info = $this->show($id);
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
                throw new BusinessLogicException('新增失败,请重新操作');
            }
        }
        //重量计费
        if (!empty($params['weight_list'])) {
            data_fill($params, 'weight_list.*.transport_price_id', $id);
            $rowCount = $this->weightChargingModel->insertAll($params['weight_list']);
            if ($rowCount === false) {
                throw new BusinessLogicException('新增失败,请重新操作');
            }
        }
        //特殊时段计费
        if (!empty($params['special_time_list'])) {
            data_fill($params, 'special_time_list.*.transport_price_id', $id);
            $rowCount = $this->specialTimeChargingModel->insertAll($params['special_time_list']);
            if ($rowCount === false) {
                throw new BusinessLogicException('新增失败,请重新操作');
            }
        }
    }
}
