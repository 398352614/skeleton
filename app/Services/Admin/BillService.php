<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;

use App\Models\Bill;
use App\Models\Ledger;
use App\Services\BaseConstService;
use App\Traits\CompanyTrait;
use App\Traits\UserTrait;
use Illuminate\Support\Arr;


class BillService extends BaseService
{

    /**
     * @var \string[][]
     */
    public $filterRules = [
        'create_date' => ['between', ['begin_date', 'end_date']],
        'user_type' => ['=', 'user_type'],
        'verify_status' => ['=', 'verify_status'],
        'mode' => ['=', 'mode']
    ];


    /**
     * AddressService constructor.
     * @param Bill $model
     */
    public function __construct(Bill $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $params['bill_no'] = $this->getOrderNoRuleService()->createBillNo();
        $bill = parent::create($params);
        if ($bill === false) {
            throw new BusinessLogicException('订单新增失败');
        }
    }

    public function getPageList()
    {
//        if ($this->formData['user_type'] == BaseConstService::USER_MERCHANT) {
        if (!empty($this->formData['code'])) {
            $where = ['code' => $this->formData['code']];
        }
        if (!empty($this->formData['merchant_group_id'])) {
            $where = ['merchant_group_id' => $this->formData['merchant_group_id']];
        }
        if (!empty($where)) {
            $merchantList = $this->getMerchantService()->getList($where, ['*'], false);
            $this->query->whereIn('payer_id', $merchantList->pluck('id')->toArray());
            $this->query->orderByDesc('id');
            $data = parent::getPageList();
        } else {
            $data = parent::getPageList();
            $merchantList = $this->getMerchantService()->getList(['id' => ['in', $data->pluck('user_id')->toArray()]], ['*'], false);
        }
        $merchantGroupList = $this->getMerchantGroupService()->getList(['id' => ['in', $merchantList->pluck('merchant_group_id')->toArray()]], ['*'], false);
        foreach ($data as $k => $v) {
            $merchant = $merchantList->where('id', $v['user_id'])->first();
            if (!empty($merchant)) {
                $data[$k] = array_merge($v, $merchant['code']);
                $data[$k]['merchant_group_name'] = $merchantGroupList->where('id', $merchant['merchant_group_id'])->first()['name'];
            }
        }
        return $data;
//        }
    }

    /**
     * @param array $data
     * @throws BusinessLogicException
     */
    public function merchantRecharge(array $data)
    {
        $data['mode'] = BaseConstService::BILL_MODE_1;
        $data['create_Date'] = today()->format('Y-m-d');
        $data['actual_amount'] = 0;
        $data['payer_type'] = BaseConstService::USER_MERCHANT;
        $data['payer_name'] = UserTrait::get($data['payer_id'], BaseConstService::USER_MERCHANT)['name'];
        $data['payee_id'] = auth()->user()->company_id;
        $data['payee_type'] = BaseConstService::USER_COMPANY;
        $data['payee_name'] = $this->getCompanyService()->getInfo(['id' => auth()->user()->id], ['*'], false)->toArray()['name'] ?? '';
        $data['operator_id'] = auth()->user()->id;
        $data['operator_type'] = BaseConstService::USER_ADMIN;
        $data['operator_name'] = auth()->user()->username;
        $data['crate_timing'] = BaseConstService::BILL_CREATE_TIMING_1;
        $data['pay_timing'] = BaseConstService::BILL_PAY_TIMING_1;
        self::store($data);
        $this->getLedgerService()->recharge($data['payer_type'], $data['payer_id'], $data['expect_amount']);
    }

    /**
     * @param array $data
     * @throws BusinessLogicException
     */
    public function merchantDeduct(array $data)
    {
        $data['mode'] = BaseConstService::BILL_MODE_1;
        $data['create_Date'] = today()->format('Y-m-d');
        $data['actual_amount'] = 0;
        $data['payer_type'] = BaseConstService::USER_MERCHANT;
        $data['payee_id'] = auth()->user()->company_id;
        $data['payee_type'] = BaseConstService::USER_COMPANY;
        $data['operator_id'] = auth()->user()->id;
        $data['operator_type'] = BaseConstService::USER_ADMIN;
        $data['operator_name'] = auth()->user()->username;
        $data['crate_timing'] = BaseConstService::BILL_CREATE_TIMING_1;
        $data['pay_timing'] = BaseConstService::BILL_PAY_TIMING_1;
        $this->store($data);
    }

}
