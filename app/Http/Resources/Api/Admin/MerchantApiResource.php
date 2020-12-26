<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class MerchantApiResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'secret' => $this->secret,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'merchant_id_name' => $this->merchant_id_name,
            'merchant_id_code' => $this->merchant_id_code,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
