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


namespace App\Services\Merchant;

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
     * @param $where
     * @param  string[]  $selectFields
     * @param  bool  $isResource
     * @param  array  $orderFields
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getInfo($where, $selectFields = ['*'], $isResource = true, $orderFields = [])
    {
        $data = parent::getInfo($where, $selectFields, $isResource, $orderFields);

        if (empty($data)) {
            return $this->init([]);
        } else {
            return $data;
        }
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function init(array $data)
    {
        return $this->create($data);
    }
}
