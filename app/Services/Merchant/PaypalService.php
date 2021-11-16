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
     * @param $data
     * @throws BusinessLogicException
     */
    public function store($data)
    {
        $bill = $this->getBillService()->getInfo(['object_no' => $data['order_no']], ['*'], false);
        if(empty($bill)){
            throw new BusinessLogicException('数据不存在');
        }
        $billVerify = $this->getBillVerifyService()->getInfo(['verify_no' => $bill['verify_no']], ['*'],false);
        if (!empty($billVerify)) {
            (new Paypal())->store($data['order_no'], $billVerify);
        }
    }

    public function pay($data)
    {
        (new Paypal())->pay($data);
    }
}
