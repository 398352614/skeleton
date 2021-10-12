<?php
/**
 * 费用服务
 * User: long
 * Date: 2020/6/22
 * Time: 13:46
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\FeeResource;
use App\Models\Fee;
use App\Services\BaseConstService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Arr;

class FeeService extends BaseService
{
    public $filterRules = [
        'status' => ['=', 'status'],
        'name' => ['like', 'name'],
        'payer_type' => ['=', 'payer_type'],
        'level' => ['=', 'level']
    ];

    public function __construct(Fee $model)
    {
        parent::__construct($model, FeeResource::class);
    }

    /**
     * 初始化
     * @return array
     */
    public function init()
    {
        $data = [];
        $data['level_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$feeLevelList);
        return $data;
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $params['is_valuable'] = BaseConstService::YES;
        $params['level'] = BaseConstService::FEE_LEVEL_2;
        if ($params['payer_type'] == BaseConstService::FEE_PAYER_TYPE_4) {
            $params['pay_type'] = BaseConstService::FEE_PAY_TYPE_1;
            $params['payee_type'] = BaseConstService::FEE_PAYEE_TYPE_1;
            $params['pay_timing'] = BaseConstService::BILL_PAY_TIMING_1;
        } elseif (in_array($params['payer_type'], [BaseConstService::FEE_PAYER_TYPE_5, BaseConstService::FEE_PAYER_TYPE_6])) {
            $params['pay_type'] = BaseConstService::FEE_PAY_TYPE_2;
            $params['payee_type'] = BaseConstService::FEE_PAYEE_TYPE_7;
            if ($params['payer_type'] == BaseConstService::FEE_PAYER_TYPE_5) {
                $params['pay_timing'] = BaseConstService::BILL_PAY_TIMING_2;
            } else {
                $params['pay_timing'] = BaseConstService::BILL_PAY_TIMING_3;
            }
        }
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }

    /**
     * 修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        unset($data['company_id']);
        $fee = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($fee)) {
            throw new BusinessLogicException('数据不存在');
        }
        if ($data['payer_type'] == BaseConstService::FEE_PAYER_TYPE_4) {
            $data['pay_type'] = BaseConstService::FEE_PAY_TYPE_1;
            $data['payee_type'] = BaseConstService::FEE_PAYEE_TYPE_1;
        } elseif (in_array($data['payer_type'], [BaseConstService::FEE_PAYER_TYPE_5, BaseConstService::FEE_PAYER_TYPE_6])) {
            $data['pay_type'] = BaseConstService::FEE_PAY_TYPE_2;
            $data['payee_type'] = BaseConstService::FEE_PAYEE_TYPE_7;
            if ($data['payer_type'] == BaseConstService::FEE_PAYER_TYPE_5) {
                $data['pay_timing'] = BaseConstService::BILL_PAY_TIMING_2;
            } else {
                $data['pay_timing'] = BaseConstService::BILL_PAY_TIMING_3;
            }
        }
        if (intval($fee['level']) == 1) {
            $data = Arr::only($data, ['name', 'amount']);
        }
        unset($data['company_id'], $data['level']);
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }

    /**
     * 删除
     * @param $id
     * @return string
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $fee = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($fee)) {
            return 'true';
        }
        if (intval($fee['level']) == 1) {
            throw new BusinessLogicException('系统级费用不能删除');
        }
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        return 'true';
    }

    public function getPageList()
    {
        if (!empty($this->formData['order_type'])) {
            if ($this->formData['order_type'] == BaseConstService::ORDER_TYPE_1) {
                $this->query->where('payer_type', '<>', BaseConstService::FEE_PAYER_TYPE_6);
            } elseif ($this->formData['order_type'] == BaseConstService::ORDER_TYPE_2) {
                $this->query->where('payer_type', '<>', BaseConstService::FEE_PAYER_TYPE_5);
            }
        }
        $this->query->orderBy('level')->orderBy('created_at');
        $data = parent::getPageList();
        return $data;
    }

}
