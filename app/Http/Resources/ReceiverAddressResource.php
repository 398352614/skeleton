<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReceiverAddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'receiver' => $this->receiver,
            'receiver_phone' => $this->receiver_phone,
            'receiver_country' => $this->receiver_country,
            'receiver_post_code' => $this->receiver_post_code,
            'receiver_house_number' => $this->receiver_house_number,
            'receiver_city' => $this->receiver_city,
            'receiver_street' => $this->receiver_street,
            'receiver_address' => $this->receiver_address,
            'lon' => $this->lon,
            'lat' => $this->lat,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
