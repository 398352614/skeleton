<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/30
 * Time: 10:42
 */

namespace App\Services\Driver;

use App\Console\Commands\FixRecharge;
use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\TourTaskResource;
use App\Models\AdditionalPackage;
use App\Models\Tour;
use App\Models\TourMaterial;
use App\Services\BaseConstService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Class TourTaskService
 * @package App\Services\Driver
 * @property TourMaterial $tourMaterialModel
 */
class TourTaskService extends BaseService
{
    public $filterRules = [
        'execution_date' => ['=', 'execution_date'],
        'status' => ['=', 'status'],
        'tour_no' => ['like', 'tour_no']
    ];

    public $orderBy = [
        'execution_date' => 'desc'
    ];

    private $tourMaterialModel;

    public function __construct(Tour $tour, TourMaterial $tourMaterial)
    {
        parent::__construct($tour, TourTaskResource::class);
        $this->tourMaterialModel = $tourMaterial;
    }

    /**
     * 获取任务列表
     * @return mixed
     */
    public function getPageList()
    {
        if (!empty($this->formData['sort_by']) && !empty($this->formData['sort'])) {
            if ($this->formData['sort_by'] == 'execution_date' && $this->formData['sort'] == BaseConstService::YES) {
                $this->query->orderByDesc('execution_date');
            } elseif ($this->formData['sort_by'] == 'execution_date' && $this->formData['sort'] == BaseConstService::NO) {
                $this->query->orderBy('execution_date');
            } elseif ($this->formData['sort_by'] == 'end_time' && $this->formData['sort'] == BaseConstService::YES) {
                $this->query->orderByDesc('end_time');
            } elseif ($this->formData['sort_by'] == 'end_time' && $this->formData['sort'] == BaseConstService::NO) {
                $this->query->orderBy('end_time');
            }
        }
        //若状态为1000,则表示当前任务
        if (!empty($this->filters['status'][1]) && (intval($this->filters['status'][1]) === 1000)) {
            $this->filters['status'] = ['in', [BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_4]];
        }
        $list = parent::getPageList();
        if (empty($list)) return $list;
        $batchFields = ['id', 'batch_no', 'place_fullname', 'place_country', 'place_post_code', 'place_house_number', 'place_city', 'place_street', 'place_address'];
        foreach ($list as &$tour) {
            //获取站点数量
            $tour['batch_count'] = $this->getBatchService()->count(['tour_no' => $tour['tour_no']]);
            //获取最后一个站点的收件人信息
            $tour['last_place'] = $this->getBatchService()->getInfo(['tour_no' => $tour['tour_no']], $batchFields, false, ['sort_id' => 'desc', 'created_at' => 'desc']);
            //获取是否有特殊事项
            $order = $this->getTrackingOrderService()->getInfo(['tour_no' => $tour['tour_no'], 'special_remark' => ['<>', null]], ['special_remark'], false);
            $tour['is_exist_special_remark'] = !empty($order) ? true : false;
        }
        return $list;
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $tour = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('数据不存在');
        }
        $tour = $tour->toArray();
        $tour['car_no'] = $tour['car_no'] ?? '';
        //获取站点数量
        $tour['batch_count'] = $this->getBatchService()->count(['tour_no' => $tour['tour_no']]);
        //获取所有站点
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no']], ['*'], false, [], ['sort_id' => 'asc', 'created_at' => 'asc']);
        //顺带包裹信息
        $additionalPackageList = AdditionalPackage::query()->whereIn('batch_no', $batchList->pluck('batch_no')->toArray())->get();
        if (!empty($additionalPackageList)) {
            $additionalPackageList = $additionalPackageList->toArray();
        } else {
            $additionalPackageList = [];
        }
        foreach ($batchList as $k => $v) {
            $batchList[$k]['additional_package_list'] = collect($additionalPackageList)->where('batch_no', $v['batch_no'])->all();
            $batchList[$k]['additional_package_count'] = count($batchList[$k]['additional_package_list']);
        }
        $tour['additional_package_list'] = $additionalPackageList;
        $tour['additional_package_count'] = count($additionalPackageList);
        //获取所有运单列表
        $trackingOrderList = $this->getTrackingOrderService()->getList(['tour_no' => $tour['tour_no']], ['*'], false)->toArray();
        $trackingOrderNoList = array_column($trackingOrderList, 'tracking_order_no');
        //获取所有材料列表
        $materialList = $this->getTourMaterialList($tour);
        //获取所有包裹列表
        $expectPickupPackageQuantity = $actualPickupPackageQuantity = $expectPiePackageQuantity = $actualPiePackageQuantity = 0;
        $packageList = $this->getTrackingOrderPackageService()->getList(['tracking_order_no' => ['in', $trackingOrderNoList]], ['*'], false)->toArray();
        for ($i = 0, $j = count($packageList); $i < $j; $i++) {
            $packageList[$i]['feature_logo'] = $packageList[$i]['feature_logo'] ?? '';
            if ($packageList[$i]['type'] == BaseConstService::PACKAGE_TYPE_1) {
                $expectPickupPackageQuantity += intval($packageList[$i]['expect_quantity']);
                $actualPickupPackageQuantity += intval($packageList[$i]['actual_quantity']);
            } else {
                $expectPiePackageQuantity += intval($packageList[$i]['expect_quantity']);
                $actualPiePackageQuantity += intval($packageList[$i]['actual_quantity']);
            }
        }
        $packageList = array_create_group_index($packageList, 'order_no');
        //将包裹列表和材料列表放在对应订单下
        $trackingOrderList = array_map(function ($trackingOrder) use ($packageList) {
            unset($trackingOrder['merchant']);
            if (empty($packageList[$trackingOrder['order_no']])) {
                $trackingOrder['package_list'] = [];
                return $trackingOrder;
            }
            $trackingOrder['package_list'] = $packageList[$trackingOrder['order_no']];
            data_set($trackingOrder['package_list'], '*.merchant', null);
            return $trackingOrder;
        }, $trackingOrderList);
        //数据填充
        //获取延迟次数
        $tour['total_delay_amount'] = $this->getTourDelayService()->count(['tour_no' => $tour['tour_no']]);
        //获取延时时间
        $tour['total_delay_time'] = intval($this->getTourDelayService()->sum('delay_time', ['tour_no' => $tour['tour_no']]));
        $tour['total_delay_time_human'] = round(intval($this->getTourDelayService()->sum('delay_time', ['tour_no' => $tour['tour_no']])) / 60) . __('分钟');
        $tour['batch_list'] = $batchList;
        $tour['tracking_order_list'] = $trackingOrderList;
        $tour['material_list'] = $materialList;
        $tour['actual_total_amount'] = number_format_simple(round($tour['sticker_amount'] + $tour['delivery_amount'] + $tour['actual_replace_amount'] + $tour['actual_settlement_amount'], 2), 2);
        //$tour['package_list'] = $packageList;
        $tour['expect_pickup_package_quantity'] = $expectPickupPackageQuantity;
        $tour['actual_pickup_package_quantity'] = $actualPickupPackageQuantity;
        $tour['expect_pie_package_quantity'] = $expectPiePackageQuantity;
        $tour['actual_pie_package_quantity'] = $actualPiePackageQuantity;
        $tour['is_exist_special_remark'] = !empty(array_column($trackingOrderList, 'special_remark')) ? true : false;
        return $tour;
    }

    /**
     * 获取材料
     * @param $tour
     * @return array
     */
    public function getTourMaterialList($tour)
    {
        if (in_array(intval($tour['status']), [BaseConstService::TOUR_STATUS_4, BaseConstService::TOUR_STATUS_5])) {
            $materialList = $this->tourMaterialModel->newQuery()->where('tour_no', '=', $tour['tour_no'])->get()->toArray();
        } else {
            $materialList = $this->getTrackingOrderMaterialService()->getList(['tour_no' => $tour['tour_no']], [
                'name',
                'code',
                DB::raw('SUM(expect_quantity) as expect_quantity'),
                DB::raw('0 as actual_quantity'),
            ], false, ['code'])->toArray();
        }
        $materialList = Arr::where($materialList, function ($material) {
            return !empty($material['code']);
        });
        return $materialList;
    }

    /**
     * 获取运单特殊事项列表
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws BusinessLogicException
     */
    public function getSpecialRemarkList($id)
    {
        $tour = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('数据不存在');
        }
        $tour = $tour->toArray();
        $trackingOrderList = $this->getTrackingOrderService()->getList(['tour_no' => $tour['tour_no'], 'special_remark' => ['<>', null]], ['id', 'order_no', 'tracking_order_no', 'special_remark', 'place_post_code', 'place_house_number'], false);
        return $trackingOrderList;
    }

    /**
     * 获取站点的特殊事项列表
     * @param $batchId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws BusinessLogicException
     */
    public function getBatchSpecialRemarkList($batchId)
    {
        $batch = $this->getBatchService()->getInfo(['id' => $batchId], ['*'], false);
        if (empty($batch)) {
            throw new BusinessLogicException('数据不存在');
        }
        $batch = $batch->toArray();
        $trackingOrderList = $this->getTrackingOrderService()->getList(['batch_no' => $batch['batch_no'], 'special_remark' => ['<>', null]], ['id', 'order_no', 'tracking_order_no', 'special_remark', 'place_post_code', 'place_house_number'], false);
        return $trackingOrderList;
    }

    /**
     * 获取特殊事项
     * @param $trackingOrderId
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getSpecialRemark($trackingOrderId)
    {
        $trackingOrder = $this->getTrackingOrderService()->getInfo(['id' => $trackingOrderId], ['*'], false);
        if (empty($trackingOrder)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $trackingOrder->toArray()['special_remark'];
    }

    /**
     * 获取运单列表
     * @param $params
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getTrackingOrderList($params)
    {
        $tour = $this->query->where('tour_no', $params['tour_no'])->where('driver_id', '<>', 0)
            ->orWhere('tour_no', $params['tour_no'])->WhereNull('driver_id')->first();
        if (empty($tour)) {
            throw new BusinessLogicException('线路任务不存在');
        }
        $tour = $tour->toArray();
        //获取所有运单列表
        $trackingOrderList = $this->getTrackingOrderService()->getList(['tour_no' => $tour['tour_no']], ['order_no', 'tracking_order_no'], false);
        if (empty($trackingOrderList)) {
            throw new BusinessLogicException('运单不存在');
        }
        $trackingOrderList = $trackingOrderList->toArray();
        //获取所有包裹列表
        $packageList = $this->getTrackingOrderPackageService()->getList(['tracking_order_no' => ['in', array_column($trackingOrderList, 'tracking_order_no')]], ['order_no', 'express_first_no', 'feature_logo']);
        $packageList = array_create_group_index($packageList, 'order_no');
        //将包裹列表和材料列表放在对应订单下
        $trackingOrderList = array_map(function ($trackingOrder) use ($packageList) {
            $trackingOrder['package_list'] = $packageList[$trackingOrder['order_no']] ?? [];
            return $trackingOrder;
        }, $trackingOrderList);
        return $trackingOrderList ?? [];
    }

    /**
     * 获取所有线路任务所有信息
     * @return array|\Illuminate\Database\Eloquent\Collection
     * @throws BusinessLogicException
     */
    public function getAllInfo()
    {
        $tour = $this->getInfo(['status' => BaseConstService::TOUR_STATUS_4], ['*'], false);
        if (empty($tour)) {
            $tour = $this->getInfo(['status' => ['<>', BaseConstService::TOUR_STATUS_5]], ['*'], false, ['id' => 'desc']);
        }
        if (empty($tour)) {
            return [];
        }
        $tour = $tour->toArray();
        $tour = $this->show($tour['id']);
        //只取第一个
        $batchFields = ['id', 'batch_no', 'place_fullname', 'place_country', 'place_post_code', 'place_house_number', 'place_city', 'place_street', 'place_address'];
        $tour['last_place'] = $this->getBatchService()->getInfo(['tour_no' => $tour['tour_no']], $batchFields, false, ['sort_id' => 'desc', 'created_at' => 'desc']);
        $tour['batch_list'] = collect($tour['batch_list'])->toArray();
        foreach ($tour['batch_list'] as $x => $y) {
            $tour['batch_list'][$x]['tracking_order_list'] = array_values(collect($tour['tracking_order_list'])->where('batch_no', $y['batch_no'])->all());
            $authPackage = collect($tour['tracking_order_list'])->pluck('package_list')->where('batch_no', $y['batch_no'])->where('status', BaseConstService::BATCH_DELIVERING)->where('is_auth', BaseConstService::IS_AUTH_1)->first();
            $tour['batch_list'][$x]['is_auth'] = !empty($authPackage) ? BaseConstService::IS_AUTH_1 : BaseConstService::IS_AUTH_2;
            $tour['batch_list'][$x]['tour_id'] = $tour['id'];
            $tour['batch_list'][$x]['actual_total_amount'] = number_format_simple(round($tour['batch_list'][$x]['sticker_amount'] + $tour['batch_list'][$x]['delivery_amount'] + $tour['batch_list'][$x]['actual_replace_amount'] + $tour['batch_list'][$x]['actual_settlement_amount'], 2), 2);
            if ($tour['batch_list'][$x]['sticker_amount'] + $tour['batch_list'][$x]['sticker_amount'] + $tour['batch_list'][$x]['settlement_amount'] + $tour['batch_list'][$x]['delivery_amount'] == 0) {
                $tour['batch_list'][$x]['no_need_to_pay'] = BaseConstService::YES;
            } else {
                $tour['batch_list'][$x]['no_need_to_pay'] = BaseConstService::NO;
            }
        }
        $tourList[] = $tour;
        return $tourList;
    }

}
