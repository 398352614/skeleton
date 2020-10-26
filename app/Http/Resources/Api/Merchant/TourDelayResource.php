<?php

namespace App\Http\Resources\Api\Merchant;

use App\Services\BaseConstService;
use Illuminate\Http\Resources\Json\JsonResource;

class TourDelayResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'tour_no' => $this->tour_no,
            'line_id' => $this->line_id,
            'line_name' => $this->line_name,
            'execution_date' => $this->execution_date,
            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver_name,
            'delay_time' => $this->delay_time,
            'delay_time_human' => $this->delay_time_human,
            'delay_type' => $this->delay_type,
            'delay_type_name' => $this->delay_type_name,
            'delay_remark' => $this->delay_remark,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
