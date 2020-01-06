<?php
/**
 * 国家 服务
 * User: long
 * Date: 2019/12/26
 * Time: 15:56
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Services\BaseService;

class CountryService extends BaseService
{
    public function __construct(Country $country)
    {
        $this->request = request();
        $this->model = $country;
        $this->query = $this->model::query();
        $this->resource = CountryResource::class;
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $rowCount = parent::create(['en_name' => $params['en_name'], 'cn_name' => $params['cn_name']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('国家新增失败');
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
            throw new BusinessLogicException('删除失败,请重新操作');
        }
    }
}