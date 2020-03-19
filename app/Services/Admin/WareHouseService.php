<?php
/**
 * 仓库服务
 * User: long
 * Date: 2019/12/21
 * Time: 11:21
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\WareHouseResource;
use App\Models\Warehouse;
use App\Services\BaseService;
use App\Traits\LocationTrait;

class WareHouseService extends BaseService
{
    public function __construct(Warehouse $warehouse)
    {
        $this->request = request();
        $this->model = $warehouse;
        $this->query = $this->model::query();
        $this->resource = WareHouseResource::class;
        $this->infoResource = WareHouseResource::class;
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('仓库新增失败,请重新操作');
        }
    }

    /**
     * 通过ID修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('仓库修改失败，请重新操作');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('仓库删除失败，请重新操作');
        }
    }
}
