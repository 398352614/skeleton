<?php
/**
 * 线路 接口
 * User: long
 * Date: 2019/12/21
 * Time: 10:04
 */

namespace App\Http\Controllers\Api\Driver;

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

}
