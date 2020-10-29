<?php

namespace App\Services\Admin;

use App\Http\Resources\Api\Admin\CarBrandResource;
use App\Http\Resources\Api\Admin\CarResource;
use App\Models\Car;
use App\Models\CarBrand;
use App\Services\BaseConstService;
use App\Services\Admin\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class CarBrandService extends BaseService
{
    public $filterRules = [
    ];

    public function __construct(CarBrand $carBrand)
    {
        parent::__construct($carBrand, CarBrandResource::class);
    }


    public function index()
    {
        return parent::getList([], ['id', 'cn_name', 'en_name'], false)->toArray();
    }

    public function getAll(){
        if(Cache::has('brand')){
            $resource=Cache::get('brand');
        }else{
            $client = new \GuzzleHttp\Client();
            $url = 'http://tool.bitefu.net/car/?type=brand&pagesize=300';
            $res = $client->request('GET', $url, [
                    'http_errors' => false
                ]
            );
            $info = (string)$res->getBody();
            $info = json_decode($info,TRUE)['info'];
            $resource =array_map(function ($info) {
                return Arr::only($info,['id','name']);
            }, $info);
            Cache::put('brand',$resource);
        };
        return $resource;
    }


}
