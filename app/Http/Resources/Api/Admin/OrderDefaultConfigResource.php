<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 4/6/2021
 * Time : 5:49 PM
 * Email: i@nizer.in
 * Blog : nizer.in
 * FileName: OrderDefaultConfigResource.php
 */


namespace App\Http\Resources\Api\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderDefaultConfigResource
 * @package App\Http\Resources\Api\Admin
 */
class OrderDefaultConfigResource extends JsonResource
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
