<?php

namespace App\Http\Resources;

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
            'name' => $this->name,
            'contacter' => $this->contacter,
            'phone' => $this->phone,
            'country' => $this->country,
            'post_code' => $this->post_code,
            'house_number' => $this->house_number,
            'city' => $this->city,
            'street' => $this->street,
            'address' => $this->address,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
