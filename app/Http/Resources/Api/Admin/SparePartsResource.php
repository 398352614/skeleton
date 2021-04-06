<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/23/2021
 * Time : 5:01 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: SparePartsResource.php
 */


namespace App\Http\Resources\Api\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SparePartsResource
 * @package App\Http\Resources\Api\Admin
 */
class SparePartsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge([
            'sp_unit_id' => $this->resource->getOriginal('sp_unit'),
            'stock_quantity' => $this->resource->getStock($this->sp_no)
        ], $this->resource->toArray());
    }
}
