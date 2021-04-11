<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 4/6/2021
 * Time : 6:35 PM
 * Email: i@nizer.in
 * Blog : nizer.in
 * FileName: OrderDefaultConfigService.php
 */


namespace App\Services\Admin;

use App\Http\Resources\Api\Admin\OrderDefaultConfigResource;
use App\Models\OrderDefaultConfig;

/**
 * Class OrderDefaultConfigService
 * @package App\Services\Admin
 */
class OrderDefaultConfigService extends BaseService
{
    /**
     * OrderDefaultConfigService constructor.
     * @param  OrderDefaultConfig  $model
     * @param  null  $infoResource
     */
    public function __construct(OrderDefaultConfig $model, $infoResource = null)
    {
        parent::__construct($model, OrderDefaultConfigResource::class, $infoResource);
    }

    /**
     * @param  array  $data
     */
    public function init(array $data)
    {
        $this->create($data);
    }
}
