<?php

namespace App\Http\Resources;

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
            'express_first_no' => $this->express_first_no,
            'express_second_no' => $this->express_second_no,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'out_status' => $this->out_status,
            'out_status_name' => $this->out_status_name,
            'execution_date' => $this->execution_date,
            'out_order_no' => $this->out_order_no,
            'exception_label' => $this->exception_label,
            'exception_label_name' => $this->exception_label_name,
            'receiver_post_code' => $this->receiver_post_code,
            'receiver_house_number' => $this->receiver_house_number,
            'driver_fullname' => $this->driver_fullname,
            'batch_no' => $this->batch_no,
            'tour_no' => $this->tour_no,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }

}
