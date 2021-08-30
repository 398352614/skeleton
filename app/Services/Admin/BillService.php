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
        $params['create_date'] = today()->format('Y-m-d');
        $bill = parent::create($params);
        if ($bill === false) {
            throw new BusinessLogicException('订单新增失败');
        }
        $this->getJournalService()->record($params);

    }

    /**
     * 查询
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPageList()
    {
        if ((!empty($this->formData['user_type']) && $this->formData['user_type'] == BaseConstService::USER_MERCHANT) || empty($this->formData['user_type'])) {
            $where = [];
            if (!empty($this->formData['code'])) {
                $where['code'] = $this->formData['code'];
            }
            if (!empty($this->formData['merchant_group_id'])) {
                $where ['merchant_group_id'] = $this->formData['merchant_group_id'];
            }
            if (!empty($where)) {
                $merchantList = $this->getMerchantService()->getList($where, ['*'], false);
                $this->query->whereIn('payer_id', $merchantList->pluck('id')->toArray());
                $this->query->orderByDesc('id');
                $data = parent::getPageList();

            } else {
                $data = parent::getPageList();
                $merchantList = $this->getMerchantService()->getList(['id' => ['in', $data->pluck('payer_id')->toArray()]], ['*'], false);
            }
            $merchantGroupList = $this->getMerchantGroupService()->getList(['id' => ['in', $merchantList->pluck('merchant_group_id')->toArray()]], ['*'], false);
            foreach ($data as $k => $v) {
                $merchant = $merchantList->where('id', $v['payer_id'])->first();
                if (!empty($merchant)) {
                    $data[$k]['code'] = $merchant['code'];
                    $data[$k]['merchant_group_name'] = $merchantGroupList->where('id', $merchant['merchant_group_id'])->first()['name'];
                }
            }
        } else {
            $data = parent::getPageList();
        }
        return $data;
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
        $data['payer_id'] = $data['merchant_id'];
        $data['payer_name'] = UserTrait::get($data['payer_id'], BaseConstService::USER_MERCHANT)['name'];
        $data['payee_id'] = auth()->user()->company_id;
        $data['payee_type'] = BaseConstService::USER_COMPANY;
        $data['payee_name'] = $this->getCompanyService()->getInfo(['id' => auth()->user()->id], ['*'], false)->toArray()['name'] ?? '';
        $data['operator_id'] = auth()->user()->id;
        $data['operator_type'] = BaseConstService::USER_ADMIN;
        $data['operator_name'] = auth()->user()->username;
        $data['create_timing'] = BaseConstService::BILL_CREATE_TIMING_1;
        $data['pay_timing'] = BaseConstService::BILL_PAY_TIMING_1;
        self::store($data);
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

    /**
     * @param $id
     * @param $data
     * @return int|void
     * @throws BusinessLogicException
     */
    public function update($id, $data)
    {
        $dbData = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($dbData)) {
            throw new BusinessLogicException('数据不存在');
        }
        $data = Arr::only($data, 'actual_amount');
        $row = parent::update(['id' => $id], $data);
        if ($row == false) {
            throw new BusinessLogicException('修改失败');
        }
    }

    /**
     * @param $id
     * @param $data
     * @throws BusinessLogicException
     */
    public function verify($id, $data)
    {
        if ($data['status'] == BaseConstService::BILL_VERIFY_STATUS_1) {
            throw new BusinessLogicException('参数非法');
        }
        $dbData = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($dbData)) {
            throw new BusinessLogicException('数据不存在');
        }
        if ($dbData['verify_status'] == BaseConstService::BILL_VERIFY_STATUS_2) {
            throw new BusinessLogicException('账单已审核，无需再次审核');
        }
        if ($dbData['verify_status'] == BaseConstService::BILL_VERIFY_STATUS_3) {
            throw new BusinessLogicException('账单已拒绝，无法再次审核');
        }
        if ($data['actual_amount'] > $dbData['expect_amount']) {
            throw new BusinessLogicException('实际金额不能大于预计金额');
        }
        $row = parent::update(['id' => $id], [
            'actual_amount' => $data['actual_amount'],
            'verify_status' => $data['verify_status']
        ]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        $this->getLedgerService()->recharge($dbData['payer_type'], $dbData['payer_id'], $data['actual_amount']);

    }

    public function show($id)
    {
        return parent::getInfo(['id' => $id], ['*'], false);
    }
}
