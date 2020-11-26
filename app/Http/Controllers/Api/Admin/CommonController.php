<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/26
 * Time: 15:34
 */

namespace App\Http\Controllers\Api\Admin;

use App\Services\CommonService;
use Illuminate\Http\Request;

/**
 * Class CommonController
 * @package App\Http\Controllers\Api\Admin
 * @property CommonService $service
 */
class CommonController
{
    public function __construct(CommonService $service)
    {
        $this->service = $service;
    }


    /**
     * 获取地址经纬度
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getLocation(Request $request)
    {
        return $this->service->getLocation($request->all());
    }

    /**
     * 获取国家列表
     * @return mixed
     */
    public function getCountryList()
    {
        return $this->service->getCountryList();
    }

    /**
     * 获取国家地址
     * @param $country
     * @return mixed
     */
    public function getCountryAddress($country)
    {
        return $this->service->getCountryAddress($country);
    }

    public function getPostcode(Request $request)
    {
        return $this->service->getPostcode($request->all());
    }
    
    /**
     * @return array
     */
    public function dictionary()
    {
        return $this->service->dictionary();
    }
}
