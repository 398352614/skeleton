<?php
/**
 * 货主线路范围 服务
 * User: long
 * Date: 2020/8/13
 * Time: 13:46
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\MerchantGroupLine;
use App\Services\BaseConstService;

class MerchantGroupLineService extends BaseService
{
    public function __construct(MerchantGroupLine $model)
    {
        parent::__construct($model, null);
    }

    /**
     * 检查最小订单量
     * @param $line
     * @param $merchantGroupLineList
     * @throws BusinessLogicException
     */
    public function checkCount($line, $merchantGroupLineList)
    {
        foreach ($merchantGroupLineList as $k => $v) {
            if (empty($this->getMerchantGroupService()->getInfo(['id' => $v['merchant_group_id']], '*', false))) {
                throw new BusinessLogicException('货主组不存在');
            }
        }
        if (collect($merchantGroupLineList)->sum('pickup_min_count') > $line['pickup_max_count']) {
            throw new BusinessLogicException('各货主组取件最小订单量之和不得超过线路取件最大订单量');
        }
        if (collect($merchantGroupLineList)->sum('pie_min_count') > $line['pie_max_count']) {
            throw new BusinessLogicException('各货主组派件最小订单量之和不得超过线路派件最大订单量');
        }
    }

    /**
     * 批量新增
     * @param $lineId
     * @param $data
     * @throws BusinessLogicException
     */
    public function storeAll($lineId, $data)
    {
        foreach ($data as &$v) {
            $v['line_id'] = $lineId;
        }
        $rowCount = parent::insertAll($data);
        if ($rowCount === false) {
            throw new BusinessLogicException('新增失败');
        }
    }

    /**
     * 统计运单数量
     *
     * @param $info
     * @param $line
     * @param int $type 1-取件2-派件3-取件和派件
     * @return array
     */
    public function sumOrderCount($info, $line, $type = BaseConstService::ORDER_TYPE_1)
    {
        if ($type == BaseConstService::ORDER_TYPE_1) {
            $arrCount['pickup_count'] = parent::sum('expect_pickup_quantity', ['line_id' => $line['id'], 'merchant_id' => $info['merchant_id'], 'execution_date' => $info['execution_date']]);
        } elseif ($type == BaseConstService::ORDER_TYPE_2) {
            $arrCount['pie_count'] = parent::sum('expect_pie_quantity', ['line_id' => $line['id'], 'merchant_id' => $info['merchant_id'], 'execution_date' => $info['execution_date']]);
        } else {
            $arrCount['pickup_count'] = parent::sum('expect_pickup_quantity', ['line_id' => $line['id'], 'merchant_id' => $info['merchant_id'], 'execution_date' => $info['execution_date']]);
            $arrCount['pie_count'] = parent::sum('expect_pie_quantity', ['line_id' => $line['id'], 'merchant_id' => $info['merchant_id'], 'execution_date' => $info['execution_date']]);
        }
        return $arrCount;
    }
}
