<?php
/**
 * 线路范围 服务
 * User: long
 * Date: 2019/12/21
 * Time: 10:37
 */

namespace App\Services\Admin;


use App\Models\LineRange;
use App\Services\BaseService;
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
        foreach ($list as $key => $line) {
            $list[$key]['range'] = $line['post_code_start'] . '-' . $line['post_code_end'];
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
        $sql = "SELECT id FROM line_range WHERE company_id=? AND country=? AND schedule IN (?) AND GREATEST(`post_code_start`,?)<=LEAST(`post_code_end`,?)";
        $sql = (!empty($id)) ? $sql . " WHERE id<>{$id}" : $sql;
        $bindings = [auth()->user()->company_id, $country, $workDayList, $postcodeStart, $postcodeEnd];
        $info = DB::selectOne($sql, $bindings);
        return !empty($info) ? true : false;
    }


}