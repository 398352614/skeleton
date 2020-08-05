<?php


namespace App\Http\Controllers\Api\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Merchant\PackageService;

/**
 * Class PackageController
 * @package App\Http\Controllers\Api\Merchant
 * @property PackageService $service
 */
class PackageController extends BaseController
{
    public function __construct(PackageService $service)
    {
        parent::__construct($service);
    }

    /**
     * @return array
     * @throws BusinessLogicException
     */
    public function showByApi()
    {
        return $this->service->showByApi($this->data);
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
