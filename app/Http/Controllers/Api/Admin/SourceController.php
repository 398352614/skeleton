<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\SourceService;
use Illuminate\Http\Request;

class SourceController extends BaseController
{
    public function __construct(SourceService $service)
    {
        parent::__construct($service);
    }

    public function index()
    {
        return $this->service->getList();
    }

    public function store(){
        $this->service->store($this->data);
    }

    public function destroy($id)
    {
        return $this->service->destroy($id);
    }

}
