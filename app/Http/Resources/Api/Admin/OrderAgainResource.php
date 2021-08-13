<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderAgainResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tracking_order_id' => $this->tracking_order_id,
            'company_id' => $this->company_id,
            'merchant_id' => $this->merchant_id,
            'merchant_id_name' => $this->merchant_id_name,
            'order_no' => $this->order_no,
            'execution_date' => $this->execution_date,
            'second_execution_date' => $this->second_execution_date,
            'out_user_id' => $this->out_user_id,
            'nature' => $this->nature,
            'settlement_type' => $this->settlement_type,
            'settlement_amount' => $this->settlement_amount,
            'replace_amount' => $this->replace_amount,
            'delivery' => $this->delivery,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'second_place_fullname' => $this->second_place_fullname,
            'second_place_phone' => $this->second_place_phone,
            'second_place_country' => $this->second_place_country,
            'second_place_country_name' => $this->second_place_country_name,
            'second_place_province' => $this->second_place_province,
            'second_place_post_code' => $this->second_place_post_code,
            'second_place_house_number' => $this->second_place_house_number,
            'second_place_city' => $this->second_place_city,
            'second_place_district' => $this->second_place_district,
            'second_place_street' => $this->second_place_street,
            'second_place_address' => $this->second_place_address,
            'second_place_lon' => $this->second_place_lon,
            'second_place_lat' => $this->second_place_lat,
            'place_fullname' => $this->place_fullname,
            'place_phone' => $this->place_phone,
            'place_country' => $this->place_country,
            'place_country_name' => $this->place_country_name,
            'place_province' => $this->place_province,
            'place_post_code' => $this->place_post_code,
            'place_house_number' => $this->place_house_number,
            'place_city' => $this->place_city,
            'place_district' => $this->place_district,
            'place_street' => $this->place_street,
            'place_address' => $this->place_address,
            'place_lon' => $this->place_lon,
            'place_lat' => $this->place_lat,
            'tracking_order_type' => $this->tracking_order_type,
            'tracking_order_type_name' => $this->tracking_order_type_name,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'type' => $this->type
        ];
    }
}
