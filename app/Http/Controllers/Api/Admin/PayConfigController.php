<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\PayConfigService;

/**
 * Class BagController
 * @package App\Http\Controllers\Api\Admin
 * @property PayConfigService $service
 */
class PayConfigController extends BaseController
{
    public $service;

    public function __construct(PayConfigService $service)
    {
        parent::__construct($service);
    }

    /**
     * 详情
     * @return array
     */
    public function show()
    {
        $info = $this->service->getInfo(['company_id' => auth()->user()->company_id], ['*'], false);
        return empty($info) ? [] : $info->toArray();
    }

    /**
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function update()
    {
        $info = $this->service->getInfo(['company_id' => auth()->user()->company_id], ['*'], false);
        return $this->service->updateById($info['id'],$this->data);
    }


}
