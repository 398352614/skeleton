<?php
/**
 * 公司配置 接口
 * User: long
 * Date: 2019/12/26
 * Time: 15:55
 */

namespace App\Http\Controllers\Api\Consumer;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\CompanyConfigService;
use App\Services\Consumer\CompanyService;

/**
 * Class CountryController
 * @package App\Http\Controllers\Api\Consumer
 * @property CompanyConfigService $service
 */
class CompanyController extends BaseController
{
    public function __construct(CompanyService $service)
    {
        parent::__construct($service);
    }

    public function index()
    {
        return $this->service->getPageList();
    }


}
