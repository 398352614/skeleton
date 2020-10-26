<?php


namespace App\Http\Resources\Api\Merchant;


use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'driver_id' => $this->driver_id,
            'number' => $this->number,
            'driver_name' => !empty($this->driver_id) ? $this->driver_id_name : __('否'),
            'status' => $this->status,
            'status_name' => $this->status_name,
            'mode' => $this->mode,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
