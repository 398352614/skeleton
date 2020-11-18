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

    /**
     * 获取日期列表
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function getDateListByPostCode($params)
    {
        if (CompanyTrait::getLineRule() === BaseConstService::LINE_RULE_AREA) {
            return [];
        }
        $postCode = $params['place_post_code'];
        if (empty($params['type'])) {
            $type = BaseConstService::TRACKING_ORDER_TYPE_2;
        } elseif ($params['type'] == BaseConstService::ORDER_TYPE_3) {
            $type = BaseConstService::ORDER_TYPE_1;
        } else {
            $type = intval($params['type']);
        }
        $lineRangeList = parent::getLineRangeListByPostcode($postCode, auth()->user()->id);
        $dateList = parent::getScheduleListByLineRangeList(['type' => $type], $lineRangeList, BaseConstService::TRACKING_ORDER_OR_BATCH_1);
        return $dateList;
    }
}
