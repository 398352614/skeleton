<?php
/**
 * 线路 服务
 * User: long
 * Date: 2019/12/21
 * Time: 10:05
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Models\Line;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\CompanyTrait;
use Illuminate\Support\Carbon;

class LineService extends BaseLineService
{
    public function __construct(Line $line)
    {
        parent::__construct($line);
    }

    public function getDateListByPostCode($postCode)
    {
        if (CompanyTrait::getLineRule() === BaseConstService::LINE_RULE_AREA) {
            return [];
        }
        //$lineRangeList = parent::getLineRangeListByPostcode($postCode);
        //$dateList = parent::getScheduleListByLineRange(['type' => BaseConstService::ORDER_TYPE_2], $lineRangeList, BaseConstService::ORDER_OR_BATCH_1);
        //return $dateList;
    }
}
