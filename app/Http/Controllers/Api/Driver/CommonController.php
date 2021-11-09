<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/26
 * Time: 15:34
 */

namespace App\Http\Controllers\Api\Driver;

use App\Services\CommonService;

/**
 * Class CommonController
 * @package App\Http\Controllers\Api\Driver
 * @property CommonService $service
 */
class CommonController
{
    public function __construct(CommonService $service)
    {
        $this->service = $service;
    }

    /**
     * @return array
     */
    public function dictionary()
    {
        return $this->service->dictionary();
    }
}
