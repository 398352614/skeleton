<?php
/**
 * 备忘录 接口
 * User: long
 * Date: 2019/12/26
 * Time: 14:09
 */

namespace App\Http\Controllers\Api\Driver;


use App\Http\Controllers\BaseController;
use App\Services\BaseService;
use App\Services\Driver\MemorandumService;

/**
 * Class MemorandumController
 * @package App\Http\Controllers\Api\Driver
 */
class MemorandumController extends BaseController
{
    public function __construct(MemorandumService $service)
    {
        parent::__construct($service);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    public function store()
    {
        return $this->service->store($this->data);
    }

    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }

    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}