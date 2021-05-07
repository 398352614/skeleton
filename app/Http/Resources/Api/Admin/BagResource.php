<?php

namespace App\Http\Resources\Api\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

class BagResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'company_id' => $this->company_id,
            'bag_no' => $this->bag_no,
            'shift_no' => $this->shift_no,
            'status' => $this->status,
            'weight' => $this->weight,
            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver_name,
            'car_no' => $this->car_no,
            'remark' => $this->remark,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
