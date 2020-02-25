<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\OrderImportLogService;
use Illuminate\Http\Request;

class OrderImportLogController extends BaseController
{
    public function __construct(OrderImportLogService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    public function show($id){
        return $this->service->showDetail($id);
    }

}
