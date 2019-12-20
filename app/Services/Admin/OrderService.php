<?php
/**
 * 订单服务
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:39
 */

namespace App\Services\Admin;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\BaseConstService;
use App\Services\BaseService;

class OrderService extends BaseService
{

    public $filterRules = [
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'order_no,out_order_no' => ['like', 'keyword']
    ];

    public function __construct(Order $order)
    {
        $this->model = $order;
        $this->query = $this->model::query();
        $this->resource = OrderResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }


    public function initIndex()
    {
        $noTakeCount = parent::count(['status' => BaseConstService::ORDER_STATUS_1]);
        $assignCount = parent::count(['status' => BaseConstService::ORDER_STATUS_2]);
        $takingCount = parent::count(['status' => BaseConstService::ORDER_STATUS_3]);
        $signedCount = parent::count(['status' => BaseConstService::ORDER_STATUS_4]);
        return ['no_take' => $noTakeCount, 'assign' => $assignCount, 'taking' => $takingCount, 'singed' => $signedCount];
    }

    //新增
    public function store($params)
    {

    }


}