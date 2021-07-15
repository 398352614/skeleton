<?php
/**
 * 线路 接口
 * User: long
 * Date: 2019/12/21
 * Time: 10:04
 */

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\LineService;

/**
 * Class LineController
 * @package App\Http\Controllers\Api\Admin
 * @property LineService $service
 */
class LineController extends BaseController
{
    public function __construct(LineService $service)
    {
        parent::__construct($service);
    }

    /**
     * 通过日期 获取线路列表
     * @return array
     */
    public function getListByDate()
    {
        return $this->service->getListByDate($this->data['date']);
    }

    /**
     * 邮编-列表查询
     * @return \App\Services\Admin\BaseLineService|array|mixed
     */
    public function postcodeIndex()
    {
        return $this->service->postcodeIndex();
    }

    /**
     * 邮编-详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function postcodeShow($id)
    {
        return $this->service->postcodeShow($id);
    }


    /**
     * 邮编-新增
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function postcodeStore()
    {
        return $this->service->postcodeStore($this->data);
    }

    /**
     * 邮编-修改
     * @param $id
     * @return bool|int|void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function postcodeUpdate($id)
    {
        return $this->service->postcodeUpdate($id, $this->data);
    }

    /**
     * 邮编-删除
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function postcodeDestroy($id)
    {
        return $this->service->postcodeDestroy($id);
    }

    /**
     * 区域-列表查询
     * @return mixed
     */
    public function areaIndex()
    {
        $isGetArea = !empty($this->data['is_get_area']) ?: 2;
        return $this->service->areaIndex($isGetArea);
    }

    /**
     * 区域-详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function areaShow($id)
    {
        return $this->service->areaShow($id);
    }

    /**
     * 区域-新增
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function areaStore()
    {
        return $this->service->areaStore($this->data);
    }

    /**
     * 区域-修改
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function areaUpdate($id)
    {
        return $this->service->areaUpdate($id, $this->data);
    }

    /**
     * 区域-删除
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function areaDestroy($id)
    {
        return $this->service->areaDestroy($id);
    }

    /**
     * 状态批量启用禁用
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function statusByList()
    {
        return $this->service->statusByList($this->data);
    }

    public function test()
    {
        return $this->service->test($this->data);
    }
}
