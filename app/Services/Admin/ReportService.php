<?php
/**
 * 报告 服务
 * User: long
 * Date: 2019/12/25
 * Time: 18:02
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\ReportResource;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ReportService extends BaseService
{
    public $filterRules = [
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'driver_id' => ['=', 'driver_id']
    ];

    public function __construct(Tour $tour)
    {
        $this->request = request();
        $this->model = $tour;
        $this->query = $this->model::query();
        $this->resource = ReportResource::class;
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

    /**
     * 站点服务
     * @return BatchService
     */
    private function getBatchService()
    {
        return self::getInstance(BatchService::class);
    }

    /**
     * 订单 服务
     * @return OrderService
     */
    private function getOrderService()
    {
        return self::getInstance(OrderService::class);
    }

    public function getPageList()
    {
        $list = parent::getPageList();
        foreach ($list as &$tour) {
            $tour['batch_count'] = $this->getBatchService()->count(['tour_no' => $tour['tour_no']]);
        }
        return $list;
    }


    /**
     * 获取详情
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $info->toArray();
        //获取站点数量
        $info['batch_count'] = $this->getBatchService()->count(['tour_no' => $info['tour_no']]);
        //组装取件线路站点信息
        $warehouseInfo = [
            'id' => $info['warehouse_id'],
            'name' => $info['warehouse_name'],
            'phone' => $info['warehouse_phone'],
            'post_code' => $info['warehouse_post_code'],
            'city' => $info['warehouse_city'],
            'address' => $info['warehouse_address'],
        ];
        //获取当前取件线路上的所有订单
        $orderList = $this->getOrderService()->getList(['tour_no' => $info['tour_no']], ['id', 'type', 'tour_no', 'batch_no', 'order_no', 'out_order_no', 'status', 'special_remark', 'remark'], false)->toArray();
        //获取所有的站点
        $batchList = $this->getBatchService()->getList(['tour_no' => $info['tour_no']], ['*'], false, [], ['sort_id' => 'asc'])->toArray();
        /**********************************************获取出库信息****************************************************/
        $outWarehouseInfo = $this->getOutWarehouseInfo($info, $warehouseInfo, $orderList);
        /**********************************************获取入库信息****************************************************/
        $inWarehouseInfo = $this->getInWarehouseInfo($info, $warehouseInfo, $orderList);
        /********************************************获取取件站点信息**************************************************/
        $detailList = $this->getBatchInfoList($batchList, $orderList);

        array_unshift($detailList, $outWarehouseInfo);
        array_push($detailList, $inWarehouseInfo);
        $info['detail_list'] = $detailList;
        return $info;
    }


    /**
     * 获取出库信息
     * @param $tour
     * @param $warehouseInfo
     * @param $orderList
     * @return mixed
     */
    private function getOutWarehouseInfo($tour, $warehouseInfo, $orderList)
    {
        $outWarehouseInfo = $warehouseInfo;
        $outWarehouseInfo['expect_quantity'] = $tour['expect_pie_quantity'];
        $outWarehouseInfo['signature'] = $tour['begin_signature'];
        $outWarehouseInfo['order_list'] = array_values(collect($orderList)
            ->map(function ($order, $key) {
                $order['type_name'] = ConstTranslateTrait::$orderTypeList[$order['type']];
                $order['status_name'] = ConstTranslateTrait::$orderStatusList[$order['status']];
                return $order;
            })->filter(function ($order, $key) {
                return (intval($order['type']) === BaseConstService::ORDER_TYPE_2);
            })->toArray());
        return $outWarehouseInfo;
    }

    /**
     * 获取出库信息
     * @param $tour
     * @param $warehouseInfo
     * @param $orderList
     * @return mixed
     */
    private function getInWarehouseInfo($tour, $warehouseInfo, $orderList)
    {
        $outWarehouseInfo = $warehouseInfo;
        $outWarehouseInfo['expect_quantity'] = $tour['expect_pickup_quantity'];
        $outWarehouseInfo['signature'] = $tour['end_signature'];
        $outWarehouseInfo['order_list'] = array_values(collect($orderList)
            ->map(function ($order, $key) {
                $order['type_name'] = ConstTranslateTrait::$orderTypeList[$order['type']];
                $order['status_name'] = ConstTranslateTrait::$orderStatusList[$order['status']];
                return $order;
            })->filter(function ($order, $key) {
                return (intval($order['type']) === BaseConstService::ORDER_TYPE_1);
            })->toArray());
        return $outWarehouseInfo;
    }

    /**
     * 获取取件站点列表信息
     * @param $batchList
     * @param $orderList
     * @return array
     */
    private function getBatchInfoList($batchList, $orderList)
    {
        $newBatchList = [];
        $orderList = collect($orderList)->map(function ($order, $key) {
            $order['type_name'] = ConstTranslateTrait::$orderTypeList[$order['type']];
            $order['status_name'] = ConstTranslateTrait::$orderStatusList[$order['status']];
            return $order;
        })->toArray();
        $orderList = array_create_group_index($orderList, 'batch_no');
        foreach ($batchList as $key => $batch) {
            $newBatchList[$key] = [
                'id' => $batch['id'],
                'name' => $batch['receiver'],
                'phone' => $batch['receiver_phone'],
                'post_code' => $batch['receiver_post_code'],
                'city' => $batch['receiver_city'],
                'address' => $batch['receiver_address'],
                'expect_quantity' => $batch['expect_pickup_quantity'] + $batch['expect_pie_quantity'],
                'signature' => $batch['signature'],
                'expect_arrive_time' => $batch['expect_arrive_time'],
                'actual_arrive_time' => $batch['actual_arrive_time'],
                'expect_distance' => $batch['expect_distance'],
                'actual_distance' => $batch['actual_distance'],
                'expect_time' => $batch['expect_time'],
                'actual_time' => $batch['actual_time'],
            ];
            $newBatchList[$key]['order_list'] = $orderList[$batch['batch_no']];
        }
        return $newBatchList;
    }
}