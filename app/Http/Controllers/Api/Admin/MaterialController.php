<?php


namespace App\Http\Controllers\Api\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\MaterialService;

/**
 * Class MaterialController
 * @package App\Http\Controllers\Api\Admin
 * @property MaterialService $service
 */
class MaterialController extends BaseController
{
    public $service;

    /**
     * MaterialController constructor.
     * @param MaterialService $service
     */
    public function __construct(MaterialService $service)
    {
        parent::__construct($service);
    }

    /**
     * 列表查询
     * @return mixed
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 获取详情
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }
}
