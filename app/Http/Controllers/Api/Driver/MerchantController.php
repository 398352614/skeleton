<?php
/**
 * 商户
 * User: long
 * Date: 2020/1/3
 * Time: 16:26
 */

namespace App\Http\Controllers\Api\Driver;


use App\Http\Controllers\BaseController;
use App\Services\BaseConstService;
use App\Services\Driver\MerchantService;

/**
 * Class BatchExceptionController
 * @package App\Http\Controllers\Api\Driver
 * @property MerchantService $service
 */
class MerchantController extends BaseController
{
    /**
     * MerchantController constructor.
     * @param MerchantService $service
     */
    public function __construct(MerchantService $service)
    {
        parent::__construct($service);
    }

    public function index(){
        return $this->service->getList(['status'=>BaseConstService::MERCHANT_STATUS_1],['*'],false);
    }
}
