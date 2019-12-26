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

class LineRangeService extends BaseService
{
    public function __construct(LineRange $lineRange)
    {
        $this->model = $lineRange;
        $this->query = $this->model::query();
    }

    /**
     * 通过线路ID列表,获取线路范围列表
     * @param $lineIdList
     * @return array
     */
    public function getAllLineRange($lineIdList)
    {
        $list = parent::getList(['line_id' => ['in', $lineIdList]], ['*'], false);
        if (empty($list)) return [];
        foreach ($list as $key => $line) {
            $list[$key]['range'] = $line['post_code_start'] . '-' . $line['post_code_end'];
        }
        $newList = [];
        $list = array_create_group_index($list, 'line_id');
        foreach ($list as $key => $lineList) {
            $newList[$key]['line_range'] = implode(';', array_column($lineList, 'range'));
            $newList[$key]['work_day_list'] = array_column($lineList, 'schedule');
        }
        return $newList;
    }

    /**
     * 检查邮编规则是否存在
     * @param $postcode
     * @param $country
     * @param $workDayList
     * @param null $id
     * @return bool
     */
    public function checkIfPostcodeExisted($postcode, $country, $workDayList, $id = null)
    {
        return $id === null ? parent::count([
                    'post_code_start' => ['>=', $postcode],
                    'post_code_end' => ['<=', $postcode],
                    'country' => $country,
                    'schedule' => ['in', $workDayList]]
            ) > 0 : parent::count([
                    'line_id' => ['<>', $id],
                    'post_code_start' => ['>=', $postcode],
                    'post_code_end' => ['<=', $postcode],
                    'country' => $country,
                    'schedule' => ['in', $workDayList]]
            ) > 0;
    }
}