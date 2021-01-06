<?php
/**
 * 线路 服务
 * User: long
 * Date: 2019/12/21
 * Time: 10:05
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\Line;
use App\Services\BaseConstService;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LineService extends BaseLineService
{
    public function __construct(Line $line)
    {
        parent::__construct($line);
    }

    /**
     * 通过日期 获取线路列表
     * @param $date
     * @return array
     */
    public function getListByDate($date)
    {
        if (CompanyTrait::getLineRule() == BaseConstService::LINE_RULE_POST_CODE) {
            $lineRangeList = $this->getLineRangeService()->getList(['schedule' => Carbon::create($date)->dayOfWeek], ['line_id'], false, ['line_id'])->toArray();
        } else {
            $lineRangeList = $this->getLineAreaService()->getList(['schedule' => Carbon::create($date)->dayOfWeek], ['line_id'], false, ['line_id'])->toArray();
        }
        $list = parent::getList(['id' => ['in', array_column($lineRangeList, 'line_id')]], ['id', 'name'], false)->toArray();
        return $list;
    }

    /**
     * 邮编-列表查询
     * @return BaseLineService|array|mixed
     */
    public function postcodeIndex()
    {
        //如果存在post_code查询
        if (!empty($this->formData['post_code'])) {
            $postCode = explode_post_code($this->formData['post_code']);
            if (!is_numeric($postCode)) {
                $this->query->where('rule', '=', 0);//保证查不到的条件
            } else {
                $this->query->whereRaw("id IN (SELECT DISTINCT line_id FROM line_range WHERE post_code_start <= {$postCode} AND post_code_end >= {$postCode})");
            }
        }
        $this->filters['rule'] = ['=', BaseConstService::LINE_RULE_POST_CODE];
        $list = parent::getPageList();
        $lineIdList = array_column($list->all(), 'id');
        if (empty($lineIdList)) return $list;
        //获取线路范围列表
        $lineRangeList = $this->getLineRangeService()->getAllLineRange($lineIdList);
        if (empty($lineRangeList)) return $list;
        foreach ($list as &$line) {
            $line['line_range'] = $lineRangeList[$line['id']]['line_range'];
            $line['work_day_list'] = array_values(array_unique($lineRangeList[$line['id']]['work_day_list']));
        }
        return $list;
    }

    /**
     * 邮编-详情
     * @param $id
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function postcodeShow($id)
    {
        $info = parent::getInfo(['id' => $id, 'rule' => BaseConstService::LINE_RULE_POST_CODE], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('线路不存在');
        }
        $info = $info->toArray();
        $lineRangeList = $this->getLineRangeService()->getList(['line_id' => $info['id']], ['country', 'post_code_start', 'post_code_end', 'schedule'], false);
        if ($lineRangeList->isEmpty()) {
            $info['line_range'] = [];
            $info['work_day_list'] = '';
        } else {
            $info['line_range'] = $lineRangeList->map(function ($lineRange, $key) {
                return collect($lineRange)->only(['post_code_start', 'post_code_end']);
            })->unique(function ($item) {
                return $item['post_code_start'] . $item['post_code_end'];
            })->toArray();
            $info['work_day_list'] = implode(',', array_values(array_unique(array_column($lineRangeList->toArray(), 'schedule'))));
        };
        return $info;

    }

    /**
     * 邮编-新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function postcodeStore($params)
    {
        //基础验证
        $this->check($params);
        //邮编范围验证
        $this->getLineRangeService()->checkRange($params['item_list'], $params['country'], $params['work_day_list']);
        //新增
        $lineId = $this->store($params);
        //邮编范围批量新增
        $this->getLineRangeService()->storeAll($lineId, $params['item_list'], $params['country'], $params['work_day_list']);
    }

    /**
     * 新增商户所有线路范围
     * @param $merchantGroupId
     * @throws BusinessLogicException
     */
    public function storeAllPostCodeLineRangeByMerchantGroupId($merchantGroupId)
    {
        $lineRangeList = $this->getLineRangeService()->getList([], ['*'], false)->toArray();
        data_set($lineRangeList, '*.merchant_group_id', $merchantGroupId);
        foreach ($lineRangeList as $key => $lineRange) {
            unset($lineRangeList[$key]['country_name']);
            unset($lineRangeList[$key]['id']);
        }
        $rowCount = $this->getMerchantGroupLineRangeService()->insertAll($lineRangeList);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * 邮编-修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function postcodeUpdate($id, $data)
    {
        $info = parent::getInfo(['id' => $id, 'rule' => BaseConstService::LINE_RULE_POST_CODE], ['id', 'country'], false);
        if (empty($info)) {
            throw new BusinessLogicException('线路不存在');
        }
        //基础验证
        $this->check($data, $info->toArray());
        //邮编范围验证
        $this->getLineRangeService()->checkRange($data['item_list'], $data['country'], $data['work_day_list'], $id);
        //修改
        $this->updateById($id, $data);
        //删除原来线路范围
        $rowCount = $this->getLineRangeService()->delete(['line_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围修改失败');
        }
        //批量新增
        $this->getLineRangeService()->storeAll($id, $data['item_list'], $data['country'], $data['work_day_list']);
    }

    /**
     * 邮编-删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function postcodeDestroy($id)
    {
        $info = parent::getInfo(['id' => $id, 'rule' => BaseConstService::LINE_RULE_POST_CODE], ['id'], false);
        if (empty($info)) {
            throw new BusinessLogicException('线路不存在');
        }
        $this->destroy($id);
        //删除线路范围
        $rowCount = $this->getLineRangeService()->delete(['line_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围删除失败');
        }
        //删除商户线路范围
        $rowCount = $this->getMerchantGroupLineRangeService()->delete(['line_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * 区域-列表查询
     * @param $isGetArea
     * @return mixed
     */
    public function areaIndex($isGetArea)
    {
        $this->filters['rule'] = ['=', BaseConstService::LINE_RULE_AREA];
        $list = parent::getPageList();
        $workdayList = array_keys(ConstTranslateTrait::$weekList);
        foreach ($list as $key => $line) {
            $list[$key]['work_day_list'] = $workdayList;
        }
        if ($isGetArea === 2) return $list;

        $lineIdList = array_column($list->all(), 'id');
        if (empty($lineIdList)) return $list;

        $lineAreaList = $this->getLineAreaService()->getList(['line_id' => ['in', $lineIdList]], ['line_id', 'coordinate_list', 'country'], false, ['line_id', 'coordinate_list', 'country'])->toArray();
        $lineAreaList = array_create_group_index($lineAreaList, 'line_id');
        if (empty($lineAreaList)) return $list;
        foreach ($list as &$line) {
            $coordinateList = array_column($lineAreaList[$line['id']], 'coordinate_list');
            $line['coordinate_list'] = !empty($coordinateList) ? array_map(function ($coordinateItemList) {
                return json_decode($coordinateItemList, true);
            }, $coordinateList) : [];
            $line['work_day_list'] = $workdayList;
        }
        return $list;
    }

    /**
     * 区域-详情
     * @param $id
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function areaShow($id)
    {
        $info = parent::getInfo(['id' => $id, 'rule' => BaseConstService::LINE_RULE_AREA], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('线路不存在');
        }
        $lineAreaList = $this->getLineAreaService()->getList(['line_id' => $info['id']], ['coordinate_list'], false)->toArray();
        $coordinateList = array_column($lineAreaList, 'coordinate_list');
        $info['coordinate_list'] = array_map(function ($coordinateItemList) {
            return json_decode($coordinateItemList, true);
        }, $coordinateList);
        return $info;
    }

    /**
     * 区域-新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function areaStore($params)
    {
        //基础验证
        $this->check($params);
        //区域范围验证
        $this->getLineAreaService()->checkArea($params['coordinate_list'], $params['country']);
        //新增
        $lineId = $this->store($params, BaseConstService::LINE_RULE_AREA);
        //区域范围批量新增
        $this->getLineAreaService()->storeAll($lineId, $params['coordinate_list'], $params['country']);
    }

    /**
     * 区域-修改
     * @param $id
     * @param $data
     * @throws BusinessLogicException
     */
    public function areaUpdate($id, $data)
    {
        $info = parent::getInfo(['id' => $id, 'rule' => BaseConstService::LINE_RULE_AREA], ['id', 'country'], false);
        if (empty($info)) {
            throw new BusinessLogicException('线路不存在');
        }
        //基础验证
        $this->check($data, $info->toArray());
        //区域范围验证
        $this->getLineAreaService()->checkArea($data['coordinate_list'], $data['country'], $id);
        //修改
        $this->updateById($id, $data);
        //删除原来区域范围验证
        $rowCount = $this->getLineAreaService()->delete(['line_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围修改失败');
        }
        //批量新增
        $this->getLineAreaService()->storeAll($id, $data['coordinate_list'], $data['country']);
    }


    /**
     * 区域-线路删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function areaDestroy($id)
    {
        $info = parent::getInfo(['id' => $id, 'rule' => BaseConstService::LINE_RULE_AREA], ['id'], false);
        if (empty($info)) {
            throw new BusinessLogicException('线路不存在');
        }
        $this->destroy($id);
        //删除线路范围
        $rowCount = $this->getLineAreaService()->delete(['line_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围删除失败');
        }
    }
}
