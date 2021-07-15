<?php
/**
 * Created by PhpStorm
 * User: Yomi
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Models\OrderAmount;
use App\Services\BaseConstService;


class OrderAmountService extends BaseService
{
    public $filterRules = [
        'order_no' => ['like', 'order_no'],
    ];

    public function __construct(OrderAmount $orderAmount)
    {
        parent::__construct($orderAmount);
    }

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
        $order = $this->getOrderService()->getInfo(['order_no' => $params['order_no']], ['*'], false);
        if (empty($order)) {
            throw new BusinessLogicException('订单不存在');
        }
        $params['actual_amount'] = 0.00;
        $params['in_total'] = $params['in_total'] ? $params['in_total'] : BaseConstService::YES;
        $params['status'] = BaseConstService::ORDER_AMOUNT_STATUS_2;
        $row = parent::create($params);
        if ($row == false) {
            throw new BusinessLogicException('新增失败');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $data = parent::getInfo(['id' => $id], ['*'], false);
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败，请重新操作');
        }
        //重算总费用
        if (!empty($data['order_no'])) {
            $this->countAmount($data['order_no']);
        }
    }

    /**
     * 计算订单总费用
     * @param $orderNo
     * @throws BusinessLogicException
     */
    public function countAmount($orderNo)
    {
        $actualTotalAmount = parent::sum('actual_amount', ['order_no' => $orderNo]);
        $expectTotalAmount = parent::sum('expect_amount', ['order_no' => $orderNo]);
        $rowCount = $this->getOrderService()->update(['order_no' => $orderNo], ['actual_total_amount' => $actualTotalAmount, 'expect_total_amount' => $expectTotalAmount]);
        if ($rowCount === false) {
            throw new BusinessLogicException('金额统计失败');
        }
    }

    /**
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $row = parent::updateById($id, $data);
        if ($row == false) {
            throw new BusinessLogicException('修改失败');
        }
        //重算总费用
        if (!empty($data['order_no'])) {
            $this->countAmount($data['order_no']);
        }
    }
}
