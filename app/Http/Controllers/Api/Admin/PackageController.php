<?php


namespace App\Http\Controllers\Api\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\PackageService;

/**
 * Class PackageController
 * @package App\Http\Controllers\Api\Admin
 * @property PackageService $service
 */
class PackageController extends BaseController
{
    public $service;

    /**
     * PackageController constructor.
     * @param PackageService $service
     */
    public function __construct(PackageService $service)
    {
        parent::__construct($service);
    }

    /**
     * 列表查询
     * @return mixed
     */
    public function index(){
        return $this->service->getPageList();
    }

    /**
     * 获取详情
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function show($id){
        return $this->service->show($id);
    }
}
