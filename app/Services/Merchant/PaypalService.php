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
        $bill = $this->getBillService()->getInfo(['object_no' => $data['order_no']], ['*'], false);
        if (empty($bill)) {
            throw new BusinessLogicException('数据不存在');
        }
        $merchant = $this->getMerchantService()->getInfo(['id' => $bill['merchant_id']], ['*'], false);
        $billVerify = $this->getBillVerifyService()->getInfo(['verify_no' => $bill['verify_no']], ['*'], false);
        if (!empty($billVerify)) {
            $this->check($billVerify['verify_no']);
            $payment=(new Paypal())->store($data['order_no']);
            //记录
            $row = parent::create([
                'merchant_id' => $bill['merchant_id'],
                'merchant_name' => $merchant['name'],
                'amount' => $data['amount'],
                'currency_unit_type' => CompanyTrait::getCompany()['currency_unit'],
                'verify_no' => $billVerify['verify_no'],
                'object_no' => $bill['object_no'],
                'payment_id'=>$payment['id']
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
        parent::update(['payment_id' => $data['payment_id']], [
            'payer_id' => $data['payer_id'],
            'payment_id' => $data['payment_id'],
            'status' => $data['success']
        ]);
    }

    /**
     * @param $verifyNo
     * @throws BusinessLogicException
     */
    public function check($verifyNo)
    {
        $data = parent::getInfo(['verify_no' => $verifyNo, 'status' => BaseConstService::PAYPAL_STATUS_1], ['*'], false);
        if (!empty($data)) {
            if ($data['status'] == BaseConstService::PAY_TYPE_1) {
                throw new BusinessLogicException('支付中，请勿重复支付');
            }
            if ($data['status'] == BaseConstService::PAY_TYPE_2) {
                throw new BusinessLogicException('支付完成，请勿重复支付');
            }
        }
    }
}
