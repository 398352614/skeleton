<?php

namespace App\Http\Resources;

use App\Models\Merchant;
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
            'company_id' => $this->company_id,
            'merchant_id' => $this->merchant_id,
            'merchant_id_name' => $this->merchant_id_name,
            'sender_fullname' => $this->sender_fullname,
            'sender_phone' => $this->sender_phone,
            'short' => $this->short,
            'sender_country' => $this->sender_country,
            'sender_country_name' => $this->sender_country_name,
            'sender_post_code' => $this->sender_post_code,
            'sender_house_number' => $this->sender_house_number,
            'sender_city' => $this->sender_city,
            'sender_street' => $this->sender_street,
            'sender_address' => $this->sender_address,
            //lon' => $this->lon,
            //'lat' => $this->lat,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
