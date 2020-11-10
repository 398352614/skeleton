<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/30
 * Time: 10:42
 */

namespace App\Services\Driver;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\TourTaskResource;
use App\Models\AdditionalPackage;
use App\Models\Tour;
use App\Models\TourMaterial;
use App\Services\Admin\TourDelayService;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class TourTaskService
 * @package App\Services\Driver
 * @property TourMaterial $tourMaterialModel
 */
class TourTaskService extends BaseService
{
    public $filterRules = [
        'execution_date' => ['=', 'execution_date'],
        'status' => ['=', 'status']
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
     * 站点 服务
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
     * 材料 服务
     * @return MaterialService
     */
    private function getMaterialService()
    {
        return self::getInstance(MaterialService::class);
    }

    /**
     * 延迟 服务
     * @return TourDelayService
     */
    private function getTourDelayService()
    {
        return self::getInstance(TourDelayService::class);
    }

    /**
     *  运单 服务
     * @return TrackingOrderService
     */
    private function getTrackingOrderService()
    {
        return self::getInstance(TrackingOrderService::class);
    }

    /**
     * 获取任务列表
     * @return mixed
     */
    public function getPageList()
    {
        sleep(10);
        //若状态为1000,则表示当前任务
        if (!empty($this->filters['status'][1]) && (intval($this->filters['status'][1]) === 1000)) {
            $this->filters['status'] = ['<>', BaseConstService::TOUR_STATUS_5];
        }
        $list = parent::getPageList();
        if (empty($list)) return $list;
        $batchFields = ['id', 'batch_no', 'receiver_fullname', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street', 'receiver_address'];
        foreach ($list as &$tour) {
            //获取站点数量
            $tour['batch_count'] = $this->getBatchService()->count(['tour_no' => $tour['tour_no']]);
            //获取最后一个站点的收件人信息
            $tour['last_receiver'] = $this->getBatchService()->getInfo(['tour_no' => $tour['tour_no']], $batchFields, false, ['sort_id' => 'desc', 'created_at' => 'desc']);
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
        $orderNoList = array_column($trackingOrderList, 'order_no');
        //获取所有材料列表
        $materialList = $this->getTourMaterialList($tour);
        //获取所有包裹列表
        $packageList = $this->getPackageService()->getList(['order_no' => ['in', $orderNoList]], ['*'], false)->toArray();
        for ($i = 0, $j = count($packageList); $i < $j; $i++) {
            $packageList[$i]['feature_logo'] = $packageList[$i]['feature_logo'] ?? '';
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
            data_set($trackingOrder['package_list'], '*.status', $trackingOrder['status']);
            data_set($trackingOrder['package_list'], '*.status_name', $trackingOrder['status_name']);
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
        $tour['actual_total_amount'] = number_format(round($tour['sticker_amount'] + $tour['delivery_amount'] + $tour['actual_replace_amount'] + $tour['actual_settlement_amount'], 2), 2);
        //$tour['package_list'] = $packageList;
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
            $materialList = $this->getMaterialService()->getList(['tour_no' => $tour['tour_no']], [
                'name',
                'code',
                DB::raw('SUM(expect_quantity) as expect_quantity'),
                DB::raw('0 as actual_quantity'),
            ], false, ['code'])->toArray();
        }
        $materialList = Arr::where($materialList, function ($material) {
            return !empty($material['code']) && !empty($material['expect_quantity']);
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
        $trackingOrderList = $this->getTrackingOrderService()->getList(['tour_no' => $tour['tour_no'], 'special_remark' => ['<>', null]], ['id', 'order_no', 'tracking_order_no', 'special_remark'], false);
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
        $trackingOrderList = $this->getTrackingOrderService()->getList(['batch_no' => $batch['batch_no'], 'special_remark' => ['<>', null]], ['id', 'order_no', 'tracking_order_no', 'special_remark'], false);
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
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getTrackingOrderList($id)
    {
        $tour = parent::getInfo(['id' => $id], ['tour_no'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在');
        }
        //获取所有运单列表
        $trackingOrderList = $this->getTrackingOrderService()->getList(['tour_no' => $tour->tour_no], ['order_no', 'tracking_order_no'], false);
        //获取所有包裹列表
        $packageList = $this->getPackageService()->getList(['order_no' => ['in', array_column($trackingOrderList, 'order_no')]], ['order_no', 'express_first_no', 'feature_logo']);
        $packageList = array_create_group_index($packageList, 'order_no');
        //将包裹列表和材料列表放在对应订单下
        $trackingOrderList = array_map(function ($trackingOrder) use ($packageList) {
            $trackingOrder['package_list'] = $packageList[$trackingOrder['order_no']] ?? [];
            return $trackingOrder;
        }, $trackingOrderList);
        return $trackingOrderList ?? [];
    }

}
