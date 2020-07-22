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
use App\Traits\CompanyTrait;
use App\Traits\LocationTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class WareHouseService extends BaseService
{
    public $filterRules = [
        'country' => ['=', 'country'],
    ];

    public function __construct(Warehouse $warehouse)
    {
        parent::__construct($warehouse, WareHouseResource::class, WareHouseResource::class);
    }

    /**
     * 线路 服务
     * @return LineService
     */
    public function getLineService()
    {
        return self::getInstance(LineService::class);
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
        $this->fillData($params);
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
        $this->fillData($data);
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('仓库修改失败，请重新操作');
        }
    }

    /**
     * 填充数据
     * @param $params
     */
    private function fillData(&$params)
    {
        //填充地址
        $params['country'] = CompanyTrait::getCountry();
        if ((CompanyTrait::getAddressTemplateId() == 1) || empty($params['address'])) {
            $params['address'] = implode(' ', array_only_fields_sort($params, ['country', 'city', 'street', 'house_number', 'post_code']));
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        //删除仓库前 先验证线路是否存在
        $line = $this->getLineService()->getInfo(['warehouse_id' => $id], ['id'], false);
        if (!empty($line)) {
            throw new BusinessLogicException('存在当前仓库的线路,请先删除线路');
        }
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('仓库删除失败，请重新操作');
        }
    }
}
