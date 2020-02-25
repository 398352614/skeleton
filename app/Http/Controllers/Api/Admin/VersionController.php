<?php
/**
 * 版本控制器
 */
namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Models\Version;
use App\Services\Admin\VersionService;
use Illuminate\Http\Request;
class VersionController extends BaseController
{
    public function __construct(VersionService $service)
    {
        parent::__construct($service);
    }

    /**
     * 版本检查
     * @return array
     */
    protected function check(){
        return $this->service->check();
    }

    /**
     * 版本列表
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(){
        return $this->service->getPageList();
    }

    /**
     * 版本新增
     * @return mixed
     */
    public function store(){
        return $this->service->store($this->data);
    }

    /**
     * 版本信息修改
     * @param $id
     * @return bool|int
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }

    /**
     * 版本删除
     * @param $id
     * @return mixed
     */
    protected function delete($id){
        return $this->service->delete($id);
    }
}
