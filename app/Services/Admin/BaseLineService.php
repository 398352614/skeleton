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
use \Illuminate\Support\Carbon;
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
        $lineData = Arr::only($params, ['name', 'country', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days', 'remark']);
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
        $rowCount = parent::updateById($id, Arr::only($data, ['name', 'country', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days', 'remark']));
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
     * @param $id
     * @throws BusinessLogicException
     */
    public function check(&$params)
    {
        $params['country'] = CompanyTrait::getCountry();
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $params['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('仓库不存在！');
        }
    }

    /**
     * 通过线路ID 获取信息信息
     * @param $lineId
     * @param $info
     * @param $orderOrBatch
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getInfoByLineId($info, $lineId, $orderOrBatch)
    {
        //获取线路信息
        $line = parent::getInfo(['id' => $lineId], ['*'], false);
        if (empty($line)) {
            throw new BusinessLogicException('当前没有合适的线路，请先联系管理员');
        }
        $line = $line->toArray();
        $this->checkRule($info, $line, $orderOrBatch);
        return $line;
    }

    /**
     * 获取线路信息
     * @param $info
     * @param $orderOrBatch
     * @return array
     * @throws BusinessLogicException
     */
    public function getInfoByRule($info, $orderOrBatch = BaseConstService::ORDER_OR_BATCH_1)
    {
        $lineId = null;
        //分配规则
        if (auth()->user()->companyConfig->line_rule == BaseConstService::LINE_RULE_POST_CODE) {
            //获取邮编数字部分
            $postCode = explode_post_code($info['receiver_post_code']);
            //获取线路范围
            $lineRange = $this->getLineRangeService()->getInfo(['post_code_start' => ['<=', $postCode], 'post_code_end' => ['>=', $postCode], 'schedule' => Carbon::parse($info['execution_date'])->dayOfWeek, 'country' => $info['receiver_country']], ['*'], false);
            if (empty($lineRange)) {
                throw new BusinessLogicException('当前订单没有合适的线路，请先联系管理员');
            }
            $lineId = $lineRange->line_id;
        } else {
            if ($orderOrBatch === BaseConstService::ORDER_OR_BATCH_1) {
                $coordinate = ['lat' => $info['lat'], 'lon' => $info['lon']];
            } else {
                $coordinate = ['lat' => $info['receiver_lat'], 'lon' => $info['receiver_lon']];
            }
            $lineAreaList = $this->getLineAreaService()->getList([], ['line_id', 'coordinate_list'], false, ['line_id', 'coordinate_list', 'country'])->toArray();
            if (empty($lineAreaList)) {
                throw new BusinessLogicException('当前订单没有合适的线路，请先联系管理员');
            }
            foreach ($lineAreaList as $lineArea) {
                $coordinateList = json_decode($lineArea['coordinate_list'], true);
                if (!empty($coordinateList) && MapAreaTrait::containsPoint($coordinateList, $coordinate)) {
                    $lineId = $lineArea['line_id'];
                    break;
                }
            }
            unset($lineAreaList);
        }
        if (empty($lineId)) {
            throw new BusinessLogicException('当前订单没有合适的线路，请先联系管理员');
        }
        //获取线路信息
        $line = parent::getInfo(['id' => $lineId], ['*'], false);
        if (empty($line)) {
            throw new BusinessLogicException('当前订单没有合适的线路，请先联系管理员');
        }
        $line = $line->toArray();
        //验证规则
        $this->checkRule($info, $line, $orderOrBatch);
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
        if (date('Y-m-d') == $info['execution_date']) {
            if (time() > strtotime($info['execution_date'] . ' ' . $line['order_deadline'])) {
                throw new BusinessLogicException('当天下单已超过截止时间');
            }
        }
        //判断预约日期是否在可预约日期范围内
        if (Carbon::today()->addDays($line['appointment_days'])->lt($info['execution_date'] . ' 00:00:00')) {
            throw new BusinessLogicException('预约日期已超过可预约时间范围');
        }
        //若不是新增取件线路，则当前取件线路必须再最大订单量内
        if ($line['is_increment'] == BaseConstService::IS_INCREMENT_2) {
            if ($orderOrBatch === BaseConstService::ORDER_OR_BATCH_1) {
                if ($info['type'] == 1) {
                    $orderCount = $this->getTourService()->sumOrderCount($info, $line, 1);
                    if (1 + $orderCount['pickup_count'] > $line['pickup_max_count']) {
                        throw new BusinessLogicException('当前线路已达到最大取件订单数量');
                    };
                } else {
                    $orderCount = $this->getTourService()->sumOrderCount($info, $line, 2);
                    if (1 + $orderCount['pie_count'] > $line['pie_max_count']) {
                        throw new BusinessLogicException('当前线路已达到最大派件订单数量');
                    };
                }
            } else {
                $orderCount = $this->getTourService()->sumOrderCount($info, $line, 3);
                if ($info['expect_pickup_quantity'] + $orderCount['pickup_count'] > $line['pickup_max_count']) {
                    throw new BusinessLogicException('当前线路已达到最大取件订单数量');
                };
                if ($info['expect_pie_quantity'] + $orderCount['pie_count'] > $line['pie_max_count']) {
                    throw new BusinessLogicException('当前线路已达到最大派件订单数量');
                };
            }
        }
    }

    /**
     * @param $params
     * @param $orderOrBatch
     * @return array
     * @throws BusinessLogicException
     */
    public function getScheduleList($params,$orderOrBatch)
    {
        $dateList=[];
        if (CompanyTrait::getLineRule() === BaseConstService::LINE_RULE_POST_CODE) {
            $lineRange = $this->getLineRangeByPostcode($params['receiver_post_code']);
        }else{
            $coordinate = ['lat' => $params['lon'] ?? $params ['receiver_lon'], 'lon' => $params['lat'] ?? $params ['receiver_lon']];
            $lineRange = $this->getLineRangeByArea($coordinate);
        }
        for ($i = 0, $j = count($lineRange); $i < $j; $i++) {
            if(!empty($lineRange[$i])){
                $dateList =array_merge($dateList,$this->checkRuleForDate($params,$lineRange[$i],$orderOrBatch));
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
     * @param $info
     * @param $lineRange
     * @param $orderOrBatch
     * @return array
     * @throws BusinessLogicException
     */
    private function checkRuleForDate($info,$lineRange,$orderOrBatch)
    {
        $line = parent::getInfo(['id' => $lineRange['line_id']], ['*'], false)->toArray();
        $date = $this->getFirstWeekDate($lineRange);
        for ($k = 0, $l = $line['appointment_days'] - $date; $k < $l; $k = $k + 7) {
            $params = ['execution_date' => Carbon::today()->addDays($date + $k)->format("Y-m-d")];
            try {
                $this->deadlineCheck($params, $line);
            } catch (BusinessLogicException $e) {
                continue;
            }
            try {
                $this->appointmentDayCheck($params, $line);
            } catch (BusinessLogicException $e) {
                continue;
            }
            if ($line['is_increment'] === BaseConstService::IS_INCREMENT_2) {
                try {
                    $this->MaxCheck($line,$params,$orderOrBatch,$info['type']);
                } catch (BusinessLogicException $e) {
                    continue;
                }
                $dateList[] = $params['execution_date'];
            }
            return $dateList ?? [];
        }
    }

    /**
     * 通过邮编获得邮编范围
     * @param $postCode
     * @return array
     */
    private function getLineRangeByPostcode($postCode)
    {
        //获取邮编数字部分
        $postCode = explode_post_code($postCode ?? 0);
        //获取线路范围
        $lineRange = $this->getLineRangeService()->query
            ->where('post_code_start', '<=', $postCode)
            ->where('post_code_end', '>=', $postCode)
            ->where('country', CompanyTrait::getCountry())
            ->get()->toArray();
        return $lineRange ?? [];
    }

    /**
     * 通过经纬度获得区域范围
     * @param $coordinate
     * @return array
     */
    private function getLineRangeByArea($coordinate)
    {
        $lineAreaList = $this->getLineAreaService()->getList([], ['line_id', 'coordinate_list'], false, ['line_id', 'coordinate_list', 'country'])->toArray();
        if(!empty($lineAreaList)){
            foreach ($lineAreaList as $lineArea) {
                $coordinateList = json_decode($lineArea['coordinate_list'], true);
                if (!empty($coordinateList) && MapAreaTrait::containsPoint($coordinateList, $coordinate)) {
                    $lineRange[] = $lineArea;
                }
            }
        }
        return $lineRange ?? [];
    }

    /**
     * 当日截止时间检查
     * @param $info
     * @param $line
     * @return mixed
     * @throws BusinessLogicException
     */
    private function deadlineCheck($info,$line){
        if (date('Y-m-d') == $info['execution_date']) {
            if (time() > strtotime($info['execution_date'] . ' ' . $line['order_deadline'])) {
                throw new BusinessLogicException('当天下单已超过截止时间');
            }
        }
        return;
    }

    /**
     * 截至日期检查
     * @param $info
     * @param $line
     * @return mixed
     * @throws BusinessLogicException
     */
    private function appointmentDayCheck($info,$line){
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
    private function pickupMaxCheck($info,$line){
        $orderCount = $this->getTourService()->sumOrderCount($info, $line, 1);
        if (1 + $orderCount['pickup_count'] > $line['pickup_max_count']) {
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
    private function pieMaxCheck($info,$line){
        $orderCount = $this->getTourService()->sumOrderCount($info, $line, 2);
        if (1 + $orderCount['pie_count'] > $line['pie_max_count']) {
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
    private function MaxBatchCheck(array $info, array $line)
    {
        $orderCount = $this->getTourService()->sumOrderCount($info, $line, 1);
        if ($info['expect_pickup_quantity'] + $orderCount['pickup_count'] > $line['pickup_max_count']) {
            throw new BusinessLogicException('当前线路已达到最大取件订单数量');
        };
        if ($info['expect_pickup_quantity'] + $orderCount['pickup_count'] > $line['pickup_max_count']) {
            throw new BusinessLogicException('当前线路已达到最大取件订单数量');
        };
        return;
    }

    /**
     * 获取首周可选日期
     * @param $lineRange
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
        return $date;
    }

    /**
     * @param $line
     * @param $params
     * @param $orderOrBatch
     * @param $type
     * @throws BusinessLogicException
     */
    private function MaxCheck($line,$params,$orderOrBatch,$type)
    {
        if($orderOrBatch === 2){
            $this->MaxBatchCheck($params, $line);
        }elseif ($orderOrBatch === 1 && $type === '1'){
            $this->pickupMaxCheck($params, $line);
        }elseif ($orderOrBatch === 1 && $type === '2'){
            $this->pieMaxCheck($params, $line);
        }
    }
}
