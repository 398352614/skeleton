<?php


namespace App\Services\Admin;


use App\Models\MerchantRecharge;
use App\Services\Admin\BaseService;

class MerchantRechargeService extends BaseService
{
    public function __construct(MerchantRecharge $merchantRecharge)
    {
        parent::__construct($merchantRecharge);
    }
}
