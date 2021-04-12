<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class MapConfigResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'front_type' => $this->front_type,
            'back_type' => $this->back_type,
            'mobile_type' => $this->mobile_type,
            'google_key' => $this->google_key,
            'google_secret' => $this->google_secret,
            'baidu_key' => $this->baidu_key,
            'baidu_secret' => $this->baidu_secret,
            'tencent_key' => $this->tencent_key,
            'tencent_secret' => $this->tencent_secret,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
