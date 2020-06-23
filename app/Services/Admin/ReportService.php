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
use App\Models\TourMaterial;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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
    ];

    private $tourMaterialModel;

    public function __construct(Tour $tour, TourMaterial $tourMaterial)
    {
        parent::__construct($tour, ReportResource::class);
        $this->tourMaterialModel = $tourMaterial;
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

    /**
     * 包裹 服务
     * @return PackageService
     */
    private function getPackageService()
    {
        return self::getInstance(PackageService::class);
    }

    /**
     * 材料服务
     * @return MaterialService
     */
    private function getMaterialService()
    {
        return self::getInstance(MaterialService::class);
    }

    public function getPageList()
    {
        $this->query->orderByDesc('created_at');
        $list = parent::getPageList();
        foreach ($list as &$tour) {
            $tour['batch_count'] = $this->getBatchService()->count(['tour_no' => $tour['tour_no']]);
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
        $info['batch_count'] = $this->getBatchService()->count(['tour_no' => $info['tour_no']]);
        $info['actual_batch_count'] = $this->getBatchService()->count(['tour_no' => $info['tour_no'], 'status' => ['in', [BaseConstService::BATCH_CHECKOUT, BaseConstService::BATCH_CANCEL]]]);
        //组装取件线路站点信息
        $warehouseInfo = [
            'id' => $info['warehouse_id'],
            'name' => $info['warehouse_name'],
            'phone' => $info['warehouse_phone'],
            'post_code' => $info['warehouse_post_code'],
            'street' => $info['warehouse_street'],
            'house_number' => $info['warehouse_house_number'],
            'city' => $info['warehouse_city'],
            'address' => $info['warehouse_address'],
        ];
        //获取当前取件线路上的所有订单
        $orderList = $this->getOrderService()->getList(['tour_no' => $info['tour_no']], ['id', 'type', 'tour_no', 'batch_no', 'order_no', 'out_order_no', 'status', 'special_remark', 'remark','settlement_amount','replace_amount','sticker_amount','sticker_no'], false)->toArray();
        //获取当前取件线路上的所有包裹
        $packageList = $this->getPackageService()->getList(['tour_no' => $info['tour_no']], ['*'], false)->toArray();
        //获取当前取件线路上的所有材料
        $materialList = $this->getMaterialService()->getList(['tour_no' => $info['tour_no']], ['*'], false)->toArray();
        //获取站点的取件材料汇总
        $tourMaterialList = $this->getTourMaterialList($info, $materialList);
        //统计预计实际材料数量
        $info['expect_material_quantity'] = 0;
        $info['actual_material_quantity'] = 0;
        if (!empty($tourMaterialList)) {
            foreach ($tourMaterialList as $v) {
                $info['expect_material_quantity'] += $v['expect_quantity'];
                $info['actual_material_quantity'] += $v['actual_quantity'];
            }
        }
        //获取所有的站点
        $batchList = $this->getBatchService()->getList(['tour_no' => $info['tour_no']], ['*'], false, [], ['sort_id' => 'asc'])->toArray();
        //统计站点的各费用
        $info['card_settlement_amount'] = 0;
        $info['card_replace_amount'] = 0;
        $info['card_sticker_amount'] = 0;
        $info['card_total_amount'] =0;
        $info['card_sticker_count'] =0;
        $info['cash_settlement_amount'] = 0;
        $info['cash_replace_amount'] = 0;
        $info['cash_sticker_amount'] = 0;
        $info['cash_total_amount'] =0;
        $info['cash_sticker_count'] =0;
        foreach ($orderList as $v) {
            if($v['status'] == BaseConstService::ORDER_STATUS_5) {
                $v['pay_type']=collect($batchList)->where('batch_no',$v['batch_no'])->first()['pay_type'];
                if ($v['pay_type'] == BaseConstService::ORDER_SETTLEMENT_TYPE_1) {
                    $info['card_settlement_amount'] += $v['settlement_amount'];
                    $info['card_replace_amount'] += $v['replace_amount'];
                    $info['card_sticker_amount'] += $v['sticker_amount'];
                    $info['card_total_amount'] += $v['settlement_amount'] + $v['replace_amount'] + $v['sticker_amount'];
                    $info['card_sticker_count'] += $v['sticker_no'] ? 1 : 0;
                } else {
                    $info['cash_settlement_amount'] += $v['settlement_amount'];
                    $info['cash_replace_amount'] += $v['replace_amount'];
                    $info['cash_sticker_amount'] += $v['sticker_amount'];
                    $info['cash_total_amount'] += $v['settlement_amount'] + $v['replace_amount'] + $v['sticker_amount'];
                    $info['cash_sticker_count'] += $v['sticker_no'] ? 1 : 0;
                }
            }
        }
        /**********************************************获取出库信息****************************************************/
        $outWarehouseInfo = $this->getOutWarehouseInfo($info, $warehouseInfo, $packageList, $tourMaterialList);
        /**********************************************获取入库信息****************************************************/
        $inWarehouseInfo = $this->getInWarehouseInfo($info, $warehouseInfo, $packageList, $tourMaterialList);
        /********************************************获取取件站点信息**************************************************/
        $detailList = $this->getBatchInfoList($batchList, $orderList, $materialList);
        $info['out_warehouse'] = $outWarehouseInfo;
        $info['detail_list'] = $detailList;
        $info['in_warehouse'] = $inWarehouseInfo;
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
     * @return array
     */
    private function getBatchInfoList($batchList, $orderList, $materialList)
    {
        $newBatchList = [];

        $orderList = array_create_group_index($orderList, 'batch_no');
        //材料处理
        $materialList = collect($materialList)->groupBy('batch_no')->toArray();
        foreach ($batchList as $key => $batch) {
            $newBatchList[$key] = [
                'id' => $batch['id'],
                'name' => $batch['receiver_fullname'],
                'phone' => $batch['receiver_phone'],
                'post_code' => $batch['receiver_post_code'],
                'city' => $batch['receiver_city'],
                'street' => $batch['receiver_street'],
                'house_number' => $batch['receiver_house_number'],
                'address' => $batch['receiver_address'],
                'expect_quantity' => $batch['expect_pickup_quantity'] + $batch['expect_pie_quantity'],
                'signature' => $batch['signature'],
                'expect_arrive_time' => $batch['expect_arrive_time'],
                'actual_arrive_time' => $batch['actual_arrive_time'],
                'expect_distance' => $batch['expect_distance'],
                'actual_distance' => $batch['actual_distance'],
                'expect_time' => $batch['expect_time'],
                'actual_time' => $batch['actual_time'],
                'expect_time_human' => $batch['expect_time_human'],
                'actual_time_human' => $batch['actual_time_human'],
            ];
            $newBatchList[$key]['order_list'] = $orderList[$batch['batch_no']];
            $newBatchList[$key]['material_list'] = !empty($materialList[$batch['batch_no']]) ? array_values($materialList[$batch['batch_no']]) : [];
        }
        return $newBatchList;
    }
}
