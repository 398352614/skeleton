<?php
/**
 * 联系人管理-收货方 接口
 * User: long
 * Date: 2020/3/16
 * Time: 13:38
 */

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\BaseController;
use App\Services\Merchant\CarouselService;

/**
 * Class AddressController
 * @package App\Http\Controllers\Api\Merchant
 * @property CarouselService $service
 */
class CarouselController extends BaseController
{
    public function __construct(CarouselService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

}
