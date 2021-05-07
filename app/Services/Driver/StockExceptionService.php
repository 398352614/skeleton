<?php
/**
 * 异常管理 服务
 * User: long
 * Date: 2020/1/3
 * Time: 16:26
 */

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\StockExceptionResource;
use App\Models\StockException;
use App\Services\BaseConstService;
use App\Traits\CompanyTrait;
use Illuminate\Support\Carbon;

class StockExceptionService extends BaseService
{
    public $filterRules = [
        'status' => ['=', 'status'],
        'order_no,batch_exception_no,batch_no' => ['like', 'keyword'],
        'created_at' => ['between', ['begin_date', 'end_date']]
    ];

    public function __construct(StockException $stockException)
    {
        parent::__construct($stockException, StockExceptionResource::class);
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
        return $info->toArray();
    }

    /**
     * 列表查询
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPageList()
    {
        $stockList = $this->getStockService()->getList(['operator_id' => auth()->user()->id], ['*'], false);
        if (!empty($stockList)) {
            $this->query->whereNotIn('express_first_no', $stockList->pluck('express_first_no')->toArray());
        }
        $this->query->orderByDesc('id')->where('driver_id', auth()->user()->id);
        return parent::getPageList();
    }

    /**
     * 异常上报
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $package = $this->getTrackingOrderPackageService()->getInfo(['express_first_no' => $params['express_first_no']], ['*'], false, ['id' => 'desc']);
        if (empty($package)) {
            throw new BusinessLogicException('数据不存在');
        }
        $trackingOrder = $this->getTrackingOrderService()->getInfo(['tracking_order_no' => $package['tracking_order_no']], ['*'], false);
        if (empty($trackingOrder)) {
            throw new BusinessLogicException('数据不存在');
        }
        $order = $this->getOrderService()->getInfo(['order_no' => $trackingOrder['order_no']], ['*'], false);
        if (empty($order)) {
            throw new BusinessLogicException('数据不存在');
        }
        if (!(in_array($order['status'], [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2, BaseConstService::ORDER_STATUS_4])
            && $order['type'] == BaseConstService::ORDER_TYPE_3
            && !empty($trackingOrder)
            && $trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_1
            && $trackingOrder['status'] == BaseConstService::TRACKING_ORDER_STATUS_6)
        ) {
            throw new BusinessLogicException('该包裹当前状态不允许上报异常');
        }
        //生成入库异常
        $stockExceptionNo = $this->getOrderNoRuleService()->createStockExceptionNo();
        $data = [
            'stock_exception_no' => $stockExceptionNo,
            'tracking_order_no' => $trackingOrder['tracking_order_no'],
            'order_no' => $trackingOrder['order_no'],
            'express_first_no' => $package['express_first_no'],
            'driver_id' => $trackingOrder['driver_id'],
            'driver_name' => $trackingOrder['driver_name'],
            'remark' => __('取件失败，无法入库'),
            'status' => BaseConstService::STOCK_EXCEPTION_STATUS_1,
        ];
        $rowCount = $this->getStockExceptionService()->create($data);
        $stockException = $rowCount->getAttributes();
        if ($rowCount === false) {
            throw new BusinessLogicException('上报异常失败，请重新操作');
        }
        if (empty(CompanyTrait::getCompany()['stock_exception_verify']) || CompanyTrait::getCompany()['stock_exception_verify'] == BaseConstService::STOCK_EXCEPTION_VERIFY_2) {
            return $this->autoDeal($stockException);
        }

    }

    /**
     * 自动处理
     * @param $stockException
     * @return array
     * @throws BusinessLogicException
     */
    public function autoDeal($stockException)
    {
        if (empty($stockException)) {
            throw new BusinessLogicException('数据不存在');
        }
        if (intval($stockException['status']) !== BaseConstService::STOCK_EXCEPTION_STATUS_1) {
            throw new BusinessLogicException('异常已处理或被拒绝');
        }
        $rowCount = parent::updateById($stockException['id'], [
            'deal_remark' => __('调整为取件成功'),
            'deal_time' => Carbon::now(),
            'operator' => __('系统'),
            'status' => BaseConstService::STOCK_EXCEPTION_STATUS_2,
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('处理失败，请重新操作');
        }
        //订单取消取派的情况，回取派中
        $statusList['order'] = BaseConstService::ORDER_STATUS_2;
        $statusList['tracking_order'] = BaseConstService::TRACKING_ORDER_STATUS_5;
        $statusList['package'] = BaseConstService::PACKAGE_STATUS_2;
        $statusList['batch'] = BaseConstService::BATCH_CHECKOUT;
        $this->statusChange($stockException, $statusList);
        //利用同步订单状态推送
        $order = $this->getOrderService()->getInfo(['order_no' => $stockException['order_no']], ['*'], false);
        if (empty($order)) {
            throw new BusinessLogicException('订单不存在');
        }
        $this->getOrderService()->synchronizeStatusList($order['id'],true);
        return $this->getStockService()->allocate($stockException['express_first_no']);
    }

    /**
     * 更换状态
     * @param $stockException
     * @param $statusList
     * @throws BusinessLogicException
     */
    public function statusChange($stockException, $statusList)
    {
        //更新包裹
        $package = $this->getPackageService()->getInfo(['express_first_no' => $stockException['express_first_no']], ['*'], false, ['id' => 'desc']);
        if (empty($package)) {
            return;
        }
        $rowCount = $this->getPackageService()->update(['express_first_no' => $package['express_first_no']], ['status' => $statusList['package']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('包裹处理失败，请重新操作');
        }
        //更新运单
        $rowCount = $this->getTrackingOrderService()->update(['tracking_order_no' => $stockException['tracking_order_no']], ['status' => $statusList['tracking_order']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('运单处理失败，请重新操作');
        }
        $trackingOrder = $this->getTrackingOrderService()->getInfoLock(['tracking_order_no' => $stockException['tracking_order_no']], ['*'], false);
        if (!empty($trackingOrder)) {
            //更新运单包裹
            $rowCount = $this->getTrackingOrderPackageService()->update(['tracking_order_no' => $stockException['tracking_order_no']], ['status' => $statusList['tracking_order'], 'actual_quantity' => 1]);
            if ($rowCount === false) {
                throw new BusinessLogicException('运单包裹处理失败，请重新操作');
            }
            //更新订单
            $rowCount = $this->getOrderService()->update(['order_no' => $trackingOrder['order_no']], ['status' => $statusList['order']]);
            if ($rowCount === false) {
                throw new BusinessLogicException('订单处理失败，请重新操作');
            }
            //更新站点
            $batch = $this->getBatchService()->getInfoLock(['batch_no' => $trackingOrder['batch_no']], ['*'], false);
            if (!empty($batch)) {
                $batchData = [
                    'status' => $statusList['batch'],
                    'actual_pickup_quantity' => $batch['actual_pickup_quantity'] + ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_1 ? 1 : 0),
                    'actual_pie_quantity' => $batch['actual_pie_quantity'] + ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_2 ? 1 : 0),
                    'actual_pickup_package_quantity' => $batch['actual_pickup_package_quantity'] + ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_1 ? 1 : 0),
                    'actual_pie_package_quantity' => $batch['actual_pie_package_quantity'] + ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_2 ? 1 : 0),
                ];
                $rowCount = $this->getBatchService()->update(['batch_no' => $trackingOrder['batch_no']], $batchData);
                if ($rowCount === false) {
                    throw new BusinessLogicException('站点处理失败，请重新操作');
                }
                //重新统计站点金额
                $this->getBatchService()->reCountAmountByNo($batch['batch_no']);
            }
            //更新取件线路
            $tour = $this->getTourService()->getInfoLock(['tour_no' => $trackingOrder['tour_no']], ['*'], false);
            if (!empty($tour)) {
                $tourData = [
                    'actual_pickup_quantity' => intval($tour['actual_pickup_quantity']) + ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_1 ? 1 : 0),
                    'actual_pie_quantity' => intval($tour['actual_pie_quantity']) + ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_2 ? 1 : 0),
                ];
                $rowCount = $this->getTourService()->update(['tour_no' => $trackingOrder['tour_no']], $tourData);
                if ($rowCount === false) {
                    throw new BusinessLogicException('取件线路处理失败，请重新操作');
                }
                //重新统计取件线路金额
                $this->getTourService()->reCountAmountByNo($tour['tour_no']);
            }
        }
    }
}
