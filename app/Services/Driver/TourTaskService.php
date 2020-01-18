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
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\BaseService;

class TourTaskService extends BaseService
{
    public $filterRules = [
        'execution_date' => ['=', 'execution_date'],
        'status' => ['=', 'status']
    ];

    public $orderBy = [
        'execution_date' => 'desc'
    ];

    public function __construct(Tour $tour)
    {
        $this->request = request();
        $this->model = $tour;
        $this->query = $this->model::query();
        $this->resource = TourTaskResource::class;
        $this->formData = $this->request->all();
        $this->setFilterRules();
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
     * 获取任务列表
     * @return mixed
     */
    public function getPageList()
    {
        //若状态为1000,则表示当前任务
        if (!empty($this->filters['status'][1]) && (intval($this->filters['status'][1]) === 1000)) {
            $this->filters['status'] = ['<>', BaseConstService::TOUR_STATUS_5];
        }
        $list = parent::getPageList();
        if (empty($list)) return $list;
        $batchFields = ['id', 'batch_no', 'receiver', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street', 'receiver_address'];
        foreach ($list as &$tour) {
            //获取站点数量
            $tour['batch_count'] = $this->getBatchService()->count(['tour_no' => $tour['tour_no']]);
            //获取最后一个站点的收件人信息
            $tour['last_receiver'] = $this->getBatchService()->getInfo(['tour_no' => $tour['tour_no']], $batchFields, false, ['sort_id' => 'desc', 'created_at' => 'desc']);
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
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no']], ['id', 'batch_no', 'status', 'receiver', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street', 'receiver_address', 'receiver_lon', 'receiver_lat'], false, [], ['sort_id' => 'asc', 'created_at' => 'asc']);
        //获取所有订单列表
        $orderList = $this->getOrderService()->getList(['tour_no' => $tour['tour_no']], ['id', 'type', 'tour_no', 'batch_no', 'order_no', 'out_order_no', 'status'], false)->toArray();
        //订单列表根据站点编号 分组
        $orderList = array_create_group_index($orderList, 'batch_no');
        //数据组合填充
        foreach ($batchList as &$batch) {
            $batch['order_list'] = $orderList[$batch['batch_no']];
        }
        $tour['batch_list'] = $batchList;
        return $tour;
    }

    /**
     * 获取订单特殊事项列表
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
        $orderList = $this->getOrderService()->getList(['tour_no' => $tour['tour_no'], 'special_remark' => ['<>', null]], ['id', 'order_no', 'special_remark'], false);
        return $orderList;
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
        $orderList = $this->getOrderService()->getList(['batch_no' => $batch['batch_no'], 'special_remark' => ['<>', null]], ['id', 'order_no', 'special_remark'], false);
        return $orderList;
    }

    /**
     * 获取特殊事项
     * @param $orderId
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getSpecialRemark($orderId)
    {
        $order = $this->getOrderService()->getInfo(['id' => $orderId], ['*'], false);
        if (empty($order)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $order->toArray()['special_remark'];
    }


}