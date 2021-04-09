<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/12/2021
 * Time : 6:29 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: CarMaintainResource.php
 */


namespace App\Http\Resources\Api\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CarMaintainResource
 * @package App\Http\Resources\Api\Admin
 */
class CarMaintainResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge($this->resource->toArray(), [
            'maintain_type_name' => $this->resource->getMaintainType($this->maintain_type),
            'is_ticket_name' => $this->resource->getIsTicket($this->is_ticket)
        ]);
    }
}
