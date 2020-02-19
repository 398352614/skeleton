<?php


namespace App\Services\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\SourceResource;
use App\Models\Source;
use App\Services\BaseService;

class SourceService extends BaseService
{
    public function __construct(Source $source)
    {
        $this->model = $source;
        $this->query = $this->model::query();
        $this->request = request();
        $this->formData = $this->request->all();
        $this->resource = SourceResource::class;

    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        parent::create(['source_name' => $params['source_name']]);
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

    public function getInfo($where, $selectFields = ['*'], $isResource = true, $orderFields = [])
    {
        return parent::getInfo($where, $selectFields, $isResource, $orderFields);
    }
}
