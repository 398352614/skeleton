<?php
/**
 * 异常管理 服务
 * User: long
 * Date: 2020/1/3
 * Time: 16:26
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\StockExceptionResource;
use App\Models\BatchException;
use App\Models\StockException;
use App\Services\BaseConstService;
use Illuminate\Support\Carbon;

class StockExceptionService extends BaseService
{
    public $filterRules = [
        'status' => ['=', 'status'],
        'order_no,stock_exception_no,express_first_no,tracking_order_no' => ['like', 'keyword'],
        'created_at' => ['between', ['begin_date', 'end_date']],
        'order_no'=>['like','order_no'],
        'express_first_no'=>['like','express_first_no']
    ];

    public function __construct(StockException $batchException)
    {
        parent::__construct($batchException, StockExceptionResource::class);
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
        $this->query->orderByDesc('id');
        return parent::getPageList();
    }

    /**
     * 异常处理
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function deal($id, $params)
    {
        $stockException = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($stockException)) {
            throw new BusinessLogicException('数据不存在');
        }
        $stockException = $stockException->toArray();
        if (intval($stockException['status']) == BaseConstService::STOCK_EXCEPTION_STATUS_2) {
            throw new BusinessLogicException('异常已处理，请勿重复处理');
        }
        if (intval($stockException['status']) == BaseConstService::STOCK_EXCEPTION_STATUS_3) {
            throw new BusinessLogicException('异常已处理，请勿重复处理');
        }
        if (empty($params['status'])) {
            $params['status'] = BaseConstService::STOCK_EXCEPTION_STATUS_2;
        }
        if (!empty($params['deal_remark'])) {
            $remark = $params['deal_remark'];
        } elseif ($params['status'] == BaseConstService::STOCK_EXCEPTION_STATUS_2) {
            $remark = __('调整为取件成功');
        } else {
            $remark = __('审核失败');
        }
        $rowCount = parent::updateById($id, [
            'deal_remark' => $remark,
            'deal_time' => Carbon::now(),
            'operator' => auth()->user()->fullname,
            'status' => $params['status'],
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('处理失败，请重新操作');
        }
        //后续处理
        $trackingOrder = $this->getTrackingOrderService()->getInfo(['tracking_order_no' => $stockException['tracking_order_no']], ['*'], false);
        if (empty($trackingOrder)) {
            return;
        }
        $order = $this->getOrderService()->getInfo(['order_no' => $trackingOrder['order_no']], ['*'], false);
        if (empty($order)) {
            return;
        }
        //订单取消取派的情况，回取派中
        $statusList['order'] = BaseConstService::ORDER_STATUS_2;
        $statusList['tracking_order'] = BaseConstService::TRACKING_ORDER_STATUS_5;
        $statusList['package'] = BaseConstService::PACKAGE_STATUS_2;
        $statusList['batch'] = BaseConstService::BATCH_CHECKOUT;
        $this->statusChange($stockException, $statusList);
        //利用同步订单状态推送
        $this->getOrderService()->synchronizeStatusList($order['id'],true);
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
        //更新包裹
        $rowCount = $this->getPackageService()->update(['id' => $package['id']], ['status' => $statusList['package']]);
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
            //更新线路任务
            $tour = $this->getTourService()->getInfoLock(['tour_no' => $trackingOrder['tour_no']], ['*'], false);
            if (!empty($tour)) {
                $tourData = [
                    'actual_pickup_quantity' => intval($tour['actual_pickup_quantity']) + ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_1 ? 1 : 0),
                    'actual_pie_quantity' => intval($tour['actual_pie_quantity']) + ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_2 ? 1 : 0),
                ];
                $rowCount = $this->getTourService()->update(['tour_no' => $trackingOrder['tour_no']], $tourData);
                if ($rowCount === false) {
                    throw new BusinessLogicException('线路任务处理失败，请重新操作');
                }
                //重新统计线路任务金额
                $this->getTourService()->reCountAmountByNo($tour['tour_no']);
            }
        }
    }
}
