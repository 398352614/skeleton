<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Merchant\AddressInfoResource;
use App\Http\Resources\Api\Merchant\AddressResource;
use App\Models\Address;
use App\Services\CommonService;
use App\Traits\CompanyTrait;
use App\Traits\LocationTrait;
use Illuminate\Support\Arr;

class AddressService extends BaseService
{
    public function __construct(Address $address)
    {
        parent::__construct($address, AddressResource::class, AddressInfoResource::class);
    }

    public $filterRules = [
        'place_fullname' => ['like', 'place_fullname'],
        'place_post_code' => ['like', 'place_post_code'],
        'place_phone' => ['like', 'place_phone'],
        'type' => ['=', 'type'],
    ];

    public $orderBy = [
        'updated_at' => 'desc',
    ];


    public function pay($id, array $data)
    {
    }

}
