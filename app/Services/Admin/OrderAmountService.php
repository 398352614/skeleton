<?php
/**
 * Created by PhpStorm
 * User: Yomi
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\OrderImportInfoResource;
use App\Http\Resources\Api\Admin\OrderImportResource;
use App\Models\OrderAmount;
use App\Models\OrderImportLog;
use App\Services\BaseConstService;
use App\Traits\ExportTrait;
use Illuminate\Support\Facades\Storage;

class OrderAmountService extends BaseService
{
    public function __construct(OrderAmount $orderAmount)
    {
        parent::__construct($orderAmount);
    }

    public $filterRules = [
        'order_no' => ['like', 'order_no'],
    ];

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $order=$this->getOrderService()->getInfo(['order_no'=>$params['order_no']],['*'],false);
        if(empty($order)){
            throw new BusinessLogicException('订单不存在');
        }
        $params['actual_amount'] = 0.00;
        $params['in_total'] = $params['in_total'] ? $params['in_total'] : BaseConstService::YES;
        $params['status'] = BaseConstService::ORDER_AMOUNT_STATUS_2;
        $row=parent::create($params);
        if($row == false){
            throw new BusinessLogicException('新增失败');
        }
    }
}
