<?php

namespace App\Http\Resources\Api\Driver;


use Illuminate\Http\Resources\Json\JsonResource;

class TrackingPackageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'express_first_no' => $this->express_first_no,
            'created_at' => (string)$this->created_at,
            'warehouse_id' => $this->warehouse_id,
            'warehouse_name' => $this->warehouse_name,
            'next_warehouse_id' => $this->next_warehouse_id,
            'next_warehouse_name' => $this->next_warehouse_name,
            'type' => $this->type,
            'distance_type' => $this->distance_type,
        ];
    }
}
