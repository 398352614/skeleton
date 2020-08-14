<?php


namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\ApiTimesService;

class ApiTimesController extends BaseController
{
    public function __construct(ApiTimesService $service)
    {
        parent::__construct($service);
    }

    public function index(){
        return $this->service->getPageList();
    }
}
