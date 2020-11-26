<?php

namespace App\Http\Resources\Api\Driver;

use App\Models\Merchant;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'merchant_id' => $this->merchant_id,
            'merchant_id_name' => $this->merchant_id_name,
            'place_fullname' => $this->place_fullname,
            'place_phone' => $this->place_phone,
            'short' => $this->short,
            'place_country' => $this->place_country,
            'place_country_name' => $this->place_country_name,
            'place_post_code' => $this->place_post_code,
            'place_house_number' => $this->place_house_number,
            'place_city' => $this->place_city,
            'place_street' => $this->place_street,
            'place_address' => $this->place_address,
            'place_lon' => $this->place_lon,
            'place_lat' => $this->place_lat,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
