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

    public function thisWeekCount(){
        return $this->service->thisWeekCount();
    }

    public function lastWeekCount(){
        return $this->service->lastWeekCount();
    }

    public function thisMonthCount(){
        return $this->service->thisMonthCount();
    }

    public function lastMonthCount(){
        return $this->service->lastMonthCount();
    }

    public function periodCount(){
        return $this->service->periodCount($this->data);
    }
}
