<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\CarBrandResource;
use App\Http\Resources\CarModelResource;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Services\BaseConstService;
use App\Services\BaseService;

class CarModelService extends BaseService
{
    public $filterRules = [
        'brand_id' => ['=', 'brand_id'],
    ];

    public function __construct(CarModel $carBrand)
    {
        $this->model = $carBrand;
        $this->query = $this->model::query();
        $this->resource = CarModelResource::class;
        $this->infoResource = CarModelResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

    /**
     * 车辆品牌 服务
     * @return CarBrandService
     */
    private function getCarBrandService()
    {
        return self::getInstance(CarBrandService::class);
    }

    public function getListByBrand($params)
    {
        return parent::getList(['brand_id' => $params['brand_id']], ['id', 'cn_name', 'en_name'], false)->toArray();
    }


    /**
     * 新增
     *
     * @param $params
     * @return BaseService|\Illuminate\Database\Eloquent\Model
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $this->check($params);
        return parent::create($params);
    }

    /**
     * 验证
     *
     * @param $params
     * @throws BusinessLogicException
     */
    public function check($params)
    {
        $brand = $this->getCarBrandService()->getInfo(['id' => $params['brand_id']]);
        if (empty($brand)) {
            throw new BusinessLogicException('车辆品牌不存在');
        }
    }


}