<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CarouselResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'status' => $this->status,
            'name' => $this->name,
            'picture_url' => $this->picture_url,
            'sort_id' => $this->sort_id,
            'rolling_time' => $this->rolling_time,
            'rolling_time_name' => $this->rolling_time_name,
            'jump_type' => $this->jump_type,
            'jump_type_name' => $this->jump_type_name,

            'inside_jump_type' => $this->inside_jump_type,
            'outside_jump_url' => $this->outside_jump_url,
            'inside_jump_type_name' => $this->inside_jump_type_name,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
