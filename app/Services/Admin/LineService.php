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
use App\Models\Line;
use App\Services\BaseService;
use Illuminate\Support\Arr;

class LineService extends BaseService
{
    public function __construct(Line $line)
    {
        $this->model = $line;
        $this->query = $this->model::query();
        $this->resource = LineResource::class;
        $this->request = request();
    }

    /**
     * 线路范围 服务
     * @return LineRangeService
     */
    public function getLineRangeService()
    {
        return self::getInstance(LineRangeService::class);
    }

    /**
     * 仓库 服务
     * @return WareHouseService
     */
    public function getWareHouseService()
    {
        return self::getInstance(WareHouseService::class);
    }

    public function getPageList()
    {
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
     * 列表查询
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
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
            })->toArray();
            $info['work_day_list'] = implode(',', array_values(array_unique(array_column($lineRangeList->toArray(), 'schedule'))));
        };
        return $info;

    }

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $params = $this->check($params);
        //线路新增
        $lineData = Arr::only($params, ['name', 'country', 'warehouse_id', 'order_max_count']);
        $lineData = array_merge($lineData, ['creator_id' => auth()->id(), 'creator_name' => auth()->user()->fullname]);
        $lineId = parent::insertGetId($lineData);
        if ($lineId === 0) {
            throw new BusinessLogicException('线路新增失败');
        }
        //线路范围新增
        $newItemList = [];
        $index = 0;
        $itemList = json_decode($params['item_list'], true);
        foreach ($params['work_day_list'] as $workDay) {
            foreach ($itemList as $key => $item) {
                $newItemList[$index]['line_id'] = $lineId;
                $newItemList[$index]['post_code_start'] = $item['post_code_start'];
                $newItemList[$index]['post_code_end'] = $item['post_code_end'];
                $newItemList[$index]['schedule'] = $workDay;
                $newItemList[$index]['country'] = $params['country'];
                $index++;
            }
        }
        $rowCount = $this->getLineRangeService()->insertAll($newItemList);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围新增失败');
        }
    }

    /**
     * 修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $data = $this->check($data, $id);
        $rowCount = parent::updateById($id, Arr::only($data, ['name', 'country', 'warehouse_id', 'order_max_count']));
        if ($rowCount === false) {
            throw new BusinessLogicException('线路修改失败');
        }
        //删除原来线路范围
        $rowCount = $this->getLineRangeService()->delete(['line_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围修改失败');
        }
        //线路范围新增
        $newItemList = [];
        $index = 0;
        $itemList = json_decode($data['item_list'], true);
        foreach ($data['work_day_list'] as $workDay) {
            foreach ($itemList as $key => $item) {
                $newItemList[$index]['line_id'] = $id;
                $newItemList[$index]['post_code_start'] = $item['post_code_start'];
                $newItemList[$index]['post_code_end'] = $item['post_code_end'];
                $newItemList[$index]['schedule'] = $workDay;
                $newItemList[$index]['country'] = $data['country'];
                $index++;
            }
        }
        $rowCount = $this->getLineRangeService()->insertAll($newItemList);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围新增失败');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        //删除线路
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路删除失败');
        }
        //删除线路范围
        $rowCount = $this->getLineRangeService()->delete(['line_id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围删除失败');
        }
    }


    /**
     * 验证
     * @param $params
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function check($params, $id = null)
    {
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $params['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('仓库不存在!');
        }
        $params['work_day_list'] = explode(',', $params['work_day_list']);
        //验证线路区间
        $this->checkRange($params, $id);
        return $params;
    }

    /**
     * 邮编范围验证
     * max(A.start,B.start)<=min(A.end,B,end)
     * @param $params
     * @param $id
     * @throws BusinessLogicException
     */
    public function checkRange($params, $id = null)
    {
        $itemList = json_decode($params['item_list'], true);
        foreach ($itemList as $item) {
            if (intval($item['post_code_end']) < intval($item['post_code_start'])) {
                throw new BusinessLogicException('结束邮编必须大于开始邮编');
            }
            if (intval($item['post_code_end']) - intval($item['post_code_start']) > 700) {
                throw new BusinessLogicException('邮编范围区间不能超过700');
            }
        }
        $length = count($itemList);
        for ($i = 0; $i <= $length - 1; $i++) {
            for ($j = $i + 1; $j <= $length - 1; $j++) {
                if (max($itemList[$i]['post_code_start'], $itemList[$j]['post_code_start']) <= min($itemList[$i]['post_code_end'], $itemList[$j]['post_code_end'])) {
                    throw new BusinessLogicException('邮编存在重叠,无法添加');
                }
            }
        }
        //当前是否已存在邮编
        foreach ($itemList as $item) {
            for ($i = $item['post_code_start']; $i <= $item['post_code_end']; $i++) {
                if ($this->getLineRangeService()->checkIfPostcodeExisted($i, $params['country'], $params['work_day_list'], $id)) {
                    throw new BusinessLogicException("邮编{$i}已存在");
                }
            }
        }
    }
}