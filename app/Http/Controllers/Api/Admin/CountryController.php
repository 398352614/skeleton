<?php
/**
 * 国家 接口
 * User: long
 * Date: 2019/12/26
 * Time: 15:55
 */

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\CountryService;
use App\Services\BaseService;

/**
 * Class CountryController
 * @package App\Http\Controllers\Api\Admin
 * @property CountryService $service
 */
class CountryController extends BaseController
{
    public function __construct(CountryService $service)
    {
        parent::__construct($service);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 新增
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * 删除
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}