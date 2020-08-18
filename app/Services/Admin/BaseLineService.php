<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/7
 * Time: 15:58
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\LineResource;
use App\Models\Line;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\CompanyTrait;
use App\Traits\ImportTrait;
use App\Traits\MapAreaTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;

class BaseLineService extends BaseService
{
    use ImportTrait;

    public $filterRules = [
        'name' => ['like', 'name'],
        'country' => ['=', 'country'],
    ];

    public $orderBy = ['id' => 'asc'];

    public function __construct(Line $line)
    {
        parent::__construct($line, LineResource::class);
    }

    /**
     * 邮编线路范围 服务
     * @return LineRangeService
     */
    public function getLineRangeService()
    {
        return self::getInstance(LineRangeService::class);
    }

    /**
     * 商户线路范围 服务
     * @return MerchantLineRangeService
     */
    public function getMerchantLineRangeService()
    {
        return self::getInstance(MerchantLineRangeService::class);
    }

    /**
     * 区域线路范围 服务
     * @return LineAreaService
     */
    public function getLineAreaService()
    {
        return self::getInstance(LineAreaService::class);
    }

    /**
     * 仓库 服务
     * @return WareHouseService
     */
    public function getWareHouseService()
    {
        return self::getInstance(WareHouseService::class);
    }

    /**
     * 取件线路 服务
     * @return TourService
     */
    private function getTourService()
    {
        return self::getInstance(TourService::class);
    }

    /**
     * 新增
     * @param $params
     * @param $rule
     * @return int
     * @throws BusinessLogicException
     */
    public function store($params, $rule = BaseConstService::LINE_RULE_POST_CODE)
    {
        $lineData = Arr::only($params, ['name', 'country', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days', 'remark', 'status']);
        $lineData = array_merge($lineData, ['rule' => $rule, 'creator_id' => auth()->id(), 'creator_name' => auth()->user()->fullname]);
        $lineId = parent::insertGetId($lineData);
        if ($lineId === 0) {
            throw new BusinessLogicException('线路新增失败');
        }
        return $lineId;
    }

    /**
     * 修改
     * @param $id
     * @param $data
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $rowCount = parent::updateById($id, Arr::only($data, ['name', 'country', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days', 'remark', 'status']));
        if ($rowCount === false) {
            throw new BusinessLogicException('线路修改失败');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $tour = $this->getTourService()->getInfo(['line_id' => $id, 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_4]]], ['id'], false);
        if (!empty($tour)) {
            throw new BusinessLogicException('当前正在使用该线路，不能操作');
        }
        //删除线路
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路删除失败');
        }
    }

    /**
     * 批量启用禁用
     * @param $data
     * @return string
     * @throws BusinessLogicException
     */
    public function statusByList($data)
    {
        $idList = explode_id_string($data['id_list']);
        //若是启用,则直接更新
        if (intval($data['status']) == BaseConstService::ON) {
            $rowCount = parent::update(['id' => ['in', $idList]], ['status' => $data['status']]);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败');
            }
            return 'true';
        }
        //若是禁用,则需要验证是否存在取派的线路
        foreach ($idList as $id) {
            $tour = $this->getTourService()->getInfo(['line_id' => $id, 'status' => ['<>', BaseConstService::TOUR_STATUS_5]], ['id', 'tour_no', 'line_name'], false);
            if (!empty($tour)) {
                throw new BusinessLogicException('线路[:line]存在取派任务线路[:tour_no]，不能操作', 1000, ['line' => $tour->line_name, 'tour_no' => $tour->tour_no]);
            }
            $rowCount = parent::update(['id' => $id], ['status' => $data['status']]);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败');
            }
        }
        return 'true';
    }

    /**
     * 验证
     * @param $params
     * @param $dbInfo
     * @throws BusinessLogicException
     */
    public function check(&$params, $dbInfo = [])
    {
        $params['country'] = !empty($dbInfo['country']) ? $dbInfo['country'] : CompanyTrait::getCountry();
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $params['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('仓库不存在！');
        }
    }

    /**
     * 通过线路ID 获取信息(仅站点)
     * @param $info
     * @param $params
     * @param $orderOrBatch
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function getInfoByLineId($info, $params)
    {
        //获取线路信息
        $line = parent::getInfo(['id' => $params['line_id']], ['*'], false);
        if (empty($line)) {
            throw new BusinessLogicException('当前没有合适的线路，请先联系管理员');
        }
        $line = $line->toArray();
        if (intval($line['status']) === BaseConstService::OFF) {
            throw new BusinessLogicException('当前线路[:line]已被禁用', 1000, ['line' => $line['name']]);
        }
        $where = ['line_id' => $params['line_id'], 'schedule' => Carbon::create($params['execution_date'])->dayOfWeek];
        if (CompanyTrait::getLineRule() === BaseConstService::LINE_RULE_POST_CODE) {
            $data = $this->getLineRangeService()->getInfo($where, ['*'], false);
        } else {
            $data = $this->getLineAreaService()->getInfo($where, ['*'], false);
        }
        if (empty($data)) {
            throw new BusinessLogicException('当前没有合适的线路，请先联系管理员');
        }
        $this->checkRule($info, $line, BaseConstService::ORDER_OR_BATCH_2, false);
        return $line;
    }

    /**
     * @param $info
     * @return mixed|string
     * @throws BusinessLogicException
     */
    public function getLineIdByInfo($info)
    {
        if (CompanyTrait::getLineRule() === BaseConstService::LINE_RULE_POST_CODE) {
            $lineRange = $this->getLineRangeByPostcode($info['receiver_post_code'], null);
        } else {
            $coordinate = ['lat' => $info['lat'] ?? $info ['receiver_lat'], 'lon' => $info['lon'] ?? $info ['receiver_lon']];
            $lineRange = $this->getLineRangeByArea($coordinate, null);
        }
        if (!empty($lineRange['line_id'])) {
            //获取线路信息
            $line = parent::getInfo(['id' => $lineRange['line_id']], ['*'], false);
            if (empty($line)) {
                throw new BusinessLogicException('当前没有合适的线路，请先联系管理员');
            }
            $line = $line->toArray();
            if (intval($line['status']) === BaseConstService::OFF) {
                return '';
            }
        }
        return $lineRange['line_id'] ?? '';
    }

    /**
     * 获取线路信息
     * @param $info
     * @param $orderOrBatch
     * @param $merchantAlone
     * @return array
     * @throws BusinessLogicException
     */
    public function getInfoByRule($info, $orderOrBatch = BaseConstService::ORDER_OR_BATCH_1, $merchantAlone = BaseConstService::NO)
    {
        $lineRange = $this->getLineRange($info, $merchantAlone);
        $line = parent::getInfo(['id' => $lineRange['line_id']], ['*'], false);
        if (empty($line)) {
            throw new BusinessLogicException('当前订单没有合适的线路，请先联系管理员');
        }
        $line = $line->toArray();
        if (intval($line['status']) === BaseConstService::OFF) {
            throw new BusinessLogicException('当前线路[:line]已被禁用', 1000, ['line' => $line['name']]);
        }
        //验证规则
        $this->checkRule($info, $line, $orderOrBatch);
        if ($merchantAlone == BaseConstService::YES) {
            $line['range_merchant_id'] = $lineRange['range_merchant_id'];
        }
        return $line;
    }

    /**
     * 验证规则
     * @param $info
     * @param $line
     * @param $orderOrBatch
     * @param bool $deadLineCheck
     * @throws BusinessLogicException
     */
    public function checkRule($info, $line, $orderOrBatch, $deadLineCheck = true)
    {
        //预约当天的，需要判断是否在下单截止日期内
        if ($deadLineCheck == true) {
            $this->deadlineCheck($info, $line);
        }
        //判断预约日期是否在可预约日期范围内
        $this->appointmentDayCheck($info, $line);
        //若不是新增取件线路，则当前取件线路必须再最大订单量内
        $this->maxCheck($info, $line, $orderOrBatch);
    }

    /**
     * 获取可选日期
     * @param $params
     * @param $orderOrBatch
     * @return array
     * @throws BusinessLogicException
     */
    public function getScheduleList($params, $orderOrBatch = BaseConstService::ORDER_OR_BATCH_1)
    {
        $lineRangeList = $this->getLineRangeList($params);
        return $this->getScheduleListByLineRangeList($params, $lineRangeList, $orderOrBatch);
    }

    /**
     * 通过线路获得可选日期（仅站点）
     * @param $params
     * @param $lineId
     * @return array
     * @throws BusinessLogicException
     */
    public function getScheduleListByLine($params, $lineId)
    {
        $lineRangeList = $this->getLineRangeListByLine($lineId);
        return $this->getScheduleListByLineRangeList($params, $lineRangeList, BaseConstService::ORDER_OR_BATCH_2, false);
    }

    /**
     * 通过线路获得线路范围列表（仅站点）
     * @param $params
     * @return array
     */
    private function getLineRangeListByLine($params)
    {
        if (CompanyTrait::getLineRule() === BaseConstService::LINE_RULE_POST_CODE) {
            $lineRangeList = $this->getMerchantLineRangeService()->getList(['line_id' => $params], ['*'], false)->toArray();
        } else {
            $lineRangeList = $this->getLineAreaService()->getList(['line_id' => $params], ['*'], false)->toArray();
        }
        $lineRangeList = collect($lineRangeList)->groupBy('schedule')->all();
        foreach ($lineRangeList as $v) {
            $newLineRangeList[] = $v[0];
        }
        return $newLineRangeList ?? [];
    }

    /**
     * 获取线路范围
     * @param $info
     * @param $merchantAlone
     * @return array|mixed
     * @throws BusinessLogicException
     */
    private function getLineRange($info, $merchantAlone)
    {
        if (CompanyTrait::getLineRule() === BaseConstService::LINE_RULE_POST_CODE) {
            if ($merchantAlone == BaseConstService::YES) {
                $lineRange = $this->getMerchantLineRangeByPostcode($info['receiver_post_code'], $info['execution_date'], $info['merchant_id']);
            } else {
                $lineRange = $this->getLineRangeByPostcode($info['receiver_post_code'], $info['execution_date']);
            }
        } else {
            $coordinate = ['lat' => $info['lat'] ?? $info ['receiver_lat'], 'lon' => $info['lon'] ?? $info ['receiver_lon']];
            $lineRange = $this->getLineRangeByArea($coordinate, $info['execution_date']);
        }
        if (empty($lineRange)) {
            throw new BusinessLogicException('当前订单没有合适的线路，请先联系管理员');
        }
        if (!empty($lineRange['is_alone']) && (intval($lineRange['is_alone']) == BaseConstService::YES)) {
            $lineRange['range_merchant_id'] = $lineRange['merchant_id'] ?? 0;
        } else {
            $lineRange['range_merchant_id'] = 0;
        }
        return $lineRange;
    }

    /**
     * 获取线路范围列表
     * @param $params
     * @return array
     */
    public function getLineRangeList($params)
    {
        if (CompanyTrait::getLineRule() === BaseConstService::LINE_RULE_POST_CODE) {
            $lineRangeList = $this->getLineRangeListByPostcode($params['receiver_post_code'], $params['merchant_id'] ?? null);
        } else {
            $coordinate = ['lat' => $params['lat'] ?? $params['receiver_lat'], 'lon' => $params['lon'] ?? $params['receiver_lon']];
            $lineRangeList = $this->getLineRangeListByArea($coordinate);
        }
        return $lineRangeList;
    }

    /**
     * 通过邮编获得线路范围
     * @param $postCode
     * @param $executionDate
     * @param $merchantId
     * @return array
     */
    private function getMerchantLineRangeByPostcode($postCode, $executionDate, $merchantId = null)
    {
        //若邮编是纯数字，则认为是比利时邮编
        $country = is_numeric(trim($postCode)) ? BaseConstService::POSTCODE_COUNTRY : CompanyTrait::getCountry();
        //获取邮编数字部分
        $postCode = explode_post_code($postCode);
        //获取线路范围
        $query = $this->getMerchantLineRangeService()->query
            ->where('post_code_start', '<=', $postCode)
            ->where('post_code_end', '>=', $postCode)
            ->where('country', $country);
        //若存在商户ID，则加
        !empty($merchantId) && $query->where('merchant_id', $merchantId);
        //若存在取派日期，则加上取派日期条件
        !empty($executionDate) && $query->where('schedule', Carbon::create($executionDate)->dayOfWeek);
        $query = $query->first();
        return !empty($query) ? $query->toArray() : [];
    }

    /**
     * 通过邮编获得线路范围
     * @param $postCode
     * @param $executionDate
     * @return array
     */
    private function getLineRangeByPostcode($postCode, $executionDate)
    {
        //若邮编是纯数字，则认为是比利时邮编
        $country = is_numeric(trim($postCode)) ? BaseConstService::POSTCODE_COUNTRY : CompanyTrait::getCountry();
        //获取邮编数字部分
        $postCode = explode_post_code($postCode);
        //获取线路范围
        $query = $this->getLineRangeService()->query
            ->where('post_code_start', '<=', $postCode)
            ->where('post_code_end', '>=', $postCode)
            ->where('country', $country);
        //若存在取派日期，则加上取派日期条件
        !empty($executionDate) && $query->where('schedule', Carbon::create($executionDate)->dayOfWeek);
        $query = $query->first();
        return !empty($query) ? $query->toArray() : [];
    }

    /**
     * 通过邮编获得线路范围列表
     * @param  $postCode
     * @param $merchantId
     * @return array
     */
    public function getLineRangeListByPostcode($postCode, $merchantId = null)
    {
        //若邮编是纯数字，则认为是比利时邮编
        $country = is_numeric(trim($postCode)) ? BaseConstService::POSTCODE_COUNTRY : CompanyTrait::getCountry();
        //获取邮编数字部分
        $postCode = explode_post_code($postCode);
        //获取线路范围
        $query = $this->getMerchantLineRangeService()->query
            ->where('post_code_start', '<=', $postCode)
            ->where('post_code_end', '>=', $postCode)
            ->where('country', $country);
        !empty($merchantId) && $query = $query->where('merchant_id', $merchantId);
        $lineRangeList = $query->get()->toArray();
        return $lineRangeList ?? [];
    }

    /**
     * 通过经纬度获得线路范围
     * @param $coordinate
     * @param $date
     * @return array
     */
    private function getLineRangeByArea($coordinate, $date)
    {
        $lineRange = [];
        if (empty($date)) {
            $lineAreaList = $this->getLineAreaService()->getList([], ['line_id', 'coordinate_list'], false)->toArray();
        } else {
            $schedule = Carbon::create($date)->dayOfWeek;
            $lineAreaList = $this->getLineAreaService()->getList(['schedule' => $schedule], ['line_id', 'coordinate_list'], false)->toArray();
        }
        if (!empty($lineAreaList)) {
            foreach ($lineAreaList as $lineArea) {
                $coordinateList = json_decode($lineArea['coordinate_list'], true);
                if (!empty($coordinateList) && MapAreaTrait::containsPoint($coordinateList, $coordinate)) {
                    $lineRange = $lineArea;
                    break;
                }
            }
        }
        return $lineRange ?? [];
    }

    /**
     * 通过经纬度获得线路范围列表
     * @param array $coordinate
     * @return array
     */
    private function getLineRangeListByArea(array $coordinate)
    {
        $lineAreaList = $this->getLineAreaService()->getList([], ['line_id', 'coordinate_list', 'schedule'], false)->toArray();
        if (!empty($lineAreaList)) {
            foreach ($lineAreaList as $lineArea) {
                $coordinateList = json_decode($lineArea['coordinate_list'], true);
                if (!empty($coordinateList) && MapAreaTrait::containsPoint($coordinateList, $coordinate)) {
                    $lineRangeList[] = $lineArea;
                }
            }
        }
        return $lineRangeList ?? [];
    }


    /**
     * 通过线路范围列表获取可选日期列表
     * @param $params
     * @param array $lineRangeList
     * @param int $orderOrBatch
     * @param $deadLineCheck
     * @return array
     * @throws BusinessLogicException
     */
    public function getScheduleListByLineRangeList($params, array $lineRangeList, int $orderOrBatch, $deadLineCheck = true)
    {
        $dateList = [];
        for ($i = 0, $j = count($lineRangeList); $i < $j; $i++) {
            if (!empty($lineRangeList[$i])) {
                $dateList = array_merge($dateList, $this->checkRuleForDate($params, $lineRangeList[$i], $orderOrBatch, $deadLineCheck));
            }
        }
        asort($dateList);
        $dateList = array_values($dateList);
        if (empty($dateList)) {
            throw new BusinessLogicException('当前订单没有合适的线路，请先联系管理员');
        }
        return $dateList ?? [];
    }

    /**
     * 验证规则 获取可选日期
     * @param $params
     * @param $lineRange
     * @param $orderOrBatch
     * @param bool $deadLineCheck
     * @return array
     */
    private function checkRuleForDate($params, $lineRange, $orderOrBatch, $deadLineCheck = true)
    {
        $line = parent::getInfo(['id' => $lineRange['line_id']], ['*'], false);
        if (!empty($line)) {
            $line = $line->toArray();
            if (intval($line['status']) == BaseConstService::OFF) {
                return [];
            }
            $date = $this->getFirstWeekDate($lineRange);
            for ($k = 0, $l = $line['appointment_days'] - $date; $k < $l; $k = $k + 7) {
                $params['execution_date'] = Carbon::today()->addDays($date + $k)->format("Y-m-d");
                try {
                    if ($deadLineCheck == true) {
                        $this->deadlineCheck($params, $line);
                    }
                    $this->appointmentDayCheck($params, $line);
                    $this->maxCheck($params, $line, $orderOrBatch);
                } catch (BusinessLogicException $e) {
                    continue;
                }
                $dateList[] = $params['execution_date'];
            }
        }
        return $dateList ?? [];
    }

    /**
     * 获取首周可选日期
     * @param $lineRange
     * @return int
     */
    private function getFirstWeekDate($lineRange)
    {
        if ($lineRange['schedule'] === 0) {
            $lineRange['schedule'] = 7;
        }
        $date = $lineRange['schedule'] - Carbon::today()->dayOfWeek;
        if (Carbon::today()->dayOfWeek > $lineRange['schedule']) {
            $date = $date + 7;
        }
        if ($lineRange['schedule'] === 7 && Carbon::today()->dayOfWeek === 0) {
            $date = 0;
        }
        return $date;
    }

    /**
     * 当日截止时间检查
     * @param $info
     * @param $line
     * @return mixed
     * @throws BusinessLogicException
     */
    private function deadlineCheck($info, $line)
    {
        if (date('Y-m-d') == $info['execution_date']) {
            if (time() > strtotime($info['execution_date'] . ' ' . $line['order_deadline'])) {
                throw new BusinessLogicException('当天下单已超过截止时间');
            }
        }
        return;
    }

    /**
     * 最大订单量检查
     * @param $params
     * @param $line
     * @param $orderOrBatch
     * @throws BusinessLogicException
     */
    private function maxCheck($params, $line, $orderOrBatch)
    {
        if ($line['is_increment'] === BaseConstService::IS_INCREMENT_2) {
            if ($orderOrBatch === 2) {
                $this->maxBatchCheck($params, $line);
            } elseif ($orderOrBatch === 1 && intval($params['type']) === BaseConstService::ORDER_TYPE_1) {
                $this->pickupMaxCheck($params, $line);
            } elseif ($orderOrBatch === 1 && intval($params['type']) === BaseConstService::ORDER_TYPE_2) {
                $this->pieMaxCheck($params, $line);
            }
        }
    }

    /**
     * 截至日期检查
     * @param $info
     * @param $line
     * @return mixed
     * @throws BusinessLogicException
     */
    private function appointmentDayCheck($info, $line)
    {
        //判断预约日期是否在可预约日期范围内
        if (Carbon::today()->addDays($line['appointment_days'])->lt($info['execution_date'] . ' 00:00:00')) {
            throw new BusinessLogicException('预约日期已超过可预约时间范围');
        }
        return;
    }

    /**
     * 最大取件量-订单检查
     * @param $info
     * @param $line
     * @return mixed
     * @throws BusinessLogicException
     */
    private function pickupMaxCheck($info, $line)
    {
        $orderCount = $this->getTourService()->sumOrderCount($info, $line, 1);
        if (1 + intval($orderCount['pickup_count']) > intval($line['pickup_max_count'])) {
            throw new BusinessLogicException('当前线路已达到最大取件订单数量');
        };
        return;
    }

    /**
     * 最大派件量-订单检查
     * @param $info
     * @param $line
     * @return mixed
     * @throws BusinessLogicException
     */
    private function pieMaxCheck($info, $line)
    {
        $orderCount = $this->getTourService()->sumOrderCount($info, $line, 2);
        if (1 + intval($orderCount['pie_count']) > intval($line['pie_max_count'])) {
            throw new BusinessLogicException('当前线路已达到最大派件订单数量');
        };
        return;
    }

    /**
     * 最大取件量&派件量 站点检查
     * @param array $info
     * @param array $line
     * @return mixed
     * @throws BusinessLogicException
     */
    private function maxBatchCheck(array $info, array $line)
    {
        $orderCount = $this->getTourService()->sumOrderCount($info, $line, 3);
        if (intval($info['expect_pickup_quantity']) > 0) {
            if (intval($info['expect_pickup_quantity']) + intval($orderCount['pickup_count']) > intval($line['pickup_max_count'])) {
                throw new BusinessLogicException('当前线路已达到最大取件订单数量');
            };
        }
        if (intval($info['expect_pie_quantity']) > 0) {
            if (intval($info['expect_pie_quantity']) + intval($orderCount['pie_count']) > intval($line['pie_max_count'])) {
                throw new BusinessLogicException('当前线路已达到最大派件订单数量');
            };
        }
        return;
    }
}
