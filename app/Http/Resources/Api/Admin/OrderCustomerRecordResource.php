<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 3/26/2021
 * Time : 2:43 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: OrderCustomerRecordResource.php
 */


namespace App\Http\Resources\Api\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderCustomerRecordResource
 * @package App\Http\Resources\Api\Admin
 */
class OrderCustomerRecordResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
