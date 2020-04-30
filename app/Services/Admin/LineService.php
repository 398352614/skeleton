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
        $lineData = Arr::only($params, ['name', 'country', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days', 'remark']);
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
        $rowCount = parent::updateById($id, Arr::only($data, ['name', 'country', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days', 'remark']));
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
        $tour = $this->getTourService()->getInfo(['line_id' => $id, 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_4]]], ['id'], false);
        if (!empty($tour)) {
            throw new BusinessLogicException('当前正在使用该线路');
        }
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
            throw new BusinessLogicException('仓库不存在！');
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
                        throw new BusinessLogicException('当前线路已达到最大派件订单数量');
                    };
                }
            } else {
                $orderCount = $this->getTourService()->sumOrderCount($info, $line, 3);
                if ($info['expect_pickup_quantity'] + $orderCount['pickup_count'] > $line['pickup_max_count']) {
                    throw new BusinessLogicException('当前线路已达到最大取件订单数量');
                };
                if ($info['expect_pie_quantity'] + $orderCount['pie_count'] > $line['pie_max_count']) {
                    throw new BusinessLogicException('当前线路已达到最大派件订单数量');
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
            ['file' => 'required|file|mimes:txt,xls,xlsx',
                'is_increment' => 'sometimes|integer|in:1,2',
                'order_deadline' => 'sometimes|date_format:H:i:s',
                'appointment_days' => 'sometimes|integer',
                'warehouse_id' => 'sometimes|integer'],
            ['file.file' => '必须是文件']);
        if (array_key_exists('warehouse_id', $params)) {
            if (empty($this->getWareHouseService()->getInfo(['id' => $params['warehouse_id']], ['*']))) {
                throw new BusinessLogicException('仓库不存在！');
            }
        }
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
        //数据获取
        $this->lineImportValidate($params);
        $params['name'] = 'line';
        $params['dir'] = 'line';
        $params['path'] = $this->getUploadService()->fileUpload($params)['path'];
        $params['path'] = str_replace(config('app.url') . '/storage/', 'public//', $params['path']);
        $row = $this->lineExcelImport($params['path'])[0];
        //内部数据处理
        for ($i = 1; $i < count($row); $i++) {
            $data[$i]['name'] = $row[$i][0];
            $data[$i]['post_code_start'] = $row[$i][1];
            $data[$i]['post_code_end'] = $row[$i][2];
            $data[$i]['schedule'] = $row[$i][3];
            $data[$i]['country'] = $row[$i][4] ?? 'NL';
            $data[$i]['appointment_days'] = $row[$i][5];
            $data[$i]['order_deadline'] = $row[$i][6];
            $data[$i]['pickup_max_count'] = $row[$i][7];
            $data[$i]['pie_max_count'] = $row[$i][8] ?? $row[$i][7];
            if ($data[$i]['pie_max_count'] === 0) {
                $data[$i]['pie_max_count'] = 10000;
            };
            if ($data[$i]['pickup_max_count'] === 0) {
                $data[$i]['pickup_max_count'] = 10000;
            };
        }
        //外部数据获取
        $lineList = collect($data)->groupBy('name')->map(function ($itemLineList) use ($params, $data) {
            $itemLineList = $itemLineList->toArray();
            $itemLineList[0]['appointment_days'] = $params['appointment_days'] ?? 30;
            $itemLineList[0]['order_deadline'] = $params['order_deadline'] ?? '23:59:59';
            $itemLineList[0]['warehouse_id'] = $params['warehouse_id'] ?? $this->getWareHouseService()->query->first()->value('id');
            $itemLineList[0]['is_increment'] = $params['is_increment'] ?? 2;
            $lineData = Arr::only($itemLineList[0], ['name', 'country', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days']);
            $lineData = array_merge($lineData, ['creator_id' => auth()->id(), 'creator_name' => auth()->user()->fullname]);
            $info = $lineData['name'];
            if ($this->getInfo(['name' => $lineData['name'], 'company_id' => auth()->user()->company_id], ['*'], false)) {
                throw new BusinessLogicException($lineData['name'] . '名称已存在');
            }
            //插入主表
            $itemLineList[0]['line_id'] = parent::insertGetId($lineData);
            for ($i = 0; $i < count($itemLineList); $i++) {
                $itemLineList[$i]['line_id'] = $itemLineList[0]['line_id'];
                $itemLineList[$i]['company_id'] = auth()->user()->company_id;
                $itemLineList[$i]['country'] = $itemLineList[0]['country'];
                $itemLineList[$i] = Arr::only($itemLineList[$i], ['name', 'line_id', 'post_code_start', 'post_code_end', 'schedule', 'country']);
                //自动别名重复（邮编范围，星期），并插入主表
                if ($itemLineList[$i]['schedule'] !== $itemLineList[0]['schedule']) {
                    $lineData['name'] = $info . $i;
                    $itemLineList[$i]['line_id'] = parent::insertGetId($lineData);
                }
            }
            return collect($itemLineList);
        });
        $lineList = array_values($lineList->toArray());
        //邮编自我验证
        for ($i = 0; $i < count($lineList); $i++) {
            $this->lineRangeValidate($lineList[$i]);
        }
        //去除按Name分组
        $info = [];
        for ($i = 0; $i < count($lineList); $i++) {
            $info = array_merge($info, $lineList[$i]);
        }
        //邮编与已有线路验证
        $this->postCodeValidate($info);
        //处理插入数据格式
        for ($i = 0; $i < count($info); $i++) {
            $info[$i] = Arr::except($info[$i], 'name');
        }
        //批量插入
        if ($this->getLineRangeService()->insertAll($info) === true) {
            return success();
        };
        return failed();
    }

    /**
     * 主表验证
     * @param $params
     * @throws BusinessLogicException
     */
    public function lineRangeValidate($params)
    {
        Validator::make($params,
            ['file' => 'required|file|mimes:txt,xls,xlsx',
                'is_increment' => 'sometimes|integer|in:1,2',
                'order_deadline' => 'sometimes|date_format:H:i:s',
                'appointment_days' => 'sometimes|integer',
                'warehouse_id' => 'sometimes|integer'],
            ['file.file' => '必须是文件']);
        $length = count($params);
        for ($i = 0; $i <= $length - 1; $i++) {
            for ($j = $i + 1; $j <= $length - 1; $j++) {
                if (max($params[$i]['post_code_start'], $params[$j]['post_code_start']) <= min($params[$i]['post_code_end'], $params[$j]['post_code_end'] && $params[$i]['line_id']) === $params[$j]['line_id']) {
                    throw new BusinessLogicException($params[$i]['name'] . '邮编存在重叠,无法添加');
                }
            }
        }
    }

    /**
     * 附表验证
     * @param $params
     * @throws BusinessLogicException
     */
    public function postCodeValidate($params)
    {
        foreach ($params as $item) {
            if ($item['post_code_start'] > $item['post_code_end']) {
                throw new BusinessLogicException($item['name'] . '邮编列表截止邮编必须大于起始邮编');
            }
            if ($item['post_code_start'] < 1000 || $item['post_code_start'] > 9999) {
                throw new BusinessLogicException($item['name'] . '邮编列表起始邮编范围必须在1000-9999之间');
            }
            if ($item['post_code_end'] < 1000 || $item['post_code_end'] > 9999) {
                throw new BusinessLogicException($item['name'] . '邮编列表截至邮编范围必须在1000-9999之间');
            }
            if ($this->getLineRangeService()->checkIfPostcodeIntervalOverlap($item['post_code_start'], $item['post_code_end'], $item['country'], $item['schedule'])) {
                throw new BusinessLogicException($item['name'] . "邮编:post_code_start到:post_code_end已存在", 1000, ['post_code_start' => $item['post_code_start'], 'post_code_end' => $item['post_code_end']]);
            }
        }
    }
}
