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
use App\Services\BaseConstService;
use App\Traits\CountryTrait;
use http\Exception\BadConversionException;
use Illuminate\Support\Facades\DB;
use WebSocket\Base;

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
        $newList = [];
        $result = [];
        $schedule = [];
        $list = parent::getList(['line_id' => ['in', $lineIdList]], ['*'], false)->toArray();
        if (empty($list)) return [];
        $list = array_create_group_index($list, 'line_id');
        foreach ($list as $lineId => $listByLine) {

            $listByLineByCountry = collect($listByLine)->groupBy('country')->toArray();
            foreach ($listByLineByCountry as $country => $lineRange) {
                $array = [];
                $result[$lineId]['line_range'][$country] = CountryTrait::getCountryName($country) . ':';
                $result[$lineId]['work_day_list'] = [];
                $schedule = [];
                foreach ($lineRange as $k => $v) {
                    if (!in_array($v['post_code_start'] . '-' . $v['post_code_end'] . ';', $array)) {
                        $result[$lineId]['line_range'][$country] .= $v['post_code_start'] . '-' . $v['post_code_end'] . ';';
                    }
                    $array[] = $v['post_code_start'] . '-' . $v['post_code_end'] . ';';
                    if (!in_array($v['schedule'], $schedule)) {
                        $result[$lineId]['work_day_list'][] = $v['schedule'];
                    }
                    $schedule[] = $v['schedule'];
                }
            }
            $newList[$lineId]['line_range'] = array_values($result[$lineId]['line_range']);
            $newList[$lineId]['work_day_list'] = array_values($result[$lineId]['work_day_list']);
        }
        return $newList;
    }

    /**
     * 批量新增
     * @param $lineId
     * @param $rangeList
     * @param $workdayList
     * @throws BusinessLogicException
     */
    public function storeAll($lineId, $rangeList, $workdayList)
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
                $newRangeList[$index]['country'] = $range['country'];
                $index++;
            }
        }
        $rowCount = parent::insertAll($newRangeList);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围新增失败');
        }
        //删除货主线路范围
        $this->getMerchantGroupLineRangeService()->storeRangeList($lineId, $rangeList, $workdayList);
    }


    /**
     * 邮编范围验证
     * max(A.start,B.start)<=min(A.end,B,end)
     * @param $rangeList
     * @param $workdayList
     * @param $id
     * @throws BusinessLogicException
     */
    public function checkRange(&$rangeList, $workdayList, $id = null)
    {
        if (empty($rangeList)) {
            throw new BusinessLogicException('邮编范围不能为空');
        }
        //单邮编赋值为范围
        foreach ($rangeList as $k => $v) {
            if ($v['type'] == BaseConstService::POSTCODE_TYPE_1) {
                if (empty($v['post_code_end'])) {
                    throw new BusinessLogicException('结束邮编不能为空');
                }
                if ($v['post_code_end'] == $v['post_code_start']) {
                    throw new BusinessLogicException('起始邮编和结束邮编不能相同，单个邮编请使用精准邮编');
                }
            } else {
                $rangeList[$k]['post_code_end'] = $rangeList[$k]['post_code_start'];
            }
        }
        $newRangeList = collect($rangeList)->groupBy('country')->toArray();
        foreach ($newRangeList as $k => $v) {
            $length = count($v);
            for ($i = 0; $i <= $length - 1; $i++) {
                for ($j = $i + 1; $j <= $length - 1; $j++) {
                    if (max($v[$i]['post_code_start'], $v[$j]['post_code_start']) <= min($v[$i]['post_code_end'], $v[$j]['post_code_end'])) {
                        if ($v[$i]['post_code_start'] == $v[$i]['post_code_end']) {
                            throw new BusinessLogicException("邮编:post_code_start已存在", 1000, ['post_code_start' => $v[$j]['post_code_start']]);
                        } else {
                            throw new BusinessLogicException("[:country]邮编范围[:post_range_1]与[:post_range_2]存在重叠,无法添加", 1000, ['country' => CountryTrait::getCountryName($v[$i]['country']), 'post_range_1' => $v[$j]['post_code_start'] . '-' . $v[$j]['post_code_end'], 'post_range_2' => $rangeList[$j]['post_code_start'] . '-' . $rangeList[$j]['post_code_end']]);
                        }
                    }
                }
            }
        }
        //当前是否已存在邮编
        foreach ($rangeList as $range) {
            if ($this->checkIfPostcodeIntervalOverlap($range['post_code_start'], $range['post_code_end'], $range['country'], $workdayList, $id)) {
                if ($range['post_code_start'] == $range['post_code_end']) {
                    throw new BusinessLogicException("邮编[:post_code_start]已存在", 1000, ['post_code_start' => $range['post_code_start']]);
                } else {
                    throw new BusinessLogicException("[:country]邮编范围[:post_code_start]到[:post_code_end]已存在", 1000, ['country' => CountryTrait::getCountryName($range['country']), 'post_code_start' => $range['post_code_start'], 'post_code_end' => $range['post_code_end']]);
                }
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
