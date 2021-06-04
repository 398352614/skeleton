<?php

namespace App\Http\Controllers\Api\Consumer;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\PackageService;
use App\Services\Consumer\PackageTrailService;

/**
 * Class PackageController
 * @package App\Http\Controllers\Api\Consumer
 * @property PackageTrailService $service
 */
class PackageTrailController extends BaseController
{

    /**
     * PackageController constructor.
     * @param PackageTrailService $service
     */
    public function __construct(PackageTrailService $service)
    {
        parent::__construct($service);
    }

    /**
     * 列表查询
     * @return mixed
     * @throws BusinessLogicException
     */
    public function index()
    {
        return $this->service->getPageList();
    }

}
