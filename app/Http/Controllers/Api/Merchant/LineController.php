<?php
/**
 * 线路 接口
 * User: long
 * Date: 2019/12/21
 * Time: 10:04
 */

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\BaseController;
use App\Services\Merchant\LineService;

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

    /**
     * 通过邮编获取日期列表
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getDateListByPostCode()
    {
        return $this->service->getDateListByPostCode($this->data);
    }
}
