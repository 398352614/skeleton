<?php

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\TrackingOrderTrailResource;
use App\Models\Order;
use App\Models\Package;
use App\Models\PackageTrail;
use App\Models\TrackingOrder;
use App\Models\TrackingOrderPackage;
use App\Models\TrackingOrderTrail;
use App\Jobs\AddData;
use App\Models\TrackingPackage;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Facades\Log;

class PackageTrailService extends \App\Services\Admin\BaseService
{
    /**
     * 运单字段
     * @var array
     */
    public static $trackingOrderPackageFields = ['company_id', 'merchant_id', 'order_no', 'tracking_order_no', 'express_first_no'];

    /**
     * 转运单字段
     * @var array
     */
    public static $trackingPackageFields = ['company_id', 'merchant_id', 'order_no', 'warehouse_id', 'warehouse_name', 'next_warehouse_id', 'next_warehouse_name', 'express_first_no'];

    /**
     * 查询条件
     * @var array
     */
    public $filterRules = [
        'tracking_order_no' => ['=', 'tracking_order_no'],
        'order_no' => ['=', 'order_no'],
    ];

    public function __construct(PackageTrail $trackingOrderTrail)
    {
        parent::__construct($trackingOrderTrail, TrackingOrderTrailResource::class, TrackingOrderTrailResource::class);
    }

    public static function storeByTour($tour, int $action)
    {
        $trackingOrderPackageList = TrackingOrderPackage::query()->select(self::$trackingOrderPackageFields)->where('tour_no', $tour['tour_no'])->get()->toArray();
        !empty($trackingOrderPackageList) && self::storeByTrackingOrderList($trackingOrderPackageList, $action, $tour);
    }

    public static function storeByBatch($batch, int $action)
    {
        $trackingOrderPackageList = TrackingOrderPackage::query()->select(self::$trackingOrderPackageFields)->where('batch_no', $batch['batch_no'])->get()->toArray();
        !empty($trackingOrderPackageList) && self::storeByTrackingOrderList($trackingOrderPackageList, $action, $batch);
    }

    public static function storeByBag($bag, int $action)
    {
        $trackingPackageList = TrackingPackage::query()->select(self::$trackingPackageFields)->where('bag_no', $bag['bag_no'])->get()->toArray();
        !empty($trackingPackageList) && self::storeByTrackingPackageList($trackingPackageList, $action, $bag);
    }

    public static function storeByShift($shift, int $action)
    {
        $trackingPackageList = TrackingPackage::query()->select(self::$trackingPackageFields)->where('shift_no', $shift['shift_no'])->get()->toArray();
        !empty($trackingPackageList) && self::storeByTrackingPackageList($trackingPackageList, $action, $shift);
    }

    public static function storeByTrackingOrder($trackingOrderList, int $action, $params = null)
    {
        $trackingOrderPackageList = TrackingOrderPackage::query()->select(self::$trackingOrderPackageFields)->whereIn('tracking_order_no', $trackingOrderList)->get()->toArray();
        !empty($trackingOrderPackageList) && self::storeByTrackingOrderList($trackingOrderPackageList, $action, $params);
    }

    /**
     * 按运单批量新增
     * @param array $packageList
     * @param int $action
     * @param null $params
     */
    public static function storeByTrackingOrderList(array $packageList, int $action, $params = null)
    {
        $data = [];
        foreach ($packageList as $k=>$v)
        {
            if(!is_array($v) && !is_object($v)){
                $packageList = [$packageList];
            }
            break;
        }
        foreach ($packageList as $key => $package) {
            $package = collect($package)->toArray();
            $data[] = self::trackingOrderStatusChangeCreateTrail($package, $action, $params ?? $package, true);
        }
        dispatch(new AddData('package-trail', $data));
    }

    public static function trackingOrderStatusChangeCreateTrail(array $package, int $action, $params = [], $list = false)
    {
        if (!empty($params['cancel_type'])) {
            $cancel = ConstTranslateTrait::batchCancelTypeList($params['cancel_type']);
        } else {
            $cancel = '其他原因';
        }
        //根据不同的类型生成不同的content
        switch ($action) {
            case BaseConstService::PACKAGE_TRAIL_CREATED:
                $content = sprintf("下单成功");
                break;
            case BaseConstService::PACKAGE_TRAIL_PICKUP:
                $content = sprintf("取件中，[%s]，电话：[%s]", $params['driver_name'], $params['driver_phone']);
                break;
            case BaseConstService::PACKAGE_TRAIL_PICKUP_DONE:
                $content = sprintf("[%s]已取件成功", $params['warehouse_fullname']);
                break;
            case BaseConstService::PACKAGE_TRAIL_PIE:
                $content = sprintf("您的包裹交给[%s],正在派送途中联系电话：[%s]", $params['driver_name'], $params['driver_phone']);
                break;
            case BaseConstService::PACKAGE_TRAIL_PIE_DONE:
                $content = sprintf("您的包裹已签收，如有疑问请电联快递员[%s]，电话：[%s]", $params['driver_name'], $params['driver_phone']);
                break;
            case BaseConstService::PACKAGE_TRAIL_PICKUP_CANCEL:
                $content = sprintf("您的包裹取件被取消，原因：[%s]。如有疑问请电联快递员[%s]，电话：[%s]", $cancel, $params['driver_name'], $params['driver_phone']);
                break;
            case BaseConstService::PACKAGE_TRAIL_PIE_CANCEL:
                $content = sprintf("您的包裹派件被取消，原因：[%s]。如有疑问请电联快递员[%s]，电话：[%s]", $cancel, $params['driver_name'], $params['driver_phone']);
                break;
            case BaseConstService::PACKAGE_TRAIL_DELETED:
                $content = '您的包裹已取消';
                break;
            case BaseConstService::PACKAGE_TRAIL_ALLOCATE:
                $content = sprintf("您的包裹在[%s]进行入库处理，操作员：[%s]", $params['warehouse_name'], $params['operator']);
                break;

            default:
                $content = '未定义的状态';
                break;
        }
        $now = now();
        $data = [
            'company_id' => $package['company_id'] ?? auth()->user()->company_id,
            'express_first_no' => $package['express_first_no'],
            'order_no' => $package['order_no'],
            'merchant_id' => $package['merchant_id'],
            'type' => $action,
            'content' => $content,
            'created_at' => $now,
            'updated_at' => $now
        ];
        if ($list == false) {
            dispatch(new AddData('package-trail', $data));
        } else {
            return $data;
        }
    }


    /**
     * 按运单批量新增
     * @param array $packageList
     * @param int $action
     * @param null $params
     */
    public static function storeByTrackingPackageList($packageList, $action, $params = null)
    {
        $data = [];
        foreach ($packageList as $k=>$v)
        {
            if(!is_array($v) && !is_object($v)){
                $packageList = [$packageList];
                dd($packageList);
            }
            break;
        }
        foreach ($packageList as $key => $package) {
            dd($packageList,$package);

            $package = collect($package)->toArray();
            $data[] = self::trackingPackageStatusChangeCreateTrail($package, $action, $params ?? $package, true);
        }
        dispatch(new AddData('package-trail', $data));
    }

    /**
     * 按转运单批量新增
     * @param array $trackingPackage
     * @param int $action
     * @param array $params
     * @param bool $list
     * @return array
     */
    public static function trackingPackageStatusChangeCreateTrail(array $trackingPackage, int $action, $params = [], $list = false)
    {
        //根据不同的类型生成不同的content
        switch ($action) {
            case BaseConstService::PACKAGE_TRAIL_PACK:
                $content = sprintf("您的包裹在[%s]进行装袋处理，操作员：[%s]", $trackingPackage['warehouse_name'], $trackingPackage['pack_operator']);
                break;
            case BaseConstService::PACKAGE_TRAIL_LOAD:
                $content = sprintf("您的包裹在[%s]进行装车处理，操作员：[%s]", $trackingPackage['warehouse_name'], $params['load_operator']);
                break;
            case BaseConstService::PACKAGE_TRAIL_OUT:
                $content = sprintf("您的包裹在[%s]已装车，正在发往[%s]，操作员：[%s]", $params['warehouse_name'], $params['next_warehouse_name'], $params['driver_name']);
                break;
            case BaseConstService::PACKAGE_TRAIL_IN:
                $content = sprintf("您的包裹到达[%s]，操作员：[%s]", auth()->user()->warehouse_name, auth()->user()->fullname);
                break;
            case BaseConstService::PACKAGE_TRAIL_UNLOAD:
                $content = sprintf("您的包裹在[%s]进行卸车处理，操作员：[%s]", auth()->user()->warehouse_name, auth()->user()->fullname);
                break;
            case BaseConstService::PACKAGE_TRAIL_UNPACK:
                $content = sprintf("您的包裹在[%s]进行拆袋处理，操作员：[%s]", auth()->user()->warehouse_name, auth()->user()->fullname);
                break;
            case BaseConstService::PACKAGE_TRAIL_ALLOCATE:
                $content = sprintf("您的包裹在[%s]进行入库处理，操作员：[%s]", $params['warehouse_name'], $params['operator']);
                break;

            default:
                $content = '未定义的状态';
                break;
        }
        return self::createTrail($trackingPackage, $action, $content, $list);
    }

    public static function createTrail($package, $action, $content, $list)
    {
        $now = now();
        $data = [
            'company_id' => $package['company_id'],
            'express_first_no' => $package['express_first_no'],
            'order_no' => $package['order_no'],
            'merchant_id' => $package['merchant_id'],
            'type' => $action,
            'content' => $content,
            'created_at' => $now,
            'updated_at' => $now
        ];
        if ($list == false) {
            dispatch(new AddData('tracking-order-trail', $data));
        } else {
            return $data;
        }
    }


    /**
     * 手动新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $params['operator'] = auth()->user()->fullname;
        $row = parent::create($params);
        if ($row == false) {
            throw new BusinessLogicException('新增失败');
        }
    }

    /**
     * 物流信息追踪
     * @param $expressFirstNo
     * @return array
     * @throws BusinessLogicException
     */
    public function index($expressFirstNo)
    {
        $package = $this->getPackageService()->getInfo(['express_first_no' => $expressFirstNo], ['*'], false);
        if (empty($package)) {
            throw new BusinessLogicException('数据不存在');
        }
        $order = $this->getOrderService()->getInfo(['order_no' => $package['order_no']], ['*'], false);
        if (empty($order)) {
            throw new BusinessLogicException('数据不存在');
        }
        $package['place_city'] = $order['place_city'];
        $package['second_place_city'] = $order['second_place_city'];
        $package['package_trail_list'] = parent::getList(['express_first_no' => $expressFirstNo], ['*'], true, [], ['id' => 'desc']);
        return $package;
    }

    public function create($data)
    {
        return parent::create($data);
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败，请重新操作');
        }
    }
}
