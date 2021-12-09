<?php

namespace App\Manager\Payment;


use App\Exceptions\BusinessLogicException;
use App\Models\CompanyConfig;
use App\Models\Order;
use App\Models\Package;
use App\Services\BaseConstService;
use App\Traits\ConstTranslateTrait;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ShippingAddress;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\Log;
use Paypal\Exception\PayPalConnectionException;
class Paypal
{
    /**
     * @var ApiContext
     */
    public $payPal;

    public function __construct()
    {
        // 下面为申请app获得的clientId和clientSecret，必填项，否则无法生成token。
        $clientId = config('tms.paypal_client_id');
        $clientSecret = config('tms.paypal_client_secret');
        $this->payPal = new ApiContext(
            new OAuthTokenCredential(
                $clientId,
                $clientSecret
            )
        );
        $this->payPal->setConfig(
            [
                'mode' => config('tms.paypal_sandbox_mode'),
                'cache.enabled' => false
            ]
        );
    }

    /**
     * @throws BusinessLogicException
     */
    public function store($billNo, $expectAmount)
    {
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        //设置商品详情
//        $order = Order::query()->where('order_no', $orderNo)->first();
//        $packageList = Package::query()->where('order_no', $orderNo)->where('status', BaseConstService::PACKAGE_STATUS_1)->get();
        $currencyUnit = CompanyConfig::query()->where('company_id', auth()->user()->company_id)->first()['currency_unit'];
        $currencyUnitShort = ConstTranslateTrait::currencyUnitTypeShortList($currencyUnit);
        $newItemList = [];
//        if (!empty($packageList)) {
//            $itemList = [];
//            foreach ($packageList as $k => $v) {
//                $itemList[$k] = new Item();
//                $itemList[$k]->setName($v['name'])
//                    ->setCurrency($currencyUnitShort)
//                    ->setQuantity($v['expect_quantity'])
//                    ->setPrice($v['settlement_amount']);
//            }
//            $newItemList = new ItemList();
//            $newItemList->setItems($itemList);
//        }
//        // 自定义用户收货地址，如果这里不定义，在支付页面能够修改，可能会误操作，与用户想等地质不一致
//        if ($order['type'] == BaseConstService::ORDER_TYPE_3) {
//            $street = $order['second_place_street'];
//            $houseNumber = $order['second_place_house_number'];
//            $city = $order['second_place_city'];
//            $phone = $order['second_place_phone'];
//            $postcode = $order['second_place_postcode'];
//            $country = $order['second_place_country'];
//        } else {
//            $street = $order['place_street'];
//            $houseNumber = $order['place_house_number'];
//            $city = $order['place_city'];
//            $phone = $order['place_phone'];
//            $postcode = $order['place_postcode'];
//            $country = $order['place_country'];
//        }
//        $address = new ShippingAddress();
//        $address
////            ->setRecipientName($billVerify['payer_name'])
//            ->setLine1($street)
//            ->setLine2($houseNumber)
//            ->setCity($city)
////            ->setState("省份")
//            ->setPhone($phone) //收货电话
//            ->setPostalCode($postcode) //邮编
//            ->setCountryCode($country);
//
//        $newItemList->setShippingAddress($address);

        //设置总价，运费等金额。注意：setSubtotal的金额必须与详情里计算出的总金额相等，否则会失败
//        $details = new Details();
//        $details->setHandlingFee($order['start_price'])
////            ->setTax(2)
//            ->setSubtotal($order['settlement_amount']);

        // 同上，金额要相等
        $amount = new Amount();
        $amount->setCurrency($currencyUnitShort)
            ->setTotal($expectAmount);
//            ->setDetails($details);


        $transaction = new Transaction();
        $transaction->setAmount($amount)
//            ->setItemList($newItemList)
            ->setDescription("账单号".$billNo)
            ->setInvoiceNumber(uniqid());
        /**
         * 回调
         * 当支付成功或者取消支付的时候调用的地址
         * success=true   支付成功
         * success=false  取消支付
         */
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(config('tms.paypal_callback_url') . '?status=' . BaseConstService::PAYPAL_STATUS_2 . '&')
            ->setCancelUrl(config('tms.paypal_callback_url') . '?status=' . BaseConstService::PAYPAL_STATUS_3 . '&');


        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        //创建支付
        try {
            $data['id'] = $payment->create($this->payPal)->getId();
        }catch ( \Exception $e) {
            Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            throw new BusinessLogicException('支付失败');
        }
        //生成地址
        $data['approvalUrl'] = $payment->getApprovalLink();
        //跳转
        return $data;
    }

    /**
     * 回调
     * @param $data
     * @return Payment
     * @throws BusinessLogicException
     */
    public function pay($data)
    {
        if (isset($data['success']) && $data['success'] == 'true') {
            $dbData=\App\Models\Paypal::query()->where('payment_id',$data['paymentId']);

            $paymentId = $data['paymentId'];
            $payment = Payment::get($paymentId, $this->payPal);

            $execution = new PaymentExecution();
            $execution->setPayerId($data['PayerID']);

            $transaction = new Transaction();
            $amount = new Amount();
//            $details = new Details();
//
//            $details->setShipping(5)
//                ->setTax(10)
//                ->setSubtotal(70);

            $amount->setCurrency($dbData['currency_unit_type']);
            $amount->setTotal($dbData['amount']);
//            $amount->setDetails($details);
            $transaction->setAmount($amount);

            // Add the above transaction object inside our Execution object.
            $execution->addTransaction($transaction);

            try {
                return $payment->execute($execution, $this->payPal);
            } catch (\Exception $ex) {
                throw new BusinessLogicException('支付失败');
            }
        } else {
            throw new BusinessLogicException('支付失败');
        }
    }
}





