<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\OrderImportService;

/**
 * Class OrderImportController
 * @package App\Http\Controllers\Api\Admin
 * @property OrderImportService $service
 */
class OrderAmountController extends BaseController
{
    public function __construct(OrderImportService $service)
    {
        $this->service = $service;
    }
}
