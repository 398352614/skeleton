<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\HomeService;
use Illuminate\Http\Request;
class HomeController extends BaseController
{
    /**
     * HomeController constructor.
     * @param HomeService $service
     * @
     */
    public function __construct(HomeService $service)
    {
        parent::__construct($service);
    }

    public function home(){
        return $this->service->home();
    }

    public function weekCount(){
        return $this->service->weekCount();
    }

    public function monthCount(){
        return $this->service->monthCount();
    }

    public function yearCount(){
        return $this->service->yearCount();
    }
}
