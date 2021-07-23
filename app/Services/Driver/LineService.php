<?php
/**
 * 线路 服务
 * User: long
 * Date: 2019/12/21
 * Time: 10:05
 */

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Models\Line;
use App\Services\BaseConstService;
use App\Traits\CompanyTrait;
use Illuminate\Support\Carbon;

class LineService extends BaseLineService
{
    public function __construct(Line $line)
    {
        parent::__construct($line);
    }

    /**
     * 获取线路列表
     * @return mixed
     */
    public function getPageList()
    {
        return parent::getPageList();
    }

    /**
     * 获取最近的日期
     * @param $params
     * @param $merchantId
     * @return array
     * @throws BusinessLogicException
     */
    public function getCurrentDate($params, $merchantId)
    {
        $params['merchant_id'] =$merchantId;
        if (CompanyTrait::getLineRule() === BaseConstService::LINE_RULE_AREA) {
            throw new BusinessLogicException('没有合适日期');
        }
        $lineRangeList = parent::getLineRangeListByPostcode($params['place_post_code'],$params['place_country'],  $merchantId);
        $executionDate = null;
        $newLine = null;
        foreach ($lineRangeList as $lineRange) {
            $line = parent::getInfo(['id' => $lineRange['line_id']], ['*'], false);
            if (empty($line) || ($line->status == BaseConstService::OFF)) {
                continue;
            }
            $line = $line->toArray();
            $date = $this->getFirstWeekDate($lineRange);
            $now = Carbon::today()->format('Y-m-d');
            for ($k = 0, $l = $line['appointment_days'] - $date; $k < $l; $k = $k + 7) {
                $params['execution_date'] = Carbon::today()->addDays($date + $k)->format('Y-m-d');
                try {
                    //若是今天，则不需要
                    if ($now == $params['execution_date']) continue;
                    $this->appointmentDayCheck($params, $line);
                    $this->maxCheck($params, $line, BaseConstService::TRACKING_ORDER_OR_BATCH_1);
                    $this->minCheck($params, $line, BaseConstService::TRACKING_ORDER_OR_BATCH_1);
                    //取最近日期
                    if (empty($executionDate) || Carbon::parse($executionDate . ' 00:00:00')->gt($params['execution_date'] . ' 00:00:00')) {
                        $executionDate = $params['execution_date'];
                        $newLine = $line;
                    }
                    break;
                } catch (BusinessLogicException $e) {
                    continue;
                }
            }
        }
        if (empty($executionDate)) {
            throw new BusinessLogicException('没有合适日期');
        }
        return [$executionDate, $newLine];
    }
}
