<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/24/2021
 * Time : 2:06 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: SparePartsStockResource.php
 */


namespace App\Http\Resources\Api\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SparePartsStockResource
 * @package App\Http\Resources\Api\Admin
 */
class SparePartsStockResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'sp_unit' => $this->resource->getSpUnit($this->sp_unit),
            'sp_unit_id' => $this->resource->getOriginal('sp_unit'),
        ]);
    }
}
