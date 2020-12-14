<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackingOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'merchant_id' => $this->merchant_id,
            'merchant_id_name' => $this->merchant_id_name,
            'order_no' => $this->order_no,
            'tracking_order_no' => $this->tracking_order_no,
            'mask_code' => $this->mask_code,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'out_user_id' => $this->out_user_id,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'out_status' => $this->out_status,
            'out_status_name' => $this->out_status_name,
            'execution_date' => $this->execution_date,
            'out_order_no' => $this->out_order_no,
            'exception_label' => $this->exception_label,
            'exception_label_name' => $this->exception_label_name,
            'place_post_code' => $this->place_post_code,
            'place_house_number' => $this->place_house_number,
            'driver_name' => $this->driver_name,
            'batch_no' => $this->batch_no,
            'tour_no' => $this->tour_no,
            'line_id' => $this->line_id,
            'line_name' => $this->line_name,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }

}
