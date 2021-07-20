<?php
/**
 * 报告 服务
 * User: long
 * Date: 2019/12/25
 * Time: 18:02
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\ReportResource;
use App\Models\Tour;
use App\Models\TourMaterial;
use App\Services\BaseConstService;
use Carbon\CarbonInterval;

/**
 * Class ReportService
 * @package App\Services\Admin
 * @property TourMaterial $tourMaterialModel
 */
class ReportService extends BaseService
{

    public $filterRules = [
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'driver_name' => ['like', 'driver_name'],
        'driver_id' => ['=', 'driver_id'],
        'tour_no' => ['like', 'tour_no'],
        'car_no' => ['like', 'car_no'],
        'status' => ['=', 'status'],
        'line_name' => ['like', 'line_name'],
        'line_name,driver_name' => ['like', 'key_word'],
    ];

    private $tourMaterialModel;

    public function __construct(Tour $tour, TourMaterial $tourMaterial)
    {
        parent::__construct($tour, ReportResource::class);
        $this->tourMaterialModel = $tourMaterial;
    }

    public function getPageList()
    {
        $this->query->orderByDesc('created_at');
        $list = parent::getPageList();
        foreach ($list as &$tour) {
            $tour['expect_batch_count'] = $this->getBatchService()->count(['tour_no' => $tour['tour_no']]);
            $tour['actual_batch_count'] = $this->getBatchService()->count(['tour_no' => $tour['tour_no'], 'status' => ['in', [BaseConstService::BATCH_CHECKOUT, BaseConstService::BATCH_CANCEL]]]);
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
        $info['expect_batch_count'] = $this->getBatchService()->count(['tour_no' => $info['tour_no']]);
        $info['actual_batch_count'] = $this->getBatchService()->count(['tour_no' => $info['tour_no'], 'status' => BaseConstService::BATCH_CHECKOUT]);
        //组装线路任务站点信息
        if (!$info['actual_time'] == 0) {
            $warehouseActualTimeHuman = CarbonInterval::second($info['actual_time'])->cascade()->forHumans();
        } else {
            $warehouseActualTimeHuman = '0秒';
        }
        if (!$info['warehouse_expect_time'] == 0) {
            $warehouseExpectTimeHuman = CarbonInterval::second($info['warehouse_expect_time'])->cascade()->forHumans();
        } else {
            $warehouseExpectTimeHuman = '0秒';
        }
        $batchList = $this->getBatchService()->getList(['tour_no' => $info['tour_no']], ['*'], false)->toArray();
        if (empty($batchList)) {
            throw new BusinessLogicException('数据不存在');
        }
        $batch = collect($batchList)->sortBy('sort_id')->sortByDesc('actual_arrive_time')->first();
        $warehouseInfo = [
            'id' => $info['warehouse_id'],
            'name' => $info['warehouse_name'],
            'phone' => $info['warehouse_phone'],
            'post_code' => $info['warehouse_post_code'],
            'street' => $info['warehouse_street'],
            'house_number' => $info['warehouse_house_number'],
            'city' => $info['warehouse_city'],
            'address' => $info['warehouse_address'],
            'warehouse_expect_time' => $info['warehouse_expect_time'],
            'warehouse_expect_distance' => $info['warehouse_expect_distance'],
            'warehouse_expect_arrive_time' => $info['warehouse_expect_arrive_time'],
            'warehouse_expect_time_human' => $warehouseExpectTimeHuman,

            'warehouse_actual_time' => 0,
            'warehouse_actual_distance' => 0,
            'warehouse_actual_arrive_time' => null,
            'warehouse_actual_time_human' => '0秒'
        ];
        if ($info['status'] == BaseConstService::TOUR_STATUS_5) {
            $warehouseInfo['warehouse_actual_time'] = strtotime($info['end_time']) - strtotime($batch['actual_arrive_time']);
            if (!$warehouseInfo['warehouse_actual_time'] == 0) {
                $warehouseInfo['warehouse_actual_time'] = CarbonInterval::second($warehouseInfo['warehouse_actual_time'])->cascade()->forHumans();
            } else {
                $warehouseInfo['warehouse_actual_time'] = '0秒';
            }
            $warehouseInfo['warehouse_actual_distance'] = $info['warehouse_expect_distance'];
            $warehouseInfo['warehouse_actual_arrive_time'] = $info['end_time'];
            $warehouseInfo['warehouse_actual_time_human'] = $warehouseInfo['warehouse_actual_time'];
        }
        //获取当前线路任务上的所有运单
        $trackingOrderList = $this->getTrackingOrderService()->getList(['tour_no' => $info['tour_no']], ['tracking_order_no', 'order_no', 'tour_no', 'batch_no'], false)->toArray();
        $orderNoList = array_column($trackingOrderList, 'order_no');
        $trackingOrderList = array_create_index($trackingOrderList, 'order_no');
        //获取订单列表
        $orderList = $this->getOrderService()->getList(['order_no' => ['in', $orderNoList]], ['id', 'type', 'out_user_id', 'order_no', 'out_order_no', 'status', 'special_remark', 'remark', 'settlement_amount', 'replace_amount', 'sticker_amount', 'delivery_amount'], false)->toArray();
        //获取当前线路任务上的所有包裹
        $packageList = $this->getTrackingOrderPackageService()->getList(['tour_no' => $info['tour_no']], ['*'], false)->toArray();
        //获取当前线路任务上的所有材料
        $materialList = $this->getTrackingOrderMaterialService()->getList(['tour_no' => $info['tour_no']], ['*'], false)->toArray();
        //获取站点的取件材料汇总
        $tourMaterialList = $this->getTourMaterialList($info, $materialList);
        //统计预计实际材料数量
        $info['expect_material_quantity'] = 0;
        $info['actual_material_quantity'] = 0;
        $info['finish_material_quantity'] = 0;
        if (!empty($tourMaterialList)) {
            foreach ($tourMaterialList as $v) {
                $info['expect_material_quantity'] += $v['expect_quantity'];
                $info['actual_material_quantity'] += $v['actual_quantity'];
                $info['finish_material_quantity'] += $v['finish_quantity'];
            }
        }
        //获取所有的站点
        $batchList = $this->getBatchService()->getList(['tour_no' => $info['tour_no']], ['*'], false, [], ['sort_id' => 'asc'])->toArray();
        $batchList = array_values($batchList);
        foreach ($batchList as $k => $v) {
            $batchList[$k]['sort_id'] = $k + 1;
        }
        //统计站点的各费用
        $info['card_sticker_count'] = $info['card_delivery_count'] = $info['cash_sticker_count'] = $info['cash_delivery_count'] = $info['api_sticker_count'] = $info['api_delivery_count'] = 0;
        foreach ($orderList as $k => $v) {
            $orderList[$k]['tracking_order_no'] = $trackingOrderList[$v['order_no']]['tracking_order_no'] ?? '';
            $orderList[$k]['batch_no'] = $trackingOrderList[$v['order_no']]['batch_no'] ?? '';
            $orderList[$k]['tour_no'] = $trackingOrderList[$v['order_no']]['tour_no'] ?? '';
            //更新订单统计
            $orderList[$k]['package_list'] = collect($packageList)->where('order_no', $v['order_no'])->all();
            $orderList[$k]['expect_settlement_amount'] = number_format_simple(round($v['settlement_amount'], 2), 2);
            $orderList[$k]['expect_replace_amount'] = number_format_simple(round($v['replace_amount'], 2), 2);
            $orderList[$k]['expect_total_amount'] = number_format_simple(round(($v['settlement_amount'] + $v['replace_amount']), 2), 2);
            if ($v['status'] == BaseConstService::TRACKING_ORDER_STATUS_5) {
                $orderList[$k]['actual_settlement_amount'] = number_format_simple(round($v['settlement_amount'], 2), 2);
                $orderList[$k]['actual_replace_amount'] = number_format_simple(round($v['replace_amount'], 2), 2);
                $orderList[$k]['actual_total_amount'] = number_format_simple(round(($v['settlement_amount'] + $v['replace_amount'] + $v['sticker_amount'] + $v['delivery_amount']), 2), 2);
                $orderList[$k]['sticker_count'] = count(collect($orderList[$k]['package_list'])->where('sticker_no', '<>', ""));
                $orderList[$k]['delivery_count'] = count(collect($orderList[$k]['package_list'])->where('delivery_amount', '<>', 0));
                $orderList[$k]['pay_type'] = collect($batchList)->where('batch_no', $v['batch_no'])->first()['pay_type'] ?? BaseConstService::BATCH_PAY_TYPE_1;
                if ($orderList[$k]['pay_type'] == BaseConstService::BATCH_PAY_TYPE_1) {
                    if (!empty($orderList[$k]['package_list'])) {
                        $info['cash_sticker_count'] += $orderList[$k]['sticker_count'];
                        $info['cash_delivery_count'] += $orderList[$k]['delivery_count'];
                    }
                } elseif ($orderList[$k]['pay_type'] == BaseConstService::BATCH_PAY_TYPE_2) {
                    if (!empty($orderList[$k]['package_list'])) {
                        $info['card_sticker_count'] += $orderList[$k]['sticker_count'];
                        $info['card_delivery_count'] += $orderList[$k]['delivery_count'];
                    }
                } elseif ($orderList[$k]['pay_type'] == BaseConstService::BATCH_PAY_TYPE_3) {
                    $info['api_sticker_count'] += $orderList[$k]['sticker_count'];
                    $info['api_delivery_count'] += $orderList[$k]['delivery_count'];
                }
            } else {
                $orderList[$k]['actual_settlement_amount'] = $orderList[$k]['actual_total_amount'] = $orderList[$k]['actual_replace_amount'] = number_format_simple(0.00, 2);
                $orderList[$k]['delivery_count'] = $orderList[$k]['sticker_count'] = 0;
            }
        }
        $info = $this->countByPayType($info, $batchList);
        //将订单的货号赋值给其所有包裹
        foreach ($packageList as $k => $v) {
            $packageList[$k]['out_order_no'] = collect($orderList)->where('order_no', $v['order_no'])->first()['out_order_no'] ?? '';
        }
        /**********************************************获取出库信息****************************************************/
        $outWarehouseInfo = $this->getOutWarehouseInfo($info, $warehouseInfo, $packageList, $tourMaterialList);
        /**********************************************获取入库信息****************************************************/
        $inWarehouseInfo = $this->getInWarehouseInfo($info, $warehouseInfo, $packageList, $tourMaterialList);
        /********************************************获取取件站点信息**************************************************/
        $detailList = $this->getBatchInfoList($batchList, $orderList, $materialList, $packageList);
        $info['out_warehouse'] = $outWarehouseInfo;
        $info['detail_list'] = $detailList;
        $info['in_warehouse'] = $inWarehouseInfo;
        return $info;
    }

    public function countByPayType($info, $batchList)
    {
        $info['card_settlement_amount'] = $info['card_replace_amount'] = $info['card_sticker_amount'] = $info['card_delivery_amount'] = $info['card_total_amount'] = 0;
        $info['cash_settlement_amount'] = $info['cash_replace_amount'] = $info['cash_sticker_amount'] = $info['cash_delivery_amount'] = $info['cash_total_amount'] = 0;
        $info['api_settlement_amount'] = $info['api_replace_amount'] = $info['api_sticker_amount'] = $info['api_delivery_amount'] = $info['api_total_amount'] = 0;
        foreach ($batchList as $k => $v) {
            $batchList[$k]['actual_total_amount'] = number_format_simple(round(($v['actual_settlement_amount'] + $v['actual_replace_amount'] + $v['sticker_amount'] + $v['delivery_amount']), 2), 2);
            //更新线路任务统计
            if ($v['pay_type'] == BaseConstService::BATCH_PAY_TYPE_1) {
                $info['cash_settlement_amount'] += floatval($v['actual_settlement_amount']);
                $info['cash_replace_amount'] += floatval($v['actual_replace_amount']);
                $info['cash_sticker_amount'] += floatval($v['sticker_amount']);
                $info['cash_delivery_amount'] += floatval($v['delivery_amount']);
                $info['cash_total_amount'] += floatval($batchList[$k]['actual_total_amount']);
            } elseif ($v['pay_type'] == BaseConstService::BATCH_PAY_TYPE_2) {
                $info['card_settlement_amount'] += floatval($v['actual_settlement_amount']);
                $info['card_replace_amount'] += floatval($v['actual_replace_amount']);
                $info['card_sticker_amount'] += floatval($v['sticker_amount']);
                $info['card_delivery_amount'] += floatval($v['delivery_amount']);
                $info['card_total_amount'] += floatval($batchList[$k]['actual_total_amount']);
            } elseif ($v['pay_type'] == BaseConstService::BATCH_PAY_TYPE_3) {
                $info['api_settlement_amount'] += floatval($v['actual_settlement_amount']);
                $info['api_replace_amount'] += floatval($v['actual_replace_amount']);
                $info['api_sticker_amount'] += floatval($v['sticker_amount']);
                $info['api_delivery_amount'] += floatval($v['delivery_amount']);
                $info['api_total_amount'] += floatval($batchList[$k]['actual_total_amount']);
            }
        }
        $info['card_settlement_amount'] = number_format_simple(round($info['card_settlement_amount'], 2), 2);
        $info['card_replace_amount'] = number_format_simple(round($info['card_replace_amount'], 2), 2);
        $info['card_sticker_amount'] = number_format_simple(round($info['card_sticker_amount'], 2), 2);
        $info['card_delivery_amount'] = number_format_simple(round($info['card_delivery_amount'], 2), 2);
        $info['card_total_amount'] = number_format_simple(round($info['card_total_amount'], 2), 2);

        $info['cash_settlement_amount'] = number_format_simple(round($info['cash_settlement_amount'], 2), 2);
        $info['cash_replace_amount'] = number_format_simple(round($info['cash_replace_amount'], 2), 2);
        $info['cash_sticker_amount'] = number_format_simple(round($info['cash_sticker_amount'], 2), 2);
        $info['cash_delivery_amount'] = number_format_simple(round($info['cash_delivery_amount'], 2), 2);
        $info['cash_total_amount'] = number_format_simple(round($info['cash_total_amount'], 2), 2);

        $info['api_settlement_amount'] = number_format_simple(round($info['api_settlement_amount'], 2), 2);
        $info['api_replace_amount'] = number_format_simple(round($info['api_replace_amount'], 2), 2);
        $info['api_sticker_amount'] = number_format_simple(round($info['api_sticker_amount'], 2), 2);
        $info['api_delivery_amount'] = number_format_simple(round($info['api_delivery_amount'], 2), 2);
        $info['api_total_amount'] = number_format_simple(round($info['api_total_amount'], 2), 2);

        return $info;
    }

    /**
     * 获取材料
     * @param $tour
     * @param $materialList
     * @return array
     */
    private function getTourMaterialList($tour, $materialList)
    {
        if (in_array(intval($tour['status']), [BaseConstService::TOUR_STATUS_4, BaseConstService::TOUR_STATUS_5])) {
            $materialList = $this->tourMaterialModel->newQuery()->where('tour_no', '=', $tour['tour_no'])->get()->toArray();
        } else {
            $materialList = collect($materialList)->groupBy('code')->map(function ($materialList, $key) {
                $quantity = $materialList->sum('expect_quantity');
                $materialList = $materialList->toArray();
                $materialList[0]['expect_quantity'] = $quantity;
                $materialList[0]['actual_quantity'] = 0;
                $materialList[0]['finish_quantity'] = 0;
                return $materialList[0];
            })->toArray();
        }
        return array_values($materialList);
    }


    /**
     * 获取出库信息
     * @param $tour
     * @param $warehouseInfo
     * @param $packageList
     * @param $tourMaterialList
     * @return mixed
     */
    private function getOutWarehouseInfo($tour, $warehouseInfo, $packageList, $tourMaterialList)
    {
        $outWarehouseInfo = $warehouseInfo;
        $outWarehouseInfo['signature'] = $tour['begin_signature'];
        $outWarehouseInfo['package_list'] = $packageList;
        $outWarehouseInfo['material_list'] = $tourMaterialList;
        return $outWarehouseInfo;
    }

    /**
     * 获取入库信息
     * @param $tour
     * @param $warehouseInfo
     * @param $packageList
     * @param $tourMaterialList
     * @return mixed
     */
    private function getInWarehouseInfo($tour, $warehouseInfo, $packageList, $tourMaterialList)
    {
        $inWarehouseInfo = $warehouseInfo;
        $inWarehouseInfo['signature'] = $tour['end_signature'];
        $inWarehouseInfo['package_list'] = $packageList;
        $inWarehouseInfo['material_list'] = $tourMaterialList;
        return $inWarehouseInfo;
    }

    /**
     * 获取取件站点列表信息
     * @param $batchList
     * @param $orderList
     * @param $materialList
     * @param $packageList
     * @return array
     */
    private function getBatchInfoList($batchList, $orderList, $materialList, $packageList)
    {
        $newBatchList = [];
        $orderList = array_create_group_index($orderList, 'batch_no');
        //材料处理
        $materialList = collect($materialList)->groupBy('batch_no')->toArray();
        foreach ($batchList as $key => $batch) {
            if (!array_key_exists($batch['batch_no'], $orderList)) {
                continue;
            }
            $newBatchList[$key] = [
                'id' => $batch['id'],
                'batch_no' => $batch['batch_no'],
                'name' => $batch['place_fullname'],
                'phone' => $batch['place_phone'],
                'province' => $batch['place_province'],
                'post_code' => $batch['place_post_code'],
                'city' => $batch['place_city'],
                'district' => $batch['place_district'],
                'street' => $batch['place_street'],
                'house_number' => $batch['place_house_number'],
                'address' => $batch['place_address'],
                'expect_quantity' => $batch['expect_pickup_quantity'] + $batch['expect_pie_quantity'],
                'sticker_amount' => number_format_simple(round($batch['sticker_amount'], 2), 2),
                'delivery_amount' => number_format_simple(round($batch['delivery_amount'], 2), 2),
                'replace_amount' => number_format_simple(round($batch['actual_replace_amount'], 2), 2),
                'settlement_amount' => number_format_simple(round($batch['actual_settlement_amount'], 2), 2),
                'total_amount' => number_format_simple(round(($batch['actual_settlement_amount'] + $batch['sticker_amount'] + $batch['delivery_amount'] + $batch['actual_replace_amount']), 2), 2),
                'cancel_type' => $batch['cancel_type'],
                'cancel_remark' => $batch['cancel_remark'],
                'cancel_picture' => $batch['cancel_picture'],
                'auth_fullname' => $batch['auth_fullname'],
                'auth_birth_date' => $batch['auth_birth_date'],
                'pay_picture' => $batch['pay_picture'],
                'pay_type' => $batch['pay_type'],
                'pay_type_name' => $batch['pay_type_name'],
                'signature' => $batch['signature'],
                'expect_arrive_time' => $batch['expect_arrive_time'],
                'actual_arrive_time' => $batch['actual_arrive_time'],
                'expect_distance' => $batch['expect_distance'],
                'actual_distance' => $batch['actual_distance'],
                'expect_time' => $batch['expect_time'],
                'actual_time' => $batch['actual_time'],
                'expect_time_human' => $batch['expect_time_human'],
                'actual_time_human' => $batch['actual_time_human'],
                'sort_id' => $batch['sort_id'],
            ];
            $newBatchList[$key]['order_list'] = $orderList[$batch['batch_no']];
            $newBatchList[$key]['package_list'] = array_values(collect($packageList)->whereIn('order_no', array_column($orderList[$batch['batch_no']], 'order_no'))->toArray());
            $newBatchList[$key]['material_list'] = !empty($materialList[$batch['batch_no']]) ? array_values($materialList[$batch['batch_no']]) : [];
        }
        $arriveBatchList = array_values(collect($newBatchList)->whereNotNull('actual_arrive_time')->sortBy('actual_arrive_time')->all());
        $notArriveBatchList = array_values(collect($newBatchList)->whereNull('actual_arrive_time')->all());
        $newBatchList = array_merge($arriveBatchList, $notArriveBatchList);
        return $newBatchList;
    }
}
