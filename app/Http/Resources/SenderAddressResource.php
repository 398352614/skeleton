<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SenderAddressResource extends JsonResource
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
            'sender' => $this->sender,
            'sender_phone' => $this->sender_phone,
            'sender_country' => $this->sender_country,
            'sender_post_code' => $this->sender_post_code,
            'sender_house_number' => $this->sender_house_number,
            'sender_city' => $this->sender_city,
            'sender_street' => $this->sender_street,
            'sender_address' => $this->sender_address,
            'lon' => $this->lon,
            'lat' => $this->lat,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
