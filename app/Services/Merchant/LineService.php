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
use App\Services\Merchant\BaseService;
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
        $postCode = $params['receiver_post_code'];
        $type = !empty($params['type']) ? intval($params['type']) : BaseConstService::ORDER_TYPE_2;
        $lineRangeList = parent::getLineRangeListByPostcode($postCode, auth()->user()->id);
        $dateList = parent::getScheduleListByLineRangeList(['type' => $type], $lineRangeList, BaseConstService::ORDER_OR_BATCH_1);
        return $dateList;
    }
}
