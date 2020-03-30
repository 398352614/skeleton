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
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\ImportTrait;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class LineService extends BaseService
{
    use ImportTrait;

    public $filterRules = [
        'name' => ['like', 'name'],
        'country' => ['=', 'country'],
    ];

    public function __construct(Line $line)
    {
        parent::__construct($line, LineResource::class);
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

    /**
     * 取件线路 服务
     * @return TourService
     */
    private function getTourService()
    {
        return self::getInstance(TourService::class);
    }

    public function postQuery($postCode)
    {
        $line_range = $this->getLineRangeService()->query
            ->where('post_code_start', '<=', $postCode)
            ->where('post_code_end', '>=', $postCode)->distinct()->pluck('line_id');
        return $this->query->whereIn('id', $line_range);
    }

    public function getPageList()
    {
        $list = [];
        //如果存在post_code查询
        if (!empty($this->formData['post_code'])) {
            $list = $this->postQuery($this->formData['post_code']);
            /*            $list=$this->query->leftJoin('line_range' ,'line_range.line_id','=','line.id')
                            ->where('line_range.post_code_start','<=',$this->formData['post_code'])
                            ->where('line_range.post_code_end','>=',$this->formData['post_code']);*/
            if (!empty($this->formData['name'])) {
                $list = $list->where('name', 'like', "%{$this->formData['name']}%");
            }
            if (!empty($this->formData['country'])) {
                $list = $list->where('country', '=', $this->formData['country']);
            }
            $list = $this->resource::collection($list->paginate());
        } else {
            $list = parent::getPageList();
        }
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
            })->unique(function ($item) {
                return $item['post_code_start'] . $item['post_code_end'];
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
        $lineData = Arr::only($params, ['name', 'country', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days']);
        $lineData = array_merge($lineData, ['creator_id' => auth()->id(), 'creator_name' => auth()->user()->fullname]);
        $lineId = parent::insertGetId($lineData);
        if ($lineId === 0) {
            throw new BusinessLogicException('线路新增失败');
        }
        //线路范围新增
        $newItemList = [];
        $index = 0;
        $itemList = $params['item_list'];
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
        $rowCount = parent::updateById($id, Arr::only($data, ['name', 'country', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days']));
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
        $itemList = $data['item_list'];
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
        //验证线路区间
        $this->checkRange($params, $id);
        $params['work_day_list'] = explode(',', $params['work_day_list']);
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
        if (empty($params['item_list'])) {
            throw new BusinessLogicException('邮编范围不能为空');
        }
        $itemList = $params['item_list'];
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
            if ($this->getLineRangeService()->checkIfPostcodeIntervalOverlap($item['post_code_start'], $item['post_code_end'], $params['country'], $params['work_day_list'], $id)) {
                throw new BusinessLogicException("邮编:post_code_start到:post_code_end已存在", 1000, ['post_code_start' => $item['post_code_start'], 'post_code_end' => $item['post_code_end']]);
            }
        }
    }

    /**
     * 获取线路信息
     * @param $info
     * @param $orderOrBatch
     * @return array
     * @throws BusinessLogicException
     */
    public function getInfoByRule($info, $orderOrBatch = BaseConstService::ORDER_OR_BATCH_1)
    {
        //获取邮编数字部分
        $postCode = explode_post_code($info['receiver_post_code']);
        //获取线路范围
        $lineRange = $this->getLineRangeService()->getInfo(['post_code_start' => ['<=', $postCode], 'post_code_end' => ['>=', $postCode], 'schedule' => Carbon::parse($info['execution_date'])->dayOfWeek, 'country' => $info['receiver_country']], ['*'], false);
        if (empty($lineRange)) {
            throw new BusinessLogicException('当前订单没有合适的线路，请先联系管理员');
        }
        $lineRange = $lineRange->toArray();
        //获取线路信息
        $line = parent::getInfo(['id' => $lineRange['line_id']], ['*'], false);
        if (empty($line)) {
            throw new BusinessLogicException('当前订单没有合适的线路，请先联系管理员');
        }
        $line = $line->toArray();
        //预约当天的，需要判断是否在下单截止日期内
        if (date('Y-m-d') == $info['execution_date']) {
            if (time() > strtotime($info['execution_date'] . ' ' . $line['order_deadline'])) {
                throw new BusinessLogicException('当天下单已超过截止时间');
            }
        }
        //判断预约日期是否在可预约日期范围内
        if (Carbon::today()->addDays($line['appointment_days'])->lt($info['execution_date'] . ' 00:00:00')) {
            throw new BusinessLogicException('预约日期已超过可预约时间范围');
        }
        //若不是新增取件线路，则当前取件线路必须再最大订单量内
        if ($line['is_increment'] == BaseConstService::IS_INCREMENT_2) {
            if ($orderOrBatch === BaseConstService::ORDER_OR_BATCH_1) {
                if ($info['type'] == 1) {
                    $orderCount = $this->getTourService()->sumOrderCount($info, $line, 1);
                    if (1 + $orderCount['pickup_count'] > $line['pickup_max_count']) {
                        throw new BusinessLogicException('当前线路已达到最大取件订单数量');
                    };
                } else {
                    $orderCount = $this->getTourService()->sumOrderCount($info, $line, 2);
                    if (1 + $orderCount['pie_count'] > $line['pie_max_count']) {
                        throw new BusinessLogicException('当前线路已达到最大取件订单数量');
                    };
                }
            } else {
                $orderCount = $this->getTourService()->sumOrderCount($info, $line, 3);
                if ($info['expect_pickup_quantity'] + $orderCount['pickup_count'] > $line['pickup_max_count']) {
                    throw new BusinessLogicException('当前线路已达到最大取件订单数量');
                };
                if ($info['expect_pie_quantity'] + $orderCount['pie_count'] > $line['pie_max_count']) {
                    throw new BusinessLogicException('当前线路已达到最大取件订单数量');
                };
            }
        }
        return $line;
    }

    public function getUploadService()
    {
        return self::getInstance(UploadService::class);
    }

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function lineImportValidate($params)
    {
        //验证$params
        $checkfile = \Illuminate\Support\Facades\Validator::make($params,
            ['file' => 'required|file|mimes:txt,xls,xlsx'],
            ['file.file' => '必须是文件']);
        if ($checkfile->fails()) {
            $error = array_values($checkfile->errors()->getMessages())[0][0];
            throw new BusinessLogicException($error, 301);
        }
    }

    /**
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function lineImport($params)
    {
        $this->lineImportValidate($params);
        $params['dir'] = 'line';
        $params['path'] = $this->getUploadService()->fileUpload($params)['path'];
        $params['path'] = str_replace('http://tms-api.test/storage/', 'public//', $params['path']);
        $row = $this->lineExcelImport($params['path'])[0];
        for($i=1;$i < count($row);$i++){
            $data[$i]['line_name'] =$row[$i][0];
            $data[$i]['post_code_start'] =$row[$i][1];
            $data[$i]['post_code_end'] =$row[$i][2];
            $data[$i]['schedule'] =$row[$i][3];
            $data[$i]['country'] =$row[$i][4];
            $data[$i]['appointment_days'] =$row[$i][5];
            $data[$i]['order_deadline'] =$row[$i][6];
            $data[$i]['pickup_max_count'] =$row[$i][7];
            $data[$i]['pie_max_count'] =$row[$i][8]??$row[$i][7];
            $data[$i]['item_list'] =['post_code_start'=>$data[$i]['post_code_start'],'post_code_end'=>$data[$i]['post_code_end'] =$row[$i][2]];
        }
        dd($data=array_create_group_index($data,'line_name'));
        for($i=0;$i<count();){

        }

        dd($data);
        return $this->insertAll($row);
    }
}
