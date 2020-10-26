<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/7
 * Time: 15:58
 */

namespace App\Services\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\LineResource;
use App\Models\Holiday;
use App\Models\HolidayDate;
use App\Models\Line;
use App\Models\MerchantHoliday;
use App\Services\Admin\MerchantLineRangeService;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\CompanyTrait;
use App\Traits\ImportTrait;
use App\Traits\MapAreaTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;

class BaseLineService extends BaseService
{
    use ImportTrait;

    public $filterRules = [
        'name' => ['like', 'name'],
        'country' => ['=', 'country'],
    ];

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
     * 获取线路信息
     * @param $info
     * @param $orderOrBatch
     * @param $merchantAlone
     * @return array
     * @throws BusinessLogicException
     */
    public function getInfoByRule($info, $orderOrBatch = BaseConstService::TRACKING_ORDER_OR_BATCH_1, $merchantAlone = BaseConstService::NO)
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
     * @throws BusinessLogicException
     */
    public function checkRule($info, $line, $orderOrBatch)
    {
        //预约当天的，需要判断是否在下单截止日期内
        $this->deadlineCheck($info, $line);
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
    public function getScheduleList($params, $orderOrBatch = BaseConstService::TRACKING_ORDER_OR_BATCH_1)
    {
        $lineRangeList = $this->getLineRangeList($params);
        return $this->getScheduleListByLineRangeList($params, $lineRangeList, $orderOrBatch);
    }

    /**
     * 获取线路范围
     * @param $info
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
            $lineRangeList = $this->getLineRangeListByPostcode($params['receiver_post_code'], auth()->user()->id);
        } else {
            $coordinate = ['lat' => $params['lat'] ?? $params ['receiver_lat'], 'lon' => $params['lon'] ?? $params ['receiver_lon']];
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
        $country = post_code_be($postCode) ? BaseConstService::POSTCODE_COUNTRY_BE : CompanyTrait::getCountry();
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
        $country = post_code_be($postCode) ? BaseConstService::POSTCODE_COUNTRY_BE : CompanyTrait::getCountry();
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
        $country = post_code_be($postCode) ? BaseConstService::POSTCODE_COUNTRY_BE : CompanyTrait::getCountry();
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
     * @return array
     * @throws BusinessLogicException
     */
    public function getScheduleListByLineRangeList($params, array $lineRangeList, int $orderOrBatch)
    {
        $dateList = [];
        for ($i = 0, $j = count($lineRangeList); $i < $j; $i++) {
            if (!empty($lineRangeList[$i])) {
                $dateList = array_merge($dateList, $this->checkRuleForDate($params, $lineRangeList[$i], $orderOrBatch));
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
     * @return array
     */
    private function checkRuleForDate($params, $lineRange, $orderOrBatch)
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
                    $this->deadlineCheck($params, $line, true);
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
     * @param $isForDate bool 是否是为了获取日期
     * @return mixed
     * @throws BusinessLogicException
     */
    private function deadlineCheck($info, $line, $isForDate = false)
    {
        if (date('Y-m-d') == $info['execution_date']) {
            //只有商户端须要,延后时间
            $time = Carbon::parse(now());
            //如果不是为了获取日期,则需要延后时间
            if (($isForDate == false) && !empty(auth()->user()->delay_time)) {
                $time->subMinutes(auth()->user()->delay_time);
            }
            if ($time->getTimestamp() > strtotime($info['execution_date'] . ' ' . $line['order_deadline'])) {
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
        //只有商户端须要,可预约日期取商户和线路中最小的
        $appointmentDays = $line['appointment_days'];
        if (!empty(auth()->user()->appointment_days) && (auth()->user()->appointment_days < $appointmentDays)) {
            $appointmentDays = auth()->user()->appointment_days;
        }
        //判断预约日期是否在可预约日期范围内
        if (Carbon::today()->addDays($appointmentDays)->lt($info['execution_date'] . ' 00:00:00')) {
            throw new BusinessLogicException('预约日期已超过可预约时间范围');
        }
        //提前下单天数判断
        if (!empty(auth()->user()->advance_days) && Carbon::today()->addDays(auth()->user()->advance_days)->gt($info['execution_date'] . ' 00:00:00')) {
            throw new BusinessLogicException('当前预约必须提前[:count_days]天预约', 1000, ['count_days' => auth()->user()->advance_days]);
        }
        //判断是否是放假日期-管理员端不用限制
        $merchantHoliday = MerchantHoliday::query()->where(['merchant_id' => auth()->user()->id])->first(['holiday_id']);
        if (empty($merchantHoliday)) return;
        $holiday = Holiday::query()->where('id', $merchantHoliday->holiday_id)->where('status', BaseConstService::ON)->first();
        if (empty($holiday)) return;
        $holidayDate = HolidayDate::query()->where('holiday_id', $merchantHoliday->holiday_id)->where('date', $info['execution_date'])->first();
        if (!empty($holidayDate)) {
            throw new BusinessLogicException('该预约日期是放假日期，不可预约');
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
