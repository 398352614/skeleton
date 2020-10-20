<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\CarBrandResource;
use App\Http\Resources\Api\Admin\CarModelResource;
use App\Http\Resources\Api\Admin\CarResource;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class CarModelService extends BaseService
{
    public $filterRules = [
        'brand_id' => ['=', 'brand_id'],
    ];

    public function __construct(CarModel $carModel)
    {
        parent::__construct($carModel, CarModelResource::class, CarModelResource::class);
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
        $brand = $this->getCarBrandService()->getInfo(['id' => $params['brand_id']], ['id'], false);
        if (empty($brand)) {
            throw new BusinessLogicException('车辆品牌不存在');
        }
    }

    public function getAll($id){
    if (Cache::has('model'.$id)) {
        $resource = Cache::get('brand');
    } else {
            $client = new \GuzzleHttp\Client();
            $url = 'http://tool.bitefu.net/car/?type=series&pagesize=300&brand_id=' . $id;
            $res = $client->request('GET', $url, [
                    'http_errors' => false
                ]
            );
            $info = (string)$res->getBody();
            $info = json_decode($info, TRUE)['info'];
            $resource = array_map(function ($info) {
                return Arr::only($info, ['id', 'name']);
            }, $info);
            Cache::put('model'.$id, $resource);
        }
    return $resource;
    }
}
