<?php

namespace App\Manager\Payment;


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

class Paypal
{
    /**
     * @var ApiContext
     */
    public $PayPal;

    public function __construct()
    {
        // 下面为申请app获得的clientId和clientSecret，必填项，否则无法生成token。
        $clientId = config('tms.paypal_client_id');
        $clientSecret = config('tms.paypal_client_secret');
        $this->PayPal = new ApiContext(
            new OAuthTokenCredential(
                $clientId,
                $clientSecret
            )
        );
        $this->PayPal->setConfig(
            [
                'mode' => config('tms.paypal_sandbox_mode'),
                'cache.enabled' => false
            ]
        );
    }

    public function store($orderNo, $billVerify)
    {
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        //设置商品详情
        /**
         * 详情信息：单价、收货地址等请结合自己的业务去数据库或者其他存储数据的地方查询
         * 这里只是演示支付流程，不结合实际业务
         */
        $order = Order::query()->where('order_no', $orderNo)->first();
        $packageList = Package::query()->where('order_no', $orderNo)->where('status', BaseConstService::PACKAGE_STATUS_1)->get();
        $currencyUnit = CompanyConfig::query()->where('company_id', $order['company_id'])->first()['currency_unit'];
        $currencyUnitShort = ConstTranslateTrait::currencyUnitTypeShortList($currencyUnit);
        $newItemList = [];
        if (!empty($packageList)) {
            $itemList = [];
            foreach ($packageList as $k => $v) {
                $itemList[$k] = new Item();
                $itemList[$k]->setName($v['name'])
                    ->setCurrency($currencyUnitShort)
                    ->setQuantity($v['expect_quantity'])
                    ->setPrice($v['settlement_amount']);
            }
            $newItemList = new ItemList();
            $newItemList->setItems($itemList);
        }
        // 自定义用户收货地址，如果这里不定义，在支付页面能够修改，可能会误操作，与用户想等地质不一致
        if ($order['type'] == BaseConstService::ORDER_TYPE_3) {
            $street = $order['second_place_street'];
            $houseNumber = $order['second_place_house_number'];
            $city = $order['second_place_city'];
            $phone = $order['second_place_phone'];
            $postcode = $order['second_place_postcode'];
            $country = $order['second_place_country'];
        } else {
            $street = $order['place_street'];
            $houseNumber = $order['place_house_number'];
            $city = $order['place_city'];
            $phone = $order['place_phone'];
            $postcode = $order['place_postcode'];
            $country = $order['place_country'];
        }
        $address = new ShippingAddress();
        $address
//            ->setRecipientName($billVerify['payer_name'])
            ->setLine1($street)
            ->setLine2($houseNumber)
            ->setCity($city)
//            ->setState("省份")
            ->setPhone($phone) //收货电话
            ->setPostalCode($postcode) //邮编
            ->setCountryCode($country);

        $newItemList->setShippingAddress($address);

        //设置总价，运费等金额。注意：setSubtotal的金额必须与详情里计算出的总金额相等，否则会失败
        $details = new Details();
        $details->setHandlingFee($order['start_price'])
//            ->setTax(2)
            ->setSubtotal($order['settlement_amount']);

        // 同上，金额要相等
        $amount = new Amount();
        $amount->setCurrency($currencyUnitShort)
            ->setTotal($order['settlement_amount']);
//            ->setDetails($details);


        $transaction = new Transaction();
        $transaction->setAmount($amount)
//            ->setItemList($newItemList)
            ->setDescription("描述")
            ->setInvoiceNumber(uniqid());
        /**
         * 回调
         * 当支付成功或者取消支付的时候调用的地址
         * success=true   支付成功
         * success=false  取消支付
         */
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(config('tms.paypal_success_url'))
            ->setCancelUrl(config('tms.paypal_cancel_url'));


        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        //创建支付
        $payment->create($this->PayPal);

        //生成地址
        $approvalUrl = $payment->getApprovalLink();
        //跳转
        header("location:" . $approvalUrl);
    }

    /**
     * 回调
     * @param $data
     * @return void
     */
    public function pay($data)
    {
        set_time_limit(3600);
        $success = trim($data['success']);
        if ($success == 'false' && !isset($data['paymentId']) && !isset($data['PayerID'])) {
            echo '取消付款';
            return;
        }

        $paymentId = trim($data['paymentId']);
        $PayerID = trim($data['PayerID']);

        if (!isset($success, $paymentId, $PayerID)) {
            echo '支付失败';
            //todo 记录失败
            return;
        }

        if ((bool)$data['success'] === 'false') {
            $log = '支付失败，支付ID【' . $paymentId . '】,支付人ID【' . $PayerID . '】';
            echo $log;
            return;
        }

        $payment = Payment::get($paymentId, $this->PayPal);

        $execute = new PaymentExecution();

        $execute->setPayerId($PayerID);

        try {
            $payment->execute($execute, $this->PayPal);
        } catch (\Exception $e) {
            $log = '支付失败，支付ID【' . $paymentId . '】,支付人ID【' . $PayerID . '】';
            echo $log;
            return;
        }
        $log = '支付成功，支付ID【' . $paymentId . '】,支付人ID【' . $PayerID . '】';
        echo $log;
    }
}





