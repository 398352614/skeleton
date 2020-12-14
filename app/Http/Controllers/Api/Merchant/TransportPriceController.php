<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Merchant\TransportPriceService;

/**
 * 运价管理
 * Class OrderController
 * @package App\Http\Controllers\Api\Admin
 * @property TransportPriceService $service
 */
class TransportPriceController extends BaseController
{
    public function __construct(TransportPriceService $service)
    {
        parent::__construct($service);
    }

    /**
     * 获取详情
     * @return array
     * @throws BusinessLogicException
     */
    public function show()
    {
        return $this->service->show();
    }

}
