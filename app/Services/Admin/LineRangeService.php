<?php
/**
 * 线路范围 服务
 * User: long
 * Date: 2019/12/21
 * Time: 10:37
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\LineRange;
use http\Exception\BadConversionException;
use Illuminate\Support\Facades\DB;

class LineRangeService extends BaseService
{
    public function __construct(LineRange $lineRange)
    {
        parent::__construct($lineRange);
    }

    /**
     * 通过线路ID列表,获取线路范围列表
     * @param $lineIdList
     * @return array
     */
    public function getAllLineRange($lineIdList)
    {
        $list = parent::getList(['line_id' => ['in', $lineIdList]], ['*'], false)->toArray();
        if (empty($list)) return [];
        foreach ($list as $key => $lineRange) {
            $list[$key]['range'] = $lineRange['post_code_start'] . '-' . $lineRange['post_code_end'];
        }
        $newList = [];
        $list = array_create_group_index($list, 'line_id');
        foreach ($list as $key => $lineList) {
            $newList[$key]['line_range'] = implode(';', array_column(multi_array_unique($lineList, 'range'), 'range'));
            $newList[$key]['work_day_list'] = array_column($lineList, 'schedule');
        }
        return $newList;
    }

    /**
     * 批量新增
     * @param $lineId
     * @param $rangeList
     * @param $country
     * @param $workdayList
     * @throws BusinessLogicException
     */
    public function storeAll($lineId, $rangeList, $country, $workdayList)
    {
        $workdayList = explode(',', $workdayList);
        $newRangeList = [];
        $index = 0;
        foreach ($workdayList as $workDay) {
            foreach ($rangeList as $key => $range) {
                $newRangeList[$index]['line_id'] = $lineId;
                $newRangeList[$index]['post_code_start'] = $range['post_code_start'];
                $newRangeList[$index]['post_code_end'] = $range['post_code_end'];
                $newRangeList[$index]['schedule'] = $workDay;
                $newRangeList[$index]['country'] = $country;
                $index++;
            }
        }
        $rowCount = parent::insertAll($newRangeList);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围新增失败');
        }
        //删除货主线路范围
        $this->getMerchantGroupLineRangeService()->storeRangeList($lineId, $rangeList, $workdayList, $country);
    }


    /**
     * 邮编范围验证
     * max(A.start,B.start)<=min(A.end,B,end)
     * @param $rangeList
     * @param $country
     * @param $workdayList
     * @param $id
     * @throws BusinessLogicException
     */
    public function checkRange($rangeList, $country, $workdayList, $id = null)
    {
        $DEList = 0;
        $NLList = 0;
        if (empty($rangeList)) {
            throw new BusinessLogicException('邮编范围不能为空');
        }
        $length = count($rangeList);
        $startList = collect($rangeList)->pluck('post_code_start')->toArray();
        $endList = collect($rangeList)->pluck('post_code_end')->toArray();
        $list = array_merge($startList, $endList);
        foreach ($list as $k => $v) {
            if ($v > 9999) {
                $DEList = $DEList + 1;
            } else {
                $NLList = $NLList + 1;
            }
            if ($DEList > 0 & $NLList > 0) {
                throw new BusinessLogicException('您选择的邮编范围跨越多个国家，暂不支持多国家线路');
            }
        }
        for ($i = 0; $i <= $length - 1; $i++) {
            for ($j = $i + 1; $j <= $length - 1; $j++) {
                if (max($rangeList[$i]['post_code_start'], $rangeList[$j]['post_code_start']) <= min($rangeList[$i]['post_code_end'], $rangeList[$j]['post_code_end'])) {
                    throw new BusinessLogicException("邮编范围:post_range_1与:post_range_2存在重叠,无法添加", 1000, ['post_range_1' => $rangeList[$j]['post_code_start'] . '-' . $rangeList[$j]['post_code_end'], 'post_range_2' => $rangeList[$j]['post_code_start'] . '-' . $rangeList[$j]['post_code_end']]);
                }
            }
        }
        //当前是否已存在邮编
        foreach ($rangeList as $range) {
            if ($this->checkIfPostcodeIntervalOverlap($range['post_code_start'], $range['post_code_end'], $country, $workdayList, $id)) {
                throw new BusinessLogicException("邮编[:post_code_start]到[:post_code_end]已存在", 1000, ['post_code_start' => $range['post_code_start'], 'post_code_end' => $range['post_code_end']]);
            }
        }
    }

    /**
     * 检查邮编规则是否存在
     * @param $postcodeStart
     * @param $postcodeEnd
     * @param $country
     * @param $workDayList
     * @param null $id
     * @return bool
     */
    public function checkIfPostcodeIntervalOverlap($postcodeStart, $postcodeEnd, $country, $workDayList, $id = null)
    {
        $sql = "SELECT id FROM line_range WHERE company_id=? AND country=? AND schedule IN ($workDayList) AND GREATEST(`post_code_start`,?)<=LEAST(`post_code_end`,?)";
        $sql = (!empty($id)) ? $sql . " AND line_id<>{$id}" : $sql;
        $bindings = [auth()->user()->company_id, $country, $postcodeStart, $postcodeEnd];
        $info = DB::selectOne($sql, $bindings);
        return !empty($info) ? true : false;
    }


    /**
     * 获取线路ID
     * @param $rules
     * @return mixed|null
     */
    public function getLineIdByRule($rules)
    {
        $query = $this->model->newQuery();
        if (!empty($rules['place_country'])) {
            $query->where('country', $rules['place_country']);
        }
        if (!empty($rules['place_post_code'])) {
            $query->where('post_code_start', '<=', $rules['place_post_code'])->where('post_code_end', '>=', $rules['place_post_code']);
        }
        $lineRange = $query->groupBy('line_id')->first(['line_id']);
        return !empty($lineRange) ? $lineRange->line_id : null;
    }


}
