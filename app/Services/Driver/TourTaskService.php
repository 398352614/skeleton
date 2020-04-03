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
use App\Models\TourMaterial;
use App\Services\BaseConstService;
use App\Services\BaseService;
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
            $tour['last_receiver'] = $this->getBatchService()->getInfo(['tour_no' => $tour['tour_no']], $batchFields, false, ['sort_id' => 'asc', 'created_at' => 'asc']);
            //获取是否有特殊事项
            $order = $this->getOrderService()->getInfo(['tour_no' => $tour['tour_no'], 'special_remark' => ['<>', null]], ['special_remark'], false);
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
        //获取所有订单列表
        $orderList = $this->getOrderService()->getList(['tour_no' => $tour['tour_no']], ['*'], false)->toArray();
        //获取所有材料列表
        $materialList = $this->getTourMaterialList($tour);
        //获取所有包裹列表
        $packageList = $this->getPackageService()->getList(['tour_no' => $tour['tour_no']], ['*'], false)->toArray();
        $packageList = array_create_group_index($packageList, 'order_no');
        //将包裹列表和材料列表放在对应订单下
        $orderList = array_map(function ($order) use ($packageList) {
            $order['package_list'] = $packageList[$order['order_no']] ?? [];
            return $order;
        }, $orderList);
        //数据填充
        $tour['batch_list'] = $batchList;
        $tour['order_list'] = $orderList;
        $tour['material_list'] = $materialList;
        //$tour['package_list'] = $packageList;
        $tour['is_exist_special_remark'] = !empty(array_column($orderList, 'special_remark')) ? true : false;
        return $tour;
    }

    /**
     * 获取材料
     * @param $tour
     * @param $materialList
     * @return array
     */
    private function getTourMaterialList($tour)
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
        return $materialList;
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
