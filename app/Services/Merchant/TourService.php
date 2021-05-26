<?php

namespace App\Services\Merchant;

use App\Events\AfterTourUpdated;
use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Merchant\TourInfoResource;
use App\Http\Resources\Api\Merchant\TourResource;
use App\Models\Batch;
use App\Models\Tour;
use App\Models\TourLog;
use App\Services\ApiServices\GoogleApiService;
use App\Services\ApiServices\TourOptimizationService;
use App\Services\BaseConstService;
use App\Services\BaseServices\XLDirectionService;
use App\Traits\ExportTrait;
use App\Traits\LocationTrait;
use App\Traits\TourRedisLockTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TourService extends BaseService
{
    use TourRedisLockTrait, ExportTrait;

    /**
     * @var GoogleApiService
     */
    public $apiClient;

    /**
     * @var XLDirectionService
     */
    public $directionClient;

    public $filterRules = [
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'driver_id' => ['=', 'driver_id'],
        'line_name' => ['like', 'line_name'],
        'tour_no' => ['like', 'tour_no']
    ];

    protected $headings = [
        'id',
        'place_fullname',
        'place_phone',
        'out_user_id',
        'place_address',
        'place_post_code',
        'place_city',
        'merchant',
        'expect_pickup_quantity',
        'expect_pie_quantity',
        'express_first_no_one',
        'express_first_no_two',
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(Tour $tour, GoogleApiService $client, XLDirectionService $directionClient)
    {
        parent::__construct($tour, TourResource::class, TourInfoResource::class);
        $this->apiClient = $client;
        $this->directionClient = $directionClient;
    }

    public function getPageList()
    {
        $orderQuery = $this->getTrackingOrderService()->query->whereNotNull('tour_no');
        if (!empty($this->formData['tour_no'])) {
            $orderQuery->where('tour_no', 'like', $this->formData['tour_no']);
        }
        if (!empty($this->formData['begin_date']) && !empty($this->formData['end_date'])) {
            $orderQuery->whereBetween('execution_date', [
                Carbon::parse($this->formData['begin_date'])->startOfDay(),
                Carbon::parse($this->formData['end_date'])->endOfDay()
            ]);
        }
        $tourNoList = $orderQuery->groupBy('tour_no')->pluck('tour_no')->toArray();
        $this->query->whereIn('tour_no', $tourNoList);
        if (!empty($this->formData['merchant_status'])) {
            if ($this->formData['merchant_status'] == BaseConstService::MERCHANT_TOUR_STATUS_1) {
                $this->filters['status'] = ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3]];
            } elseif (in_array($this->formData['merchant_status'], [BaseConstService::MERCHANT_TOUR_STATUS_2, BaseConstService::MERCHANT_TOUR_STATUS_3])) {
                $this->filters['status'] = ['=', intval($this->formData['merchant_status']) + 2];
            } else {
                unset($this->filters['merchant_status']);
            }
        }
        return parent::getPageList();
    }

    /**
     * 站点加入取件线路
     * @param $batch
     * @param $line
     * @param $order
     * @param $tour
     * @return BaseService|array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function join($batch, $line, $order, $tour = [])
    {
        $tour = !empty($tour) ? $tour : $this->getTourInfo($batch, $line);
        //加入取件线路
        $quantity = (intval($order['type']) === BaseConstService::TRACKING_ORDER_TYPE_1) ? ['expect_pickup_quantity' => 1] : ['expect_pie_quantity' => 1];
        $tour = !empty($tour) ? $this->joinExistTour($tour, $quantity) : $this->joinNewTour($batch, $line, $quantity);
        return $tour;
    }

    /**
     * 加入新的取件线路
     * @param $line
     * @param $batch
     * @param $quantity
     * @return BaseService|array|\Illuminate\Database\Eloquent\Model|mixed
     * @throws BusinessLogicException
     */
    private function joinNewTour($batch, $line, $quantity)
    {
        //获取网点信息
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('网点不存在！');
        }
        $warehouse = $warehouse->toArray();
        $tourNo = $this->getOrderNoRuleService()->createTourNo();
        $tour = parent::create(
            array_merge([
                'tour_no' => $tourNo,
                'line_id' => $line['id'],
                'line_name' => $line['name'],
                'execution_date' => $batch['execution_date'],
                'warehouse_id' => $warehouse['id'],
                'warehouse_name' => $warehouse['name'],
                'warehouse_phone' => $warehouse['phone'],
                'warehouse_country' => $warehouse['country'],
                'warehouse_post_code' => $warehouse['post_code'],
                'warehouse_city' => $warehouse['city'],
                'warehouse_street' => $warehouse['street'],
                'warehouse_house_number' => $warehouse['house_number'],
                'warehouse_address' => $warehouse['address'],
                'warehouse_lon' => $warehouse['lon'],
                'warehouse_lat' => $warehouse['lat'],
                'merchant_id' => $batch['merchant_id'] ?? 0
            ], $quantity)
        );
        if ($tour === false) {
            throw new BusinessLogicException('站点加入取件线路失败，请重新操作！');
        }
        return $tour->getOriginal();
    }


    /**
     * 加入已存在取件线路
     * @param $tour
     * @param $quantity
     * @return mixed
     * @throws BusinessLogicException
     */
    public function joinExistTour($tour, $quantity)
    {
        $data = [
            'expect_pickup_quantity' => !empty($quantity['expect_pickup_quantity']) ? $tour['expect_pickup_quantity'] + $quantity['expect_pickup_quantity'] : $tour['expect_pickup_quantity'],
            'expect_pie_quantity' => !empty($quantity['expect_pie_quantity']) ? $tour['expect_pie_quantity'] + $quantity['expect_pie_quantity'] : $tour['expect_pie_quantity'],
        ];
        $rowCount = parent::updateById($tour['id'], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点加入取件线路失败，请重新操作！');
        }
        $tour = array_merge($tour, $data);
        return $tour;
    }


    /**
     * 移除站点运单
     * @param $order
     * @param $batch
     * @throws BusinessLogicException
     */
    public function removeBatchTrackingOrder($order, $batch)
    {
        $info = $this->getInfoOfStatus(['tour_no' => $order['tour_no']], true, [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3], true);
        $quantity = $info['expect_pickup_quantity'] + $info['expect_pie_quantity'];
        //当站点中不存在其他运单时,删除站点;若还存在其他运单,则只移除运单
        if ($quantity - 1 <= 0) {
            $rowCount = parent::delete(['id' => $info['id']]);
        } else {
            $data = (intval($order['type']) === BaseConstService::TRACKING_ORDER_TYPE_1) ? ['expect_pickup_quantity' => $info['expect_pickup_quantity'] - 1] : ['expect_pie_quantity' => $info['expect_pie_quantity'] - 1];
            $rowCount = parent::updateById($info['id'], $data);
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('取件移除运单失败，请重新操作');
        }
    }


    /**
     * 获取取件线路信息
     * @param $batch
     * @param $line
     * @param $isLock
     * @param $tourNo
     * @param $isAssign
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getTourInfo($batch, $line, $isLock = true, $tourNo = null, $isAssign = false)
    {
        if (!empty($tourNo)) {
            $this->query->where('tour_no', '=', $tourNo);
        }
        //若不存在取件线路或者超过最大订单量,则新建取件线路
        if ((intval($batch['expect_pickup_quantity']) > 0) && ($isAssign == false)) {
            $this->query->where(DB::raw('expect_pickup_quantity+' . 1), '<=', $line['pickup_max_count']);
        }
        if ((intval($batch['expect_pie_quantity']) > 0) && ($isAssign == false)) {
            $this->query->where(DB::raw('expect_pie_quantity+' . 1), '<=', $line['pie_max_count']);
        }
        $where = ['line_id' => $line['id'], 'execution_date' => $batch['execution_date'], 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2]]];
        isset($batch['merchant_id']) && $where['merchant_id'] = $batch['merchant_id'];
        $tour = ($isLock === true) ? parent::getInfoLock($where, ['*'], false) : parent::getInfo($where, ['*'], false);
        return !empty($tour) ? $tour->toArray() : [];
    }

    /**
     * 重新统计金额
     * @param $tourNo
     * @throws BusinessLogicException
     */
    public function reCountAmountByNo($tourNo)
    {
        $totalReplaceAmount = $this->getBatchService()->sum('replace_amount', ['tour_no' => $tourNo]);
        $totalSettlementAmount = $this->getBatchService()->sum('settlement_amount', ['tour_no' => $tourNo]);
        $rowCount = parent::update(['tour_no' => $tourNo], ['replace_amount' => $totalReplaceAmount, 'settlement_amount' => $totalSettlementAmount]);
        if ($rowCount === false) {
            throw new BusinessLogicException('金额统计失败');
        }
    }


    /**
     * 此处要求batchIds 为有序,并且已完成或者异常的 batch 在前方,未完成的 batch 在后方
     * @throws BusinessLogicException
     */
    public function getNextBatchAndUpdateIndex($batchIds): Batch
    {
        $first = false;
        foreach ($batchIds as $key => $batchId) {
            $tempbatch = Batch::where('id', $batchId)->first();
            if (!$first && in_array($tempbatch->status, [
                    BaseConstService::BATCH_WAIT_ASSIGN,
                    BaseConstService::BATCH_WAIT_OUT,
                    BaseConstService::BATCH_DELIVERING,
                    BaseConstService::BATCH_ASSIGNED
                ])) {
                if ($tempbatch) {
                    $batch = $tempbatch;
                    $first = true; // 找到了下一个目的地
                }
            }
            $tempbatch->update(['sort_id' => $key + 1]);
        }
        if ($batch ?? null) {
            return $batch;
        }

        throw new BusinessLogicException('未查找到下一个目的地');
    }

    /**
     * 更新批次配送顺序
     */
    public function updateBatchIndex()
    {
        // * @apiParam {String}   batch_ids                  有序的批次数组
        // * @apiParam {String}   tour_no                    在途编号
        // set_time_limit(240);

        app('log')->info('更新线路传入的参数为:', $this->formData);

        $tour = Tour::where('tour_no', $this->formData['tour_no'])->firstOrFail();

        throw_if(
            $tour->batchs->count() != count($this->formData['batch_ids']),
            new BusinessLogicException('线路的站点数量不正确')
        );

        //此处的所有 batchids 应该经过验证!
        $nextBatch = $this->getNextBatchAndUpdateIndex($this->formData['batch_ids']);

        TourLog::create([
            'tour_no' => $this->formData['tour_no'],
            'action' => BaseConstService::TOUR_LOG_UPDATE_LINE,
            'status' => BaseConstService::TOUR_LOG_PENDING,
        ]);

        event(new AfterTourUpdated($tour, $nextBatch->batch_no));

        return '修改线路成功';
    }

    /**
     * 自动优化线路任务
     */
    public function autoOpTour()
    {
        //需要先判断当前 tour 是否被锁定状态!!! 中间件或者 validate 验证规则???
        // set_time_limit(240);

        $tour = Tour::where('tour_no', $this->formData['tour_no'])->firstOrFail();

        TourLog::create([
            'tour_no' => $this->formData['tour_no'],
            'action' => BaseConstService::TOUR_LOG_UPDATE_LINE,
            'status' => BaseConstService::TOUR_LOG_PENDING,
        ]);

        TourOptimizationService::getOpInstance($tour->company_id)->autoUpdateTour($tour);

        return '修改线路成功';
    }

    public function autoOpIndex(Tour $tour)
    {
        $key = 1;
        if ($tour->status != BaseConstService::TOUR_STATUS_4) { // 未取派的情况下,所有 batch 都是未取派的
            $batchs = $tour->batchs;
        } else {
            $preBatchs = $tour->batchs()->where('status', '<>', BaseConstService::BATCH_DELIVERING)->get()->sortByDesc('sort_id'); // 已完成的包裹排序值放前面并不影响 -- 此处不包括未开始的
            foreach ($preBatchs as $preBatch) {
                $preBatch->update(['sort_id' => $key]);
                $key++;
            }
            $batchs = $tour->batchs()->where('status', BaseConstService::BATCH_DELIVERING)->get();
        }

        $batchNos = [];

        //必须大于等于 2 才调用优化接口
        if (count($batchs) >= 2) {
            $driverLoc = [
                'batch_no' => 'driver_location',
                'place_lat' => $tour->driver_location['latitude'],
                'place_lon' => $tour->driver_location['longitude'],
            ];
            app('log')->debug('查看当前 batch 总数为:' . count($batchs) . '当前的 batch 为:', $batchs->toArray());
            app('log')->debug('整合后的数据为:', array_merge([$driverLoc], $batchs->toArray()));

            $batchNos = $this->directionClient->GetRoute(array_merge([$driverLoc], $batchs->toArray()));

            throw_unless(
                $batchNos,
                new BusinessLogicException('优化线路失败')
            );
        }

        $nextBatch = null;

        app('log')->info('当前返回的值为:' . json_encode($batchNos));

        foreach ($batchNos as $k => $batchNo) {
            Batch::where('batch_no', $batchNo)->update(['sort_id' => $key + $k]);
            if (!$nextBatch) {
                $nextBatch = Batch::where('batch_no', $batchNo)->first();
            }
        }

        return $nextBatch;
    }

    /**
     * 处理计算时间和距离的回调
     */
    public function dealCallback()
    {
        throw_unless(
            self::getTourLock($this->formData['line_code']) == 1,
            new BusinessLogicException('不存在的动作')
        );

        $tourLog = TourLog::where('tour_no', $this->formData['line_code'])->where('status', BaseConstService::TOUR_LOG_PENDING)->where('action', $this->formData['type'])->first();
        // app('log')->info('日志的时间戳为:' . $lineLog->timestamp . '当天开始的时间戳为:' . strtotime(date("Y-m-d")));
        if (time() - $tourLog->created_at->timestamp > 3600 * 24 || $tourLog->created_at->timestamp < strtotime(date("Y-m-d"))) { // 标记为异常日志
            app('log')->info('异常的线路日志为:' . $this->formData['line_code']);
            $tourLog->update(['status' => BaseConstService::TOUR_LOG_ERROR]);
            self::setTourLock($this->formData['line_code'], 0);
            throw new BusinessLogicException('更新时间已超时');
        }

        $info = $this->apiClient->LineInfo($this->formData['line_code']);
        if (!$info || $info['ret'] == 0) { // 返回错误的情况下直接返回
            app('log')->info('更新动作失败,错误信息为:' . $info['msg']);
            self::setTourLock($this->formData['line_code'], 0);
            return '已知道该次更新失败';
        }
        $data = $info['data'];

        app('log')->info('开始更新线路,线路标识为:' . $this->formData['line_code']);
        app('log')->info('api返回的结果为:', $info);

        TourLog::where('tour_no', $this->formData['line_code'])->where('action', $this->formData['type'])->update(['status' => BaseConstService::TOUR_LOG_COMPLETE]); // 日志标记为已完成
        $tour = Tour::where('tour_no', $this->formData['line_code'])->first();
        $max_time = 0;
        $max_distance = 0;

        foreach ($data['loc_res'] as $key => $res) {
            $tourBatch = Batch::where('batch_no', str_replace($this->formData['line_code'], '', $key))->where('tour_no', $this->formData['line_code'])->first();
            $tourBatch->expect_arrive_time = date('Y-m-d H:i:s', time() + $res['time']);
            $tourBatch->expect_distance = $res['distance'];
            $tourBatch->save();
            $max_time = max($max_time, $res['time']);
            $max_distance = max($max_distance, $res['distance']);
        }

        if ($tour->expect_time == 0) { // 只有未更新过的线路需要更新期望时间和距离
            $tour->expect_time = $max_time;
            $tour->expect_distance = $max_distance;
            $tour->save();
        }
        $tour->lave_distance = $max_distance;

        app('log')->info('更新线路完成,线路标识为:' . $this->formData['line_code']);
        //取消锁
        self::setTourLock($this->formData['line_code'], 0);
        return '更新完成';
    }

    /**
     * 查询
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getBatchCountInfo($id)
    {
        $info = parent::getInfo(['id' => $id, 'status' => ['in', [BaseConstService::TOUR_STATUS_4, BaseConstService::TOUR_STATUS_5]]], ['*'], true);
        if (empty($info)) {
            throw new BusinessLogicException('该取件线路不在取派中，无法进行追踪');
        }
        //通过订单查询站点编号
        $batchNoList = $this->getOrderService()->query->whereNotNull('batch_no')->where('tour_no', $info['tour_no'])->pluck('batch_no')->toArray();
        if (empty($batchNoList)) {
            throw new BusinessLogicException('该取件线路不在取派中，无法进行追踪');
        }
        $info['batchs'] = $this->getBatchService()->getList(['batch_no' => ['in', $batchNoList]], ['*'], true)->toArray(request()) ?? [];
        $info['batchs'] = collect($info['batchs'])->sortBy('sort_id')->all();
        foreach ($info['batchs'] as $k => $v) {
            $info['batchs'][$k]['sort_id'] = $k + 1;
        }
        $info['batchs'] = array_values($info['batchs']);
        $info['batch_count'] = $this->getBatchService()->count(['tour_no' => $info['tour_no']]);
        return $info;
    }

    protected function getRelativeUrl(string $url): string
    {
        return str_replace(config('app.url'), '', $url);
    }

    /**
     * 导出站点表格
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function batchExcel($id)
    {
        //取出数据
        $cellData = [];
        $tour_no = $this->query->where('id', '=', $id)->value('tour_no');
        if (empty($tour_no)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $this->getBatchService()->getList(['tour_no' => $tour_no], ['*'], false, [], ['sort_id' => 'asc', 'created_at' => 'asc'])->toArray();
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        //整理结构
        for ($i = 0; $i < count($info); $i++) {
            $orderInfo = $this->getOrderService()->getList(['batch_no' => $info[$i]['batch_no']], ['*'], false);
            if (empty($orderInfo)) {
                throw new BusinessLogicException('数据不存在');
            }
            $packageInfo = $this->getPackageService()->getList(['order_no' => $orderInfo[0]['order_no']], ['*'], false);
            if (empty($packageInfo)) {
                throw new BusinessLogicException('数据不存在');
            }
            $cellData[$i][0] = $i + 1;
            $cellData[$i][1] = $info[$i]['place_fullname'];
            $cellData[$i][2] = $info[$i]['place_phone'];
            $cellData[$i][3] = $orderInfo[0]['out_user_id'] ?? '';
            $cellData[$i][4] = $info[$i]['place_street'] . ' ' . $info[$i]['place_house_number'];
            $cellData[$i][5] = $info[$i]['place_post_code'];
            $cellData[$i][6] = $info[$i]['place_city'];
            $cellData[$i][7] = $orderInfo[0]['merchant_id_name'];
            $cellData[$i][8] = $info[$i]['expect_pickup_quantity'];
            $cellData[$i][9] = $info[$i]['expect_pie_quantity'];
            $cellData[$i][10] = $packageInfo[0]['express_first_no'] ?? "";
            $cellData[$i][11] = $packageInfo[1]['express_first_no'] ?? "";
        }
        for ($i = 0; $i < count($cellData); $i++) {
            $cellData[$i] = array_values($cellData[$i]);
        }
        $cellData = array_reverse($cellData);
        $dir = 'batchList';
        $name = date('Ymd') . $tour_no . auth()->user()->company_id;
        return $this->excelExport($name, $this->headings, $cellData, $dir);
    }

    /**
     * 导出城市线路
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function cityTxt($id)
    {
        $tourInfo = $this->getInfo(['id' => $id], ['*'], false);
        if (empty($tourInfo)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $this->getBatchService()->getList(['tour_no' => $tourInfo['tour_no']], ['*'], false, [], ['sort_id' => 'asc'])->toArray();
        $cityList = '';
        for ($i = 0; $i < count($info); $i++) {
            $cityList = $cityList . $info[$i]['place_city'] . '-';
        }
        $cityList = rtrim($cityList, "-");
        $params['name'] = $tourInfo['tour_no'];
        $params['txt'] = $tourInfo['line_name'] . ' ' . $tourInfo['driver_name'] . ':' . $tourInfo['driver_phone'] . ' ' . $cityList;
        $params['dir'] = 'tour';
        //return $this->txtExport($params['name'],$params['txt'],$params['dir']);
        return $params;
    }

    /**
     * 导出站点地图
     * @param $id
     * @return array
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function batchPng($id)
    {
        $tourInfo = $this->getInfo(['id' => $id], ['*'], false);
        if (empty($tourInfo)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $this->getBatchService()->getList(['tour_no' => $tourInfo['tour_no']], ['*'], false, [], ['sort_id' => 'asc'])->toArray();
        $params[0]['lon'] = $tourInfo['warehouse_lon'];
        $params[0]['lat'] = $tourInfo['warehouse_lat'];
        for ($i = 1; $i <= count($info); $i++) {
            $params[$i]['lon'] = $info[$i - 1]['place_lon'];
            $params[$i]['lat'] = $info[$i - 1]['place_lat'];
        }
        $name = $tourInfo['tour_no'];
        return LocationTrait::getBatchMap($params, $name);
    }

    /**
     * 统计订单数量
     *
     * @param $info
     * @param $line
     * @param int $type 1-取件2-派件3-取件和派件
     * @return array
     */
    public function sumOrderCount($info, $line, $type = 1)
    {
        $arrCount = [];
        if ($type === 1) {
            $arrCount['pickup_count'] = parent::sum('expect_pickup_quantity', ['line_id' => $line['id'], 'execution_date' => $info['execution_date']]);
        } elseif ($type === 2) {
            $arrCount['pie_count'] = parent::sum('expect_pie_quantity', ['line_id' => $line['id'], 'execution_date' => $info['execution_date']]);
        } else {
            $arrCount['pickup_count'] = parent::sum('expect_pickup_quantity', ['line_id' => $line['id'], 'execution_date' => $info['execution_date']]);
            $arrCount['pie_count'] = parent::sum('expect_pie_quantity', ['line_id' => $line['id'], 'execution_date' => $info['execution_date']]);
        }
        return $arrCount;
    }
}
