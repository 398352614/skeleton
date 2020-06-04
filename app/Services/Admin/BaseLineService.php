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
     */
    public function getScheduleList($params)
    {
        if (CompanyTrait::getLineRule() === BaseConstService::LINE_RULE_POST_CODE) {
            $this->getScheduleListByPostcode($params);
        }else{
            $this->getScheduleListByArea($params);
        }
    }

    private function getScheduleListByPostcode($params)
    {
        $lineRange = $this->getLineRangeByPostcode($params['receiver_post_code']);
        for ($i = 0, $j = count($lineRange); $i < $j; $i++) {
            $line[$i] = parent::getInfo(['id' => $lineRange[$i]['line_id']], ['*'], false)->toArray();
            if (!empty($line[$i])) {
                //获得当前邮编范围的首天
                if ($lineRange[$i]['schedule'] === 0) {
                    $lineRange[$i]['schedule'] = 7;
                }
                if (Carbon::today()->dayOfWeek <= $lineRange[$i]['schedule']) {
                    $date = $lineRange[$i]['schedule'] - Carbon::today()->dayOfWeek;
                } else {
                    $date = $lineRange[$i]['schedule'] - Carbon::today()->dayOfWeek + 7;
                }
                //如果线路不自增，验证最大订单量
                if ($line[$i]['is_increment'] == BaseConstService::IS_INCREMENT_2) {
                }else{

                }
            }
        }
    }

    private function getScheduleListByArea($params)
    {
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
}
