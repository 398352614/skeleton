<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Merchant\AddressResource;
use App\Manager\Payment\Paypal;
use App\Models\Bill;
use App\Services\BaseConstService;
use App\Traits\CompanyTrait;
use App\Traits\FactoryInstanceTrait;


class PaypalService extends BaseService
{
    public function __construct(Bill $model)
    {
        parent::__construct($model, AddressResource::class);
    }

    public $filterRules = [
        'place_fullname' => ['like', 'place_fullname'],
        'place_post_code' => ['like', 'place_post_code'],
        'place_phone' => ['like', 'place_phone'],
        'type' => ['=', 'type'],
    ];

    public $orderBy = [
        'updated_at' => 'desc',
    ];

    /**
     * 创建支付单
     * @param $data
     * @return mixed
     * @throws BusinessLogicException
     */
    public function store($data)
    {
        $bill = $this->getBillService()->getInfo(['bill_no' => $data['bill_no']], ['*'], false);
        if (empty($bill)) {
            throw new BusinessLogicException('数据不存在');
        }
        $merchant = $this->getMerchantService()->getInfo(['id' => $bill['merchant_id']], ['*'], false);
        if (!empty($bill)) {
            $this->check($bill['verify_no']);
            if ($bill['expect_amount'] == 0) {
                throw new BusinessLogicException('金额为0，无法创建支付单');
            }
            $payment = (new Paypal())->store($data['bill_no'], $bill['expect_amount']);
            //记录
            $row = parent::create([
                'merchant_id' => $bill['merchant_id'],
                'merchant_name' => $merchant['name'],
                'amount' => $bill['expect_amount'],
                'bill_no' => $data['bill_no'],
                'currency_unit_type' => CompanyTrait::getCompany()['currency_unit'],
                'object_no' => $bill['object_no'],
                'payment_id' => $payment['id']
            ]);
            if ($row == false) {
                throw new BusinessLogicException('支付失败');
            }
            return $payment;
        }
    }

    /**
     * 完成支付
     * @param $data
     * @throws BusinessLogicException
     */
    public function pay($data)
    {
        (new Paypal())->pay($data);
        $dbData = parent::getInfo(['payment_id' => $data['payment_id']], ['*'], false);
        if (!empty($dbData)) {
            parent::update(['payment_id' => $data['payment_id']], [
                'payer_id' => $data['payer_id'],
                'payment_id' => $data['payment_id'],
                'status' => $data['success']
            ]);
            //更新对账单
            $params = [
                'pay_type' => BaseConstService::PAY_TYPE_1,
                'actual_amount' => $dbData['amount'],
                'status' => $data['status']
            ];
            $this->getBillService()->pay($params);
        }

    }

    /**
     * @param $verifyNo
     * @throws BusinessLogicException
     */
    public function check($billNo)
    {
        $data = parent::getInfo(['bill_no' => $billNo, 'status' => BaseConstService::PAYPAL_STATUS_1], ['*'], false);
        if (!empty($data)) {

            if ($data['status'] == BaseConstService::BILL_STATUS_2) {
                throw new BusinessLogicException('支付完成，请勿重复支付');
            }
        }
    }
}
