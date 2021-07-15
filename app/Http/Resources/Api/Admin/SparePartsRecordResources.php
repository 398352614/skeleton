<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/24/2021
 * Time : 3:40 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: SparePartsRecordResources.php
 */


namespace App\Http\Resources\Api\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SparePartsRecordResources
 * @package App\Http\Resources\Api\Admin
 */
class SparePartsRecordResources extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge($this->resource->toArray(), [
            'price_total' => $this->receive_quantity * $this->receive_price,
            'sp_unit_name' => $this->resource->getSpUnit($this->sp_unit),
            'receive_status_name' => $this->resource->getReceiveStatus($this->receive_status)
        ]);
    }
}
