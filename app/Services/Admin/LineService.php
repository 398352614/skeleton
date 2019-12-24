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
        $lineIdList = array_column($list['data'], 'id');
        if (empty($lineIdList)) return $list;
        //获取线路范围列表
        $lineRangeList = $this->getLineRangeService()->getAllLineRange($lineIdList);
        if (empty($lineRangeList)) return $list;
        foreach ($list['data'] as &$line) {
            $line['line_range'] = $lineRangeList[$line['id']]['line_range'];
            $line['work_day_list'] = $lineRangeList[$line['id']]['line_range'];
        }
        return $list;
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
                $index++;
            }
        }
        $rowCount = parent::insertAll($newItemList);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路范围新增失败');
        }
    }


    /**
     * 验证
     * max(A.start,B.start)<=min(A.end,B,end)
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     */
    public function check($params)
    {
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $params['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('仓库不存在!');
        }
        $params['work_day_list'] = explode(',', $params['work_day_list']);
        return $params;
    }

    public function checkRange($codeArr1, $codeArr2)
    {
        $max = max($codeArr1['min'],$codeArr2['min']);
        $min = min($codeArr1['max'],$codeArr2['max']);
        //max(A.start,B.start)<=min(A.end,B,end)

    }
}