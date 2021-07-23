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
use App\Traits\CompanyTrait;

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
        $lineRangeList = parent::getLineRangeListByPostcode($postCode, $params['place_country'], auth()->user()->id);
        $dateList = parent::getScheduleListByLineRangeList(['type' => $type], $lineRangeList, BaseConstService::TRACKING_ORDER_OR_BATCH_1);
        return $dateList;
    }

    public function getAllLineRange()
    {
        if (CompanyTrait::getLineRule() === BaseConstService::LINE_RULE_AREA) {
            return '';
        }
        $allLineRange = $this->getLineRangeService()->getList(['company_id' => auth()->user()->company_id], ['*'], false);
        foreach ($allLineRange as $k => $v) {
            $allLineRange[$k] = array_only_fields_sort($v, ['id', 'post_code_start', 'post_code_end', 'schedule']);
        }
        return $allLineRange;
    }
}
