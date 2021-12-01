<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Models\Bill;
use App\Services\BaseConstService;
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
        'mode' => ['=', 'mode'],
        'object_no' => ['like', 'object_no'],
        'bill_no' => ['like', 'bill_no'],
        'verify_no' => ['like', 'verify_no'],
        'status' => ['=', 'status'],
        'payer_name' => ['=', 'payer_name']
    ];


    /**
     * AddressService constructor.
     * @param Bill $model
     */
    public function __construct(Bill $model)
    {
        parent::__construct($model);
    }

    public $orderBy = ['id' => 'desc'];

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $params['bill_no'] = $this->getOrderNoRuleService()->createBillNo();
        $params['create_date'] = today()->format('Y-m-d');
        $bill = parent::create($params);
        if ($bill == false) {
            throw new BusinessLogicException('订单新增失败');
        }
//        if ($params['pay_timing'] == BaseConstService::FEE_PAY_TYPE_1) {
//            $this->getJournalService()->record($params);
//        }
        return $bill;
    }

    /**
     * 查询
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws BusinessLogicException
     */
    public function getPageList()
    {
        $this->query
            ->where('payer_id', auth()->user()->id)
            ->where('payer_type', BaseConstService::USER_MERCHANT)
            ->where('mode', BaseConstService::BILL_MODE_1);
        $data = parent::getPageList();
        return $data;
    }

    /**
     * @param array $data
     * @throws BusinessLogicException
     */
    public function storeByRecharge(array $data)
    {
        $data['mode'] = BaseConstService::BILL_MODE_1;
        $data['pay_mode'] = BaseConstService::PAY_MODE_2;
        $data['create_Date'] = today()->format('Y-m-d');
        $data['actual_amount'] = 0;
        $data['payer_type'] = BaseConstService::USER_MERCHANT;
        $data['payer_id'] = auth()->user()->id;
        $data['payer_name'] = UserTrait::get($data['payer_id'], BaseConstService::USER_MERCHANT)['name'];
        $data['payee_id'] = auth()->user()->company_id;
        $data['payee_type'] = BaseConstService::USER_COMPANY;
        $data['payee_name'] = $this->getCompanyService()->getInfo(['id' => auth()->user()->id], ['*'], false)['name'] ?? '';
        $data['operator_id'] = auth()->user()->id;
        $data['operator_type'] = BaseConstService::USER_ADMIN;
        $data['operator_name'] = auth()->user()->username;
        $data['create_timing'] = BaseConstService::BILL_CREATE_TIMING_2;
        $data['pay_timing'] = BaseConstService::BILL_PAY_TIMING_1;
        $data['status'] = BaseConstService::BILL_STATUS_1;
        $data = self::store($data);
        return $data;
    }

    /**
     * @param $data
     * @param $fee
     * @param $order
     * @param int $status
     * @throws BusinessLogicException
     */
    public function storeByFee($data, $fee, $order, $status = BaseConstService::BILL_VERIFY_STATUS_1)
    {
        $data['type'] = BaseConstService::BILL_TYPE_2;
        $data['fee_id'] = $fee['id'];
        $data['fee_name'] = $fee['name'];
        $data['mode'] = BaseConstService::BILL_MODE_2;
        $data['create_Date'] = today()->format('Y-m-d');
        $data['create_timing'] = BaseConstService::BILL_CREATE_TIMING_1;
        $data['pay_timing'] = $fee['pay_timing'];
        if ($fee['pay_timing'] == BaseConstService::BILL_PAY_TIMING_1) {
            $data['actual_amount'] = $data['expect_amount'] ?? 0;
        } else {
            $data['actual_amount'] = 0;
        }
        //填充付款方
        $data['payer_type'] = $fee['payer_type'] ?? BaseConstService::USER_MERCHANT;
        if ($data['payer_type'] == BaseConstService::USER_MERCHANT) {
            $data['payer_id'] = $order['merchant_id'];
            $data['payer_name'] = UserTrait::get($data['payer_id'], BaseConstService::USER_MERCHANT)['name'];
        } elseif ($data['payer_type'] == BaseConstService::USER_RECEIVER && $order['type'] == BaseConstService::ORDER_TYPE_3) {
            $data['payer_name'] = $order['second_place_fullname'];
        } elseif ($data['payer_type'] == BaseConstService::USER_SENDER || $data['payer_type'] == BaseConstService::USER_RECEIVER) {
            $data['payer_name'] = $order['place_fullname'];
        }
        //填充收款方
        $data['payee_type'] = $fee['payee_type'] ?? BaseConstService::USER_COMPANY;
        if ($data['payee_type'] == BaseConstService::USER_COMPANY) {
            $data['payee_id'] = auth()->user()->company_id;
            $data['payee_name'] = $this->getCompanyService()->getInfo(['id' => auth()->user()->id], ['*'], false)['name'] ?? '';
        } elseif ($data['payee_type'] == BaseConstService::FEE_PAYEE_TYPE_7) {
            $data['payee_type'] = BaseConstService::USER_DRIVER;
        }
        $data['object_type'] = BaseConstService::BILL_OBJECT_TYPE_1;
        $data['object_no'] = $order['order_no'];
        if ($fee['pay_type'] == BaseConstService::FEE_PAY_TYPE_1) {
            $data['pay_type'] = BaseConstService::PAY_TYPE_4;
        }
        $data['operator_id'] = auth()->user()->id;
        $data['operator_type'] = BaseConstService::USER_ADMIN;
        $data['operator_name'] = auth()->user()->username;
        $data['status'] = $status;
        $data['pay_mode'] = BaseConstService::PAY_MODE_2;
        self::store($data);
    }

    /**
     * @param $data
     * @param $user
     * @throws BusinessLogicException
     * pay_type,actual_amount,status
     */
    public function pay($data)
    {
        $user = UserTrait::get(auth()->user()->id, BaseConstService::USER_MERCHANT);
        $dbData = parent::getInfoLock(['bill_no' => $data['bill_no']], ['*'], false);
        if (empty($dbData)) {
            throw new BusinessLogicException('数据不存在');
        }
        if ($dbData['status'] !== BaseConstService::BILL_STATUS_1) {
            throw new BusinessLogicException('状态错误');
        }
        if ($data['status'] == BaseConstService::BILL_STATUS_1) {
            throw new BusinessLogicException('非法参数');
        }
        $row = parent::update(['id' => $dbData['id']], [
            'actual_amount' => $data['actual_amount'] ?? 0,
            'status' => $data['status'],
            'operator_type' => $user['user_type'],
            'operator_id' => $user['id'],
            'operator_name' => $user['name'],
            'pay_type' => $data['pay_type'],
        ]);
        if ($row == false) {
            throw new BusinessLogicException('支付失败');
        }
//        $row = $this->getOrderService()->update(['order_no' => $dbData['object_no']], ['pay_status' => $data - 1]);
//        if ($row == false) {
//            throw new BusinessLogicException('支付失败');
//        }
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
        $data['crate_timing'] = BaseConstService::BILL_CREATE_TIMING_2;
        $data['pay_timing'] = BaseConstService::BILL_PAY_TIMING_1;
        $this->store($data);
    }

    /**
     * @param $id
     * @param $data
     * @return int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
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
        if ($data['verify_status'] == BaseConstService::BILL_VERIFY_STATUS_2) {
//            if (empty($data['actual_amount'])) {
//                throw new BusinessLogicException('实际金额不能为空');
//            }
//            if ($data['actual_amount'] > $dbData['expect_amount']) {
//                throw new BusinessLogicException('实际金额不能大于预计金额');
//            }
            $row = parent::update(['id' => $id], [
                'actual_amount' => $dbData['expect_amount'] ?? 0,
                'verify_status' => $data['verify_status'],
                'verify_time' => now()
            ]);
            if ($row == false) {
                throw new BusinessLogicException('操作失败');
            }
            $this->getLedgerService()->recharge($dbData['payer_type'], $dbData['payer_id'], $data['actual_amount']);
        } elseif ($data['verify_status'] == BaseConstService::BILL_VERIFY_STATUS_3) {
            $row = parent::update(['id' => $id], [
                'verify_status' => $data['verify_status'],
                'verify_time' => now()
            ]);
            if ($row == false) {
                throw new BusinessLogicException('操作失败');
            }
        } elseif ($data['verify_status'] == BaseConstService::BILL_VERIFY_STATUS_1) {
            throw new BusinessLogicException('参数非法');
        }
    }

    public function show($id)
    {
        $data = parent::getInfo(['id' => $id], ['*'], false);
        if ($data['payer_type'] == BaseConstService::USER_MERCHANT) {
            $merchant = $this->getMerchantService()->getInfo(['id' => $data['payer_id']], ['*'], false);
            if (!empty($merchant)) {
                $merchantGroup = $this->getMerchantGroupService()->getInfo(['id' => $merchant['merchant_group_id']], ['*'], false);
            }
            $data['code'] = $merchant['code'];
            $data['merchant_group_name'] = $merchantGroup['name'] ?? '';
        }
        return $data;
    }

    /**
     * @param $data
     * @param $transportPrice
     * @param int $status
     * @throws BusinessLogicException
     */
    public function storeByTransportPrice($data, $transportPrice, $status = BaseConstService::BILL_VERIFY_STATUS_2)
    {
        $data['expect_amount'] = $data['settlement_amount'];
        $data['create_timing'] = BaseConstService::BILL_CREATE_TIMING_1;
        $data['pay_timing'] = $transportPrice['pay_timing'];
        $data['type'] = BaseConstService::BILL_TYPE_1;
        $data['fee_id'] = $transportPrice['id'];
        $data['fee_name'] = __('运费');
        $data['mode'] = BaseConstService::BILL_MODE_2;
        $data['create_Date'] = today()->format('Y-m-d');
        $data['payer_type'] = $transportPrice['payer_type'];
        if ($transportPrice['payer_type'] == BaseConstService::USER_MERCHANT) {
            $data['payer_id'] = $data['merchant_id'];
            $data['payer_name'] = UserTrait::get($data['payer_id'], BaseConstService::USER_MERCHANT)['name'];
            $data['actual_amount'] = $data['expect_amount'] ?? 0;
        } else {
            $data['actual_amount'] = 0;
        }
        if ($transportPrice['payee_type'] == BaseConstService::USER_COMPANY) {
            $data['payee_id'] = auth()->user()->company_id;
            $data['payee_name'] = $this->getCompanyService()->getInfo(['id' => auth()->user()->id], ['*'], false)['name'] ?? '';
        } elseif ($data['payee_type'] == BaseConstService::FEE_PAYEE_TYPE_7) {
            $data['payee_type'] = BaseConstService::USER_DRIVER;
        }
        $data['payee_type'] = $transportPrice['payee_type'];
        $data['object_type'] = BaseConstService::BILL_OBJECT_TYPE_1;
        $data['object_no'] = $data['order_no'];
        if ($transportPrice['pay_type'] == BaseConstService::FEE_PAY_TYPE_1) {
            $data['pay_type'] = BaseConstService::PAY_TYPE_4;
        }
        $data['operator_id'] = auth()->user()->id;
        $data['operator_type'] = BaseConstService::USER_ADMIN;
        $data['operator_name'] = auth()->user()->username;
        $data['create_timing'] = BaseConstService::BILL_CREATE_TIMING_2;
        $data['pay_timing'] = $transportPrice['pay_timing'];
        $data['status'] = $status;
        self::store($data);
    }
}
