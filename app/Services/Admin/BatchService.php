<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\BatchResource;
use App\Http\Resources\TourResource;
use App\Models\Batch;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\OrderNoRuleService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;

class BatchService extends BaseService
{

    public $filterRules = [
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'order_no,out_order_no' => ['like', 'keyword']
    ];

    public function __construct(Batch $batch)
    {
        $this->model = $batch;
        $this->query = $this->model::query();
        $this->resource = BatchResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

    /**
     * 线路服务
     * @return LineService
     */
    public function getLineService()
    {
        return self::getInstance(LineService::class);
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
     * 单号规则 服务
     * @return OrderNoRuleService
     */
    public function getOrderNoRuleService()
    {
        return self::getInstance(OrderNoRuleService::class);
    }

    /**
     * 取件线路 服务
     * @return TourService
     */
    public function getTourService()
    {
        return self::getInstance(TourService::class);
    }

    //新增
    public function store($params)
    {

    }


    /**
     * 加入站点
     * @param $order
     * @return array
     * @throws BusinessLogicException
     */
    public function join($order)
    {
        $line = [];
        $batch = parent::getInfo([
            'receiver' => $order['receiver'],
            'receiver_phone' => $order['receiver_phone'],
            'receiver_country' => $order['receiver_country'],
            'receiver_house_number' => $order['receiver_house_number'],
            'receiver_post_code' => $order['receiver_post_code'],
            'status' => ['in', [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED]]
        ], ['*'], false);
        //若存在相同相同站点,则直接加入站点,否则新建站点
        if (!empty($batch)) {
            $line = $this->getLineService()->getInfo(['id' => $batch['line_id']], ['*'], false);
            if (empty($line)) {
                throw new BusinessLogicException('当前订单没有合适的线路,请先联系管理员');
            }
            $line = $line->toArray();
            $batch = $batch->toArray();
            $data = (intval($order['type']) === 1) ? ['expect_pickup_quantity' => intval($batch['expect_pickup_quantity']) + 1] : ['expect_pie_quantity' => intval($batch['expect_pie_quantity']) + 1];
            $rowCount = parent::updateById($batch['id'], $data);
            if ($rowCount === false) {
                throw new BusinessLogicException('订单加入站点失败!');
            }
        } else {
            //todo 验证邮编是否存在经纬度
            list($lon, $lat) = $this->getLocation();
            $postCode = explode_post_code($order['receiver_post_code']);
            $lineRange = $this->getLineRangeService()->getInfo(['post_code_start' => ['<=', $postCode], 'post_code_end' => ['>=', $postCode], 'schedule' => Carbon::parse($order['execution_date'])->dayOfWeek], ['*'], false);
            if (empty($lineRange)) {
                throw new BusinessLogicException('当前订单没有合适的线路,请先联系管理员');
            }
            $lineRange = $lineRange->toArray();
            $line = $this->getLineService()->getInfo(['id' => $lineRange['line_id']], ['*'], false);
            if (empty($line)) {
                throw new BusinessLogicException('当前订单没有合适的线路,请先联系管理员');
            }
            $line = $line->toArray();
            $batchNo = $this->getOrderNoRuleService()->createBatchNo();
            $batch = parent::create($this->fillData($order, $line, $batchNo, $lon, $lat));
            if ($batch === false) {
                throw new BusinessLogicException('订单加入站点失败!');
            }
            $batch = $batch->getOriginal();
        }
        /**************************************站点加入取件线路********************************************************/
        $tour = $this->getTourService()->join($batch, $line, $order['type']);
        //填充取件线路编号
        $rowCount = parent::updateById($batch['id'], ['tour_no' => $tour['tour_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点加入取件线路失败,请重新操作');
        }
        return [$batch, $tour];
    }

    /**
     * 填充站点新增数据
     * @param $order
     * @param $line
     * @param $batchNo
     * @param $lon
     * @param $lat
     * @return array
     */
    private function fillData($order, $line, $batchNo, $lon, $lat)
    {
        $data = [
            'batch_no' => $batchNo,
            'line_id' => $line['id'],
            'line_name' => $line['name'],
            'execution_date' => $order['execution_date'],
            'receiver' => $order['receiver'],
            'receiver_phone' => $order['receiver_phone'],
            'receiver_country' => $order['receiver_country'],
            'receiver_post_code' => $order['receiver_post_code'],
            'receiver_house_number' => $order['receiver_house_number'],
            'receiver_city' => $order['receiver_city'],
            'receiver_street' => $order['receiver_street'],
            'receiver_address' => $order['receiver_address'],
            'receiver_lon' => $lon,
            'receiver_lat' => $lat,
        ];
        if (intval($order['type']) === 1) {
            $data['expect_pickup_quantity'] = 1;
        } else {
            $data['expect_pie_quantity'] = 1;
        }
        return $data;
    }


    public function getLocation()
    {
        return ['3.14', '3.14'];
    }


}