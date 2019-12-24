<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->id,
            'batch_no' => $this->batch_no,
            'tour_no' => $this->tour_no,
            'line_id' => $this->line_id,
            'line_name' => $this->line_name,
            'execution_date' => $this->execution_date,
            'status' => $this->status,
            'exception_type' => $this->exception_type,
            'exception_remark' => $this->exception_remark,
            'exception_picture' => $this->exception_picture,
            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver_name,
            'driver_phone' => $this->driver_phone,
            'driver_rest_time' => $this->driver_rest_time,
            'car_id' => $this->car_id,
            'car_no' => $this->car_no,
            'sort_id' => $this->sort_id,
            'expect_pickup_quantity' => $this->expect_pickup_quantity,
            'actual_pickup_quantity' => $this->actual_pickup_quantity,
            'expect_pie_quantity' => $this->expect_pie_quantity,
            'actual_pie_quantity' => $this->actual_pie_quantity,
            'receiver' => $this->receiver,
            'receiver_phone' => $this->receiver_phone,
            'receiver_country' => $this->receiver_country,
            'receiver_post_code' => $this->receiver_post_code,
            'receiver_house_number' => $this->receiver_house_number,
            'receiver_city' => $this->receiver_city,
            'receiver_street' => $this->receiver_street,
            'receiver_address' => $this->receiver_address,
            'receiver_lon' => $this->receiver_lon,
            'receiver_lat' => $this->receiver_lat,
            'expect_arrive_time' => $this->expect_arrive_time,
            'actual_arrive_time' => $this->actual_arrive_time,
            'expect_distance' => $this->expect_distance,
            'actual_time' => $this->actual_time,
            'order_amount' => $this->order_amount,
            'replace_amount' => $this->replace_amount,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
    
}
