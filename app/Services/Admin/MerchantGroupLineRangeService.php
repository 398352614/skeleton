<?php
/**
 * 货主线路范围 服务
 * User: long
 * Date: 2020/8/13
 * Time: 13:46
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\MerchantGroupLineRange;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class MerchantGroupLineRangeService extends BaseService
{
    public function __construct(MerchantGroupLineRange $model)
    {
        parent::__construct($model, null);
    }

    /**
     * 获取货主线路服务范围
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        /**********************************************1.获取线路*******************************************************/
        $line = $this->getLineService()->getInfo(['id' => $id], ['id', 'name'], false);
        if (empty($line)) {
            throw new BusinessLogicException('数据不存在');
        }
        $line = $line->toArray();
        /*******************************************2.获取线路范围*****************************************************/
        $lineRangeList = $this->getLineRangeService()->getList(['line_id' => $id], ['post_code_start', 'post_code_end', 'schedule'], false);
        $lineRangeList = $lineRangeList->groupBy(function ($lineRange) {
            return $lineRange['post_code_start'] . '-' . $lineRange['post_code_end'];
        })->map(function ($detailLineRangeList) {
            $detailLineRangeList = $detailLineRangeList->toArray();
            $detailLineRangeList = [
                'post_code_range' => $detailLineRangeList[0]['post_code_start'] . '-' . $detailLineRangeList[0]['post_code_end'],
                'workday_list' => array_column($detailLineRangeList, 'schedule')
            ];
            return $detailLineRangeList;
        })->toArray();
        /*******************************************3.获取货主线路范围*************************************************/
        $merchantGroupLineRangeList = parent::getList(['line_id' => $id], ['*'], false)->toArray();
        $merchantGroupIdList = array_unique(array_column($merchantGroupLineRangeList, 'merchant_group_id'));
        $merchantGroupList = $this->getMerchantGroupService()->getList(['id' => ['in', $merchantGroupIdList]], ['id', 'name'], false)->toArray();
        $merchantGroupList = array_create_index($merchantGroupList, 'id');
        /**********************************************4.数据填充******************************************************/
        $merchantGroupLineRangeList = collect($merchantGroupLineRangeList)->map(function ($merchantGroupLineRange, $key) use ($merchantGroupList) {
            $merchantGroupLineRange['merchant_group_id_name'] = $merchantGroupList[$merchantGroupLineRange['merchant_group_id']]['name'];
            $merchantGroupLineRange['post_code_range'] = $merchantGroupLineRange['post_code_start'] . '-' . $merchantGroupLineRange['post_code_end'];
            return collect($merchantGroupLineRange);
        })->groupBy('post_code_range')->toArray();
        foreach ($merchantGroupLineRangeList as $postCodeRange => $postCodeRangeList) {
            $newPostCodeRangeList = collect($postCodeRangeList)->groupBy('merchant_group_id')->map(function ($merchantGroupRangeList) {
                $merchantGroupRangeList = $merchantGroupRangeList->toArray();
                $newMerchantGroupRange = Arr::only($merchantGroupRangeList[0], ['id', 'company_id', 'merchant_group_id', 'merchant_group_id_name', 'line_id', 'is_alone']);
                $newMerchantGroupRange['workday_list'] = array_column($merchantGroupRangeList, 'schedule');
                return collect($newMerchantGroupRange);
            })->toArray();
            $lineRangeList[$postCodeRange]['merchant_group_list'] = array_values($newPostCodeRangeList);
        }
        data_fill($lineRangeList, '*.merchant_group_list', []);
        $line['merchant_group_line_range_list'] = array_values($lineRangeList);
        return $line;
    }

    /**
     * 更新
     * @param $id
     * @param $data
     * @throws BusinessLogicException
     */
    public function createOrUpdate($id, $data)
    {
        $merchantGroupLineRangeList = $data['merchant_group_line_range_list'];
        $line = $this->getLineService()->getInfo(['id' => $id], ['id', 'name'], false);
        if (empty($line)) {
            throw new BusinessLogicException('数据不存在');
        }
        //获取货主列表
        $merchantGroupIdList = array_unique(array_column($merchantGroupLineRangeList, 'merchant_group_id'));
        $merchantGroupList = $this->getMerchantGroupService()->getList(['id' => ['in', $merchantGroupIdList]], ['id', 'name'], false)->toArray();
        $merchantGroupList = array_create_index($merchantGroupList, 'id');
        //获取线路范围列表
        $lineRangeList = $this->getLineRangeService()->getList(['line_id' => $id], ['*'], false);
        $lineRangeList = $lineRangeList->groupBy(function ($lineRange) {
            return $lineRange['post_code_start'] . '-' . $lineRange['post_code_end'];
        })->map(function ($detailLineRangeList) {
            $detailLineRangeList = $detailLineRangeList->toArray();
            $workdayList = implode(',', array_column($detailLineRangeList, 'schedule'));
            return collect([
                'line_id' => $detailLineRangeList[0]['line_id'],
                'post_code_start' => $detailLineRangeList[0]['post_code_start'],
                'post_code_end' => $detailLineRangeList[0]['post_code_end'],
                'country' => $detailLineRangeList[0]['country'],
                'workday_list' => $workdayList
            ]);
        })->toArray();
        //验证线路范围是否存在
        $merchantGroupLineRangeList = collect($merchantGroupLineRangeList)->filter(function ($merchantGroupLineRange) use ($lineRangeList, $merchantGroupList) {
            return !empty($lineRangeList[$merchantGroupLineRange['post_code_range']]) && !empty($merchantGroupList[$merchantGroupLineRange['merchant_group_id']]);
        })->unique(function ($merchantGroupLineRange) {
            return $merchantGroupLineRange['merchant_group_id'] . '-' . $merchantGroupLineRange['post_code_range'];
        })->map(function ($merchantGroupLineRange) use ($lineRangeList) {
            $merchantGroupLineRange['line_id'] = $lineRangeList[$merchantGroupLineRange['post_code_range']]['line_id'];
            $merchantGroupLineRange['country'] = $lineRangeList[$merchantGroupLineRange['post_code_range']]['country'];
            $merchantGroupLineRange['workday_list'] = implode(',', array_intersect(explode_id_string($merchantGroupLineRange['workday_list']), explode_id_string($lineRangeList[$merchantGroupLineRange['post_code_range']]['workday_list'])));
            return collect($merchantGroupLineRange);
        })->toArray();
        $merchantGroupLineRangeList = array_values($merchantGroupLineRangeList);

        //删除线路的货主线路范围
        $rowCount = parent::delete(['line_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //新增线路的货主线路范围
        foreach ($merchantGroupLineRangeList as $merchantGroupLineRange) {
            $workdayList = explode(',', $merchantGroupLineRange['workday_list']);
            $newList = [];
            foreach ($workdayList as $key => $workday) {
                $newList[$key] = $merchantGroupLineRange;
                $newList[$key]['schedule'] = $workday;
                list($newList[$key]['post_code_start'], $newList[$key]['post_code_end']) = explode('-', $newList[$key]['post_code_range']);
                unset($newList[$key]['post_code_range'], $newList[$key]['workday_list']);
            }
            $rowCount = parent::insertAll($newList);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败，请重新操作');
            }
        }
    }

    /**
     * 批量新增范围
     * @param $lineId
     * @param $rangeList
     * @param $workdayList
     * @param $country
     * @throws BusinessLogicException
     */
    public function storeRangeList($lineId, $rangeList, $workdayList, $country)
    {
        //删除货主线路范围-不在取派日期中的
        $rowCount = parent::delete(['line_id' => $lineId, 'schedule' => ['not in', $workdayList]]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //删除货主线路范围-不在邮编范围内的
        $postCodeRangeList = [];
        foreach ($rangeList as $key => $range) {
            $postCodeRangeList[] = $range['post_code_start'] . '-' . $range['post_code_end'];
        }
        $rowCount = $this->model->newQuery()->where('line_id', $lineId)->whereNotIn(DB::raw("CONCAT(post_code_start,'-',post_code_end)"), $postCodeRangeList)->delete();
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //获取新增的取派日期列表
        $merchantGroupLineRangeList = parent::getList(['line_id' => $lineId], ['*'], false)->toArray();
        $dbWorkdayList = array_unique(array_column($merchantGroupLineRangeList, 'schedule'));
        $diffWorkdayList = array_diff($workdayList, $dbWorkdayList);
        if (!empty($diffWorkdayList)) {
            $merchantGroupLineRangeList = collect($merchantGroupLineRangeList)->groupBy(function ($merchantGroupLineRange) {
                return $merchantGroupLineRange['post_code_start'] . '-' . $merchantGroupLineRange['post_code_end'];
            })->map(function ($detailMerchantGroupLineRangeList) {
                $detailMerchantGroupLineRangeList = $detailMerchantGroupLineRangeList->toArray();
                return collect(Arr::only($detailMerchantGroupLineRangeList[0], ['merchant_group_id', 'line_id', 'post_code_start', 'post_code_end', 'country', 'is_alone']));
            })->toArray();
            $dataList = [];
            foreach ($diffWorkdayList as $workday) {
                foreach ($merchantGroupLineRangeList as $MerchantGroupRange) {
                    $dataList[] = array_merge($MerchantGroupRange, ['schedule' => $workday]);
                }
            }
            $rowCount = parent::insertAll($dataList);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败，请重新操作');
            }
        }
        //新增新的邮编的所有货主范围
        $merchantPostCodeRangeList = [];
        $merchantGroupLineRangeList = parent::getList(['line_id' => $lineId], ['post_code_start', 'post_code_end'], false, ['post_code_start', 'post_code_end']);
        foreach ($merchantGroupLineRangeList as $merchantGroupLineRange) {
            $merchantPostCodeRangeList[] = $merchantGroupLineRange['post_code_start'] . '-' . $merchantGroupLineRange['post_code_end'];
        }
        $diffPostCodeRangeList = array_diff($postCodeRangeList, $merchantPostCodeRangeList);
        if (empty($diffPostCodeRangeList)) return;
        $merchantGroupList = $this->getMerchantGroupService()->getList([], ['*'], false)->toArray();
        if (empty($merchantGroupList)) return;
        $insetRangeList = [];
        foreach ($merchantGroupList as $merchantGroup) {
            foreach ($diffPostCodeRangeList as $postCodeRange) {
                list($postCodeStart, $postCodeEnd) = explode('-', $postCodeRange);
                foreach ($workdayList as $schedule) {
                    $insetRangeList[] = [
                        'merchant_group_id' => $merchantGroup['id'],
                        'line_id' => $lineId,
                        'post_code_start' => $postCodeStart,
                        'post_code_end' => $postCodeEnd,
                        'schedule' => $schedule,
                        'country' => $country
                    ];
                }
            }
        }
        $rowCount = parent::insertAll($insetRangeList);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }
}
