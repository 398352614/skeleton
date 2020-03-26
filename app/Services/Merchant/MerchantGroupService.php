<?php
/**
 * 商户组 服务
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\MerchantGroupResource;
use App\Models\MerchantGroup;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Support\Arr;

class MerchantGroupService extends BaseService
{
    public function __construct(MerchantGroup $merchantGroup)
    {
        parent::__construct($merchantGroup);
    }
}
