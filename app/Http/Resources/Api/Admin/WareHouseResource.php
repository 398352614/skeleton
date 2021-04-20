<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class WareHouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'is_center' => $this->is_center,
            'is_center_name' => $this->is_center_name,
            'acceptance_type' => $this->acceptance_type,
            'acceptance_type_name' => $this->acceptance_type_name,
            'fullname'=> $this->fullname,
            'company_name'=> $this->company_name,
            'phone'=> $this->phone,
            'email'=> $this->email,
            'avatar'=> $this->avatar,
            'country' => $this->country,
            'country_name' => $this->country_name,
            'province' => $this->province,
            'city' => $this->city,
            'district' => $this->district,
            'post_code' => $this->post_code,
            'street' => $this->street,
            'house_number' => $this->house_number,
            'address' => $this->address,
            'lon' => $this->lon,
            'lat' => $this->lat,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
