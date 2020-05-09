<?php
/**
 * 线路 服务
 * User: long
 * Date: 2019/12/21
 * Time: 10:05
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\LineResource;
use App\Http\Validate\Api\Admin\LineValidate;
use App\Models\Line;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\ImportTrait;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LineService extends BaseLineService
{
    public function __construct(Line $line)
    {
        parent::__construct($line);
    }

    public function getUploadService()
    {
        return self::getInstance(UploadService::class);
    }

    /**
     * 邮编-列表查询
     * @return BaseLineService|array|mixed
     */
    public function postcodeIndex()
    {
        //如果存在post_code查询
        if (!empty($this->formData['post_code'])) {
            $this->query->whereRaw("line_id IN (SELECT DISTINCT line_id FROM line_range WHERE  post_code_start>={$this->formData['post_code']} AND post_code_end <={$this->formData['post_code']})");
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
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
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
     * 邮编-修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function postcodeUpdate($id, $data)
    {
        $info = parent::getInfo(['id' => $id, 'rule' => BaseConstService::LINE_RULE_POST_CODE], ['id'], false);
        if (empty($info)) {
            throw new BusinessLogicException('线路不存在');
        }
        //基础验证
        $this->check($data);
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
        if ($isGetArea === 2) return $list;

        $lineIdList = array_column($list->all(), 'id');
        if (empty($lineIdList)) return $list;

        $lineAreaList = $this->getLineAreaService()->getList(['line_id' => ['in', $lineIdList]], ['line_id', 'coordinate_list', 'country'], false, ['line_id', 'coordinate_list', 'country'])->toArray();
        $lineAreaList = array_create_index($lineAreaList, 'line_id');
        if (empty($lineAreaList)) return $list;

        foreach ($list as &$line) {
            $line['coordinate_list'] = json_decode($lineAreaList[$line['id']]['coordinate_list'], true);
        }
        return $list;
    }

    /**
     * 区域-详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function areaShow($id)
    {
        $info = parent::getInfo(['id' => $id, 'rule' => BaseConstService::LINE_RULE_AREA], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('线路不存在');
        }
        $lineArea = $this->getLineAreaService()->getInfo(['line_id' => $info['id']], ['coordinate_list'], false)->toArray();
        $info['coordinate_list'] = json_decode($lineArea['coordinate_list'], true);
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
        $info = parent::getInfo(['id' => $id, 'rule' => BaseConstService::LINE_RULE_AREA], ['id'], false);
        if (empty($info)) {
            throw new BusinessLogicException('线路不存在');
        }
        //基础验证
        $this->check($data);
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
