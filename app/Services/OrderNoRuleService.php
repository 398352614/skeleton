<?php
/**
 * 单号规则 服务
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/18
 * Time: 14:01
 */

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Models\OrderNoRule;

class OrderNoRuleService extends BaseService
{
    public function __construct(OrderNoRule $orderNoRule)
    {
        $this->model = $orderNoRule;
        $this->query = $this->model::query();
    }

    /**
     * 创建订单编号
     * @param $type
     * @return string
     * @throws BusinessLogicException
     */
    public function createOrderNo()
    {
        $info = parent::getInfoLock(['company_id' => auth()->user()->company_id, 'type' => BaseConstService::ORDER_NO_TYPE], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('单号规则不存在,请先添加单号规则');
        }
        $info = $info->toArray();
        $orderNo = BaseConstService::TMS . $info['prefix'] . sprintf("%0{$info['length']}s", $info['start_index']);
        $rowCount = parent::incrementById($info['id'], 'start_index', ['start_index' => 1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('单号生成失败,请重新操作');
        }
        return $orderNo;
    }

    //创建取派批次编号
    public function createBatchNo()
    {

    }


    //创建取件线路编号
    public function createTourNo()
    {

    }

}