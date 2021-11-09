<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\BaseController;
use App\Services\PackageNoRuleService;

/**
 * 单号规则管理
 * Class OrderController
 * @package App\Http\Controllers\Api\Admin
 * @property PackageNoRuleService $service
 */
class PackageNoRuleController extends BaseController
{
    public function __construct(PackageNoRuleService $service)
    {
        parent::__construct($service);
    }

    public function index()
    {
        if (!empty($this->data['status'])) {
            $where = ['status' => $this->data['status']];
        }else{
            $where=[];
        }
        return $this->service->getList($where, ['*'], false);
    }
}
